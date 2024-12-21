<?php

namespace App\Filament\Resources\IncomeResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Invoice;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;
use Barryvdh\Snappy\Facades\SnappyPdf;
use DateTimeInterface;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\Modal\Actions\ButtonAction;

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
                            ->label('Dane sprzedawcy')
                            ->schema([
                                Forms\Components\Placeholder::make('seller_data')
                                    ->label('')
                                    ->content(function () {
                                        $user = auth()->user();
                                        $contractorData = collect([
                                            $user->seller_name ?? $user->name,
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
                    ->label('Kupującego można edytować na stronie przychodu.'),
                Forms\Components\Placeholder::make('top_banner_information')
                    ->label('Sprzedawcę można edytować w ustawieniach profilu.'),
                Forms\Components\Radio::make('tax_exemption_type')
                    ->label('Zwolnienie podatkowe')
                    ->default('objective')
                    ->options([
                        'objective' => 'Podmiotowe - nieprzekroczenie 200 000 PLN obrotu (art. 113 ust. 1 pkt 9 ustawy o VAT).',
                        'subjective' => 'Przedmiotowe - rodzaj prowadzonej działalności (art. 43 ust 1 ustawy o VAT).',
                    ])
                    ->required()
                    ->columnSpan(2),
                Forms\Components\TextInput::make('invoice_number')
                    ->label('Numer faktury')
                    ->default(fn() => $this->generateInvoiceNumber())
                    ->required(),
                Forms\Components\DatePicker::make('issue_date')
                    ->label('Data wystawienia')
                    ->default(now())
                    ->required(),
                Forms\Components\DatePicker::make('transaction_date')
                    ->label('Data transakcji')
                    ->default(now())
                    ->required(),
                Forms\Components\DatePicker::make('due_date')
                    ->label('Termin płatności')
                    ->default(now())
                    ->required(),
                Forms\Components\Toggle::make('is_paid')
                    ->label('Opłacona')
                    ->required(),
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
                        if (empty($data['invoice_number'])) {
                            $data['invoice_number'] = $this->generateInvoiceNumber();
                        }

                        // Sprawdź, czy numer faktury nie jest duplikatem
                        $existingInvoice = Invoice::where('invoice_number', $data['invoice_number'])
                            ->count();

                        if ($existingInvoice > 0) {
                            throw ValidationException::withMessages(['mountedTableActionsData.0.invoice_number' => 'Numer faktury jest już zajęty.']);
                        }

                        return $data;
                    })
                    ->using(function (array $data, string $model): Invoice {
                        $income = $this->getOwnerRecord();
                        $user = auth()->user();
                        $contractor = $income->contractor;
                        $parentInvoice = $income->invoices()->orderBy('created_at', 'desc')->first();

                        $invoice = $model::create([
                            'invoice_id' => $parentInvoice->id,
                            'invoice_number' => $data['invoice_number'],
                            'income_id' => $income->id,
                            'contractor_id' => $contractor->id,
                            'issue_date' => $data['issue_date'],
                            'transaction_date' => $data['transaction_date'],
                            'due_date' => $data['due_date'],
                            'description' => $income->description,
                            'amount' => $income->amount,
                            'tax_exemption_type' => $data['tax_exemption_type'],
                            'is_paid' => $data['is_paid'],
                            'user_id' => $user->id,
                            'seller_name' => $user->seller_name ?? $user->name,
                            'seller_tax_id' => $user->seller_tax_id,
                            'seller_country' => $user->seller_country,
                            'seller_city' => $user->seller_city,
                            'seller_postal_code' => $user->seller_postal_code,
                            'seller_address' => $user->seller_address,
                            'seller_email' => $user->seller_email,
                            'seller_phone' => $user->seller_phone,
                            'contractor_name' => $contractor->name,
                            'contractor_tax_id' => $contractor->tax_id,
                            'contractor_country' => $contractor->country,
                            'contractor_city' => $contractor->city,
                            'contractor_postal_code' => $contractor->postal_code,
                            'contractor_address' => $contractor->address,
                            'contractor_email' => $contractor->email,
                            'contractor_phone' => $contractor->phone,
                        ]);

                        // Add InvoiceItem to the Invoice by copying from Income
                        foreach ($income->items as $item) {
                            $invoice->items()->create($item->toArray());
                        }

                        $pdf = SnappyPdf::loadView('invoices.pdf', ['invoice' => $invoice]);
                        // Windows fix
                        $pdf->setTemporaryFolder(storage_path('snappy'));
                        // use laravel-medialibrary to store the pdf
                        $invoice->addMediaFromStream($pdf->output())
                            ->usingFileName('user-' . $user->id . '-invoice_id-' . $invoice->id . '-invoice_number-' . Str::replace(['/', '\\'], '-', $invoice->invoice_number) . '.pdf')
                            ->toMediaCollection();

                        return $invoice;
                    }),
            ])
            ->actions([
                Action::make('preview')
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
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    protected function generateInvoiceNumber(): string
    {
        $currentMonth = now()->format('m');
        $currentYear = now()->format('Y');
        $lastInvoice = Invoice::where('user_id', auth()->user()->id)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->orderBy('created_at', 'desc')
            ->first();

        $lastNumber = $lastInvoice ? intval(explode('/', $lastInvoice->invoice_number)[0]) : 0;
        $newNumber = $lastNumber + 1;

        return sprintf('%d/%s/%s', $newNumber, $currentMonth, $currentYear);
    }
}
