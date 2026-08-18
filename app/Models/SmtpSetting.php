<?php

namespace App\Models;

use App\Enums\SmtpEncryption;
use Illuminate\Database\Eloquent\Model;

class SmtpSetting extends Model
{
    /**
     * Indicates that the model does not use created_at timestamp.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'host',
        'port',
        'username',
        'password_encrypted',
        'encryption',
        'from_address',
        'from_name',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'port' => 'integer',
            'encryption' => SmtpEncryption::class,
        ];
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::saving(function (SmtpSetting $model) {
            $model->updated_at = now();
        });
    }

    /**
     * Get the decrypted password.
     */
    public function getPasswordAttribute(): string
    {
        return decrypt($this->password_encrypted);
    }
}
