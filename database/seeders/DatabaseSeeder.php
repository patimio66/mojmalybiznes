<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\ExpenseItem;
use App\Models\User;
use App\Models\Income;
use App\Models\IncomeItem;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()
            ->create([
                'name' => 'John Doe',
                'email' => 'test@example.com',
            ]);
        Auth::login($user);

        Income::factory(100)->hasItems(IncomeItem::factory()->count(rand(1, 10)))->create();

        Income::all()->each(function (Income $income) {
            $income->amount = $income->items->reduce(function ($subtotal, $incomeItem) {
                return $subtotal + ($incomeItem->amount ?? 0);
            }, 0);
            $income->save();
        });

        Expense::factory(100)->hasItems(ExpenseItem::factory()->count(rand(1, 10)))->create();

        Expense::all()->each(function (Expense $income) {
            $income->amount = $income->items->reduce(function ($subtotal, $incomeItem) {
                return $subtotal + ($incomeItem->amount ?? 0);
            }, 0);
            $income->save();
        });
    }
}
