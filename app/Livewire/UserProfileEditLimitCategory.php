<?php

namespace App\Livewire;

use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Notifications\Notification;
use Jeffgreco13\FilamentBreezy\Livewire\MyProfileComponent;
use Illuminate\Support\Carbon;

class UserProfileEditLimitCategory extends MyProfileComponent
{
    protected string $view = "livewire.user-profile-edit-limit-category";
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
        return $form
            ->schema([
                Forms\Components\Select::make('limit_category')
                    ->label('Kategoria limitu')
                    ->helperText('Wybierz odpowiednią kategorię, a my automatycznie przypilnujemy miesięcznego limitu przychodu.')
                    ->required()
                    ->options([
                        'default' => 'Domyślna (75% tzw. minimalnej krajowej)',
                        'unemployed' => 'Status bezrobotnego (50% tzw. minimalnej krajowej)',
                    ])
                    ->default('default'),
            ])
            ->statePath('data');
    }

    // only capture the custom component field
    public function submit(): void
    {
        $data = collect($this->form->getState())->only($this->only)->all();

        if ($this->exceedsLimit($data['limit_category'])) {
            Notification::make()
                ->danger()
                ->title('Zablokowano operację.')
                ->body('Przekroczono limit przychodu. Zmiana kategorii limitu spowodowałaby przekroczenie miesięcznego limitu przychodu.')
                ->send();
            return;
        }

        $this->user->update($data);
        Notification::make()
            ->success()
            ->title('Zapisano.')
            ->send();
    }

    protected function exceedsLimit($newCategory): bool
    {
        $currentIncome = $this->user->getMonthlyIncome(now());
        $limit = User::monthlyIncomeLimit(now(), $newCategory);

        return $currentIncome > $limit;
    }
}
