<?php

namespace App\Filament\Resources\AccountResource\Actions;

use App\Models\Account;
use App\Models\Institution;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

class ImportAccountsAction extends Action
{
    public static function getDefaultName(): string
    {
        return 'import_accounts';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Import Accounts')
            ->icon('heroicon-o-arrow-up-tray')
            ->color('success')
            ->form([
                FileUpload::make('csv_file')
                    ->label('CSV File')
                    ->acceptedFileTypes(['text/csv', 'application/csv', '.csv'])
                    ->required()
                    ->helperText('Upload a CSV file with columns: name, type, amount, institution_name (optional)')
                    ->disk('local')
                    ->directory('imports')
                    ->visibility('private'),
            ])
            ->action(function (array $data): void {
                $this->importAccounts($data['csv_file']);
            });
    }

    protected function importAccounts(string $filePath): void
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
                    if (empty($record['name']) || empty($record['type']) || !isset($record['amount'])) {
                        $errors[] = "Row " . ($index + 2) . ": Missing required fields (name, type, amount)";
                        continue;
                    }

                    // Validate type
                    if (!array_key_exists($record['type'], Account::getTypes())) {
                        $errors[] = "Row " . ($index + 2) . ": Invalid type '{$record['type']}'. Valid types: " . implode(', ', array_keys(Account::getTypes()));
                        continue;
                    }

                    // Validate amount
                    if (!is_numeric($record['amount'])) {
                        $errors[] = "Row " . ($index + 2) . ": Amount must be numeric";
                        continue;
                    }

                    // Find institution if provided
                    $institutionId = null;
                    if (!empty($record['institution_name'])) {
                        $institution = Institution::where('name', 'like', '%' . trim($record['institution_name']) . '%')->first();
                        if ($institution) {
                            $institutionId = $institution->id;
                        } else {
                            $errors[] = "Row " . ($index + 2) . ": Institution '{$record['institution_name']}' not found";
                            continue;
                        }
                    }

                    // Create account
                    Account::create([
                        'name' => trim($record['name']),
                        'type' => trim($record['type']),
                        'amount' => (float) $record['amount'],
                        'institution_id' => $institutionId,
                        'user_id' => auth()->id(),
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
                    ->body("Successfully imported {$imported} accounts.")
                    ->success()
                    ->send();
            }

            if (!empty($errors)) {
                Notification::make()
                    ->title('Import Completed with Errors')
                    ->body("Imported {$imported} accounts. " . count($errors) . " rows had errors: " . implode('; ', array_slice($errors, 0, 5)) . (count($errors) > 5 ? '...' : ''))
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
