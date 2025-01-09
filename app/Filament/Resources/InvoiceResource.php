<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $modelLabel = 'Faktura';
    protected static ?string $pluralModelLabel = 'Faktury';
    protected static ?string $navigationGroup = 'Zyski';

    protected static ?string $navigationIcon = 'heroicon-o-document-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('invoice_id')
                    ->numeric(),
                Forms\Components\TextInput::make('income_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('invoice_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('issue_date')
                    ->required(),
                Forms\Components\DatePicker::make('transaction_date')
                    ->required(),
                Forms\Components\DatePicker::make('due_date')
                    ->required(),
                Forms\Components\TextInput::make('notes')
                    ->maxLength(255),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('tax_exemption_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_paid')
                    ->required(),
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('seller_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('seller_tax_id')
                    ->maxLength(255),
                Forms\Components\TextInput::make('seller_country')
                    ->required()
                    ->maxLength(255)
                    ->default('pl'),
                Forms\Components\TextInput::make('seller_city')
                    ->maxLength(255),
                Forms\Components\TextInput::make('seller_postal_code')
                    ->maxLength(255),
                Forms\Components\TextInput::make('seller_address')
                    ->maxLength(255),
                Forms\Components\TextInput::make('seller_email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('seller_phone')
                    ->tel()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_type')
                    ->label('Rodzaj')
                    ->badge()
                    ->state(function (Invoice $invoice) {
                        return $invoice->invoice_id ? 'faktura korygująca' : 'faktura pierwotna';
                    })
                    ->color(function (Invoice $invoice) {
                        return $invoice->invoice_id ? 'warning' : 'primary';
                    }),

                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('Numer faktury')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Tytuł')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('issue_date')
                    ->label('Data wystawienia')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('transaction_date')
                    ->label('Data transakcji')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('due_date')
                    ->label('Termin płatności')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Wartość')
                    ->numeric()
                    ->money('PLN')
                    ->sortable(),
                Tables\Columns\TextColumn::make('contractor.name')
                    ->label('Kontrahent')
                    ->url(fn(Invoice $invoice) => route('filament.app.resources.contractors.edit', $invoice->contractor_id)),
                Tables\Columns\TextColumn::make('income.title')
                    ->label('Tytuł')
                    ->url(fn(Invoice $invoice) => route('filament.app.resources.incomes.edit', $invoice->income_id)),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Utworzono')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Zaktualizowano')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('issue_date', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('preview')
                    ->label('Podgląd')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Podgląd faktury')
                    ->modalWidth('5xl')
                    ->modalSubmitActionLabel('Pobierz')
                    ->action(fn(Invoice $invoice) => $invoice->download())
                    ->modalContent(function (Invoice $invoice) {
                        return view('invoices.pdf', ['invoice' => $invoice]);
                    }),
                Tables\Actions\Action::make('download')
                    ->label('Pobierz')
                    ->icon('heroicon-c-document-arrow-down')
                    ->action(fn(Invoice $invoice) => $invoice->download()),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListInvoices::route('/'),
            // 'create' => Pages\CreateInvoice::route('/create'),
            // 'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
