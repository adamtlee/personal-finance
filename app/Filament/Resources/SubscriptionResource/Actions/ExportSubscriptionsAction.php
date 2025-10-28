<?php

namespace App\Filament\Resources\SubscriptionResource\Actions;

use Filament\Actions\Action;

class ExportSubscriptionsAction extends Action
{
    public static function getDefaultName(): string
    {
        return 'export';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Export CSV')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('success')
            ->url(fn () => route('subscriptions.export'));
    }
}

