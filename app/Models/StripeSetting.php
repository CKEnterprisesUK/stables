<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StripeSetting extends Model
{
    /**
     * Indicates that the model does not use created_at timestamp.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'stripe_key',
        'stripe_secret_encrypted',
        'webhook_secret_encrypted',
        'price_id',
        'stripe_account_id',
        'stripe_connect_status',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::saving(function (StripeSetting $model) {
            $model->updated_at = now();
        });
    }

    /**
     * Get the decrypted Stripe secret key.
     */
    public function getStripeSecretAttribute(): ?string
    {
        return $this->stripe_secret_encrypted
            ? decrypt($this->stripe_secret_encrypted)
            : null;
    }

    /**
     * Get the decrypted webhook secret.
     */
    public function getWebhookSecretAttribute(): ?string
    {
        return $this->webhook_secret_encrypted
            ? decrypt($this->webhook_secret_encrypted)
            : null;
    }

    /**
     * Check if Stripe Connect is active.
     */
    public function isConnected(): bool
    {
        return $this->stripe_connect_status === 'connected' && !empty($this->stripe_account_id);
    }

    /**
     * Check if onboarding is still pending.
     */
    public function isPending(): bool
    {
        return $this->stripe_connect_status === 'pending';
    }
}
