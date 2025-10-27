<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountResource\Pages;
use App\Filament\Resources\AccountResource\RelationManagers;
use App\Models\Account;
use App\Models\Institution;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AccountResource extends Resource
{
    protected static ?string $model = Account::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';
    
    protected static ?string $navigationLabel = 'Accounts';
    
    protected static ?string $modelLabel = 'Account';
    
    protected static ?string $pluralModelLabel = 'Accounts';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Account Name'),
                
                Forms\Components\Select::make('type')
                    ->required()
                    ->options(Account::getTypes())
                    ->label('Account Type'),
                
                Forms\Components\Select::make('institution_id')
                    ->label('Institution')
                    ->options(function () {
                        $institutions = Institution::orderBy('name')->get();
                        return $institutions->pluck('name', 'id')->toArray();
                    })
                    ->searchable()
                    ->preload()
                    ->placeholder('Select an institution')
                    ->helperText('Choose the bank or investment company for this account')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Institution Name'),
                        
                        Forms\Components\Select::make('type')
                            ->required()
                            ->options(Institution::getTypes())
                            ->label('Institution Type'),
                        
                        Forms\Components\TextInput::make('website')
                            ->url()
                            ->maxLength(255)
                            ->label('Website'),
                        
                        Forms\Components\Textarea::make('description')
                            ->maxLength(1000)
                            ->rows(3)
                            ->label('Description'),
                    ])
                    ->createOptionUsing(function (array $data): int {
                        return Institution::create($data)->getKey();
                    }),
                
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->step(0.01)
                    ->label('Current Balance'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Account Name'),
                
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'checking' => 'success',
                        'savings' => 'info',
                        'credit_card' => 'warning',
                        'money_market' => 'primary',
                        'cd' => 'secondary',
                        'investment' => 'success',
                        'other' => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => Account::getTypes()[$state] ?? $state)
                    ->sortable()
                    ->label('Account Type'),
                
                Tables\Columns\TextColumn::make('institution.name')
                    ->searchable()
                    ->sortable()
                    ->placeholder('No institution')
                    ->label('Institution'),
                
                Tables\Columns\TextColumn::make('amount')
                    ->money('USD')
                    ->sortable()
                    ->label('Balance'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Created'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(Account::getTypes())
                    ->label('Account Type'),
                
                Tables\Filters\SelectFilter::make('institution_id')
                    ->label('Institution')
                    ->relationship('institution', 'name')
                    ->searchable()
                    ->preload(),
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
            'index' => Pages\ListAccounts::route('/'),
            'create' => Pages\CreateAccount::route('/create'),
            'edit' => Pages\EditAccount::route('/{record}/edit'),
        ];
    }
}
