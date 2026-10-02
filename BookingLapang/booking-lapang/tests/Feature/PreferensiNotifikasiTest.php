<?php

use App\Models\User;
use App\Notifications\Concerns\MenghormatiPreferensi;
use App\Notifications\PesanBaruDiterima;
use App\Notifications\RefundGagal;
use App\Notifications\BookingDikonfirmasi;
use App\Support\PreferensiNotifikasi;

// via() cuma membaca preferensi user, jadi constructor (butuh model) dilewati.
function kosong(string $kelas): object
{
    return (new ReflectionClass($kelas))->newInstanceWithoutConstructor();
}

test('user baru dapat default: transaksional aktif, promo mati', function () {
    $user = User::factory()->create();
    $pref = PreferensiNotifikasi::untuk($user);

    expect($pref['booking_confirmed'])->toBe(['mail', 'database'])
        ->and($pref['promo'])->toBe([]);
});

test('halaman pengaturan notifikasi tampil', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('pengaturan.notifikasi'))
        ->assertOk()
        ->assertSee('Pengaturan Notifikasi');
});

test('simpan preferensi: checkbox yang tidak dicentang jadi kosong', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->put(route('pengaturan.notifikasi.update'), [
            'notifikasi' => ['pesan_chat_baru' => ['database'], 'promo' => ['mail', 'sms']],
        ])
        ->assertRedirect(route('pengaturan.notifikasi'));

    $pref = $user->fresh()->preferensi_notifikasi;
    expect($pref['pesan_chat_baru'])->toBe(['database'])
        ->and($pref['promo'])->toBe(['mail'])          // channel asing dibuang
        ->and($pref['slot_waitlist'])->toBe([]);        // tidak dicentang = mati
});

test('skenario 5: matikan email chat, notifikasi chat hanya in-app', function () {
    $user = User::factory()->create(['preferensi_notifikasi' => ['pesan_chat_baru' => ['database']]]);

    expect(kosong(PesanBaruDiterima::class)->via($user))->toBe(['database']);
});

test('transaksional dimatikan semua tetap masuk in-app', function () {
    $user = User::factory()->create(['preferensi_notifikasi' => ['booking_confirmed' => []]]);

    expect(kosong(BookingDikonfirmasi::class)->via($user))->toBe(['database']);
});

test('non-transaksional boleh dimatikan total', function () {
    $user = User::factory()->create(['preferensi_notifikasi' => ['pesan_chat_baru' => []]]);

    expect(kosong(PesanBaruDiterima::class)->via($user))->toBe([]);
});

test('skenario 6: semua dimatikan, refund gagal tetap lewat mail', function () {
    $semuaMati = array_map(fn () => [], PreferensiNotifikasi::JENIS);
    $semuaMati['refund_gagal'] = [];
    $user = User::factory()->create(['preferensi_notifikasi' => $semuaMati]);

    expect(kosong(RefundGagal::class)->via($user))->toBe(['mail']);
});

test('skenario 7: seluruh Notification memakai MenghormatiPreferensi dan tidak hardcode channel', function () {
    $files = glob(app_path('Notifications/*.php'));
    expect(count($files))->toBeGreaterThanOrEqual(6);

    foreach ($files as $file) {
        $kelas = 'App\\Notifications\\'.basename($file, '.php');
        expect(class_uses($kelas))->toHaveKey(MenghormatiPreferensi::class, "$kelas belum pakai trait");
        expect(file_get_contents($file))->toContain('channelSesuaiPreferensi');
    }
});

test('API simpan preferensi menolak channel tidak dikenal', function () {
    $user = User::factory()->create();

    $this->actingAs($user, 'sanctum')
        ->putJson('/api/pengaturan/notifikasi', ['preferensi' => ['promo' => ['sms']]])
        ->assertStatus(422);
});