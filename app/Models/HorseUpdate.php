<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HorseUpdate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'horse_id',
        'title',
        'body',
    ];

    /**
     * Get the horse that this update belongs to.
     */
    public function horse(): BelongsTo
    {
        return $this->belongsTo(Horse::class);
    }

    /**
     * Get the photos for this update.
     */
    public function photos(): HasMany
    {
        return $this->hasMany(UpdatePhoto::class, 'update_id');
    }
}
