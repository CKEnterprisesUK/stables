# Design Document

## Introduction

This document describes the technical architecture for the Horse Sponsorship Platform — a single-tenant PHP Laravel application that enables a horse stable to offer sponsorships to the public. The system integrates Stripe for recurring payments, provides a public gallery, authenticated sponsor portal, and admin panel for managing all aspects of the platform.

## Architecture Overview

The application follows a standard Laravel MVC architecture with the following layers:

- **Routes & Middleware**: Web routes with role-based middleware (guest, sponsor, admin)
- **Controllers**: Handle HTTP requests, delegate to services
- **Services**: Business logic layer (Stripe integration, certificate generation, notifications)
- **Models & Repositories**: Eloquent ORM models with relationships
- **Views**: Blade templates with shared layout components
- **Events & Listeners**: Async email notifications via Laravel's event system
- **Webhooks**: Stripe webhook handler for subscription state synchronization

```
┌─────────────────────────────────────────────────────────┐
│                      Web Browser                         │
├──────────┬──────────────────┬───────────────────────────┤
│  Public  │  Sponsor Portal  │       Admin Panel         │
│  Gallery │  (auth: sponsor) │     (auth: admin)         │
├──────────┴──────────────────┴───────────────────────────┤
│                   Laravel Routes                         │
│           (web.php + middleware groups)                  │
├─────────────────────────────────────────────────────────┤
│                    Controllers                          │
│  GalleryController | SponsorController | AdminController│
├─────────────────────────────────────────────────────────┤
│                     Services                            │
│  StripeService | CertificateService | NotificationSvc   │
├─────────────────────────────────────────────────────────┤
│               Eloquent Models & Events                  │
│  Horse | Sponsor | Sponsorship | Update | Branding      │
├─────────────────────────────────────────────────────────┤
│                    MySQL Database                        │
│               (XAMPP / MariaDB)                          │
└─────────────────────────────────────────────────────────┘
          │                              │
          ▼                              ▼
   ┌─────────────┐              ┌──────────────┐
   │   Stripe    │              │  Mail (SMTP) │
   │   API       │              │  / Mailpit   │
   └─────────────┘              └──────────────┘
```

## Components

### 1. Public Gallery Module

**Responsibility**: Display all horses to unauthenticated visitors with sponsorship CTAs.

```php
// app/Http/Controllers/GalleryController.php
class GalleryController extends Controller
{
    public function index(): View
    {
        $horses = Horse::with('photos')->orderBy('name')->get();
        $branding = StableBranding::first();
        return view('gallery.index', compact('horses', 'branding'));
    }
}
```

**Routes**: No auth middleware applied.

### 2. Sponsorship Signup Module

**Responsibility**: Collect visitor details, create Stripe subscription, provision sponsor account.

```php
// app/Http/Controllers/SignupController.php
class SignupController extends Controller
{
    public function store(SignupRequest $request, Horse $horse): RedirectResponse
    {
        // 1. Create user account
        // 2. Create Stripe customer + subscription via Laravel Cashier
        // 3. Create local Sponsorship record
        // 4. Authenticate user
        // 5. Redirect to sponsor portal
    }
}

// app/Http/Requests/SignupRequest.php
class SignupRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
            'monthly_amount' => ['required', 'numeric', 'min:1'],
            'child_name' => ['nullable', 'string', 'max:255'],
        ];
    }
}
```

### 3. Authentication Module

**Responsibility**: Email/password login (Laravel Breeze) plus custom magic link authentication.

```php
// app/Http/Controllers/Auth/MagicLinkController.php
class MagicLinkController extends Controller
{
    public function request(MagicLinkRequest $request): RedirectResponse
    {
        $token = MagicLink::create([
            'user_id' => $user->id,
            'token' => Str::random(64),
            'expires_at' => now()->addMinutes(15),
        ]);

        Mail::to($user)->send(new MagicLinkMail($token));
        return back()->with('status', 'Magic link sent!');
    }

    public function authenticate(string $token): RedirectResponse
    {
        $link = MagicLink::where('token', $token)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->firstOrFail();

        $link->update(['used_at' => now()]);
        Auth::login($link->user);

        return redirect()->route('sponsor.dashboard');
    }
}
```

