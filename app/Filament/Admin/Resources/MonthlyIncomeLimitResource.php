<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MonthlyIncomeLimitResource\Pages;
use App\Filament\Admin\Resources\MonthlyIncomeLimitResource\RelationManagers;
use App\Models\MonthlyIncomeLimit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MonthlyIncomeLimitResource extends Resource
{
    protected static ?string $model = MonthlyIncomeLimit::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('default')
                    ->label('Kwota domyślna (default)')
                    ->helperText('Kwota dla osoby zatrudnionej. Limit miesięczny.')
                    ->required()
                    ->numeric()
                    ->suffix('zł')
                    ->inputMode('decimal')
                    ->live(onBlur: true)
                    ->step(0.01)
                    ->minValue(1),
                Forms\Components\TextInput::make('unemployed')
                    ->label('Kwota dla osoby bezrobotnej (unemployed)')
                    ->helperText('Kwota dla osoby bezrobotnej. Limit miesięczny.')
                    ->required()
                    ->numeric()
                    ->suffix('zł')
                    ->inputMode('decimal')
                    ->live(onBlur: true)
                    ->step(0.01)
                    ->minValue(1),
                Forms\Components\DatePicker::make('starts_at')
                    ->label('Początek')
                    ->helperText('Wybierz pierwszy dzień miesiąca.')
                    ->required(),
                Forms\Components\DatePicker::make('ends_at')
                    ->label('Koniec')
                    ->helperText('Wybierz ostatni dzień miesiąca. Może być puste (bezterminowy).'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('default')
                    ->label('Kwota domyślna (default)')
                    ->numeric()
                    ->money('PLN')
                    ->sortable(),
                Tables\Columns\TextColumn::make('unemployed')
                    ->label('Kwota dla osoby bezrobotnej (unemployed)')
                    ->numeric()
                    ->money('PLN')
                    ->sortable(),
                Tables\Columns\TextColumn::make('starts_at')
                    ->label('Początek')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ends_at')
                    ->label('Koniec')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListMonthlyIncomeLimits::route('/'),
            'create' => Pages\CreateMonthlyIncomeLimit::route('/create'),
            'edit' => Pages\EditMonthlyIncomeLimit::route('/{record}/edit'),
        ];
    }
}
