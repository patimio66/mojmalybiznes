<?php

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;
use App\Filament\Resources\IncomeResource;
use App\Filament\Resources\IncomeResource\Pages\EditIncome;
use App\Filament\Resources\IncomeResource\RelationManagers\InvoicesRelationManager;
use App\Models\Income;
use App\Models\IncomeItem;
use Filament\Forms\Components\Repeater;
use App\Models\User;
use Filament\Facades\Filament;
use App\Models\Contractor;
use App\Models\Invoice;
use Filament\Tables\Actions\CreateAction;

beforeEach(function () {
    Filament::setCurrentPanel(
        Filament::getPanel('app')
    );
    actingAs(User::factory()->create());
    Repeater::fake();
});

it('can render relation manager', function () {
    $newIncome = Income::factory()->make();

    livewire(InvoicesRelationManager::class, [
        'ownerRecord' => $newIncome,
        'pageClass' => EditIncome::class,
    ])
        ->assertSuccessful();
});

it('can create an invoice for income using relation manager', function () {
    $contractor = Contractor::factory()->create();
    $newIncome = Income::factory()->create([
        'contractor_id' => $contractor->id,
    ]);
    auth()->user()->update([
        'seller_country' => 'pl' // required for sqlite
    ]);

    livewire(InvoicesRelationManager::class, [
        'ownerRecord' => $newIncome,
        'pageClass' => EditIncome::class,
    ])
        ->assertTableActionExists('create')
        ->mountTableAction(CreateAction::class)
        ->setTableActionData([])
        ->callMountedTableAction()
        ->assertHasNoTableActionErrors();

    $currentMonth = now()->format('m');
    $currentYear = now()->format('Y');
    $lastInvoice = Invoice::whereMonth('created_at', $currentMonth)
        ->whereYear('created_at', $currentYear)
        ->orderBy('created_at', 'desc')
        ->first();

    $lastNumber = $lastInvoice ? intval(explode('/', $lastInvoice->invoice_number)[0]) : 0;

    $invoiceNumber = sprintf('%d/%s/%s', $lastNumber, $currentMonth, $currentYear);

    assertDatabaseHas(Invoice::class, [
        'invoice_number' => $invoiceNumber,
    ]);
});
