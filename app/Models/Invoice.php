<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'sponsorship_id',
        'stripe_invoice_id',
        'amount',
        'currency',
        'status',
        'invoice_date',
        'hosted_invoice_url',
        'pdf_url',
        'description',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'invoice_date' => 'datetime',
        ];
    }

    /**
     * Get the user (sponsor) that owns the invoice.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the sponsorship associated with the invoice.
     */
    public function sponsorship(): BelongsTo
    {
        return $this->belongsTo(Sponsorship::class);
    }

    /**
     * Get the formatted amount in the invoice currency.
     */
    public function getFormattedAmountAttribute(): string
    {
        $symbol = match (strtolower($this->currency)) {
            'gbp' => '£',
            'eur' => '€',
            'usd' => '$',
            default => strtoupper($this->currency) . ' ',
        };

        return $symbol . number_format($this->amount / 100, 2);
    }
}
