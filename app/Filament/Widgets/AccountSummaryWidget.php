<?php

namespace App\Filament\Widgets;

use App\Models\Account;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AccountSummaryWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $accountTypes = Account::getTypes();
        $stats = [];

        foreach ($accountTypes as $type => $label) {
            $total = Account::where('type', $type)->sum('amount');
            
            $stats[] = Stat::make($label, '$' . number_format($total, 2))
                ->description('Total ' . strtolower($label) . ' balance')
                ->color($this->getColorForType($type))
                ->icon($this->getIconForType($type));
        }

        return $stats;
    }

    private function getColorForType(string $type): string
    {
        return match ($type) {
            'checking' => 'success',
            'savings' => 'info',
            'credit_card' => 'warning',
            'money_market' => 'primary',
            'cd' => 'secondary',
            'investment' => 'success',
            'other' => 'gray',
        };
    }

    private function getIconForType(string $type): string
    {
        return match ($type) {
            'checking' => 'heroicon-o-credit-card',
            'savings' => 'heroicon-o-building-library',
            'credit_card' => 'heroicon-o-banknotes',
            'money_market' => 'heroicon-o-chart-bar',
            'cd' => 'heroicon-o-clock',
            'investment' => 'heroicon-o-chart-pie',
            'other' => 'heroicon-o-wallet',
        };
    }
}