### 4. Sponsor Portal Module

**Responsibility**: Display sponsorships, horse updates, certificates; manage payment methods and cancellations.

```php
// app/Http/Controllers/Sponsor/DashboardController.php
class DashboardController extends Controller
{
    public function index(): View
    {
        $sponsor = auth()->user();
        $sponsorships = $sponsor->sponsorships()
            ->with('horse.updates')
            ->orderByDesc('created_at')
            ->get();

        return view('sponsor.dashboard', compact('sponsorships'));
    }
}

// app/Http/Controllers/Sponsor/SponsorshipController.php
class SponsorshipController extends Controller
{
    public function cancel(Sponsorship $sponsorship): RedirectResponse
    {
        $this->authorize('own', $sponsorship);
        app(StripeService::class)->cancelSubscription($sponsorship);
        return back()->with('status', 'Sponsorship cancelled.');
    }
}
```

### 5. Certificate Generation Module

**Responsibility**: Generate PDF certificates with sponsor/child name, horse name, date, and branding.

```php
// app/Services/CertificateService.php
class CertificateService
{
    public function generate(Sponsorship $sponsorship): string
    {
        $branding = StableBranding::first();
        $displayName = $sponsorship->child_name ?? $sponsorship->user->name;

        $pdf = Pdf::loadView('certificates.sponsorship', [
            'displayName' => $displayName,
            'horseName' => $sponsorship->horse->name,
            'startDate' => $sponsorship->created_at->format('F j, Y'),
            'stableName' => $branding->name,
            'stableLogo' => $branding->logo_path,
        ]);

        return $pdf->output();
    }
}
```

**Package**: `barryvdh/laravel-dompdf` for PDF generation.

### 6. Admin Panel Module

**Responsibility**: CRUD for horses, manage sponsors/sponsorships, create updates, configure branding.

```php
// app/Http/Controllers/Admin/HorseController.php
class HorseController extends Controller
{
    public function store(HorseRequest $request): RedirectResponse
    {
        $horse = Horse::create($request->validated());

        foreach ($request->file('photos', []) as $photo) {
            $horse->photos()->create([
                'path' => $photo->store('horses', 'public'),
            ]);
        }

        return redirect()->route('admin.horses.index');
    }
}

// app/Http/Controllers/Admin/UpdateController.php
class UpdateController extends Controller
{
    public function store(UpdateRequest $request, Horse $horse): RedirectResponse
    {
        $update = $horse->updates()->create($request->validated());

        foreach ($request->file('photos', []) as $photo) {
            $update->photos()->create([
                'path' => $photo->store('updates', 'public'),
            ]);
        }

        event(new HorseUpdateCreated($update));
        return redirect()->route('admin.horses.show', $horse);
    }
}
```

### 7. Stripe Integration Module

**Responsibility**: Manage subscriptions via Laravel Cashier, handle webhooks.

```php
// app/Services/StripeService.php
class StripeService
{
    public function createSubscription(User $user, int $amountInCents): Subscription
    {
        return $user->newSubscription('sponsorship', [])
            ->meteredPrice(config('services.stripe.price_id'))
            ->create($user->defaultPaymentMethod()->id, [
                'quantity' => $amountInCents,
            ]);
    }

    public function cancelSubscription(Sponsorship $sponsorship): void
    {
        $sponsorship->user->subscription('sponsorship')->cancel();
        $sponsorship->update(['status' => 'cancelled', 'ends_at' => now()]);
    }
}

// app/Http/Controllers/WebhookController.php (extends Cashier's)
class WebhookController extends CashierController
{
    public function handleCustomerSubscriptionDeleted(array $payload): Response
    {
        parent::handleCustomerSubscriptionDeleted($payload);

        $stripeId = $payload['data']['object']['id'];
        $sponsorship = Sponsorship::where('stripe_subscription_id', $stripeId)->first();

        if ($sponsorship) {
            $sponsorship->update(['status' => 'cancelled']);
        }

        return response('OK');
    }

    public function handleInvoicePaymentFailed(array $payload): Response
    {
        $stripeId = $payload['data']['object']['subscription'];
        $sponsorship = Sponsorship::where('stripe_subscription_id', $stripeId)->first();

        if ($sponsorship) {
            $sponsorship->user->notify(new PaymentFailedNotification($sponsorship));
        }

        return response('OK');
    }
}
```

