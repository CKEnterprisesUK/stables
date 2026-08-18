<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Horse;
use App\Models\SmtpSetting;
use App\Models\StableBranding;
use App\Models\StripeSetting;
use Illuminate\View\View;

class SetupController extends Controller
{
    /**
     * Display the setup checklist for the platform.
     */
    public function index(): View
    {
        $steps = $this->buildChecklist();
        $completedCount = collect($steps)->where('completed', true)->count();
        $totalCount = count($steps);

        return view('admin.setup.index', compact('steps', 'completedCount', 'totalCount'));
    }

    /**
     * Check if there are any incomplete setup steps.
     */
    public static function hasIncompleteSteps(): bool
    {
        $stripe = StripeSetting::first();
        $smtp = SmtpSetting::first();
        $branding = StableBranding::first();
        $horseCount = Horse::count();

        $checks = [
            $stripe && $stripe->isConnected(),
            $stripe && !empty($stripe->sponsorship_amount_cents) && !empty($stripe->price_id),
            $smtp && !empty($smtp->host),
            $branding && (!empty($branding->logo_path) || !empty($branding->name)),
            $horseCount > 0,
        ];

        return in_array(false, $checks, true);
    }

    /**
     * Build the checklist steps with their completion status.
     *
     * @return array<int, array{key: string, title: string, description: string, why: string, completed: bool, action_url: string, action_label: string}>
     */
    protected function buildChecklist(): array
    {
        $stripe = StripeSetting::first();
        $smtp = SmtpSetting::first();
        $branding = StableBranding::first();
        $horseCount = Horse::count();

        return [
            [
                'key' => 'stripe',
                'title' => 'Connect Stripe',
                'description' => 'Link your Stripe account so sponsors can pay securely by card. You\'ll be guided through Stripe\'s setup process.',
                'why' => 'Without a payment provider, sponsors won\'t be able to complete their sign-up. Stripe handles card processing, payouts, and compliance so you don\'t have to.',
                'completed' => $stripe && $stripe->isConnected(),
                'action_url' => route('admin.settings.stripe'),
                'action_label' => 'Set up payments',
            ],
            [
                'key' => 'pricing',
                'title' => 'Set sponsorship price',
                'description' => 'Choose the monthly amount that sponsors will be charged. The payment product is created automatically in Stripe.',
                'why' => 'Sponsors need to know how much they\'ll pay each month. Setting the price also configures the Stripe product needed to process payments.',
                'completed' => $stripe && !empty($stripe->sponsorship_amount_cents) && !empty($stripe->price_id),
                'action_url' => route('admin.settings.general'),
                'action_label' => 'Set pricing',
            ],
            [
                'key' => 'email',
                'title' => 'Configure email',
                'description' => 'Add your SMTP details so the platform can send welcome emails, update notifications, and magic login links.',
                'why' => 'Sponsors receive email notifications when you post updates about their horse. Without email configured, they\'ll miss important news and won\'t be able to log in via magic links.',
                'completed' => $smtp && !empty($smtp->host),
                'action_url' => route('admin.settings.smtp'),
                'action_label' => 'Set up email',
            ],
            [
                'key' => 'branding',
                'title' => 'Add your branding',
                'description' => 'Upload your logo and set your stable name. This appears on the public gallery, emails, and sponsor dashboard.',
                'why' => 'Branding builds trust with sponsors. A recognisable logo and name makes the sponsorship page feel professional and authentic.',
                'completed' => $branding && (!empty($branding->logo_path) || !empty($branding->name)),
                'action_url' => route('admin.branding.edit'),
                'action_label' => 'Customise branding',
            ],
            [
                'key' => 'horse',
                'title' => 'Add your first horse',
                'description' => 'Create a horse profile with photos and a description. Sponsors browse these profiles to choose which horse to support.',
                'why' => 'Horses are the heart of the platform. Sponsors need at least one horse to choose from before they can sign up.',
                'completed' => $horseCount > 0,
                'action_url' => route('admin.horses.create'),
                'action_label' => 'Add a horse',
            ],
        ];
    }
}
