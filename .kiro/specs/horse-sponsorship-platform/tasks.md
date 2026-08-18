# Implementation Plan: Horse Sponsorship Platform

## Overview

Build a single-tenant PHP Laravel 11.x application that enables a horse stable to offer public sponsorships with Stripe-based recurring payments. The implementation proceeds from database schema and models through authentication, public gallery, sponsor portal, admin panel, Stripe integration, certificates, and notifications — wiring everything together incrementally.

## Tasks

- [x] 1. Project setup, database schema, and core models
  - [x] 1.1 Install dependencies and configure project
    - Install Laravel Cashier, laravel-dompdf, intervention/image via Composer
    - Configure `.env` with Stripe keys, mail settings, and database connection for XAMPP/MariaDB
    - Run `php artisan storage:link` for public disk access
    - _Requirements: 12.3, 11.4_

  - [x] 1.2 Create database migrations
    - Create migration for `horses` table (id, name, facts, timestamps)
    - Create migration for `horse_photos` table (id, horse_id FK, path, sort_order, timestamps)
    - Create migration for `sponsorships` table (id, user_id FK, horse_id FK, stripe_subscription_id, monthly_amount, child_name nullable, status enum, ends_at nullable, timestamps)
    - Create migration for `horse_updates` table (id, horse_id FK, title, body, timestamps)
    - Create migration for `update_photos` table (id, update_id FK, path, timestamps)
    - Create migration for `magic_links` table (id, user_id FK, token, expires_at, used_at nullable, timestamps)
    - Create migration for `stable_brandings` table (id, name, logo_path, timestamps)
    - Add `role` enum column to `users` table (sponsor, admin)
    - _Requirements: 12.2_

  - [x] 1.3 Create Eloquent models and relationships
    - Create `Horse` model with `photos()`, `updates()`, `sponsorships()`, `activeSponsors()` relationships
    - Create `HorsePhoto` model with `horse()` relationship
    - Create `Sponsorship` model with `user()`, `horse()` relationships, `SponsorshipStatus` enum cast
    - Create `HorseUpdate` model with `horse()`, `photos()` relationships
    - Create `UpdatePhoto` model with `update()` relationship
    - Create `MagicLink` model with `user()` relationship and `isValid()` method
    - Create `StableBranding` model
    - Update `User` model with `Billable` trait, `sponsorships()`, `activeSponsorships()`, `UserRole` enum cast
    - Create `UserRole` and `SponsorshipStatus` enums in `app/Enums/`
    - _Requirements: 12.1, 12.2_

  - [x] 1.4 Create database seeder for initial admin user and branding
    - Seed an admin user account
    - Seed a default `StableBranding` record
    - _Requirements: 12.1_

- [x] 2. Authentication module
  - [x] 2.1 Install and configure Laravel Breeze
    - Install Laravel Breeze with Blade stack
    - Customize registration to set `role` to `sponsor` by default
    - _Requirements: 3.1_

  - [x] 2.2 Create role-based middleware
    - Create `RoleMiddleware` that checks `auth()->user()->role` against required role
    - Register middleware alias `role` in bootstrap/app.php
    - _Requirements: 3.4_

  - [x] 2.3 Implement magic link authentication
    - Create `MagicLinkController` with `request()` and `authenticate()` methods
    - Create `MagicLinkRequest` form request for email validation
    - Create `MagicLinkMail` mailable with the authentication link
    - Magic link expires after 15 minutes and is single-use
    - On success, redirect to sponsor portal dashboard
    - Create Blade views for magic link request form
    - _Requirements: 3.2, 3.3, 3.4_

  - [ ]* 2.4 Write property tests for magic link authentication
    - **Property 6: Magic link single-use and time-limited**
    - **Validates: Requirements 3.2, 3.3**

