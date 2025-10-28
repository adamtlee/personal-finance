<?php

namespace App\Filament\Resources\SubscriptionResource\Pages;

use App\Filament\Resources\SubscriptionResource;
use App\Filament\Resources\SubscriptionResource\Actions\ExportSubscriptionsAction;
use App\Filament\Resources\SubscriptionResource\Actions\ImportSubscriptionsAction;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubscriptions extends ListRecords
{
    protected static string $resource = SubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ImportSubscriptionsAction::make(),
            ExportSubscriptionsAction::make(),
        ];
    }
}