### 8. Notification Module

**Responsibility**: Email notifications for updates, payment failures, and admin cancellations.

```php
// app/Listeners/SendUpdateNotification.php
class SendUpdateNotification implements ShouldQueue
{
    public function handle(HorseUpdateCreated $event): void
    {
        $update = $event->update;
        $sponsors = $update->horse->activeSponsors();

        foreach ($sponsors as $sponsor) {
            $sponsor->notify(new HorseUpdateNotification($update));
        }
    }
}
```

### 9. SMTP Configuration Module

**Responsibility**: Allow admin to configure SMTP email settings from the admin panel, with runtime override of Laravel's mail configuration.

```php
// app/Http/Controllers/Admin/SmtpSettingsController.php
class SmtpSettingsController extends Controller
{
    public function edit(): View
    {
        $settings = SmtpSetting::first();
        return view('admin.settings.smtp', compact('settings'));
    }

    public function update(SmtpSettingsRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['password_encrypted'] = encrypt($data['password']);
        unset($data['password']);

        SmtpSetting::updateOrCreate(['id' => 1], $data);

        return back()->with('status', 'SMTP settings saved.');
    }

    public function sendTestEmail(): RedirectResponse
    {
        $admin = auth()->user();

        Mail::to($admin->email)->send(new TestSmtpMail());

        return back()->with('status', 'Test email sent to ' . $admin->email);
    }
}

// app/Http/Requests/SmtpSettingsRequest.php
class SmtpSettingsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'host' => ['required', 'string', 'max:255'],
            'port' => ['required', 'integer', 'min:1', 'max:65535'],
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
            'encryption' => ['required', Rule::in(['tls', 'ssl', 'none'])],
            'from_address' => ['required', 'email', 'max:255'],
            'from_name' => ['required', 'string', 'max:255'],
        ];
    }
}
```

```php
// app/Services/MailConfigProvider.php
class MailConfigProvider
{
    /**
     * Apply SMTP settings from the database to Laravel's mail config.
     * Called via a service provider boot() method.
     * Falls back to .env defaults if no DB settings exist.
     */
    public function apply(): void
    {
        $settings = SmtpSetting::first();

        if (!$settings) {
            return; // Use .env defaults
        }

        $encryption = $settings->encryption === 'none' ? null : $settings->encryption;

        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.host' => $settings->host,
            'mail.mailers.smtp.port' => $settings->port,
            'mail.mailers.smtp.username' => $settings->username,
            'mail.mailers.smtp.password' => decrypt($settings->password_encrypted),
            'mail.mailers.smtp.encryption' => $encryption,
            'mail.from.address' => $settings->from_address,
            'mail.from.name' => $settings->from_name,
        ]);
    }
}

// app/Providers/MailConfigServiceProvider.php
class MailConfigServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (app()->runningInConsole() && !app()->runningUnitTests()) {
            return;
        }

        app(MailConfigProvider::class)->apply();
    }
}
```

**Registration**: Add `MailConfigServiceProvider` to `bootstrap/providers.php`.

## Data Models

### Entity Relationship Diagram

