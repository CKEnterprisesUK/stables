-- =============================================================================
-- Gift Sponsorship Feature — Production SQL Statements
-- Run these manually on your production database.
-- =============================================================================
-- Date: 2026-08-18
-- Description: Creates the gift_sponsorships table and adds gift_sponsorship_id
--              to the existing sponsorships table.
-- =============================================================================

-- 1. Create the gift_sponsorships table
-- -----------------------------------------------------------------------------
CREATE TABLE `gift_sponsorships` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(32) NOT NULL,
    `horse_id` BIGINT UNSIGNED NOT NULL,
    `purchaser_name` VARCHAR(255) NOT NULL,
    `purchaser_email` VARCHAR(255) NOT NULL,
    `recipient_name` VARCHAR(255) NULL,
    `recipient_message` VARCHAR(500) NULL,
    `months` INT NOT NULL,
    `amount_paid` INT NOT NULL COMMENT 'Total amount in cents (one-time payment)',
    `stripe_payment_intent_id` VARCHAR(255) NULL,
    `status` VARCHAR(255) NOT NULL DEFAULT 'purchased' COMMENT 'purchased, redeemed, expired',
    `redeemed_by_user_id` BIGINT UNSIGNED NULL,
    `sponsorship_id` BIGINT UNSIGNED NULL,
    `redeemed_at` DATETIME NULL,
    `expires_at` DATETIME NOT NULL COMMENT 'When the gift code expires (1 year from purchase)',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,

    UNIQUE KEY `gift_sponsorships_code_unique` (`code`),

    CONSTRAINT `gift_sponsorships_horse_id_foreign`
        FOREIGN KEY (`horse_id`) REFERENCES `horses` (`id`) ON DELETE CASCADE,

    CONSTRAINT `gift_sponsorships_redeemed_by_user_id_foreign`
        FOREIGN KEY (`redeemed_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,

    CONSTRAINT `gift_sponsorships_sponsorship_id_foreign`
        FOREIGN KEY (`sponsorship_id`) REFERENCES `sponsorships` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 2. Add gift_sponsorship_id column to the sponsorships table
-- -----------------------------------------------------------------------------
ALTER TABLE `sponsorships`
    ADD COLUMN `gift_sponsorship_id` BIGINT UNSIGNED NULL AFTER `ends_at`,
    ADD CONSTRAINT `sponsorships_gift_sponsorship_id_foreign`
        FOREIGN KEY (`gift_sponsorship_id`) REFERENCES `gift_sponsorships` (`id`) ON DELETE SET NULL;


-- =============================================================================
-- NOTES:
-- - The `status` column on the existing `sponsorships` table now accepts these
--   values: 'active', 'gift', 'cancelled', 'expired'
--   No schema change needed — it's a VARCHAR column, the new values are handled
--   in application code via the SponsorshipStatus enum.
--
-- - After running these statements, deploy the updated application code.
--
-- - Set up a cron job to run: php artisan schedule:run (every minute)
--   The sponsorships:check-expiring command is scheduled daily at 08:00.
--   If you already have the scheduler running, no additional cron entry is needed.
-- =============================================================================
