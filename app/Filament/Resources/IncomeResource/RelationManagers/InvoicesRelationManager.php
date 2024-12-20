<?php

namespace App\Filament\Resources\IncomeResource\RelationManagers;

use App\Filament\Resources\ContractorResource;
use App\Models\Contractor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class InvoicesRelationManager extends RelationManager
{
    protected static string $relationship = 'invoices';

    protected static ?string $title = 'Faktury';
    protected static ?string $modelLabel = 'Faktura';
    protected static ?string $pluralModelLabel = 'Faktury';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\Fieldset::make('contractor_details')
                            ->label('Dane kupującego')
                            ->schema([
                                Forms\Components\Placeholder::make('contractor_data')
                                    ->label('')
                                    ->content(function (RelationManager $livewire) {
                                        $contractor = $livewire->getOwnerRecord()->contractor;
                                        $contractorData = collect([
                                            $contractor->name,
                                            $contractor->tax_id,
                                            $contractor->address,
                                            trim($contractor->postal_code . ' ' . $contractor->city),
                                            $contractor->email,
                                            $contractor->phone,
                                        ]);

                                        return new HtmlString($contractorData->filter()->implode('<br>'));
                                    }),
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
                                Forms\Components\Select::make('seller_country')
                                    ->label('Kraj')
                                    ->helperText('Obecnie obsługujemy tylko Polskę')
                                    ->required()
                                    ->options([
                                        'pl' => 'Polska'
                                    ])
                                    ->default('pl'),
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
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('invoice_number')
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = auth()->id();

                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