```
┌──────────────┐         ┌──────────────────┐
│    users     │         │      horses      │
├──────────────┤         ├──────────────────┤
│ id           │         │ id               │
│ name         │    ┌───►│ name             │
│ email        │    │    │ facts (text)     │
│ password     │    │    │ created_at       │
│ role (enum)  │    │    │ updated_at       │
│ stripe_id    │    │    └──────────────────┘
│ created_at   │    │             │
│ updated_at   │    │             │ 1:N
└──────────────┘    │             ▼
       │            │    ┌──────────────────┐
       │ 1:N        │    │   horse_photos   │
       ▼            │    ├──────────────────┤
┌──────────────────┐│    │ id               │
│  sponsorships    ││    │ horse_id (FK)    │
├──────────────────┤│    │ path             │
│ id               ││    │ sort_order       │
│ user_id (FK)     ││    │ created_at       │
│ horse_id (FK) ───┘│    └──────────────────┘
│ stripe_sub_id    ││
│ monthly_amount   ││           │ 1:N
│ child_name       ││           ▼
│ status (enum)    ││   ┌──────────────────┐
│ ends_at          ││   │   horse_updates  │
│ created_at       ││   ├──────────────────┤
│ updated_at       ││   │ id               │
└──────────────────┘│   │ horse_id (FK)    │
                    │   │ title            │
                    │   │ body (text)      │
                    │   │ created_at       │
                    │   │ updated_at       │
                    │   └──────────────────┘
                    │            │
                    │            │ 1:N
                    │            ▼
                    │   ┌──────────────────┐
                    │   │  update_photos   │
                    │   ├──────────────────┤
                    │   │ id               │
                    │   │ update_id (FK)   │
                    │   │ path             │
                    │   │ created_at       │
                    │   └──────────────────┘
                    │
┌──────────────────┐│   ┌──────────────────┐
│   magic_links    ││   │ stable_brandings │
├──────────────────┤│   ├──────────────────┤
│ id               ││   │ id               │
│ user_id (FK)     ││   │ name             │
│ token            ││   │ logo_path        │
│ expires_at       ││   │ updated_at       │
│ used_at          ││   └──────────────────┘
│ created_at       ││
└──────────────────┘│   ┌──────────────────────┐
                    │   │    smtp_settings      │
                    │   ├──────────────────────┤
                    │   │ id                    │
                    │   │ host                  │
                    │   │ port                  │
                    │   │ username              │
                    │   │ password_encrypted    │
                    │   │ encryption (enum)     │
                    │   │ from_address          │
                    │   │ from_name             │
                    │   │ updated_at            │
                    │   └──────────────────────┘
```

### Model Definitions

```php
// app/Models/User.php
class User extends Authenticatable
{
    use Billable; // Laravel Cashier

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $casts = [
        'role' => UserRole::class, // enum: sponsor, admin
    ];

    public function sponsorships(): HasMany
    {
        return $this->hasMany(Sponsorship::class);
    }

    public function activeSponsorships(): HasMany
    {
        return $this->sponsorships()->where('status', 'active');
    }
}

// app/Models/Horse.php
class Horse extends Model
{
    protected $fillable = ['name', 'facts'];

    public function photos(): HasMany
    {
        return $this->hasMany(HorsePhoto::class)->orderBy('sort_order');
    }

    public function updates(): HasMany
    {
        return $this->hasMany(HorseUpdate::class)->orderByDesc('created_at');
    }

    public function sponsorships(): HasMany
    {
        return $this->hasMany(Sponsorship::class);
    }

    public function activeSponsors(): Collection
    {
        return User::whereHas('sponsorships', function ($q) {
            $q->where('horse_id', $this->id)->where('status', 'active');
        })->get();
    }
}

// app/Models/Sponsorship.php
class Sponsorship extends Model
{
    protected $fillable = [
        'user_id', 'horse_id', 'stripe_subscription_id',
        'monthly_amount', 'child_name', 'status', 'ends_at',
    ];

    protected $casts = [
        'status' => SponsorshipStatus::class, // enum: active, cancelled
        'monthly_amount' => 'integer',
        'ends_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function horse(): BelongsTo
    {
        return $this->belongsTo(Horse::class);
    }

    public function isChildSponsorship(): bool
    {
        return !is_null($this->child_name);
    }
}

// app/Models/MagicLink.php
class MagicLink extends Model
{
    protected $fillable = ['user_id', 'token', 'expires_at', 'used_at'];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isValid(): bool
    {
        return is_null($this->used_at) && $this->expires_at->isFuture();
    }
}

// app/Models/SmtpSetting.php
class SmtpSetting extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'host', 'port', 'username', 'password_encrypted',
        'encryption', 'from_address', 'from_name',
    ];

    protected $casts = [
        'port' => 'integer',
        'encryption' => SmtpEncryption::class, // enum: tls, ssl, none
    ];

    // updated_at managed manually or via model events
    protected static function booted(): void
    {
        static::saving(function ($model) {
            $model->updated_at = now();
        });
    }

    public function getPasswordAttribute(): string
    {
        return decrypt($this->password_encrypted);
    }
}
```

