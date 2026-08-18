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
     * Determine if this is a child sponsorship.
     */
    public function isChildSponsorship(): bool
    {
        return !is_null($this->child_name);
    }
}
