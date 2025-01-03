<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Pages\Dashboard;
use Filament\Support\Colors\Color;
use App\Livewire\UserProfileEditSeller;
use Filament\Navigation\NavigationItem;
use Filament\Navigation\NavigationGroup;
use App\Filament\Resources\IncomeResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Navigation\NavigationBuilder;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use App\Filament\Resources\ExpenseResource;
use App\Livewire\UserProfileShowStorageLimit;
use App\Filament\Admin\Resources\UserResource;
use App\Livewire\UserProfileEditLimitCategory;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use DutchCodingCompany\FilamentSocialite\Provider;
use Guava\FilamentKnowledgeBase\KnowledgeBasePlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use App\Filament\Resources\IncomeResource\Widgets\DashboardStats;
use DutchCodingCompany\FilamentSocialite\FilamentSocialitePlugin;
use App\Filament\Resources\IncomeResource\Widgets\IncomeLimitChart;
use App\Filament\Resources\IncomeResource\Widgets\IncomeYearlyChart;
use App\Filament\Resources\ExpenseResource\Widgets\ExpenseYearlyChart;

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
            ->plugins([
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
                    ]),
                KnowledgeBasePlugin::make(),
                FilamentSocialitePlugin::make()
                    // (required) Add providers corresponding with providers in `config/services.php`.
                    ->providers([
                        // Create a provider 'gitlab' corresponding to the Socialite driver with the same name.
                        Provider::make('google')
                            ->label('Zaloguj się, używając Google')
                            ->icon('fab-google')
                            ->color(Color::hex('#DB4437'))
                            ->outlined(false)
                            // ->scopes(['...'])
                            // ->with(['...'])
                            ->stateless(false),
                    ])
                    ->slug('app')
                    ->registration(true)
                    // (optional) Enable/disable registration of new (socialite-) users using a callback.
                    // In this example, a login flow can only continue if there exists a user (Authenticatable) already.
                    ->registration(fn(string $provider, SocialiteUserContract $oauthUser, ?Authenticatable $user) => (bool) $user)
                // (optional) Change the associated model class.
                // ->userModelClass(\App\Models\User::class)
                // (optional) Change the associated socialite class (see below).
                // ->socialiteUserModelClass(\App\Models\SocialiteUser::class)
            ])
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->databaseTransactions();
    }
}