### Enums

```php
// app/Enums/UserRole.php
enum UserRole: string
{
    case Sponsor = 'sponsor';
    case Admin = 'admin';
}

// app/Enums/SponsorshipStatus.php
enum SponsorshipStatus: string
{
    case Active = 'active';
    case Cancelled = 'cancelled';
}

// app/Enums/SmtpEncryption.php
enum SmtpEncryption: string
{
    case Tls = 'tls';
    case Ssl = 'ssl';
    case None = 'none';
}
```

## Interfaces

### Route Structure

```php
// routes/web.php

// Public (no auth)
Route::get('/', [GalleryController::class, 'index'])->name('gallery');
Route::get('/horses/{horse}', [GalleryController::class, 'show'])->name('gallery.show');
Route::get('/sponsor/{horse}', [SignupController::class, 'create'])->name('signup.create');
Route::post('/sponsor/{horse}', [SignupController::class, 'store'])->name('signup.store');

// Magic Link
Route::post('/magic-link', [MagicLinkController::class, 'request'])->name('magic-link.request');
Route::get('/magic-link/{token}', [MagicLinkController::class, 'authenticate'])->name('magic-link.authenticate');

// Sponsor Portal (auth + role:sponsor)
Route::middleware(['auth', 'role:sponsor'])->prefix('portal')->name('sponsor.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/sponsorships/{sponsorship}/certificate', [CertificateController::class, 'show'])->name('certificate');
    Route::get('/sponsorships/{sponsorship}/certificate/download', [CertificateController::class, 'download'])->name('certificate.download');
    Route::post('/sponsorships/{sponsorship}/cancel', [SponsorshipController::class, 'cancel'])->name('sponsorship.cancel');
    Route::get('/billing', [BillingController::class, 'redirectToStripe'])->name('billing');
});

// Admin Panel (auth + role:admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('horses', Admin\HorseController::class);
    Route::resource('horses.updates', Admin\UpdateController::class)->only(['create', 'store']);
    Route::get('/sponsors', [Admin\SponsorController::class, 'index'])->name('sponsors.index');
    Route::post('/sponsorships/{sponsorship}/cancel', [Admin\SponsorController::class, 'cancel'])->name('sponsorship.cancel');
    Route::get('/branding', [Admin\BrandingController::class, 'edit'])->name('branding.edit');
    Route::put('/branding', [Admin\BrandingController::class, 'update'])->name('branding.update');
    Route::get('/settings/smtp', [Admin\SmtpSettingsController::class, 'edit'])->name('settings.smtp');
    Route::put('/settings/smtp', [Admin\SmtpSettingsController::class, 'update'])->name('settings.smtp.update');
    Route::post('/settings/smtp/test', [Admin\SmtpSettingsController::class, 'sendTestEmail'])->name('settings.smtp.test');
});

// Stripe Webhook
Route::post('/stripe/webhook', [WebhookController::class, 'handleWebhook'])->name('stripe.webhook');
```

### Service Interfaces

```php
// app/Services/StripeService.php
interface StripeServiceInterface
{
    public function createSubscription(User $user, int $amountInCents, string $paymentMethodId): Subscription;
    public function cancelSubscription(Sponsorship $sponsorship): void;
    public function getPortalUrl(User $user): string;
}

// app/Services/CertificateService.php
interface CertificateServiceInterface
{
    public function generate(Sponsorship $sponsorship): string; // Returns PDF content
    public function getDisplayName(Sponsorship $sponsorship): string;
}

// app/Services/NotificationService.php
interface NotificationServiceInterface
{
    public function notifySponsorsOfUpdate(HorseUpdate $update): void;
    public function notifyPaymentFailed(Sponsorship $sponsorship): void;
    public function notifySponsorshipCancelledByAdmin(Sponsorship $sponsorship): void;
}

// app/Services/MailConfigProvider.php
interface MailConfigProviderInterface
{
    public function apply(): void; // Override Laravel mail config from DB settings, or no-op if none exist
}
```

