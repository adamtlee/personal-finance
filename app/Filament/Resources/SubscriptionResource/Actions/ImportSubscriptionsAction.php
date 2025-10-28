<?php

namespace App\Filament\Resources\SubscriptionResource\Actions;

use App\Models\Subscription;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

class ImportSubscriptionsAction extends Action
{
    public static function getDefaultName(): string
    {
        return 'import_subscriptions';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Import Subscriptions')
            ->icon('heroicon-o-arrow-up-tray')
            ->color('success')
            ->form([
                FileUpload::make('csv_file')
                    ->label('CSV File')
                    ->acceptedFileTypes(['text/csv', 'application/csv', '.csv'])
                    ->required()
                    ->helperText('Upload a CSV file with columns: name, price, billing_frequency, category, status, next_billing_date (optional), description (optional)')
                    ->disk('local')
                    ->directory('imports')
                    ->visibility('private'),
            ])
            ->action(function (array $data): void {
                $this->importSubscriptions($data['csv_file']);
            });
    }

    protected function importSubscriptions(string $filePath): void
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
                    if (empty($record['name']) || empty($record['price']) || empty($record['billing_frequency']) || empty($record['category']) || empty($record['status'])) {
                        $errors[] = "Row " . ($index + 2) . ": Missing required fields (name, price, billing_frequency, category, status)";
                        continue;
                    }

                    // Validate price
                    if (!is_numeric($record['price'])) {
                        $errors[] = "Row " . ($index + 2) . ": Price must be numeric";
                        continue;
                    }

                    // Validate billing frequency
                    if (!array_key_exists($record['billing_frequency'], Subscription::getBillingFrequencies())) {
                        $errors[] = "Row " . ($index + 2) . ": Invalid billing_frequency '{$record['billing_frequency']}'. Valid options: " . implode(', ', array_keys(Subscription::getBillingFrequencies()));
                        continue;
                    }

                    // Validate category
                    if (!array_key_exists($record['category'], Subscription::getCategories())) {
                        $errors[] = "Row " . ($index + 2) . ": Invalid category '{$record['category']}'. Valid options: " . implode(', ', array_keys(Subscription::getCategories()));
                        continue;
                    }

                    // Validate status
                    if (!array_key_exists($record['status'], Subscription::getStatuses())) {
                        $errors[] = "Row " . ($index + 2) . ": Invalid status '{$record['status']}'. Valid options: " . implode(', ', array_keys(Subscription::getStatuses()));
                        continue;
                    }

                    // Parse next billing date if provided
                    $nextBillingDate = null;
                    if (!empty($record['next_billing_date'])) {
                        try {
                            $nextBillingDate = \Carbon\Carbon::parse($record['next_billing_date'])->format('Y-m-d');
                        } catch (\Exception $e) {
                            $errors[] = "Row " . ($index + 2) . ": Invalid next_billing_date format '{$record['next_billing_date']}'. Use YYYY-MM-DD format";
                            continue;
                        }
                    }

                    // Create subscription
                    Subscription::create([
                        'name' => trim($record['name']),
                        'price' => (float) $record['price'],
                        'billing_frequency' => trim($record['billing_frequency']),
                        'category' => trim($record['category']),
                        'status' => trim($record['status']),
                        'next_billing_date' => $nextBillingDate,
                        'description' => !empty($record['description']) ? trim($record['description']) : null,
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
                    ->body("Successfully imported {$imported} subscriptions.")
                    ->success()
                    ->send();
            }

            if (!empty($errors)) {
                Notification::make()
                    ->title('Import Completed with Errors')
                    ->body("Imported {$imported} subscriptions. " . count($errors) . " rows had errors: " . implode('; ', array_slice($errors, 0, 5)) . (count($errors) > 5 ? '...' : ''))
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
