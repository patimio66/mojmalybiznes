<?php

use App\Filament\Admin\Resources\UserResource;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    Filament::setCurrentPanel(
        Filament::getPanel('admin')
    );
    actingAs(User::factory()->create());
});

test('does not allow every user to access admin dashboard', function () {
    actingAs(User::factory()->create());
    $this->get(UserResource::getUrl())->assertForbidden();
});

test('admin dashboard is displayed only for admin user', function () {
    actingAs(User::factory(['email' => 'test@quiksite.pl', 'email_verified_at' => now()])->create());
    $this->get(UserResource::getUrl())->assertSuccessful();
});
