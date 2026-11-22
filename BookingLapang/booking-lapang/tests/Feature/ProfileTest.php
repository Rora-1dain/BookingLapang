<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('profile data can be retrieved via api', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->getJson('/api/me/profile');

    $response->assertOk()
        ->assertJsonStructure(['user' => ['id', 'email', 'name'], 'tier', 'poin']);
});

test('profile information can be updated via api', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->putJson('/api/me/profile', [
        'name' => 'Test User Updated',
        'email' => 'updated@example.com',
    ]);

    $response->assertOk()
        ->assertJson(['message' => 'Profil berhasil diperbarui.']);

    $user->refresh();

    $this->assertSame('Test User Updated', $user->name);
    $this->assertSame('updated@example.com', $user->email);
});

test('user can delete their account via api', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password123'),
    ]);
    Sanctum::actingAs($user);

    $response = $this->deleteJson('/api/me', [
        'password' => 'password123',
    ]);

    $response->assertOk()
        ->assertJson(['message' => 'Akun berhasil dihapus.']);

    $this->assertNull(User::find($user->id));
});

test('correct password must be provided to delete account via api', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password123'),
    ]);
    Sanctum::actingAs($user);

    $response = $this->deleteJson('/api/me', [
        'password' => 'wrong-password',
    ]);

    $response->assertStatus(422);

    $this->assertNotNull(User::find($user->id));
});