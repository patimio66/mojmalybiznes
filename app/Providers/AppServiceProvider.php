<?php

namespace App\Providers;

use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\Column;
use Filament\Tables\Table;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Table::configureUsing(function (Table $table) {
            $table->paginated([25, 50, 100])
                ->defaultPaginationPageOption(25);
        });
        Column::configureUsing(function (Column $column): void {
            $column
                ->toggleable();
        });
        FileUpload::configureUsing(function (FileUpload $column): void {
            $column
                ->panelLayout(null)
                ->visibility('private');
        });
    }
}
