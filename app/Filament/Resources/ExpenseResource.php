<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpenseResource\Pages;
use App\Filament\Resources\ExpenseResource\RelationManagers;
use App\Models\Expense;
use App\Models\ExpenseItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;

    protected static ?string $modelLabel = 'Wydatek';
    protected static ?string $pluralModelLabel = 'Wydatki';
    protected static ?string $navigationGroup = 'Straty';

    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-down';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('contractor_id')
                    ->label('Kontrahent')
                    ->required()
                    ->relationship(name: 'contractor', titleAttribute: 'name')
                    ->searchable()
                    ->createOptionForm(fn(Form $form) => ContractorResource::form($form))
                    ->editOptionForm(fn(Form $form) => ContractorResource::form($form))
                    ->columnSpan(['default' => 1, 'lg' => 'full']),
                Forms\Components\TextInput::make('title')
                    ->label('Tytuł transakcji')
                    ->required()
                    ->helperText('np.: Zakupy materiałów do świec')
                    ->maxLength(255)
                    ->columnSpan(['default' => 1, 'lg' => 'full']),
                Forms\Components\Repeater::make('items')
                    ->relationship()
                    ->label('Pozycje')
                    ->columns(12)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        self::updateTotals($get, $set);
                    })
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Tytuł pozycji')
                            ->columnSpan(['default' => 1, 'lg' => 4])
                            ->required()
                            ->helperText('np.: Wosk sojowy')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('quantity')
                            ->label('Ilość')
                            ->columnSpan(['default' => 1, 'lg' => 2])
                            ->required()
                            ->numeric()
                            ->inputMode('decimal')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Set $set, Get $get) => $set('amount', ExpenseItem::calculateTotal($get('price'), $get('quantity'))))
                            ->step(0.01)
                            ->minValue(0.01),
                        Forms\Components\Select::make('uom')
                            ->label('Jednostka miary')
                            ->columnSpan(['default' => 1, 'lg' => 2])
                            ->required()
                            ->searchable()
                            ->default('szt.')
                            ->options([
                                'szt.',
                                "pcs.",
                                "zest.",
                                "set",
                                "kg",
                                "t",
                                "g",
                                "m",
                                "cm",
                                "mm",
                                "m",
                                'm²',
                                'm³',
                                "l",
                                "ml",
                                "m³",
                                "godz.",
                                "h",
                                "min",
                                "dni",
                                "opak.",
                                "karton",
                                "paleta",
                            ]),
                        Forms\Components\TextInput::make('price')
                            ->label('Kwota')
                            ->columnSpan(['default' => 1, 'lg' => 2])
                            ->required()
                            ->numeric()
                            ->suffix('zł')
                            ->inputMode('decimal')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Set $set, Get $get) => $set('amount', ExpenseItem::calculateTotal($get('price'), $get('quantity'))))
                            ->step(0.01)
                            ->minValue(0),
                        Forms\Components\TextInput::make('amount')
                            ->columnSpan(['default' => 1, 'lg' => 2])
                            ->readOnly()
                            ->dehydrated(false)
                            ->label('Suma')
                            ->numeric()
                            ->suffix('zł')
                            ->minValue(0),
                    ])
                    ->minItems(1)
                    ->reorderableWithButtons()
                    ->orderColumn('order_column')
                    ->cloneable()
                    ->columnSpan(['default' => 1, 'lg' => 'full']),
                Forms\Components\TextInput::make('amount')
                    ->label('Suma transakcji')
                    ->readOnly()
                    ->suffix('zł')
                    ->live(onBlur: true)
                    ->afterStateHydrated(function (Get $get, Set $set) {
                        self::updateTotals($get, $set);
                    })
                    ->columnSpan(['default' => 1, 'lg' => 2]),
                Forms\Components\DatePicker::make('date')
                    ->label('Data transakcji')
                    ->default(now())
                    ->required()
                    ->columnSpan(['default' => 1, 'lg' => 3]),
                Forms\Components\TextInput::make('notes')
                    ->label('Notatka')
                    ->helperText('Notatka jest prywatna i nie pojawi się w raportach.')
                    ->maxLength(255)
                    ->columnSpan(['default' => 1, 'lg' => 7]),
                Forms\Components\SpatieMediaLibraryFileUpload::make('document')
                    ->label('Załącznik')
                    ->helperText('Dodaj dowód transakcji w formacie PDF. Może przydać się w przypadku kontroli. Maksymalny rozmiar pliku to 2MB. Jeśli plik się nie mieści, skompresuj go.')
                    ->openable()
                    ->multiple()
                    ->acceptedFileTypes(['application/pdf'])
                    ->minSize(1)
                    ->maxSize(2048)
                    ->columnSpan(['default' => 1, 'lg' => 'full']),
            ])
            ->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Tytuł transakcji')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Kwota transakcji')
                    ->money('PLN')
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Data transakcji')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('notes')
                    ->label('Notatka')
                    ->limit(50)
                    ->searchable(),
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
            'index' => Pages\ListExpenses::route('/'),
            'create' => Pages\CreateExpense::route('/create'),
            'edit' => Pages\EditExpense::route('/{record}/edit'),
        ];
    }

    public static function updateTotals(Get $get, Set $set): void
    {
        $items = collect($get('items'));

        $total = Expense::calculateTotal($items);

        $set('amount', $total);
    }
}
