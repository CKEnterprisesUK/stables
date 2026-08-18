<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Horse extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'facts',
        'date_of_birth',
        'breed',
        'colour',
        'gender',
        'height_hands',
        'arrival_date',
        'personality',
        'favourite_treats',
        'backstory',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'arrival_date' => 'date',
        'height_hands' => 'decimal:1',
    ];

    /**
     * Get the horse's age calculated from date of birth.
     */
    public function getAgeAttribute(): ?int
    {
        if (!$this->date_of_birth) {
            return null;
        }

        return $this->date_of_birth->age;
    }

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