## Error Handling

### Stripe Errors

```php
// app/Exceptions/StripeExceptionHandler.php
class StripeExceptionHandler
{
    public function handle(StripeException $e, Sponsorship $sponsorship = null): void
    {
        Log::error('Stripe error', [
            'message' => $e->getMessage(),
            'sponsorship_id' => $sponsorship?->id,
        ]);

        // Re-throw as application exception for controller handling
        throw new PaymentProcessingException($e->getMessage(), $e);
    }
}
```

### Webhook Validation

```php
// Middleware: VerifyStripeWebhook
// Laravel Cashier provides built-in webhook signature verification.
// The WebhookController extends Cashier's controller which handles this.
```

### File Upload Validation

```php
// Validation rules for photo uploads
'photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], // 5MB max per photo
'logo' => ['image', 'mimes:jpg,jpeg,png,svg', 'max:2048'], // 2MB max for logo
```

### Magic Link Errors

- **Expired link**: Redirect to login with flash message "This link has expired. Please request a new one."
- **Used link**: Redirect to login with flash message "This link has already been used."
- **Invalid link**: Return 404.

### Authorization Errors

- Sponsors can only access their own sponsorships (policy-based authorization).
- Admin routes are protected by `role:admin` middleware.
- Unauthorized access returns 403 Forbidden.

## Key Dependencies

| Package | Purpose |
|---------|---------|
| `laravel/framework` ^11.x | Core framework |
| `laravel/breeze` | Auth scaffolding (email/password) |
| `laravel/cashier` | Stripe subscription management |
| `barryvdh/laravel-dompdf` | PDF certificate generation |
| `intervention/image` | Image processing for uploads |

## File Storage

- **Horse photos**: `storage/app/public/horses/` (symlinked via `php artisan storage:link`)
- **Update photos**: `storage/app/public/updates/`
- **Stable logo**: `storage/app/public/branding/`
- **Disk**: `public` disk (local filesystem, XAMPP-compatible)

## Correctness Properties

*A property is a characteristic or behavior that should hold true across all valid executions of a system — essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.*

### Property 1: Gallery displays all horses

*For any* set of horses persisted in the database, the public gallery endpoint SHALL return all of them with their correct names, photos, and facts.

**Validates: Requirements 1.1**

### Property 2: Gallery CTA links to correct signup

*For any* horse displayed in the public gallery, there SHALL exist a call-to-action that links to the sponsorship signup URL for that specific horse.

**Validates: Requirements 1.3, 1.4**

### Property 3: Signup creates monthly Stripe subscription

*For any* valid sponsorship signup (valid email, password, and amount), completing the flow SHALL create a Stripe subscription with monthly billing frequency and the specified amount.

**Validates: Requirements 2.2, 11.1**

### Property 4: Signup creates linked sponsor account

*For any* completed sponsorship signup, the platform SHALL create an authenticated user account with the sponsor role, linked to the corresponding Stripe subscription and horse.

**Validates: Requirements 2.3**

### Property 5: Child sponsorship name persistence

*For any* sponsorship signup that includes a child name, the child name SHALL be stored on the sponsorship record; for any signup without a child name, the child_name field SHALL be null.

**Validates: Requirements 2.4, 2.5**

### Property 6: Magic link single-use and time-limited

*For any* magic link token, it SHALL authenticate the user exactly once and SHALL be rejected after use or after the expiration time has passed.

**Validates: Requirements 3.2, 3.3**

### Property 7: Authentication redirects to sponsor portal

*For any* successful sponsor authentication (via either email/password or magic link), the platform SHALL redirect the user to the sponsor portal dashboard.

**Validates: Requirements 3.4**

