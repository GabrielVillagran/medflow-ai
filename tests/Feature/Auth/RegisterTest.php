<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('registers a patient and returns an access token', function () {
    $response = $this->postJson('/api/auth/register', [
        'name' => 'Gabriel Villagran',
        'email' => 'gabriel@example.com',
        'password' => 'StrongPassword123!',
        'password_confirmation' => 'StrongPassword123!',
        'device_name' => 'Pest',
    ]);

    $response
        ->assertCreated()
        ->assertJsonPath(
            'message',
            'Registration completed successfully.',
        )
        ->assertJsonPath(
            'token_type',
            'Bearer',
        )
        ->assertJsonPath(
            'user.name',
            'Gabriel Villagran',
        )
        ->assertJsonPath(
            'user.email',
            'gabriel@example.com',
        )
        ->assertJsonPath(
            'user.role',
            UserRole::Patient->value,
        )
        ->assertJsonStructure([
            'token',
            'token_type',
            'user' => [
                'id',
                'name',
                'email',
                'role',
                'email_verified_at',
                'created_at',
            ],
        ]);

    $user = User::query()
        ->where('email', 'gabriel@example.com')
        ->firstOrFail();

    expect($user->role)
        ->toBe(UserRole::Patient);

    expect(
        Hash::check(
            'StrongPassword123!',
            $user->password,
        ),
    )->toBeTrue();

    $this->assertDatabaseCount(
        'personal_access_tokens',
        1,
    );
});

it('does not allow duplicate email registration', function () {
    User::factory()->create([
        'email' => 'gabriel@example.com',
    ]);

    $response = $this->postJson('/api/auth/register', [
        'name' => 'Another Gabriel',
        'email' => 'gabriel@example.com',
        'password' => 'StrongPassword123!',
        'password_confirmation' => 'StrongPassword123!',
        'device_name' => 'Pest',
    ]);

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'email',
        ]);

    $this->assertDatabaseCount('users', 1);
});

it('does not accept a role from registration input', function () {
    $response = $this->postJson('/api/auth/register', [
        'name' => 'Gabriel Villagran',
        'email' => 'gabriel@example.com',
        'password' => 'StrongPassword123!',
        'password_confirmation' => 'StrongPassword123!',
        'device_name' => 'Pest',
        'role' => 'administrator',
    ]);

    $response->assertCreated();

    $user = User::query()
        ->where('email', 'gabriel@example.com')
        ->firstOrFail();

    expect($user->role)
        ->toBe(UserRole::Patient);
});

it('validates registration input', function () {
    $response = $this->postJson(
        '/api/auth/register',
        [],
    );

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'name',
            'email',
            'password',
            'device_name',
        ]);
});
