<?php

namespace App\Providers;

use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Columns\Column;
use Illuminate\Support\ServiceProvider;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Guava\FilamentKnowledgeBase\Filament\Panels\KnowledgeBasePanel;
use Illuminate\Validation\ValidationException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        KnowledgeBasePanel::configureUsing(
            fn(KnowledgeBasePanel $panel) => $panel
                ->viteTheme('resources/css/filament/app/theme.css')
        );
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
        Page::$reportValidationErrorUsing = function (ValidationException $exception) {
            Notification::make()
                ->title($exception->getMessage())
                ->danger()
                ->send();
        };
    }
}
