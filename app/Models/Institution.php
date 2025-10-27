<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Institution extends Model
{
    protected $fillable = [
        'name',
        'type',
        'website',
        'description',
    ];

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }

    public static function getTypes(): array
    {
        return [
            'bank' => 'Bank',
            'investment_company' => 'Investment Company',
            'credit_union' => 'Credit Union',
            'brokerage' => 'Brokerage',
            'other' => 'Other',
        ];
    }
}
