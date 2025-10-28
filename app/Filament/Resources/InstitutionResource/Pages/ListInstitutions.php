<?php

namespace App\Filament\Resources\InstitutionResource\Pages;

use App\Filament\Resources\InstitutionResource;
use App\Filament\Resources\InstitutionResource\Actions\ExportInstitutionsAction;
use App\Filament\Resources\InstitutionResource\Actions\ImportInstitutionsAction;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInstitutions extends ListRecords
{
    protected static string $resource = InstitutionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ImportInstitutionsAction::make(),
            ExportInstitutionsAction::make(),
        ];
    }
}
