<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Account extends Model
{
    protected $fillable = [
        'name',
        'type',
        'amount',
        'user_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getTypes(): array
    {
        return [
            'checking' => 'Checking',
            'savings' => 'Savings',
            'credit_card' => 'Credit Card',
            'money_market' => 'Money Market',
            'cd' => 'Certificate of Deposit',
            'investment' => 'Investment',
            'other' => 'Other',
        ];
    }
}
