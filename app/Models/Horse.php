<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Horse extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'facts',
    ];

    /**
     * Get the photos for the horse, ordered by sort_order.
     */
    public function photos(): HasMany
    {
        return $this->hasMany(HorsePhoto::class)->orderBy('sort_order');
    }

    /**
     * Get the updates for the horse, ordered by newest first.
     */
    public function updates(): HasMany
    {
        return $this->hasMany(HorseUpdate::class)->orderByDesc('created_at');
    }

    /**
     * Get the sponsorships for the horse.
     */
    public function sponsorships(): HasMany
    {
        return $this->hasMany(Sponsorship::class);
    }

    /**
     * Get users with active sponsorships for this horse.
     */
    public function activeSponsors(): Collection
    {
        return User::whereHas('sponsorships', function ($query) {
            $query->where('horse_id', $this->id)->where('status', 'active');
        })->get();
    }
}
