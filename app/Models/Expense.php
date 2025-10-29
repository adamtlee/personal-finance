<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    protected $fillable = [
        'expense_id',
        'date',
        'merchant_name',
        'payment_method',
        'category',
        'amount',
        'currency',
        'receipt_image',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getPaymentMethods(): array
    {
        return [
            'cash' => 'Cash',
            'credit_card' => 'Credit Card',
            'debit_card' => 'Debit Card',
            'bank_transfer' => 'Bank Transfer',
            'check' => 'Check',
            'digital_wallet' => 'Digital Wallet (PayPal, Venmo, etc.)',
            'cryptocurrency' => 'Cryptocurrency',
            'other' => 'Other',
        ];
    }

    public static function getCategories(): array
    {
        return [
            'food_dining' => 'Food & Dining',
            'transportation' => 'Transportation',
            'shopping' => 'Shopping',
            'entertainment' => 'Entertainment',
            'utilities' => 'Utilities',
            'healthcare' => 'Healthcare',
            'education' => 'Education',
            'travel' => 'Travel',
            'business' => 'Business',
            'personal_care' => 'Personal Care',
            'home_garden' => 'Home & Garden',
            'insurance' => 'Insurance',
            'charity' => 'Charity',
            'subscriptions' => 'Subscriptions',
            'other' => 'Other',
        ];
    }

    public static function getCurrencies(): array
    {
        return [
            'USD' => 'US Dollar ($)',
            'EUR' => 'Euro (€)',
            'GBP' => 'British Pound (£)',
            'CAD' => 'Canadian Dollar (C$)',
            'AUD' => 'Australian Dollar (A$)',
            'JPY' => 'Japanese Yen (¥)',
            'CHF' => 'Swiss Franc (CHF)',
            'CNY' => 'Chinese Yuan (¥)',
            'INR' => 'Indian Rupee (₹)',
            'BRL' => 'Brazilian Real (R$)',
        ];
    }

    /**
     * Generate a unique expense ID
     * Format: EXP-YYYY-NNNNNN (e.g., EXP-2024-000001)
     */
    public static function generateExpenseId(): string
    {
        $year = date('Y');
        $prefix = "EXP-{$year}-";
        
        // Get the last expense ID for this year
        $lastExpense = static::where('expense_id', 'like', $prefix . '%')
            ->orderBy('expense_id', 'desc')
            ->first();
        
        if ($lastExpense) {
            // Extract the number part and increment it
            $lastNumber = (int) substr($lastExpense->expense_id, strlen($prefix));
            $nextNumber = $lastNumber + 1;
        } else {
            // First expense of the year
            $nextNumber = 1;
        }
        
        // Format with leading zeros (6 digits)
        $expenseId = $prefix . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
        
        // Double-check uniqueness (in case of race conditions)
        $attempts = 0;
        while (static::where('expense_id', $expenseId)->exists() && $attempts < 10) {
            $nextNumber++;
            $expenseId = $prefix . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
            $attempts++;
        }
        
        return $expenseId;
    }

    /**
     * Get the next expense ID that would be generated (for display purposes)
     */
    public static function getNextExpenseId(): string
    {
        return static::generateExpenseId();
    }

    /**
     * Boot method to auto-generate expense_id when creating
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($expense) {
            if (empty($expense->expense_id)) {
                $expense->expense_id = static::generateExpenseId();
            }
        });
    }
}
