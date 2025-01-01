<?php

namespace App\Providers\Filament;

use App\Filament\Admin\Resources\UserResource;
use App\Filament\Resources\ExpenseResource;
use App\Filament\Resources\ExpenseResource\Widgets\ExpenseYearlyChart;
use App\Filament\Resources\IncomeResource;
use App\Filament\Resources\IncomeResource\Widgets\IncomeLimitChart;
use App\Filament\Resources\IncomeResource\Widgets\IncomeYearlyChart;
use App\Filament\Resources\IncomeResource\Widgets\DashboardStats;
use App\Http\Middleware\TransactionMiddleware;
use App\Livewire\UserProfileEditLimitCategory;
use App\Livewire\UserProfileEditSeller;
use App\Livewire\UserProfileShowStorageLimit;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('app')
            ->path('app')
            ->login()
            ->registration()
            ->passwordReset()
            // ->emailVerification()
            // ->profile()
            ->plugin(
                BreezyCore::make()
                    ->myProfile(
                        shouldRegisterUserMenu: true, // Sets the 'account' link in the panel User Menu (default = true)
                        shouldRegisterNavigation: false, // Adds a main navigation item for the My Profile page (default = false)
                    )
                    ->enableTwoFactorAuthentication()
                    ->myProfileComponents([
                        UserProfileEditLimitCategory::class,
                        UserProfileEditSeller::class,
                        UserProfileShowStorageLimit::class,
                    ])
            )
            ->colors([
                'primary' => Color::Emerald,
            ])
            ->navigationItems([
                NavigationItem::make('settings')
                    ->label('Ustawienia konta')
                    ->url(fn(): string => route('filament.app.pages.my-profile'))
                    ->isActiveWhen(fn() => request()->routeIs('filament.app.pages.my-profile'))
                    ->icon('heroicon-o-cog-6-tooth'),
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Zyski'),
                NavigationGroup::make()
                    ->label('Straty'),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            // ->pages([])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                DashboardStats::class,
                IncomeYearlyChart::class,
                ExpenseYearlyChart::class,
                IncomeLimitChart::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                TransactionMiddleware::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->databaseTransactions();
    }
}
