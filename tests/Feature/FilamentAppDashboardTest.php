<?php

use App\Filament\Pages\Dashboard;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    Filament::setCurrentPanel(
        Filament::getPanel('app')
    );
    actingAs(User::factory()->create());
});

test('app dashboard is displayed properly', function () {
    $this->get(Dashboard::getUrl())->assertSuccessful();
});
