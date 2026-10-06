<?php

use App\Models\LanggananUser;
use App\Models\MembershipPaket;
use App\Models\MembershipTransaction;
use App\Models\User;
use App\Services\MembershipService;
use App\Services\PaymentService;

function paketUji(float $diskon = 10, float $harga = 100000): MembershipPaket
{
    return MembershipPaket::create([
        'nama' => 'Uji '.uniqid(),
        'harga_bulanan' => $harga,
        'persentase_diskon_booking' => $diskon,
        'kuota_booking_gratis' => 0,
    ]);
}

test('daftar paket membership bisa dilihat tanpa login', function () {
    paketUji();

    $this->getJson('/api/membership/paket')
        ->assertOk()
        ->assertJsonStructure(['data' => [['id', 'nama', 'harga_bulanan', 'diskon']]]);
});

test('berlangganan mengembalikan snap token & baru aktif setelah settlement', function () {
    $user = User::factory()->create();
    $paket = paketUji();

    // Midtrans tidak boleh dipanggil sungguhan saat test (tanpa kredensial &
    // jaringan). Ganti hanya pembuatan Snap Token dengan transaksi lokal;
    // sinkron status (jalur aktivasi) tetap memakai implementasi asli.
    $payment = new class extends PaymentService
    {
        public function buatTransaksiMembership(User $user, MembershipPaket $paket): array
        {
            $trx = MembershipTransaction::create([
                'user_id' => $user->id,
                'membership_paket_id' => $paket->id,
                'order_id' => 'MEMBERSHIP-TEST-'.uniqid(),
                'gross_amount' => (float) $paket->harga_bulanan,
                'status' => 'pending',
            ]);

            return ['trx' => $trx, 'snap_token' => 'SNAP-TOKEN-TEST'];
        }
    };
    $this->app->instance(PaymentService::class, $payment);

    $this->actingAs($user, 'sanctum')
        ->postJson('/api/membership/berlangganan', ['membership_paket_id' => $paket->id])
        ->assertCreated()
        ->assertJsonPath('snap_token', 'SNAP-TOKEN-TEST')
        ->assertJsonStructure(['snap_token', 'client_key', 'order_id', 'transaction_id']);

    // Sebelum pembayaran settlement, membership belum aktif.
    expect(app(MembershipService::class)->langgananAktif($user->id))->toBeNull();

    // Setelah Midtrans melaporkan settlement, membership aktif 30 hari.
    $trx = MembershipTransaction::where('user_id', $user->id)->firstOrFail();
    $payment->sinkronStatusMembership($trx, 'settlement');

    $aktif = app(MembershipService::class)->langgananAktif($user->id);
    expect($aktif)->not->toBeNull()
        ->and($aktif->tanggal_berakhir->toDateString())->toBe(today()->addDays(30)->toDateString());
});

test('paket yang sama diperpanjang 30 hari, bukan dobel', function () {
    $user = User::factory()->create();
    $paket = paketUji();
    $service = app(MembershipService::class);

    $service->berlangganan($user, $paket);
    $service->berlangganan($user, $paket);

    expect(LanggananUser::where('user_id', $user->id)->count())->toBe(1)
        ->and($service->langgananAktif($user->id)->tanggal_berakhir->toDateString())
        ->toBe(today()->addDays(60)->toDateString());
});

test('ganti paket membatalkan paket lama', function () {
    $user = User::factory()->create();
    $service = app(MembershipService::class);
    $lama = paketUji(5, 50000);
    $baru = paketUji(20, 200000);

    $service->berlangganan($user, $lama);
    $service->berlangganan($user, $baru);

    expect(LanggananUser::where('user_id', $user->id)->where('status', 'dibatalkan')->count())->toBe(1)
        ->and($service->langgananAktif($user->id)->membership_paket_id)->toBe($baru->id);
});

test('diskon booking mengikuti paket aktif, nol kalau tidak berlangganan', function () {
    $user = User::factory()->create();
    $service = app(MembershipService::class);

    expect($service->diskonBooking($user->id, 200000)['nominal'])->toBe(0.0);

    $service->berlangganan($user, paketUji(10));

    expect($service->diskonBooking($user->id, 200000)['nominal'])->toBe(20000.0);
});

test('daftar sebagai pemilik lapangan memberi role pemilik_lapangan', function () {
    $this->postJson('/api/register', [
        'name' => 'Pemilik Uji',
        'email' => 'pemilik-uji@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'pemilik_lapangan',
    ])->assertCreated()->assertJsonPath('user.role', 'pemilik_lapangan');
});

test('daftar tanpa memilih peran jadi pemesan (user)', function () {
    $this->postJson('/api/register', [
        'name' => 'Pemesan Uji',
        'email' => 'pemesan-uji@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertCreated()->assertJsonPath('user.role', 'user');
});

test('daftar tidak boleh membuat admin', function () {
    $this->postJson('/api/register', [
        'name' => 'Nakal',
        'email' => 'nakal@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'admin',
    ])->assertStatus(422);
});

test('dashboard admin hanya untuk admin', function () {
    $this->actingAs(User::factory()->create(), 'sanctum')
        ->getJson('/api/admin/dashboard')
        ->assertForbidden();

    $this->actingAs(User::factory()->create(['role' => 'admin']), 'sanctum')
        ->getJson('/api/admin/dashboard')
        ->assertOk()
        ->assertJsonStructure(['ringkasan', 'antrian', 'pendapatan_bulanan', 'lapangan_favorit', 'booking_terbaru']);
});

test('dashboard pemilik: pemilik boleh, user biasa tanpa lapangan ditolak', function () {
    $this->actingAs(User::factory()->create(), 'sanctum')
        ->getJson('/api/pemilik/dashboard')
        ->assertForbidden();

    $this->actingAs(User::factory()->create(['role' => 'pemilik_lapangan']), 'sanctum')
        ->getJson('/api/pemilik/dashboard')
        ->assertOk()
        ->assertJsonStructure(['verifikasi', 'ringkasan', 'lapangan', 'pendapatan_bulanan', 'booking_terbaru']);
});