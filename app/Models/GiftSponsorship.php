<?php

namespace App\Models;

use App\Enums\GiftSponsorshipStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GiftSponsorship extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'code',
        'horse_id',
        'purchaser_name',
        'purchaser_email',
        'recipient_name',
        'recipient_message',
        'months',
        'amount_paid',
        'stripe_payment_intent_id',
        'status',
        'redeemed_by_user_id',
        'sponsorship_id',
        'redeemed_at',
        'expires_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => GiftSponsorshipStatus::class,
            'months' => 'integer',
            'amount_paid' => 'integer',
            'redeemed_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Get the horse associated with this gift.
     */
    public function horse(): BelongsTo
    {
        return $this->belongsTo(Horse::class);
    }

    /**
     * Get the user who redeemed this gift.
     */
    public function redeemedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'redeemed_by_user_id');
    }

    /**
     * Get the sponsorship created from this gift.
     */
    public function sponsorship(): BelongsTo
    {
        return $this->belongsTo(Sponsorship::class);
    }

    /**
     * Check if this gift has been redeemed.
     */
    public function isRedeemed(): bool
    {
        return $this->status === GiftSponsorshipStatus::Redeemed;
    }

    /**
     * Check if this gift is still available to redeem.
     */
    public function isAvailable(): bool
    {
        return $this->status === GiftSponsorshipStatus::Purchased
            && $this->expires_at->isFuture();
    }

    /**
     * Check if this gift code has expired.
     */
    public function isExpired(): bool
    {
        return $this->status === GiftSponsorshipStatus::Expired
            || ($this->status === GiftSponsorshipStatus::Purchased && $this->expires_at->isPast());
    }

    /**
     * Generate a unique gift code.
     */
    public static function generateCode(): string
    {
        do {
            $code = strtoupper(substr(bin2hex(random_bytes(8)), 0, 16));
            // Format as XXXX-XXXX-XXXX-XXXX for readability
            $code = implode('-', str_split($code, 4));
        } while (self::where('code', $code)->exists());

        return $code;
    }
}
