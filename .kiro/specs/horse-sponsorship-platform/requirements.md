# Requirements Document

## Introduction

A PHP Laravel SaaS application that enables a horse stable to offer sponsorships of their horses to the public. The platform provides a public-facing horse gallery, a sponsorship signup flow with Stripe-based recurring payments, an authenticated sponsor portal for managing sponsorships, and an admin panel for managing horses, sponsors, and stable branding. The application is single-tenant (one stable per installation) and runs on XAMPP locally for development.

## Glossary

- **Platform**: The Laravel-based horse sponsorship web application
- **Public_Gallery**: The publicly accessible page displaying all available horses with photos and facts
- **Sponsor**: An authenticated user who has an active or past sponsorship of one or more horses
- **Admin**: The stable administrator who manages horses, sponsors, content, and branding
- **Horse**: A horse listed on the platform with associated photos, facts, and sponsorship availability
- **Sponsorship**: A recurring monthly payment relationship between a Sponsor and a Horse via Stripe
- **Child_Sponsorship**: A Sponsorship where the child's name is displayed on the certificate, managed entirely by the parent/guardian Sponsor
- **Certificate**: A downloadable/viewable document confirming the Sponsorship, including the horse name and optionally the child's name
- **Sponsor_Portal**: The authenticated area where Sponsors manage their sponsorships and view updates
- **Admin_Panel**: The authenticated area where the Admin manages horses, sponsors, content, and branding
- **Stripe_Subscription**: A recurring monthly payment managed through the Stripe payment platform
- **Update**: A photo or text post created by the Admin about a specific Horse, visible to that Horse's Sponsors
- **Stable_Branding**: Configurable branding elements including stable name and logo
- **SMTP_Settings**: Admin-configured email delivery settings including host, port, username, password, encryption type, from address, and from name

## Requirements

### Requirement 1: Public Horse Gallery

**User Story:** As a visitor, I want to browse a gallery of horses with their photos and facts, so that I can learn about available horses before deciding to sponsor one.

#### Acceptance Criteria

1. THE Public_Gallery SHALL display all horses that have been added by the Admin, including each horse's name, photos, and facts.
2. THE Public_Gallery SHALL be accessible without authentication.
3. WHEN a visitor views the Public_Gallery, THE Platform SHALL display a call-to-action to sponsor alongside each horse listing.
4. WHEN a visitor selects the sponsorship call-to-action for a horse, THE Platform SHALL direct the visitor to the sponsorship signup flow for that horse.

### Requirement 2: Sponsorship Signup Flow

**User Story:** As a visitor, I want to sign up to sponsor a horse for a monthly amount via Stripe, so that I can support a horse I care about.

#### Acceptance Criteria

1. WHEN a visitor initiates a sponsorship, THE Platform SHALL collect the visitor's email, password, and payment details.
2. WHEN a visitor completes the sponsorship signup, THE Platform SHALL create a Stripe_Subscription for the specified monthly amount.
3. WHEN a visitor completes the sponsorship signup, THE Platform SHALL create an authenticated Sponsor account linked to the Stripe_Subscription.
4. THE Platform SHALL allow the visitor to optionally designate the sponsorship as a Child_Sponsorship by providing the child's name during signup.
5. WHEN a sponsorship is designated as a Child_Sponsorship, THE Platform SHALL store the child's name for display on the Certificate only.

### Requirement 3: Sponsor Authentication

**User Story:** As a sponsor, I want to log in to my account using email/password or a magic link, so that I can access my sponsorship portal securely.

#### Acceptance Criteria

1. THE Platform SHALL provide email and password authentication for Sponsors using Laravel Breeze or Jetstream scaffolding.
2. THE Platform SHALL provide magic link authentication as an alternative login method for Sponsors.
3. WHEN a Sponsor requests a magic link, THE Platform SHALL send a single-use, time-limited authentication link to the Sponsor's registered email address.
4. WHEN a Sponsor authenticates via either method, THE Platform SHALL redirect the Sponsor to the Sponsor_Portal.

### Requirement 4: Sponsor Portal — Manage Sponsorships

**User Story:** As a sponsor, I want to view, update, and cancel my sponsorships, so that I can manage my financial commitments.

#### Acceptance Criteria

1. WHEN a Sponsor accesses the Sponsor_Portal, THE Platform SHALL display all active and past sponsorships for that Sponsor.
2. WHEN a Sponsor chooses to update a sponsorship, THE Platform SHALL allow the Sponsor to modify payment method details via Stripe.
3. WHEN a Sponsor chooses to cancel a sponsorship, THE Platform SHALL cancel the associated Stripe_Subscription.
4. WHEN a Sponsor cancels a sponsorship, THE Platform SHALL confirm the cancellation and display the effective end date.

### Requirement 5: Sponsor Portal — Horse Updates

**User Story:** As a sponsor, I want to view photos and updates about my sponsored horse(s), so that I can stay connected with the horse I support.

#### Acceptance Criteria

