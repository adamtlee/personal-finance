<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpenseResource\Pages;
use App\Filament\Resources\ExpenseResource\RelationManagers;
use App\Models\Expense;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    
    protected static ?string $navigationLabel = 'Expenses';
    
    protected static ?string $modelLabel = 'Expense';
    
    protected static ?string $pluralModelLabel = 'Expenses';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('expense_id')
                    ->label('Expense ID')
                    ->default(fn () => Expense::generateExpenseId())
                    ->disabled()
                    ->dehydrated()
                    ->helperText('This ID is automatically generated'),
                
                Forms\Components\DatePicker::make('date')
                    ->required()
                    ->label('Date')
                    ->default(now()),
                
                Forms\Components\TextInput::make('merchant_name')
                    ->required()
                    ->maxLength(255)
                    ->label('Merchant Name')
                    ->placeholder('e.g., Amazon, Starbucks, Shell'),
                
                Forms\Components\Select::make('payment_method')
                    ->required()
                    ->options(Expense::getPaymentMethods())
                    ->label('Payment Method'),
                
                Forms\Components\Select::make('category')
                    ->required()
                    ->options(Expense::getCategories())
                    ->label('Category'),
                
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->label('Amount')
                    ->placeholder('0.00'),
                
                Forms\Components\Select::make('currency')
                    ->required()
                    ->options(Expense::getCurrencies())
                    ->default('USD')
                    ->label('Currency'),
                
                Forms\Components\FileUpload::make('receipt_image')
                    ->label('Receipt Image')
                    ->image()
                    ->directory('receipts')
                    ->visibility('private')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
                    ->maxSize(5120) // 5MB
                    ->helperText('Upload an image of your receipt (max 5MB)'),
                
                Forms\Components\Textarea::make('notes')
                    ->maxLength(1000)
                    ->rows(3)
                    ->label('Notes')
                    ->placeholder('Additional notes about this expense...'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('expense_id')
                    ->label('Expense ID')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('merchant_name')
                    ->label('Merchant')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('category')
                    ->label('Category')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'food_dining' => 'success',
                        'transportation' => 'warning',
                        'shopping' => 'info',
                        'entertainment' => 'primary',
                        'utilities' => 'secondary',
                        'healthcare' => 'danger',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Payment Method')
                    ->badge()
                    ->color('gray'),
                
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->money(fn ($record) => $record->currency ?? 'USD')
                    ->sortable(),
                
                Tables\Columns\ImageColumn::make('receipt_image')
                    ->label('Receipt')
                    ->disk('local')
                    ->height(40)
                    ->width(40)
                    ->defaultImageUrl(url('/images/no-receipt.svg')),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options(Expense::getCategories()),
                
                Tables\Filters\SelectFilter::make('payment_method')
                    ->options(Expense::getPaymentMethods()),
                
                Tables\Filters\Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('date_from')
                            ->label('From Date'),
                        Forms\Components\DatePicker::make('date_until')
                            ->label('Until Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['date_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('date', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExpenses::route('/'),
            'create' => Pages\CreateExpense::route('/create'),
            'edit' => Pages\EditExpense::route('/{record}/edit'),
        ];
    }
}