- [x] 3. Public gallery module
  - [x] 3.1 Implement GalleryController and views
    - Create `GalleryController` with `index()` and `show()` methods
    - `index()` returns all horses with photos, ordered by name
    - `show()` returns single horse detail with all photos and facts
    - Inject `StableBranding` into views for stable name/logo display
    - Create `gallery.index` Blade view with horse cards showing photos, names, facts
    - Create `gallery.show` Blade view with full horse detail
    - Include sponsorship CTA button on each horse linking to signup
    - _Requirements: 1.1, 1.2, 1.3, 1.4_

  - [ ]* 3.2 Write property tests for public gallery
    - **Property 1: Gallery displays all horses**
    - **Property 2: Gallery CTA links to correct signup**
    - **Validates: Requirements 1.1, 1.3, 1.4**

- [x] 4. Sponsorship signup module
  - [x] 4.1 Implement SignupController and Stripe subscription creation
    - Create `SignupController` with `create()` and `store()` methods
    - Create `SignupRequest` form request (email, password, monthly_amount, child_name)
    - `store()` flow: create user, create Stripe customer + subscription via Cashier, create local Sponsorship record, authenticate user, redirect to portal
    - Create `signup.create` Blade view with form fields (email, password, confirm password, monthly amount, optional child name)
    - Include Stripe payment element for collecting payment method
    - _Requirements: 2.1, 2.2, 2.3, 2.4, 2.5, 11.1_

  - [ ]* 4.2 Write property tests for sponsorship signup
    - **Property 3: Signup creates monthly Stripe subscription**
    - **Property 4: Signup creates linked sponsor account**
    - **Property 5: Child sponsorship name persistence**
    - **Validates: Requirements 2.2, 2.3, 2.4, 2.5, 11.1**

- [x] 5. Checkpoint - Ensure all tests pass
  - Ensure all tests pass, ask the user if questions arise.

- [x] 6. Sponsor portal module
  - [x] 6.1 Implement sponsor dashboard
    - Create `Sponsor\DashboardController` with `index()` method
    - Display all sponsor's sponsorships (active and past) with horse info
    - Show horse updates for each sponsored horse (reverse chronological)
    - Restrict update visibility to active sponsorships only
    - Create `sponsor.dashboard` Blade view
    - _Requirements: 4.1, 5.1, 5.2, 5.3_

  - [x] 6.2 Implement sponsorship cancellation
    - Create `Sponsor\SponsorshipController` with `cancel()` method
    - Add authorization policy: sponsor can only cancel their own sponsorships
    - Call `StripeService::cancelSubscription()` on cancel
    - Display confirmation with effective end date
    - _Requirements: 4.3, 4.4_

  - [x] 6.3 Implement billing portal redirect
    - Create `Sponsor\BillingController` with `redirectToStripe()` method
    - Redirect sponsor to Stripe Customer Portal for payment method management
    - _Requirements: 4.2_

  - [ ]* 6.4 Write property tests for sponsor portal
    - **Property 8: Portal displays all sponsor's sponsorships**
    - **Property 11: Update visibility restricted to active sponsors**
    - **Property 12: Updates in reverse chronological order**
    - **Validates: Requirements 4.1, 5.1, 5.2, 5.3**

- [x] 7. Certificate generation module
  - [x] 7.1 Implement CertificateService and controller
    - Create `CertificateService` implementing `CertificateServiceInterface`
    - `generate()` loads Blade view with horse name, start date, display name, stable branding
    - Display name = child_name if Child_Sponsorship, else sponsor's name
    - Create `Sponsor\CertificateController` with `show()` (view in browser) and `download()` (PDF download) methods
    - Create `certificates.sponsorship` Blade template with certificate layout
    - Use `barryvdh/laravel-dompdf` for PDF rendering
    - _Requirements: 6.1, 6.2, 6.3, 6.4_

  - [ ]* 7.2 Write property tests for certificate generation
    - **Property 13: Certificate content correctness**
    - **Validates: Requirements 6.1, 6.2, 6.4**

