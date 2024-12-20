<?php

namespace App\Filament\Resources\IncomeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
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
                                Forms\Components\Placeholder::make('seller_data')
                                    ->label('')
                                    ->content(function () {
                                        $user = auth()->user();
                                        $contractorData = collect([
                                            $user->seller_name,
                                            $user->seller_tax_id,
                                            $user->seller_address,
                                            trim($user->seller_postal_code . ' ' . $user->seller_city),
                                            $user->seller_email,
                                            $user->seller_phone,
                                        ]);

                                        return new HtmlString($contractorData->filter()->implode('<br>'));
                                    }),
                            ])
                            ->columns(1)
                            ->columnSpan(1),
                    ])
                    ->columns(2),
                Forms\Components\Placeholder::make('top_banner_information')
                    ->label('Kupującego można też edytować na stronie przychodu.'),
                Forms\Components\Placeholder::make('top_banner_information')
                    ->label('Sprzedającego można edytować w ustawieniach profilu.'),
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

                        // TODO: SET INVOICE NUMBER

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
