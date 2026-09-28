<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('returns the authenticated user', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->getJson('/api/auth/me');

    $response
        ->assertOk()
        ->assertJsonPath(
            'data.id',
            $user->id,
        )
        ->assertJsonPath(
            'data.email',
            $user->email,
        );
});

it('rejects unauthenticated access to current user', function () {
    $this->getJson('/api/auth/me')
        ->assertUnauthorized();
});
