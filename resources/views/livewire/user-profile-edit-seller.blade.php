<x-filament-breezy::grid-section md=2 title="Dane sprzedawcy" description="Te informacje zostaną użyte na dokumentach, takich, jak faktura sprzedaży.">
  <x-filament::card>
    <form wire:submit.prevent="submit" class="space-y-6">

      {{ $this->form }}

      <div class="text-right">
        <x-filament::button type="submit" form="submit" class="align-right">
          Zapisz
        </x-filament::button>
      </div>
    </form>
  </x-filament::card>
</x-filament-breezy::grid-section>
