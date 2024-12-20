<?php

namespace App\Livewire;

use Filament\Facades\Filament;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Notifications\Notification;
use Jeffgreco13\FilamentBreezy\Livewire\MyProfileComponent;

class UserProfileEditSeller extends MyProfileComponent
{
    protected string $view = "livewire.user-profile-edit-seller";
    public array $only = [
        'seller_name',
        'seller_tax_id',
        'seller_country',
        'seller_city',
        'seller_postal_code',
        'seller_address',
        'seller_email',
        'seller_phone',
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
        return $form
            ->schema([
                Forms\Components\TextInput::make('seller_name')
                    ->label('Imię i nazwisko')
                    ->default(auth()->user()->name)
                    ->nullable()
                    ->maxLength(255),
                Forms\Components\TextInput::make('seller_tax_id')
                    ->label('Numer podatkowy')
                    ->helperText('np. NIP lub PESEL')
                    ->maxLength(255),
                Forms\Components\Select::make('seller_country')
                    ->label('Kraj')
                    ->helperText('Obecnie obsługujemy tylko Polskę')
                    ->required()
                    ->options([
                        'pl' => 'Polska'
                    ])
                    ->default('pl'),
                Forms\Components\TextInput::make('seller_address')
                    ->label('Adres')
                    ->maxLength(255),
                Forms\Components\Group::make([
                    Forms\Components\TextInput::make('seller_postal_code')
                        ->mask('99-999')
                        ->label('Kod pocztowy')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('seller_city')
                        ->label('Miasto')
                        ->maxLength(255),
                ])
                    ->columns(2),
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
