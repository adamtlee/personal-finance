<?php

namespace App\Filament\Pages;

use App\Services\BigDataApiService;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class StockSearch extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';
    
    protected static string $view = 'filament.pages.stock-search';
    
    protected static ?string $navigationLabel = 'Stock Search';
    
    protected static ?string $title = 'Stock Search';
    
    protected static ?int $navigationSort = 10;

    public ?string $ticker = null;
    public ?array $searchResults = null;
    public bool $hasSearched = false;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('search')
                ->label('Search Stock')
                ->icon('heroicon-o-magnifying-glass')
                ->form([
                    TextInput::make('ticker')
                        ->label('Stock Ticker')
                        ->placeholder('e.g., ISPO, AAPL, MSFT')
                        ->required()
                        ->maxLength(10)
                        ->autofocus()
                        ->extraInputAttributes(['style' => 'text-transform: uppercase'])
                        ->helperText('Enter a stock ticker symbol to search'),
                ])
                ->action(function (array $data): void {
                    $ticker = strtoupper(trim($data['ticker'] ?? ''));

                    if (empty($ticker)) {
                        Notification::make()
                            ->title('Error')
                            ->body('Please enter a stock ticker symbol.')
                            ->danger()
                            ->send();
                        return;
                    }

                    $this->ticker = $ticker;
                    $this->hasSearched = true;
                    $this->searchResults = null;

                    try {
                        $apiService = new BigDataApiService();
                        $results = $apiService->searchCompany($this->ticker);

                        if ($results === null) {
                            Notification::make()
                                ->title('Error')
                                ->body('Failed to fetch stock data. Please check your API key configuration.')
                                ->danger()
                                ->send();
                            return;
                        }

                        $this->searchResults = $results;

                        Notification::make()
                            ->title('Success')
                            ->body('Stock data retrieved successfully.')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Error')
                            ->body('An error occurred while searching for stock data: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}