### Property 8: Portal displays all sponsor's sponsorships

*For any* authenticated sponsor with N sponsorships (active and past), the portal SHALL display exactly those N sponsorships.

**Validates: Requirements 4.1**

### Property 9: Cancellation triggers Stripe cancellation

*For any* active sponsorship cancelled by either the sponsor or the admin, the platform SHALL call the Stripe cancellation API with the correct subscription ID.

**Validates: Requirements 4.3, 8.2**

### Property 10: Cancellation confirms with end date

*For any* sponsorship cancellation, the platform SHALL display a confirmation message and the effective end date of the sponsorship.

**Validates: Requirements 4.4**

### Property 11: Update visibility restricted to active sponsors

*For any* horse update and any user, the update SHALL be visible if and only if the user has an active sponsorship for that update's horse.

**Validates: Requirements 5.1, 5.3**

### Property 12: Updates in reverse chronological order

*For any* set of updates displayed to a sponsor, they SHALL be ordered by creation date descending (newest first).

**Validates: Requirements 5.2**

### Property 13: Certificate content correctness

*For any* sponsorship, the generated certificate SHALL contain: the horse's name, the sponsorship start date, the current stable branding (name and logo), and the correct display name — where the display name is the child's name for child sponsorships, or the sponsor's name otherwise.

**Validates: Requirements 6.1, 6.2, 6.4**

### Property 14: Horse data round-trip

*For any* horse data (name, facts, photos) submitted via the admin panel, storing and then retrieving the horse SHALL yield identical data.

**Validates: Requirements 7.1, 7.2**

### Property 15: Horse removal excludes from gallery

*For any* horse removed by the admin, that horse SHALL no longer appear in the public gallery results.

**Validates: Requirements 7.3**

### Property 16: Multiple photos per horse

*For any* horse with N uploaded photos (where N ≥ 1), all N photos SHALL be stored and retrievable in their specified order.

**Validates: Requirements 7.4**

### Property 17: Admin views all sponsors and sponsorships

*For any* set of sponsors in the system, the admin sponsor management view SHALL display all of them with their associated sponsorships.

**Validates: Requirements 8.1**

### Property 18: Admin cancellation notifies sponsor

*For any* sponsorship cancelled by the admin, the platform SHALL send an email notification to the affected sponsor's registered email address.

**Validates: Requirements 8.3**

### Property 19: Update creation persists with photos

*For any* update created by the admin with text content and N photos, the update and all N photos SHALL be persisted and associated correctly with the target horse.

**Validates: Requirements 9.1, 9.3**

### Property 20: Update notification targets active sponsors only

*For any* horse update created by the admin, email notifications SHALL be sent to exactly those sponsors who have an active sponsorship for that horse — no more, no fewer.

**Validates: Requirements 9.2**

### Property 21: Branding propagation

*For any* branding update (name or logo), the updated values SHALL be reflected in the public gallery, sponsor portal, and generated certificates.

**Validates: Requirements 10.3**

### Property 22: Payment failure notification

*For any* Stripe payment failure webhook event, the platform SHALL send an email notification to the sponsor associated with the failed subscription.

**Validates: Requirements 11.2**

### Property 23: Webhook synchronizes subscription status

*For any* Stripe subscription cancellation webhook event, the platform SHALL update the corresponding local sponsorship record's status to cancelled.

**Validates: Requirements 11.3**

### Property 24: SMTP settings persistence

*For any* valid SMTP configuration (host, port, username, password, encryption type, from address, from name) saved by the admin, retrieving the settings SHALL return identical values with the password decryptable to its original plaintext.

**Validates: Requirements 13.2**

### Property 25: SMTP settings used for email delivery

*For any* email sent by the platform when SMTP settings exist in the database, the mail system SHALL use those database-configured settings (host, port, username, password, encryption, from address, from name) rather than the .env defaults.

**Validates: Requirements 13.3**

### Property 26: SMTP fallback to .env

*For any* email sent by the platform when no SMTP settings exist in the database, the platform SHALL use the .env mail configuration values unchanged.

**Validates: Requirements 13.3**
