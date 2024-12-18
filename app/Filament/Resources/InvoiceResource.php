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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                Forms\Components\TextInput::make('description')
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
                Tables\Columns\TextColumn::make('invoice_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('income_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('invoice_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('issue_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('transaction_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tax_exemption_type')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_paid')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('contractor.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('contractor_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contractor_tax_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contractor_country')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contractor_city')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contractor_postal_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contractor_address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contractor_email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contractor_phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('seller_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('seller_tax_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('seller_country')
                    ->searchable(),
                Tables\Columns\TextColumn::make('seller_city')
                    ->searchable(),
                Tables\Columns\TextColumn::make('seller_postal_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('seller_address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('seller_email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('seller_phone')
                    ->searchable(),
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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
