<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'name',
        'price',
        'billing_frequency',
        'description',
        'category',
        'status',
        'next_billing_date',
        'user_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'next_billing_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getBillingFrequencies(): array
    {
        return [
            'weekly' => 'Weekly',
            'monthly' => 'Monthly',
            'yearly' => 'Yearly',
        ];
    }

    public static function getCategories(): array
    {
        return [
            'entertainment' => 'Entertainment',
            'software' => 'Software',
            'news' => 'News & Media',
            'fitness' => 'Fitness & Health',
            'education' => 'Education',
            'productivity' => 'Productivity',
            'cloud_storage' => 'Cloud Storage',
            'music' => 'Music',
            'video' => 'Video Streaming',
            'gaming' => 'Gaming',
            'food_delivery' => 'Food Delivery',
            'transportation' => 'Transportation',
            'other' => 'Other',
        ];
    }

    public static function getStatuses(): array
    {
        return [
            'active' => 'Active',
            'paused' => 'Paused',
            'cancelled' => 'Cancelled',
            'expired' => 'Expired',
        ];
    }
}
