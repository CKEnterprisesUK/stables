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
    public function getStripeSecretAttribute(): string
    {
        return decrypt($this->stripe_secret_encrypted);
    }

    /**
     * Get the decrypted webhook secret.
     */
    public function getWebhookSecretAttribute(): string
    {
        return decrypt($this->webhook_secret_encrypted);
    }
}
