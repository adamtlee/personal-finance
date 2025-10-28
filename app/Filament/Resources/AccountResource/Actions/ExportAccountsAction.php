<?php

namespace App\Filament\Resources\AccountResource\Actions;

use Filament\Actions\Action;

class ExportAccountsAction extends Action
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
            ->url(fn () => route('accounts.export'));
    }
}
