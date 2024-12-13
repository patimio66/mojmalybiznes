<?php

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;
use App\Filament\Resources\IncomeResource;
use App\Models\Income;
use App\Models\IncomeItem;
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
    get(IncomeResource::getUrl('index'))->assertSuccessful();
});

it('can render create page', function () {
    get(IncomeResource::getUrl('create'))->assertSuccessful();
});

it('can create an income', function () {
    $newIncome = Income::factory()->make(['description' => 'Random description']);
    $newIncomeItems = IncomeItem::factory()->count(1)->make();

    $newIncomeItemsFormArray = $newIncomeItems->map(function ($incomeItem) {
        return [
            'title' => $incomeItem->title,
            'quantity' => $incomeItem->quantity,
            'uom' => $incomeItem->uom,
            'price' => $incomeItem->price,
        ];
    })->toArray();

    livewire(IncomeResource\Pages\CreateIncome::class)
        ->fillForm([
            'title' => $newIncome->title,
            'items' => $newIncomeItemsFormArray,
            'date' => $newIncome->date->format('Y-m-d'),
            'description' => $newIncome->description,
        ])
        ->assertFormSet(function (array $state) {
            expect($state['items'])
                ->toHaveCount(1);
        })
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas(Income::class, [
        'title' => $newIncome->title,
        'amount' => $newIncomeItems->sum('amount'),
        'date' => $newIncome->date->format('Y-m-d'),
        'description' => $newIncome->description,
    ]);

    assertDatabaseHas(IncomeItem::class, [
        'title' => $newIncomeItems->first()->title,
        'quantity' => $newIncomeItems->first()->quantity,
        'uom' => $newIncomeItems->first()->uom,
        'price' => $newIncomeItems->first()->price,
    ]);

    assertDatabaseHas(IncomeItem::class, [
        'title' => $newIncomeItems->last()->title,
        'quantity' => $newIncomeItems->last()->quantity,
        'uom' => $newIncomeItems->last()->uom,
        'price' => $newIncomeItems->last()->price,
    ]);
});

it('validates that at least one IncomeItem is required', function () {
    $newIncome = Income::factory()->make(['description' => 'Random description']);

    livewire(IncomeResource\Pages\CreateIncome::class)
        ->fillForm([
            'title' => $newIncome->title,
            'items' => [],
            'date' => $newIncome->date->format('Y-m-d'),
            'description' => $newIncome->description,
        ])
        ->assertFormSet(function (array $state) {
            expect($state['items'])
                ->toHaveCount(0);
        })
        ->call('create')
        ->assertHasErrors();

    assertDatabaseMissing(Income::class, [
        'title' => $newIncome->title,
        'date' => $newIncome->date->format('Y-m-d'),
        'description' => $newIncome->description,
    ]);
});
