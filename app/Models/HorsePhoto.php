<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HorsePhoto extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'horse_id',
        'path',
        'sort_order',
    ];

    /**
     * Get the horse that owns the photo.
     */
    public function horse(): BelongsTo
    {
        return $this->belongsTo(Horse::class);
    }
}
