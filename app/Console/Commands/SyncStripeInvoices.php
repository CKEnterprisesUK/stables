<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Models\Sponsorship;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Laravel\Cashier\Cashier;

class SyncStripeInvoices extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'invoices:sync
                            {--user= : Sync invoices for a specific user ID only}
                            {--limit=100 : Maximum number of invoices to fetch per customer from Stripe}';

    /**
     * The console command description.
     */
    protected $description = 'Sync invoices from the Stripe API into the local database, backfilling any missing records.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $query = User::whereNotNull('stripe_id')->where('stripe_id', '!=', '');

        if ($userId = $this->option('user')) {
            $query->where('id', $userId);
        }

        $users = $query->get();

        if ($users->isEmpty()) {
            $this->info('No users with a Stripe customer ID found.');
            return Command::SUCCESS;
        }

        $this->info("Syncing invoices for {$users->count()} user(s)...");

        $totalCreated = 0;
        $totalUpdated = 0;
        $totalSkipped = 0;

        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        foreach ($users as $user) {
            [$created, $updated, $skipped] = $this->syncUserInvoices($user);
            $totalCreated += $created;
            $totalUpdated += $updated;
            $totalSkipped += $skipped;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Sync complete:");
        $this->line("  Created: {$totalCreated}");
        $this->line("  Updated: {$totalUpdated}");
        $this->line("  Skipped (errors): {$totalSkipped}");

        return Command::SUCCESS;
    }

    /**
     * Sync all Stripe invoices for a single user.
     *
     * @return array{int, int, int} [created, updated, skipped]
     */
    private function syncUserInvoices(User $user): array
    {
        $created = 0;
        $updated = 0;
        $skipped = 0;

        try {
            $limit = (int) $this->option('limit');
            $stripeInvoices = Cashier::stripe()->invoices->all([
                'customer' => $user->stripe_id,
                'limit' => min($limit, 100),
            ]);

            foreach ($stripeInvoices->autoPagingIterator() as $index => $stripeInvoice) {
                if ($index >= $limit) {
                    break;
                }

                $result = $this->upsertInvoice($user, $stripeInvoice);

                if ($result === 'created') {
                    $created++;
                } elseif ($result === 'updated') {
                    $updated++;
                }
            }
        } catch (\Exception $e) {
            $skipped++;
            $this->error("  Error syncing user {$user->id} ({$user->email}): {$e->getMessage()}");
        }

        return [$created, $updated, $skipped];
    }

    /**
     * Create or update a local invoice record from a Stripe invoice object.
     */
    private function upsertInvoice(User $user, object $stripeInvoice): string
    {
        $stripeInvoiceId = $stripeInvoice->id;
        $stripeSubscriptionId = $stripeInvoice->subscription ?? null;

        // Try to link to a local sponsorship via the subscription ID
        $sponsorship = null;
        if ($stripeSubscriptionId) {
            $sponsorship = Sponsorship::where('stripe_subscription_id', $stripeSubscriptionId)
                ->where('user_id', $user->id)
                ->first();
        }

        $existing = Invoice::where('stripe_invoice_id', $stripeInvoiceId)->first();

        Invoice::updateOrCreate(
            ['stripe_invoice_id' => $stripeInvoiceId],
            [
                'user_id' => $user->id,
                'sponsorship_id' => $sponsorship?->id,
                'amount' => $stripeInvoice->amount_paid ?? $stripeInvoice->total ?? 0,
                'currency' => $stripeInvoice->currency ?? config('cashier.currency', 'gbp'),
                'status' => $stripeInvoice->status ?? 'paid',
                'invoice_date' => isset($stripeInvoice->created)
                    ? Carbon::createFromTimestamp($stripeInvoice->created)
                    : now(),
                'hosted_invoice_url' => $stripeInvoice->hosted_invoice_url ?? null,
                'pdf_url' => $stripeInvoice->invoice_pdf ?? null,
                'description' => $stripeInvoice->description
                    ?? ($stripeInvoice->lines->data[0]->description ?? null),
            ]
        );

        return $existing ? 'updated' : 'created';
    }
}
