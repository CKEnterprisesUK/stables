<?php

namespace App\Console\Commands;

use App\Enums\GiftSponsorshipStatus;
use App\Enums\SponsorshipStatus;
use App\Models\GiftSponsorship;
use App\Models\Sponsorship;
use App\Notifications\SponsorshipExpiringNotification;
use Illuminate\Console\Command;

class CheckExpiringSponsorships extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'sponsorships:check-expiring';

    /**
     * The console command description.
     */
    protected $description = 'Check for expiring gift sponsorships, send reminder emails, and mark expired ones.';

    /**
     * Reminder thresholds in days before expiry.
     */
    private const REMINDER_DAYS = [30, 7, 1];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Checking for expiring gift sponsorships...');

        $this->sendExpiryReminders();
        $this->markExpiredSponsorships();
        $this->markExpiredGiftCodes();

        $this->info('Done.');

        return Command::SUCCESS;
    }

    /**
     * Send reminder emails for sponsorships expiring at the threshold days.
     */
    private function sendExpiryReminders(): void
    {
        foreach (self::REMINDER_DAYS as $days) {
            $targetDate = now()->addDays($days)->startOfDay();

            $sponsorships = Sponsorship::with(['user', 'horse'])
                ->where('status', SponsorshipStatus::Gift)
                ->whereNotNull('ends_at')
                ->whereDate('ends_at', $targetDate->toDateString())
                ->get();

            foreach ($sponsorships as $sponsorship) {
                if ($sponsorship->user) {
                    $sponsorship->user->notify(
                        new SponsorshipExpiringNotification($sponsorship, $days)
                    );
                    $this->line("  Sent {$days}-day reminder to {$sponsorship->user->email} for {$sponsorship->horse->name}");
                }
            }
        }
    }

    /**
     * Mark gift sponsorships that have passed their ends_at as expired.
     */
    private function markExpiredSponsorships(): void
    {
        $expired = Sponsorship::with(['user', 'horse'])
            ->where('status', SponsorshipStatus::Gift)
            ->whereNotNull('ends_at')
            ->where('ends_at', '<=', now())
            ->get();

        foreach ($expired as $sponsorship) {
            $sponsorship->update(['status' => SponsorshipStatus::Expired]);

            // Send expiry notification
            if ($sponsorship->user) {
                $sponsorship->user->notify(
                    new SponsorshipExpiringNotification($sponsorship, 0)
                );
                $this->line("  Marked expired and notified: {$sponsorship->user->email} for {$sponsorship->horse->name}");
            }
        }

        $this->info("Marked {$expired->count()} sponsorship(s) as expired.");
    }

    /**
     * Mark unredeemed gift codes that have passed their expires_at as expired.
     */
    private function markExpiredGiftCodes(): void
    {
        $expiredCodes = GiftSponsorship::where('status', GiftSponsorshipStatus::Purchased)
            ->where('expires_at', '<=', now())
            ->update(['status' => GiftSponsorshipStatus::Expired->value]);

        $this->info("Marked {$expiredCodes} unredeemed gift code(s) as expired.");
    }
}
