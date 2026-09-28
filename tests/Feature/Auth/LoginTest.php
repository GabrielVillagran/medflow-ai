<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('logs in a user with valid credentials', function () {
    User::factory()->create([
        'email' => 'gabriel@example.com',
        'password' => 'StrongPassword123!',
    ]);

    $response = $this->postJson('/api/auth/login', [
        'email' => 'gabriel@example.com',
        'password' => 'StrongPassword123!',
        'device_name' => 'Pest',
    ]);

    $response
        ->assertOk()
        ->assertJsonPath(
            'message',
            'Login completed successfully.',
        )
        ->assertJsonPath(
            'token_type',
            'Bearer',
        )
        ->assertJsonStructure([
            'token',
            'token_type',
            'user',
        ]);

    $this->assertDatabaseCount(
        'personal_access_tokens',
        1,
    );
});

it('rejects invalid credentials', function () {
    User::factory()->create([
        'email' => 'gabriel@example.com',
        'password' => 'StrongPassword123!',
    ]);

    $response = $this->postJson('/api/auth/login', [
        'email' => 'gabriel@example.com',
        'password' => 'IncorrectPassword123!',
        'device_name' => 'Pest',
    ]);

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'email',
        ]);

    $this->assertDatabaseCount(
        'personal_access_tokens',
        0,
    );
});
