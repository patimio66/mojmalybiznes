<?php

use App\Models\User;

use function Pest\Laravel\actingAs;

it('returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

it('returns a successful response on /app route as guest', function () {
    actingAs(User::factory()->create());

    $response = $this->get('/app');

    $response->assertStatus(200);
});
