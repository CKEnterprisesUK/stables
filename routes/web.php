<?php

use App\Http\Controllers\Admin\BrandingController;
use App\Http\Controllers\Admin\GeneralSettingsController;
use App\Http\Controllers\Admin\HorseController as AdminHorseController;
use App\Http\Controllers\Admin\SetupController;
use App\Http\Controllers\Admin\SmtpSettingsController;
use App\Http\Controllers\Admin\SponsorController as AdminSponsorController;
use App\Http\Controllers\Admin\SponsorshipInfoController;
use App\Http\Controllers\Admin\StripeSettingsController;
use App\Http\Controllers\Admin\UpdateController as AdminUpdateController;
use App\Http\Controllers\Auth\MagicLinkController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\Sponsor\AddSponsorshipController;
use App\Http\Controllers\Sponsor\BillingController;
use App\Http\Controllers\Sponsor\CertificateController;
use App\Http\Controllers\Sponsor\DashboardController;
use App\Http\Controllers\Sponsor\FinanceController;
use App\Http\Controllers\Sponsor\HorseUpdatesController;
use App\Http\Controllers\Sponsor\SponsorshipController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

// Public Gallery Routes (no auth required)
Route::get('/', [GalleryController::class, 'index'])->name('gallery');
Route::get('/horses/{horse}', [GalleryController::class, 'show'])->name('gallery.show');
Route::get('/stables', [GalleryController::class, 'stables'])->name('stables');
Route::get('/sponsorship-info', function () {
    $branding = \App\Models\StableBranding::first();
    return view('gallery.sponsorship-info', compact('branding'));
})->name('sponsorship-info');

// Legal Pages (no auth required)
Route::get('/privacy', fn () => view('legal.privacy'))->name('legal.privacy');
Route::get('/terms', fn () => view('legal.terms'))->name('legal.terms');

// Sponsorship Signup (no auth required)
Route::get('/sponsor/{horse}', [SignupController::class, 'create'])->name('signup.create');
Route::post('/sponsor/{horse}', [SignupController::class, 'store'])->name('signup.store');

// Magic Link Authentication (no auth required)
Route::post('/magic-link', [MagicLinkController::class, 'request'])->name('magic-link.request');
Route::get('/magic-link/{token}', [MagicLinkController::class, 'authenticate'])->name('magic-link.authenticate');

// Sponsor Portal (auth + role:sponsor)
Route::middleware(['auth', 'role:sponsor'])->prefix('portal')->name('sponsor.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/billing', [BillingController::class, 'redirectToStripe'])->name('billing');
    Route::get('/finance', [FinanceController::class, 'index'])->name('finance');
    Route::get('/horses/{horse}/updates', [HorseUpdatesController::class, 'index'])->name('horse.updates');
    Route::get('/sponsorships/add', [AddSponsorshipController::class, 'create'])->name('sponsorship.create');
    Route::post('/sponsorships/{horse}', [AddSponsorshipController::class, 'store'])->name('sponsorship.store');
    Route::get('/sponsorships/{sponsorship}/certificate', [CertificateController::class, 'show'])->name('certificate');
    Route::get('/sponsorships/{sponsorship}/certificate/download', [CertificateController::class, 'download'])->name('certificate.download');
    Route::post('/sponsorships/{sponsorship}/cancel', [SponsorshipController::class, 'cancel'])->name('sponsorship.cancel');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Panel — all admin roles can access horses and post updates
Route::middleware(['auth', 'role:super_admin,sponsorship_admin,update_admin'])->prefix('admin')->name('admin.')->group(function () {
    // Horse management & updates — accessible by all admin roles
    Route::resource('horses', AdminHorseController::class);
    Route::resource('horses.updates', AdminUpdateController::class)->only(['create', 'store']);
    Route::post('/horses/{horse}/updates/{update}/notify', [AdminUpdateController::class, 'notify'])->name('horses.updates.notify');
});

// Admin Panel — sponsorship & finance management (super_admin + sponsorship_admin)
Route::middleware(['auth', 'role:super_admin,sponsorship_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/sponsors', [AdminSponsorController::class, 'index'])->name('sponsors.index');
    Route::get('/sponsors/{user}', [AdminSponsorController::class, 'show'])->name('sponsors.show');
    Route::post('/sponsorships/{sponsorship}/cancel', [AdminSponsorController::class, 'cancel'])->name('sponsorship.cancel');
});

// Admin Panel — settings & config (super_admin only)
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/setup', [SetupController::class, 'index'])->name('setup');

    Route::get('/settings/general', [GeneralSettingsController::class, 'edit'])->name('settings.general');
    Route::put('/settings/general', [GeneralSettingsController::class, 'update'])->name('settings.general.update');
    Route::get('/branding', [BrandingController::class, 'edit'])->name('branding.edit');
    Route::put('/branding', [BrandingController::class, 'update'])->name('branding.update');
    Route::get('/sponsorship-info', [SponsorshipInfoController::class, 'edit'])->name('sponsorship-info.edit');
    Route::put('/sponsorship-info', [SponsorshipInfoController::class, 'update'])->name('sponsorship-info.update');
    Route::get('/settings/smtp', [SmtpSettingsController::class, 'edit'])->name('settings.smtp');
    Route::put('/settings/smtp', [SmtpSettingsController::class, 'update'])->name('settings.smtp.update');
    Route::post('/settings/smtp/test', [SmtpSettingsController::class, 'sendTestEmail'])->name('settings.smtp.test');
    Route::get('/settings/stripe', [StripeSettingsController::class, 'edit'])->name('settings.stripe');
    Route::get('/settings/stripe/connect', [StripeSettingsController::class, 'connect'])->name('settings.stripe.connect');
    Route::get('/settings/stripe/callback', [StripeSettingsController::class, 'callback'])->name('settings.stripe.callback');
    Route::delete('/settings/stripe/disconnect', [StripeSettingsController::class, 'disconnect'])->name('settings.stripe.disconnect');
    Route::get('/settings/stripe/dashboard', [StripeSettingsController::class, 'dashboard'])->name('settings.stripe.dashboard');
    Route::post('/settings/stripe/create-product', [StripeSettingsController::class, 'createProduct'])->name('settings.stripe.create-product');

    // Admin user management (super_admin only)
    Route::get('/admins', [\App\Http\Controllers\Admin\AdminController::class, 'index'])->name('admins.index');
    Route::get('/admins/create', [\App\Http\Controllers\Admin\AdminController::class, 'create'])->name('admins.create');
    Route::post('/admins', [\App\Http\Controllers\Admin\AdminController::class, 'store'])->name('admins.store');
    Route::get('/admins/{user}/edit', [\App\Http\Controllers\Admin\AdminController::class, 'edit'])->name('admins.edit');
    Route::put('/admins/{user}', [\App\Http\Controllers\Admin\AdminController::class, 'update'])->name('admins.update');
    Route::delete('/admins/{user}', [\App\Http\Controllers\Admin\AdminController::class, 'destroy'])->name('admins.destroy');
});

// Stripe Webhook (excluded from CSRF via bootstrap/app.php)
Route::post('/stripe/webhook', [WebhookController::class, 'handleWebhook'])->name('stripe.webhook');

require __DIR__.'/auth.php';