- [x] 8. Stripe integration module
  - [x] 8.1 Implement StripeService
    - Create `StripeService` implementing `StripeServiceInterface`
    - `createSubscription()`: create Stripe customer and subscription with specified monthly amount
    - `cancelSubscription()`: cancel Stripe subscription, update local sponsorship status and ends_at
    - `getPortalUrl()`: generate Stripe Customer Portal URL for payment method management
    - _Requirements: 11.1, 11.3_

  - [x] 8.2 Implement Stripe webhook controller
    - Create `WebhookController` extending Cashier's webhook controller
    - Handle `customer.subscription.deleted`: update local sponsorship status to cancelled
    - Handle `invoice.payment_failed`: notify sponsor via email
    - Register webhook route (excluded from CSRF verification)
    - _Requirements: 11.2, 11.3, 11.4_

  - [ ]* 8.3 Write property tests for Stripe integration
    - **Property 9: Cancellation triggers Stripe cancellation**
    - **Property 22: Payment failure notification**
    - **Property 23: Webhook synchronizes subscription status**
    - **Validates: Requirements 4.3, 8.2, 11.2, 11.3**

- [x] 9. Checkpoint - Ensure all tests pass
  - Ensure all tests pass, ask the user if questions arise.

- [x] 10. Admin panel — horse management
  - [x] 10.1 Implement Admin HorseController (CRUD)
    - Create `Admin\HorseController` with full resource methods (index, create, store, edit, update, destroy)
    - Create `HorseRequest` form request with validation (name, facts, photos)
    - Handle multiple photo uploads, store to `public/horses/` disk
    - On destroy, remove horse from gallery (soft delete or hard delete per design)
    - Create Blade views: `admin.horses.index`, `admin.horses.create`, `admin.horses.edit`, `admin.horses.show`
    - _Requirements: 7.1, 7.2, 7.3, 7.4_

  - [ ]* 10.2 Write property tests for horse management
    - **Property 14: Horse data round-trip**
    - **Property 15: Horse removal excludes from gallery**
    - **Property 16: Multiple photos per horse**
    - **Validates: Requirements 7.1, 7.2, 7.3, 7.4**

- [x] 11. Admin panel — sponsor management
  - [x] 11.1 Implement Admin SponsorController
    - Create `Admin\SponsorController` with `index()` and `cancel()` methods
    - `index()` displays all sponsors with their associated sponsorships
    - `cancel()` cancels the Stripe subscription and notifies the sponsor via email
    - Create `admin.sponsors.index` Blade view showing sponsors and sponsorships
    - _Requirements: 8.1, 8.2, 8.3_

  - [ ]* 11.2 Write property tests for admin sponsor management
    - **Property 17: Admin views all sponsors and sponsorships**
    - **Property 18: Admin cancellation notifies sponsor**
    - **Validates: Requirements 8.1, 8.2, 8.3**

- [x] 12. Admin panel — content updates
  - [x] 12.1 Implement Admin UpdateController
    - Create `Admin\UpdateController` with `create()` and `store()` methods
    - Create `UpdateRequest` form request (title, body, photos)
    - Store update with associated photos to `public/updates/` disk
    - Fire `HorseUpdateCreated` event after persisting update
    - Create Blade views: `admin.updates.create`
    - _Requirements: 9.1, 9.3_

  - [ ]* 12.2 Write property tests for content updates
    - **Property 19: Update creation persists with photos**
    - **Validates: Requirements 9.1, 9.3**

- [x] 13. Admin panel — branding configuration
  - [x] 13.1 Implement Admin BrandingController
    - Create `Admin\BrandingController` with `edit()` and `update()` methods
    - Handle stable name text field and logo file upload
    - Store logo to `public/branding/` disk
    - Create Blade views: `admin.branding.edit`
    - _Requirements: 10.1, 10.2, 10.3_

  - [ ]* 13.2 Write property tests for branding
    - **Property 21: Branding propagation**
    - **Validates: Requirements 10.3**

