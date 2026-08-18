<?php

namespace App\Models;

use App\Enums\SponsorshipStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sponsorship extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'horse_id',
        'stripe_subscription_id',
        'monthly_amount',
        'child_name',
        'status',
        'ends_at',
        'gift_sponsorship_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => SponsorshipStatus::class,
            'monthly_amount' => 'integer',
            'ends_at' => 'datetime',
        ];
    }

    /**
     * Get the user (sponsor) that owns the sponsorship.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the horse associated with the sponsorship.
     */
    public function horse(): BelongsTo
    {
        return $this->belongsTo(Horse::class);
    }

    /**
     * Get the gift sponsorship that created this sponsorship (if any).
     */
    public function giftSponsorship(): BelongsTo
    {
        return $this->belongsTo(GiftSponsorship::class);
    }

    /**
     * Determine if this is a child sponsorship.
     */
    public function isChildSponsorship(): bool
    {
        return !is_null($this->child_name);
    }

    /**
     * Determine if this sponsorship was created from a gift.
     */
    public function isGift(): bool
    {
        return $this->status === SponsorshipStatus::Gift;
    }

    /**
     * Determine if this gift sponsorship has expired.
     */
    public function isExpired(): bool
    {
        return $this->status === SponsorshipStatus::Expired;
    }
}
