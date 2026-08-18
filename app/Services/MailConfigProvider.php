<?php

namespace App\Services;

use App\Models\SmtpSetting;
use Illuminate\Database\QueryException;

class MailConfigProvider
{
    /**
     * Apply SMTP settings from the database to Laravel's mail config.
     * Falls back to .env defaults if no DB settings exist or if the
     * table has not been migrated yet.
     */
    public function apply(): void
    {
        try {
            $settings = SmtpSetting::first();
        } catch (QueryException) {
            // Table doesn't exist yet (migrations not run)
            return;
        }

        if (!$settings) {
            return;
        }

        $encryption = $settings->encryption->value === 'none' ? null : $settings->encryption->value;

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
