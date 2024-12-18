<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IncomeResource\Pages;
use App\Filament\Resources\IncomeResource\RelationManagers;
use App\Models\Contractor;
use App\Models\Income;
use App\Models\IncomeItem;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Tabs;


class IncomeResource extends Resource
{
    protected static ?string $model = Income::class;

    protected static ?string $modelLabel = 'Przychód';
    protected static ?string $pluralModelLabel = 'Przychody';

    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-up';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('Transakcja')
                            ->schema([
                                Forms\Components\Grid::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->label('Tytuł transakcji')
                                            ->required()
                                            ->helperText('np.: Zamówienie świec zapachowych')
                                            ->maxLength(255)
                                            ->columnSpan('full'),
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
                                                    ->columnSpan(4)
                                                    ->required()
                                                    ->helperText('np.: Świeca o zapachu lawendowym')
                                                    ->maxLength(255),
                                                Forms\Components\TextInput::make('quantity')
                                                    ->label('Ilość')
                                                    ->columnSpan(2)
                                                    ->required()
                                                    ->numeric()
                                                    ->inputMode('decimal')
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(fn(Set $set, Get $get) => $set('amount', IncomeItem::calculateTotal($get('price'), $get('quantity'))))
                                                    ->step(0.01)
                                                    ->minValue(0.01),
                                                Forms\Components\Select::make('uom')
                                                    ->label('Jednostka miary')
                                                    ->columnSpan(2)
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
                                                    ->columnSpan(2)
                                                    ->required()
                                                    ->numeric()
                                                    ->suffix('zł')
                                                    ->inputMode('decimal')
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(fn(Set $set, Get $get) => $set('amount', IncomeItem::calculateTotal($get('price'), $get('quantity'))))
                                                    ->step(0.01)
                                                    ->minValue(0),
                                                Forms\Components\TextInput::make('amount')
                                                    ->columnSpan(2)
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
                                            ->columnSpan('full'),
                                        Forms\Components\TextInput::make('amount')
                                            ->label('Suma transakcji')
                                            ->readOnly()
                                            ->suffix('zł')
                                            ->live(onBlur: true)
                                            ->afterStateHydrated(function (Get $get, Set $set) {
                                                self::updateTotals($get, $set);
                                            })
                                            ->columnSpan(2),
                                        Forms\Components\DatePicker::make('date')
                                            ->label('Data sprzedaży')
                                            ->default(now())
                                            ->required()
                                            ->columnSpan(3),
                                        Forms\Components\TextInput::make('description')
                                            ->label('Notatka')
                                            ->helperText('Notatka jest prywatna i nie pojawi się w raportach.')
                                            ->maxLength(255)
                                            ->columnSpan(7),
                                    ])
                                    ->columns(12),
                            ]),
                        Tabs\Tab::make('Faktura')
                            ->schema([
                                Forms\Components\Section::make('Wygeneruj fakturę')
                                    ->id('invoice_generate')
                                    ->description('Wypełnij wymagane dane i wygeneruj fakturę.')
                                    ->schema([
                                        Forms\Components\Grid::make()
                                            ->schema([
                                                Forms\Components\Fieldset::make('contractor_details')
                                                    ->label('Dane kupującego')
                                                    ->schema([
                                                        Forms\Components\Select::make('contractor_id')
                                                            ->label('Kontrahent')
                                                            ->relationship(name: 'contractor', titleAttribute: 'name')
                                                            ->searchable()
                                                            ->createOptionForm(fn(Form $form) => ContractorResource::form($form))
                                                            ->editOptionForm(fn(Form $form) => ContractorResource::form($form))
                                                            ->afterStateUpdated(function (Get $get, Set $set) {
                                                                $contractor = Contractor::find($get('contractor_id'));
                                                                if ($contractor) {
                                                                    $set('contractor_name', $contractor->name);
                                                                    $set('contractor_tax_id', $contractor->tax_id);
                                                                    $set('contractor_country', $contractor->country);
                                                                    $set('contractor_address', $contractor->address);
                                                                    $set('contractor_postal_code', $contractor->postal_code);
                                                                    $set('contractor_city', $contractor->city);
                                                                    $set('contractor_email', $contractor->email);
                                                                    $set('contractor_phone', $contractor->phone);
                                                                }
                                                            })
                                                            ->columnSpan('full'),
                                                        Forms\Components\TextInput::make('contractor_name')
                                                            ->label('Nazwa własna')
                                                            ->helperText('np. nazwa firmy lub imię i nazwisko')
                                                            ->required(function (Get $get): bool {
                                                                return $get('contractor_id') ? true : false;
                                                            })
                                                            ->maxLength(255),
                                                        Forms\Components\TextInput::make('contractor_tax_id')
                                                            ->label('Numer podatkowy')
                                                            ->helperText('np. NIP lub PESEL')
                                                            ->maxLength(255),
                                                        // Forms\Components\Select::make('contractor_country')
                                                        //     ->label('Kraj')
                                                        //     ->required()
                                                        //     ->options([
                                                        //         'pl' => 'Polska'
                                                        //     ])
                                                        //     ->default('pl'),
                                                        Forms\Components\TextInput::make('contractor_address')
                                                            ->label('Adres')
                                                            ->maxLength(255),
                                                        Forms\Components\Group::make([
                                                            Forms\Components\TextInput::make('contractor_postal_code')
                                                                ->mask('99-999')
                                                                ->label('Kod pocztowy')
                                                                ->maxLength(255),
                                                            Forms\Components\TextInput::make('contractor_city')
                                                                ->label('Miasto')
                                                                ->maxLength(255),
                                                        ])
                                                            ->columns(2),
                                                        Forms\Components\Group::make([
                                                            Forms\Components\TextInput::make('contractor_email')
                                                                ->label('Email')
                                                                ->email()
                                                                ->maxLength(255),
                                                            Forms\Components\TextInput::make('contractor_phone')
                                                                ->label('Telefon')
                                                                ->tel()
                                                                ->maxLength(255),
                                                        ])
                                                            ->columns(2),
                                                    ])
                                                    ->columns(1)
                                                    ->columnSpan(1),
                                                Forms\Components\Fieldset::make('seller_details')
                                                    ->label('Dane sprzedającego')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('seller_name')
                                                            ->label('Imię i nazwisko')
                                                            ->default(auth()->user()->name)
                                                            ->required()
                                                            ->maxLength(255),
                                                        Forms\Components\TextInput::make('seller_tax_id')
                                                            ->label('Numer podatkowy')
                                                            ->helperText('np. NIP lub PESEL')
                                                            ->maxLength(255),
                                                        // Forms\Components\Select::make('seller_country')
                                                        //     ->label('Kraj')
                                                        //     ->required()
                                                        //     ->options([
                                                        //         'pl' => 'Polska'
                                                        //     ])
                                                        //     ->default('pl'),
                                                        Forms\Components\TextInput::make('seller_address')
                                                            ->label('Adres')
                                                            ->maxLength(255),
                                                        Forms\Components\Group::make([
                                                            Forms\Components\TextInput::make('seller_postal_code')
                                                                ->mask('99-999')
                                                                ->label('Kod pocztowy')
                                                                ->maxLength(255),
                                                            Forms\Components\TextInput::make('seller_city')
                                                                ->label('Miasto')
                                                                ->maxLength(255),
                                                        ])
                                                            ->columns(2),
                                                        Forms\Components\Group::make([
                                                            Forms\Components\TextInput::make('seller_email')
                                                                ->label('Email')
                                                                ->email()
                                                                ->maxLength(255),
                                                            Forms\Components\TextInput::make('seller_phone')
                                                                ->label('Telefon')
                                                                ->tel()
                                                                ->maxLength(255),
                                                        ])
                                                            ->columns(2),
                                                    ])
                                                    ->columns(1)
                                                    ->columnSpan(1),
                                            ])
                                            ->columns(2),
                                    ])
                                    ->footerActions([
                                        Action::make('test')
                                            ->label('Wygeneruj fakturę')
                                            ->requiresConfirmation()
                                            ->color('danger')
                                            ->action(function (Get $get) {
                                                // ... WIP
                                            }),
                                    ]),
                            ]),
                    ])
                    ->persistTab()
                    ->persistTabInQueryString('incomes-tabs'),
            ])
            ->columns(1);
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
                    ->label('Data sprzedaży')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
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
            'index' => Pages\ListIncomes::route('/'),
            'create' => Pages\CreateIncome::route('/create'),
            'edit' => Pages\EditIncome::route('/{record}/edit'),
        ];
    }

    public static function updateTotals(Get $get, Set $set): void
    {
        $items = collect($get('items'));

        $total = Income::calculateTotal($items);

        $set('amount', $total);
    }
}
