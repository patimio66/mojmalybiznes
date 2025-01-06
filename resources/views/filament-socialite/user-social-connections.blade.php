<div>
  <x-filament::grid default="1" gap="4">
    @foreach ($providers as $provider)
      <x-filament::section>
        <div class="fi-section-content-ctn">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              <x-filament::icon icon="fab-{{ strtolower($provider->getName()) }}" class="w-6 h-6" />
              <span class="fi-text-medium">{{ ucfirst($provider->getName()) }}</span>
            </div>

            @php
              $connectedProvider = $userProviders->where('provider', strtolower($provider->getName()))->first();
            @endphp

            @if ($connectedProvider)
              <x-filament::button wire:click="deleteProvider({{ $connectedProvider->id }})" color="danger" outlined>
                Odłącz
              </x-filament::button>
            @else
              <x-filament::button tag="a" :href="route('socialite.filament.app.oauth.redirect', ['provider' => $provider->getName()])" color="primary" outlined>
                Połącz
              </x-filament::button>
            @endif
          </div>
        </div>
      </x-filament::section>
    @endforeach
  </x-filament::grid>
</div>
