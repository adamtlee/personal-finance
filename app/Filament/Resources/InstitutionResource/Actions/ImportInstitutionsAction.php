<?php

namespace App\Filament\Resources\InstitutionResource\Actions;

use App\Models\Institution;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

class ImportInstitutionsAction extends Action
{
    public static function getDefaultName(): string
    {
        return 'import_institutions';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Import Institutions')
            ->icon('heroicon-o-arrow-up-tray')
            ->color('success')
            ->form([
                FileUpload::make('csv_file')
                    ->label('CSV File')
                    ->acceptedFileTypes(['text/csv', 'application/csv', '.csv'])
                    ->required()
                    ->helperText('Upload a CSV file with columns: name, type, website, description')
                    ->disk('local')
                    ->directory('imports')
                    ->visibility('private'),
            ])
            ->action(function (array $data): void {
                $this->importInstitutions($data['csv_file']);
            });
    }

    protected function importInstitutions(string $filePath): void
    {
        try {
            $csv = Reader::createFromPath(Storage::path($filePath), 'r');
            $csv->setHeaderOffset(0);
            
            $records = $csv->getRecords();
            $imported = 0;
            $errors = [];

            foreach ($records as $index => $record) {
                try {
                    // Validate required fields
                    if (empty($record['name']) || empty($record['type'])) {
                        $errors[] = "Row " . ($index + 2) . ": Missing required fields (name, type)";
                        continue;
                    }

                    // Validate type
                    if (!array_key_exists($record['type'], Institution::getTypes())) {
                        $errors[] = "Row " . ($index + 2) . ": Invalid type '{$record['type']}'. Valid types: " . implode(', ', array_keys(Institution::getTypes()));
                        continue;
                    }

                    // Create institution
                    Institution::create([
                        'name' => trim($record['name']),
                        'type' => trim($record['type']),
                        'website' => !empty($record['website']) ? trim($record['website']) : null,
                        'description' => !empty($record['description']) ? trim($record['description']) : null,
                    ]);

                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            // Clean up the uploaded file
            Storage::delete($filePath);

            // Show notification
            if ($imported > 0) {
                Notification::make()
                    ->title('Import Successful')
                    ->body("Successfully imported {$imported} institutions.")
                    ->success()
                    ->send();
            }

            if (!empty($errors)) {
                Notification::make()
                    ->title('Import Completed with Errors')
                    ->body("Imported {$imported} institutions. " . count($errors) . " rows had errors: " . implode('; ', array_slice($errors, 0, 5)) . (count($errors) > 5 ? '...' : ''))
                    ->warning()
                    ->send();
            }

        } catch (\Exception $e) {
            Notification::make()
                ->title('Import Failed')
                ->body('Error reading CSV file: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}
