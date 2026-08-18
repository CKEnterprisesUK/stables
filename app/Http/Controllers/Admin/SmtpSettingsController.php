<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SmtpSettingsRequest;
use App\Mail\TestSmtpMail;
use App\Models\SmtpSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class SmtpSettingsController extends Controller
{
    /**
     * Show the SMTP settings form.
     */
    public function edit(): View
    {
        $settings = SmtpSetting::first();

        return view('admin.settings.smtp', compact('settings'));
    }

    /**
     * Update the SMTP settings.
     */
    public function update(SmtpSettingsRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $attributes = [
            'host' => $data['host'],
            'port' => $data['port'],
            'username' => $data['username'],
            'encryption' => $data['encryption'],
            'from_address' => $data['from_address'],
            'from_name' => $data['from_name'],
        ];

        if (!empty($data['password'])) {
            $attributes['password_encrypted'] = encrypt($data['password']);
        }

        SmtpSetting::updateOrCreate(['id' => 1], $attributes);

        return back()->with('status', 'SMTP settings saved.');
    }

    /**
     * Send a test email to the authenticated admin.
     */
    public function sendTestEmail(): RedirectResponse
    {
        $admin = auth()->user();

        try {
            Mail::to($admin->email)->send(new TestSmtpMail());
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Failed to send test email. Please check your SMTP settings and try again.');
        }

        return back()->with('status', 'Test email sent to ' . $admin->email);
    }
}
