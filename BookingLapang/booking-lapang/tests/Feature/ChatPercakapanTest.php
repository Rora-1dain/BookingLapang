<?php

use App\Models\Lapangan;
use App\Models\Percakapan;
use App\Models\User;

// Percakapan user <-> admin dibuat lewat POST /api/percakapan { tujuan: 'admin' }.
// Label "lawan bicara" harus tergantung SUDUT PANDANG yang membuka daftar:
// pemesan melihat "Admin Booking Lapang", admin melihat nama pemesan.

test('daftar percakapan admin: admin melihat nama pemesan, bukan dirinya sendiri', function () {
    $pemesan = User::factory()->create(['name' => 'Budi Pemesan']);
    $admin = User::factory()->create(['name' => 'Siti Admin', 'role' => 'admin']);

    $this->actingAs($pemesan, 'sanctum')
        ->postJson('/api/percakapan', ['tujuan' => 'admin'])
        ->assertCreated();

    // Dari sudut pandang pemesan: lawan = admin, judul = "Admin Booking Lapang".
    $this->actingAs($pemesan, 'sanctum')
        ->getJson('/api/percakapan')
        ->assertOk()
        ->assertJsonPath('data.0.lapangan', 'Admin Booking Lapang')
        ->assertJsonPath('data.0.lawan_bicara', 'Siti Admin')
        ->assertJsonPath('data.0.peran_lawan', 'admin');

    // Dari sudut pandang admin: lawan = pemesan, judul = nama pemesan.
    $this->actingAs($admin, 'sanctum')
        ->getJson('/api/percakapan')
        ->assertOk()
        ->assertJsonPath('data.0.lapangan', 'Budi Pemesan')
        ->assertJsonPath('data.0.lawan_bicara', 'Budi Pemesan')
        ->assertJsonPath('data.0.peran_lawan', 'pemesan');
});

test('daftar percakapan lapangan: peran lawan benar dari kedua sisi', function () {
    $pemilik = User::factory()->create(['name' => 'Pak Pemilik']);
    $pemesan = User::factory()->create(['name' => 'Bu Pemesan']);
    $lapangan = Lapangan::factory()->create([
        'nama_lapangan' => 'Lapangan Uji',
        'pemilik_id' => $pemilik->id,
    ]);

    $this->actingAs($pemesan, 'sanctum')
        ->postJson('/api/percakapan', ['lapangan_id' => $lapangan->id])
        ->assertCreated();

    $this->actingAs($pemesan, 'sanctum')
        ->getJson('/api/percakapan')
        ->assertOk()
        ->assertJsonPath('data.0.lapangan', 'Lapangan Uji')
        ->assertJsonPath('data.0.lawan_bicara', 'Pak Pemilik')
        ->assertJsonPath('data.0.peran_lawan', 'pemilik');

    $this->actingAs($pemilik, 'sanctum')
        ->getJson('/api/percakapan')
        ->assertOk()
        ->assertJsonPath('data.0.lapangan', 'Lapangan Uji')
        ->assertJsonPath('data.0.lawan_bicara', 'Bu Pemesan')
        ->assertJsonPath('data.0.peran_lawan', 'pemesan');
});

test('admin hanya melihat percakapan yang melibatkan dirinya', function () {
    $pemesan = User::factory()->create();
    $adminLain = User::factory()->create(['role' => 'admin']);
    $adminTarget = User::factory()->create(['role' => 'admin']);

    // Chat admin diarahkan ke admin pertama (order by id), bukan adminTarget.
    config(['services.admin_chat_user_id' => $adminLain->id]);

    $this->actingAs($pemesan, 'sanctum')
        ->postJson('/api/percakapan', ['tujuan' => 'admin'])
        ->assertCreated();

    expect(Percakapan::where('tipe', 'admin')->count())->toBe(1);

    $this->actingAs($adminTarget, 'sanctum')
        ->getJson('/api/percakapan')
        ->assertOk()
        ->assertJsonCount(0, 'data');
});
