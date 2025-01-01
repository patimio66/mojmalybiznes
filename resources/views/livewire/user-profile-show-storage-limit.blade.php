<x-filament-breezy::grid-section md=2 title="Limity"
  description="Aby zapobiec nadużyciom, na każde konto nałożono limity. Ich przekroczenie na pewno nie będzie łatwe. Limity można zwiększyć, kontaktując się bezpośrednio.">
  <x-filament::card>
    {{ $this->form }}
    {{-- <form wire:submit.prevent="submit" class="space-y-6">
      <div class="text-right">
        <x-filament::button type="submit" form="submit" class="align-right">
          Poproś o zwiększenie limitu
        </x-filament::button>
      </div>
    </form> --}}
  </x-filament::card>
</x-filament-breezy::grid-section>