- [x] 14. Admin panel — SMTP email settings
  - [x] 14.1 Create smtp_settings migration and model
    - Create migration for `smtp_settings` table (id, host, port, username, password_encrypted, encryption enum, from_address, from_name, updated_at)
    - Create `SmtpSetting` model with encrypted password accessor, `SmtpEncryption` enum
    - _Requirements: 13.2_

  - [x] 14.2 Implement MailConfigProvider service
    - Create `MailConfigProvider` that reads SMTP settings from database and overrides Laravel mail config at runtime
    - If no DB settings exist, fall back to .env defaults (no-op)
    - Create `MailConfigServiceProvider` and register in bootstrap/providers.php
    - _Requirements: 13.3_

  - [x] 14.3 Implement Admin SmtpSettingsController and views
    - Create `Admin\SmtpSettingsController` with `edit()`, `update()`, `sendTestEmail()` methods
    - Create `SmtpSettingsRequest` form request with validation
    - Store password using `encrypt()`, display form with current settings (password masked)
    - Create `admin.settings.smtp` Blade view with form fields and "Send Test Email" button
    - Create `TestSmtpMail` mailable for test emails
    - _Requirements: 13.1, 13.2, 13.4_

  - [ ]* 14.4 Write property tests for SMTP settings
    - **Property 24: SMTP settings persistence**
    - **Property 25: SMTP settings used for email delivery**
    - **Property 26: SMTP fallback to .env**
    - **Validates: Requirements 13.2, 13.3**

- [x] 15. Notification module
  - [x] 15.1 Implement events, listeners, and notifications
    - Create `HorseUpdateCreated` event
    - Create `SendUpdateNotification` listener (queued) that notifies active sponsors of the horse
    - Create `HorseUpdateNotification` mailable/notification
    - Create `PaymentFailedNotification` notification
    - Create `SponsorshipCancelledByAdminNotification` notification
    - Register event-listener mappings in `EventServiceProvider`
    - _Requirements: 9.2, 11.2, 8.3_

  - [ ]* 15.2 Write property tests for notifications
    - **Property 20: Update notification targets active sponsors only**
    - **Validates: Requirements 9.2**

- [x] 16. Route wiring and layout finalization
  - [x] 16.1 Wire all routes in web.php
    - Define public routes (gallery, signup, magic link)
    - Define sponsor portal routes with `auth` + `role:sponsor` middleware
    - Define admin routes with `auth` + `role:admin` middleware
    - Define Stripe webhook route (excluded from CSRF)
    - _Requirements: 1.2, 3.4, 12.1_

  - [x] 16.2 Create shared Blade layout and navigation
    - Create `layouts.app` master layout with stable branding header
    - Create navigation partial with conditional links for guest/sponsor/admin
    - Ensure branding (name, logo) is loaded via a view composer or shared middleware
    - _Requirements: 10.3, 12.1_

  - [ ]* 16.3 Write integration tests for route access control
    - Test that guests can access gallery but not portal/admin
    - Test that sponsors can access portal but not admin
    - Test that admins can access admin panel
    - _Requirements: 1.2, 3.4_

- [x] 17. Final checkpoint - Ensure all tests pass
  - Ensure all tests pass, ask the user if questions arise.

## Notes

- Tasks marked with `*` are optional and can be skipped for faster MVP
- Each task references specific requirements for traceability
- Checkpoints ensure incremental validation
- Property tests validate universal correctness properties from the design document
- Unit tests validate specific examples and edge cases
- The Stripe integration should be tested using Stripe's test mode and mock webhooks
- Laravel Cashier handles most Stripe complexity; the StripeService wraps Cashier for app-specific logic
- All file uploads use Laravel's `public` disk (local filesystem via XAMPP)

## Task Dependency Graph

```json
{
  "waves": [
    { "id": 0, "tasks": ["1.1"] },
    { "id": 1, "tasks": ["1.2", "14.1"] },
    { "id": 2, "tasks": ["1.3", "1.4", "14.2"] },
    { "id": 3, "tasks": ["2.1", "8.1", "14.3"] },
    { "id": 4, "tasks": ["2.2", "2.3", "8.2", "14.4"] },
    { "id": 5, "tasks": ["2.4", "3.1", "4.1", "8.3"] },
    { "id": 6, "tasks": ["3.2", "4.2", "6.1", "6.3", "10.1"] },
    { "id": 7, "tasks": ["6.2", "6.4", "7.1", "10.2", "11.1", "12.1", "13.1"] },
    { "id": 8, "tasks": ["7.2", "11.2", "12.2", "13.2", "15.1"] },
    { "id": 9, "tasks": ["15.2", "16.1", "16.2"] },
    { "id": 10, "tasks": ["16.3"] }
  ]
}
```
