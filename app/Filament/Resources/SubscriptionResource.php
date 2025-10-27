<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriptionResource\Pages;
use App\Filament\Resources\SubscriptionResource\RelationManagers;
use App\Models\Subscription;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    
    protected static ?string $navigationLabel = 'Subscriptions';
    
    protected static ?string $modelLabel = 'Subscription';
    
    protected static ?string $pluralModelLabel = 'Subscriptions';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Subscription Name')
                    ->placeholder('e.g., Netflix, Spotify, Adobe Creative Cloud'),
                
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->step(0.01)
                    ->label('Price')
                    ->placeholder('0.00'),
                
                Forms\Components\Select::make('billing_frequency')
                    ->required()
                    ->options(Subscription::getBillingFrequencies())
                    ->label('Billing Frequency')
                    ->default('monthly'),
                
                Forms\Components\Select::make('category')
                    ->required()
                    ->options(Subscription::getCategories())
                    ->label('Category')
                    ->default('other'),
                
                Forms\Components\Select::make('status')
                    ->required()
                    ->options(Subscription::getStatuses())
                    ->label('Status')
                    ->default('active'),
                
                Forms\Components\DatePicker::make('next_billing_date')
                    ->label('Next Billing Date')
                    ->placeholder('When is the next payment due?')
                    ->helperText('Optional: Track when your next payment is due'),
                
                Forms\Components\Textarea::make('description')
                    ->maxLength(1000)
                    ->rows(3)
                    ->label('Description')
                    ->placeholder('Additional notes about this subscription')
                    ->helperText('Optional: Add any notes or details about this subscription'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Subscription Name'),
                
                Tables\Columns\TextColumn::make('price')
                    ->money('USD')
                    ->sortable()
                    ->label('Price'),
                
                Tables\Columns\TextColumn::make('billing_frequency')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'weekly' => 'warning',
                        'monthly' => 'success',
                        'yearly' => 'info',
                    })
                    ->formatStateUsing(fn (string $state): string => Subscription::getBillingFrequencies()[$state] ?? $state)
                    ->sortable()
                    ->label('Billing'),
                
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn (string $state): string => Subscription::getCategories()[$state] ?? $state)
                    ->sortable()
                    ->label('Category'),
                
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'paused' => 'warning',
                        'cancelled' => 'danger',
                        'expired' => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => Subscription::getStatuses()[$state] ?? $state)
                    ->sortable()
                    ->label('Status'),
                
                Tables\Columns\TextColumn::make('next_billing_date')
                    ->date()
                    ->sortable()
                    ->placeholder('Not set')
                    ->label('Next Billing'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Created'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('billing_frequency')
                    ->options(Subscription::getBillingFrequencies())
                    ->label('Billing Frequency'),
                
                Tables\Filters\SelectFilter::make('category')
                    ->options(Subscription::getCategories())
                    ->label('Category'),
                
                Tables\Filters\SelectFilter::make('status')
                    ->options(Subscription::getStatuses())
                    ->label('Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListSubscriptions::route('/'),
            'create' => Pages\CreateSubscription::route('/create'),
            'edit' => Pages\EditSubscription::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', auth()->id());
    }
}
