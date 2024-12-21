<?php

namespace Database\Seeders;

use App\Models\Contractor;
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
        if (User::count() === 0) {
            $user = User::factory()
                ->create([
                    'name' => 'John Doe',
                    'email' => 'test@example.com',
                ]);
        } else {
            $user = User::first();
        }
        Auth::login($user);
        Contractor::factory(10)->create();

        $users = User::factory(99)->create();
        foreach ($users as $user) {
            Auth::login($user);
            Contractor::factory(10)->create();
        }
    }
}