1. WHEN a Sponsor accesses the Sponsor_Portal, THE Platform SHALL display Updates posted by the Admin for each of the Sponsor's sponsored horses.
2. THE Platform SHALL display Updates in reverse chronological order.
3. THE Platform SHALL restrict Update visibility to Sponsors who have an active Sponsorship for the associated Horse.

### Requirement 6: Sponsorship Certificate

**User Story:** As a sponsor, I want to download or view my sponsorship certificate, so that I have proof of my sponsorship.

#### Acceptance Criteria

1. WHEN a Sponsor requests a Certificate, THE Platform SHALL generate a Certificate displaying the Sponsor's name, the Horse's name, and the sponsorship start date.
2. WHEN the Sponsorship is a Child_Sponsorship, THE Platform SHALL display the child's name on the Certificate instead of the Sponsor's name.
3. THE Platform SHALL allow Sponsors to download the Certificate as a PDF or view it in the browser.
4. THE Certificate SHALL incorporate the configured Stable_Branding (stable name and logo).

### Requirement 7: Admin — Manage Horses

**User Story:** As an admin, I want to add, edit, and remove horses with their photos and facts, so that I can keep the gallery current.

#### Acceptance Criteria

1. WHEN the Admin adds a horse, THE Admin_Panel SHALL store the horse's name, photos, and facts.
2. WHEN the Admin edits a horse, THE Admin_Panel SHALL update the horse's name, photos, or facts.
3. WHEN the Admin removes a horse, THE Admin_Panel SHALL remove the horse from the Public_Gallery.
4. THE Admin_Panel SHALL allow multiple photos to be uploaded per horse.

### Requirement 8: Admin — Manage Sponsors

**User Story:** As an admin, I want to view all sponsors and cancel sponsorships, so that I can manage the stable's sponsorship relationships.

#### Acceptance Criteria

1. WHEN the Admin accesses the sponsor management section, THE Admin_Panel SHALL display a list of all Sponsors and their associated Sponsorships.
2. WHEN the Admin cancels a Sponsorship, THE Admin_Panel SHALL cancel the associated Stripe_Subscription.
3. WHEN the Admin cancels a Sponsorship, THE Platform SHALL notify the affected Sponsor via email.

### Requirement 9: Admin — Content Updates

**User Story:** As an admin, I want to add photos and updates for a horse that trigger emails to that horse's sponsors, so that sponsors feel engaged.

#### Acceptance Criteria

1. WHEN the Admin creates an Update for a Horse, THE Admin_Panel SHALL store the Update with associated photos and text content.
2. WHEN the Admin creates an Update for a Horse, THE Platform SHALL send an email notification to all Sponsors with an active Sponsorship for that Horse.
3. THE Admin_Panel SHALL allow the Admin to associate one or more photos with each Update.

### Requirement 10: Admin — Stable Branding Configuration

**User Story:** As an admin, I want to configure my stable's branding (name and logo), so that the platform reflects my stable's identity.

#### Acceptance Criteria

1. THE Admin_Panel SHALL allow the Admin to configure the stable name.
2. THE Admin_Panel SHALL allow the Admin to upload a stable logo.
3. WHEN the Admin updates Stable_Branding, THE Platform SHALL reflect the updated branding across the Public_Gallery, Sponsor_Portal, and Certificates.

### Requirement 11: Stripe Payment Integration

**User Story:** As a platform operator, I want recurring monthly payments handled via Stripe, so that sponsorship revenue is collected reliably.

#### Acceptance Criteria

1. WHEN a Sponsorship is created, THE Platform SHALL create a Stripe_Subscription with monthly billing frequency.
2. WHEN a Stripe_Subscription payment fails, THE Platform SHALL notify the Sponsor via email of the payment failure.
3. WHEN a Stripe_Subscription is cancelled (by Sponsor or Admin), THE Platform SHALL update the Sponsorship status to cancelled.
4. THE Platform SHALL use Stripe webhooks to synchronize subscription status changes.

### Requirement 12: Single-Tenant Architecture

**User Story:** As a platform operator, I want the application to serve a single stable per installation, so that deployment and configuration remain simple.

#### Acceptance Criteria

1. THE Platform SHALL operate as a single-tenant application serving one stable per installation.
2. THE Platform SHALL store all Stable_Branding configuration locally within the application database.
3. THE Platform SHALL use Laravel framework with XAMPP-compatible configuration for local development.

### Requirement 13: Admin — SMTP Email Configuration

**User Story:** As an admin, I want to configure SMTP email settings from the admin panel settings page, so that I can manage email delivery without needing to edit server configuration files.

#### Acceptance Criteria

1. THE Admin_Panel SHALL provide a settings page where the Admin can configure SMTP settings including: host, port, username, password, encryption type (TLS/SSL/none), from address, and from name.
2. WHEN the Admin saves SMTP settings, THE Platform SHALL store the settings in the database with the password encrypted.
3. WHEN sending any email, THE Platform SHALL use the admin-configured SMTP settings if they exist, falling back to environment file defaults otherwise.
4. THE Admin_Panel SHALL provide a "Send Test Email" button that sends a test email to the Admin's registered email address using the configured SMTP settings.
