<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UpdatePhoto extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'update_id',
        'path',
    ];

    /**
     * Get the horse update that owns the photo.
     */
    public function horseUpdate(): BelongsTo
    {
        return $this->belongsTo(HorseUpdate::class, 'update_id');
    }
}
