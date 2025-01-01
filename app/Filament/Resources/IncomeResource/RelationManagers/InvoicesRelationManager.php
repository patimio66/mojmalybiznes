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
use Illuminate\Validation\ValidationException;
use Filament\Resources\RelationManagers\RelationManager;

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
                        Forms\Components\Section::make('Uwaga. Tworzysz fakturę korygującą.')
                            ->description('Faktura korygująca jest dokumentem, który służy do poprawienia błędów w fakturze pierwotnej. Zmień wartości pozycji w Przychodzie, a następnie wypełnij poniższe pola.')
                            ->hidden(fn(): bool => $this->getOwnerRecord()->invoices()->count() == 0 ? true : false)
                            ->schema([
                                Forms\Components\Placeholder::make('corrective_invoice_conditions')
                                    ->label('Fakturę korygującą wystawiasz tylko wtedy, gdy po wystawieniu faktury pierwotnej:')
                                    ->content(new HtmlString('
<ul class="list-disc list-inside">
<li>stwierdziłeś pomyłkę w cenie, stawce, kwocie podatku lub w jakiejkolwiek innej pozycji faktury</li>
<li>udzielasz obniżki ceny w formie rabatu</li>
<li>nabywca zwrócił ci towar</li>
<li>zwróciłeś nabywcy całość lub części zapłaty</li>
<li>wystawiłeś fakturę bez adnotacji „mechanizm podzielonej płatności”, mimo iż miałeś taki obowiązek.</li>
</ul>
<p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Źródło: <a href="https://www.biznes.gov.pl/pl/portal/00228" class="hover:underline">https://www.biznes.gov.pl/pl/portal/00228</a></p>
'))
                                    ->columnSpan('full'),
                            ])
                            ->collapsed()
                            ->columnSpan('full'),
                        Forms\Components\TextInput::make('correction_reason')
                            ->label('Powód wystawienia faktury korygującej')
                            ->hidden(fn() => !$this->getOwnerRecord()->invoices()->exists())
                            ->required(fn() => $this->getOwnerRecord()->invoices()->exists())
                            ->columnSpan('full'),
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
                                        $income = $this->getOwnerRecord();
                                        if ($income->invoices()->exists()) {
                                            $invoice = $income->invoices()->orderBy('created_at', 'desc')->first();
                                            $sellerData = collect([
                                                $invoice->seller_name,
                                                $invoice->seller_tax_id,
                                                $invoice->seller_address,
                                                trim($invoice->seller_postal_code . ' ' . $invoice->seller_city),
                                                $invoice->seller_email,
                                                $invoice->seller_phone,
                                            ]);
                                        } else {
                                            $user = auth()->user();
                                            $sellerData = collect([
                                                $user->seller_name ?? $user->name,
                                                $user->seller_tax_id,
                                                $user->seller_address,
                                                trim($user->seller_postal_code . ' ' . $user->seller_city),
                                                $user->seller_email,
                                                $user->seller_phone,
                                            ]);
                                        }

                                        return new HtmlString($sellerData->filter()->implode('<br>'));
                                    }),
                            ])
                            ->columns(1)
                            ->columnSpan(1),
                        Forms\Components\Placeholder::make('edit_customer_information')
                            ->label('Kupującego można edytować na stronie przychodu.')
                            ->columns(1)
                            ->columnSpan(1),
                        Forms\Components\Placeholder::make('edit_seller_information')
                            ->label('Sprzedawcę można edytować w ustawieniach profilu.')
                            ->columns(1)
                            ->columnSpan(1),
                    ])
                    ->columns(2),
                Forms\Components\Radio::make('tax_exemption_type')
                    ->label('Zwolnienie podatkowe')
                    ->default('objective')
                    ->options([
                        'objective' => 'Podmiotowe - nieprzekroczenie 200 000 PLN obrotu (art. 113 ust. 1 pkt 9 ustawy o VAT).',
                        'subjective' => 'Przedmiotowe - rodzaj prowadzonej działalności (art. 43 ust 1 ustawy o VAT).',
                    ])
                    ->required()
                    ->columnSpan('full'),
                Forms\Components\TextInput::make('invoice_number')
                    ->label('Numer faktury')
                    ->default(fn() => $this->generateInvoiceNumber())
                    ->columnSpan(1),
                Forms\Components\DatePicker::make('issue_date')
                    ->label('Data wystawienia')
                    ->default(now())
                    ->required()
                    ->columnSpan(1),
                Forms\Components\DatePicker::make('transaction_date')
                    ->label('Data transakcji')
                    ->disabled(fn() => $this->getOwnerRecord()->invoices()->exists())
                    ->helperText(fn(): string => $this->getOwnerRecord()->invoices()->exists() ? 'Nie może być zmieniona, jeśli istnieje faktura pierwotna.' : '')
                    ->default(fn() => $this->getOwnerRecord()->date)
                    ->required()
                    ->columnSpan(1),
                Forms\Components\DatePicker::make('due_date')
                    ->label('Termin płatności')
                    ->default(fn() => $this->getOwnerRecord()->date->addWeek() ?? now()->addWeek())
                    ->required()
                    ->columnSpan(1),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('invoice_number')
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
                    ->sortable(),
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
                            'invoice_id' => $parentInvoice?->id ?? null,
                            'invoice_number' => $data['invoice_number'],
                            'income_id' => $income->id,
                            'contractor_id' => $contractor->id,
                            'issue_date' => $data['issue_date'],
                            'transaction_date' => $data['transaction_date'] ?? $parentInvoice->transaction_date,
                            'due_date' => $data['due_date'],
                            'is_paid' => $income->is_paid,
                            'title' => $income->title,
                            'notes' => $income->notes,
                            'amount' => $income->amount,
                            'tax_exemption_type' => $data['tax_exemption_type'],
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

                        // Skip PDF generation in test context
                        if (!app()->environment('testing')) {
                            $pdf = SnappyPdf::loadView('invoices.pdf', ['invoice' => $invoice]);
                            // Windows fix
                            $pdf->setTemporaryFolder(storage_path('app\\temp'));
                            // use laravel-medialibrary to store the pdf
                            $invoice->addMediaFromStream($pdf->output())
                                ->usingFileName('user:' . $user->id . '-invoice_id:' . $invoice->id . '-invoice_number:' . Str::replace(['/', '\\'], '-', $invoice->invoice_number) . '.pdf')
                                ->toMediaCollection();
                        }

                        return $invoice;
                    }),
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
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
        $lastInvoice = Invoice::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->orderBy('created_at', 'desc')
            ->first();

        $lastNumber = $lastInvoice ? intval(explode('/', $lastInvoice->invoice_number)[0]) : 0;
        $newNumber = $lastNumber + 1;

        return sprintf('%d/%s/%s', $newNumber, $currentMonth, $currentYear);
    }
}
