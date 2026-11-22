<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

test('password can be updated via api', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password123'),
    ]);
    Sanctum::actingAs($user);

    $response = $this->putJson('/api/me/password', [
        'current_password' => 'password123',
        'password' => 'new-password123',
        'password_confirmation' => 'new-password123',
    ]);

    $response->assertOk()
        ->assertJson(['message' => 'Password berhasil diperbarui.']);

    $this->assertTrue(Hash::check('new-password123', $user->refresh()->password));
});

test('correct password must be provided to update password via api', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password123'),
    ]);
    Sanctum::actingAs($user);

    $response = $this->putJson('/api/me/password', [
        'current_password' => 'wrong-password',
        'password' => 'new-password123',
        'password_confirmation' => 'new-password123',
    ]);

    $response->assertStatus(422);
});