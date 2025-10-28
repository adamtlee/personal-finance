<?php

namespace App\Filament\Resources\AccountResource\Pages;

use App\Filament\Resources\AccountResource;
use App\Filament\Resources\AccountResource\Actions\ExportAccountsAction;
use App\Models\Account;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListAccounts extends ListRecords
{
    protected static string $resource = AccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ExportAccountsAction::make(),
        ];
    }
    
    protected function getTableQuery(): Builder
    {
        return Account::query()->where('user_id', auth()->id());
    }
}
