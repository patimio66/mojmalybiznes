<?php

namespace App\Livewire;

use Filament\Facades\Filament;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Notifications\Notification;
use Jeffgreco13\FilamentBreezy\Livewire\MyProfileComponent;

class UserProfileSocialMediaConnections extends MyProfileComponent
{
    protected string $view = "livewire.user-profile-social-media-connections";
    public $user;
    public $userClass;

    public function mount()
    {
        $this->user = Filament::getCurrentPanel()->auth()->user();
        $this->userClass = get_class($this->user);
    }

    public function deleteProvider($providerId)
    {
        $this->user->socialiteUsers()->where('id', $providerId)->delete();

        Notification::make()
            ->success()
            ->title('Połączenie zostało usunięte.')
            ->send();
    }

    public function form(Form $form): Form
    {
        $providers = Filament::getCurrentPanel()->getPlugins()['filament-socialite']->getProviders();
        $userProviders = $this->user->socialiteUsers;

        return $form
            ->schema([
                Forms\Components\View::make('filament-socialite.user-social-connections')
                    ->viewData([
                        'providers' => $providers,
                        'userProviders' => $userProviders
                    ])
            ]);
    }
}
