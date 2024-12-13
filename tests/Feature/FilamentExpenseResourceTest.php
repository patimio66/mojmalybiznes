<?php

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;
use App\Filament\Resources\ExpenseResource;
use App\Models\Expense;
use App\Models\ExpenseItem;
use Filament\Forms\Components\Repeater;
use App\Models\User;
use Filament\Facades\Filament;

beforeEach(function () {
    Filament::setCurrentPanel(
        Filament::getPanel('app')
    );
    actingAs(User::factory()->create());
    Repeater::fake();
});

it('can render index page', function () {
    get(ExpenseResource::getUrl('index'))->assertSuccessful();
});

it('can render create page', function () {
    get(ExpenseResource::getUrl('create'))->assertSuccessful();
});

it('can create an expense', function () {
    $newExpense = Expense::factory()->make(['description' => 'Random description']);
    $newExpenseItems = ExpenseItem::factory()->count(1)->make();

    $newExpenseItemsFormArray = $newExpenseItems->map(function ($expenseItem) {
        return [
            'title' => $expenseItem->title,
            'quantity' => $expenseItem->quantity,
            'uom' => $expenseItem->uom,
            'price' => $expenseItem->price,
        ];
    })->toArray();

    livewire(ExpenseResource\Pages\CreateExpense::class)
        ->fillForm([
            'title' => $newExpense->title,
            'items' => $newExpenseItemsFormArray,
            'date' => $newExpense->date,
            'description' => $newExpense->description,
        ])
        ->assertFormSet(function (array $state) {
            expect($state['items'])
                ->toHaveCount(1);
        })
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas(Expense::class, [
        'title' => $newExpense->title,
        'amount' => $newExpenseItems->sum('amount'),
        'date' => $newExpense->date,
        'description' => $newExpense->description,
    ]);

    assertDatabaseHas(ExpenseItem::class, [
        'title' => $newExpenseItems->first()->title,
        'quantity' => $newExpenseItems->first()->quantity,
        'uom' => $newExpenseItems->first()->uom,
        'price' => $newExpenseItems->first()->price,
    ]);

    assertDatabaseHas(ExpenseItem::class, [
        'title' => $newExpenseItems->last()->title,
        'quantity' => $newExpenseItems->last()->quantity,
        'uom' => $newExpenseItems->last()->uom,
        'price' => $newExpenseItems->last()->price,
    ]);
});

it('validates that at least one ExpenseItem is required', function () {
    $newExpense = Expense::factory()->make(['description' => 'Random description']);

    livewire(ExpenseResource\Pages\CreateExpense::class)
        ->fillForm([
            'title' => $newExpense->title,
            'items' => [],
            'date' => $newExpense->date,
            'description' => $newExpense->description,
        ])
        ->assertFormSet(function (array $state) {
            expect($state['items'])
                ->toHaveCount(0);
        })
        ->call('create')
        ->assertHasErrors();

    assertDatabaseMissing(Expense::class, [
        'title' => $newExpense->title,
        'date' => $newExpense->date,
        'description' => $newExpense->description,
    ]);
});
