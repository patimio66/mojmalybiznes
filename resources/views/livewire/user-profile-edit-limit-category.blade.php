<x-filament-breezy::grid-section md=2 title="Limit miesięczny"
  description="Zgodnie z obowiązującymi przepisami, aby uniknąć obowiązku rejestracji, miesięczne przychody nie mogą przekroczyć 75% minimalnego wynagrodzenia. Osoby, które chcą zachować status bezrobotnego, nie mogą osiągnąć przychodu wyższego niż 50%.">
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
