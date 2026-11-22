<?php

use App\Models\NotificationPreference;
use App\Models\User;
use App\Notifications\BookingDikonfirmasi;
use App\Notifications\PesanBaruDiterima;
use App\Notifications\RefundGagal;
use App\Notifications\VerifikasiDiterima;
use App\Traits\ChannelSesuaiPreferensi;

// via() cuma membaca preferensi user, jadi constructor (butuh model) dilewati.
function notifTanpaKonstruktor(string $kelas): object
{
    return (new ReflectionClass($kelas))->newInstanceWithoutConstructor();
}

function aturPreferensi(User $user, string $tipe, bool $email, bool $database): void
{
    NotificationPreference::create([
        'user_id' => $user->id,
        'tipe_notifikasi' => $tipe,
        'email_aktif' => $email,
        'database_aktif' => $database,
    ]);
}

test('user tanpa preferensi memakai default notifikasi', function () {
    $user = User::factory()->create();

    expect(notifTanpaKonstruktor(BookingDikonfirmasi::class)->via($user))->toBe(['mail', 'database'])
        ->and(notifTanpaKonstruktor(PesanBaruDiterima::class)->via($user))->toBe(['database']);
});

test('skenario 5: matikan email chat, notifikasi chat hanya in-app', function () {
    $user = User::factory()->create();
    aturPreferensi($user, 'chat', false, true);

    expect(notifTanpaKonstruktor(PesanBaruDiterima::class)->via($user))->toBe(['database']);
});

test('semua channel dimatikan, tetap masuk in-app', function () {
    $user = User::factory()->create();
    aturPreferensi($user, 'chat', false, false);

    expect(notifTanpaKonstruktor(PesanBaruDiterima::class)->via($user))->toBe(['database']);
});

test('skenario 6: semua dimatikan, refund gagal tetap lewat email', function () {
    $user = User::factory()->create();
    foreach (['booking', 'chat', 'lapangan', 'payout', 'waitlist', 'refund', 'verifikasi', 'ulasan'] as $tipe) {
        aturPreferensi($user, $tipe, false, false);
    }

    expect(notifTanpaKonstruktor(RefundGagal::class)->via($user))->toContain('mail')
        ->and(notifTanpaKonstruktor(VerifikasiDiterima::class)->via($user))->toContain('mail');
});

test('skenario 7: seluruh Notification memakai ChannelSesuaiPreferensi', function () {
    $files = glob(app_path('Notifications/*.php'));
    expect(count($files))->toBeGreaterThanOrEqual(6);

    foreach ($files as $file) {
        $kelas = 'App\\Notifications\\'.basename($file, '.php');
       expect(class_uses($kelas))->toHaveKey(ChannelSesuaiPreferensi::class);
        expect(file_get_contents($file))->toContain('channelSesuaiPreferensi($notifiable)');
    }
});

test('preferensi notifikasi bisa dibaca via api', function () {
    $user = User::factory()->create();
    \Laravel\Sanctum\Sanctum::actingAs($user);

    $response = $this->getJson('/api/notifikasi/preferensi');

    $response->assertOk()
        ->assertJsonStructure(['data' => ['chat', 'booking']]);
});

test('simpan preferensi lewat api', function () {
    $user = User::factory()->create();
    \Laravel\Sanctum\Sanctum::actingAs($user);

    $response = $this->putJson('/api/notifikasi/preferensi', [
        'preferensi' => [
            'chat' => ['email' => false, 'database' => true],
        ],
    ]);

    $response->assertOk();

    $this->assertDatabaseHas('notification_preferences', [
        'user_id' => $user->id,
        'tipe_notifikasi' => 'chat',
        'email_aktif' => false,
        'database_aktif' => true,
    ]);
});