<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('revokes the current access token', function () {
    $user = User::factory()->create();

    $token = $user
        ->createToken('Pest')
        ->plainTextToken;

    $this
        ->withToken($token)
        ->postJson('/api/auth/logout')
        ->assertOk()
        ->assertJsonPath(
            'message',
            'Logout completed successfully.',
        );

    $this->assertDatabaseCount(
        'personal_access_tokens',
        0,
    );
});

it('rejects unauthenticated logout requests', function () {
    $this->postJson('/api/auth/logout')
        ->assertUnauthorized();
});
