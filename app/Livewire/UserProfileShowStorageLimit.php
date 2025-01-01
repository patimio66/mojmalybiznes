<?php

namespace App\Livewire;

use Filament\Facades\Filament;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Notifications\Notification;
use Jeffgreco13\FilamentBreezy\Livewire\MyProfileComponent;

class UserProfileShowStorageLimit extends MyProfileComponent
{
    protected string $view = "livewire.user-profile-show-storage-limit";
    public array $only = [
        'limit_category',
    ];
    public array $data;
    public $user;
    public $userClass;

    // this example shows an additional field we want to capture and save on the user
    public function mount()
    {
        $this->user = Filament::getCurrentPanel()->auth()->user();
        $this->userClass = get_class($this->user);

        $this->form->fill($this->user->only($this->only));
    }

    public function form(Form $form): Form
    {
        $storageUsedPercentage = ($this->user->storage_used / (1024 * 1024 * 1024)) * 100;
        $storageLimitContent = $this->user->storage_used_for_humans . ' z 1 GB limitu (' . round($storageUsedPercentage, 2) . '%)';

        if ($storageUsedPercentage >= 90) {
            $storageLimitContent .= ' - Zbliżasz się do limitu miejsca!';
        }

        return $form
            ->schema([
                Forms\Components\Placeholder::make('storage_limit')
                    ->label('Miejsce na dokumenty:')
                    ->content($storageLimitContent)
                    ->columnSpan('full'),
            ])
            ->statePath('data');
    }

    // only capture the custome component field
    public function submit(): void
    {
        $data = collect($this->form->getState())->only($this->only)->all();
        $this->user->update($data);
        Notification::make()
            ->success()
            ->title('Zapisano.')
            ->send();
    }
}
