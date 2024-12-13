<?php

use function Pest\Livewire\livewire;
use App\Filament\Resources\IncomeResource;
use App\Models\Income;
use App\Models\IncomeItem;
use Filament\Forms\Components\Repeater;

it('creates an income', function () {
    Repeater::fake();

    $newIncome = Income::factory()->make(['description' => 'Random description']);
    $newIncomeItems = IncomeItem::factory()->count(2)->make();

    livewire(IncomeResource\Pages\CreateIncome::class)
        ->fillForm([
            'title' => $newIncome->title,
            'items' => [
                [
                    'title' => $newIncomeItems->first()->title,
                    'quantity' => $newIncomeItems->first()->quantity,
                    'uom' => $newIncomeItems->first()->uom,
                    'price' => $newIncomeItems->first()->price,
                ],
            ],
            'date' => $newIncome->date->format('Y-m-d'),
            'description' => $newIncome->description,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Income::class, [
        'title' => $newIncome->title,
        'amount' => $newIncomeItems->first()->amount,
        'date' => $newIncome->date->format('Y-m-d'),
        'description' => $newIncome->description,
    ]);

    $this->assertDatabaseHas(IncomeItem::class, [
        'title' => $newIncomeItems->first()->title,
        'quantity' => $newIncomeItems->first()->quantity,
        'uom' => $newIncomeItems->first()->uom,
        'price' => $newIncomeItems->first()->price,
    ]);
});
