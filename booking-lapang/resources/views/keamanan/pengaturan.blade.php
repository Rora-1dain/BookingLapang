<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengaturan Keamanan (2FA)</title>
    <style>
        body { font-family: system-ui, sans-serif; background: #f3f4f6; margin: 0; padding: 24px; color: #111827; }
        .card { max-width: 520px; margin: 0 auto; background: #fff; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,.1); }
        h1 { font-size: 20px; margin-top: 0; }
        .alert { padding: 10px 14px; border-radius: 8px; margin-bottom: 14px; font-size: 14px; }
        .ok { background: #dcfce7; color: #166534; }
        .err { background: #fee2e2; color: #991b1b; }
        .kode { font-family: monospace; background: #f3f4f6; padding: 6px 10px; border-radius: 6px; display: block; margin: 4px 0; }
        input[type=text] { width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; box-sizing: border-box; margin: 8px 0; }
        button { background: #2563eb; color: #fff; border: 0; padding: 10px 16px; border-radius: 8px; cursor: pointer; }
        button.link { background: none; color: #6b7280; padding: 0; text-decoration: underline; }
        .qr { text-align: center; margin: 16px 0; }
    </style>
</head>
<body>
<div class="card">
    <h1>Autentikasi Dua Faktor (2FA)</h1>

    @if (session('success')) <div class="alert ok">{{ session('success') }}</div> @endif
    @if (session('error')) <div class="alert err">{{ session('error') }}</div> @endif
    @if ($errors->any()) <div class="alert err">{{ $errors->first() }}</div> @endif

    @if ($recoveryCodes)
        <p><strong>Recovery code</strong> (tiap kode hanya bisa dipakai sekali, catat sekarang karena tidak akan ditampilkan lagi):</p>
        @foreach ($recoveryCodes as $kode)
            <span class="kode">{{ $kode }}</span>
        @endforeach
    @endif

    @if ($aktif)
        <p>Status: <strong>AKTIF</strong>. Akun Anda dilindungi kode dari aplikasi authenticator.</p>
    @elseif ($sedangAktivasi)
        <p>1. Scan QR ini dengan Google Authenticator / Authy:</p>
        <div class="qr">{!! $qrSvg !!}</div>
        <p>Atau masukkan kunci manual: <span class="kode">{{ $secret }}</span></p>
        <p>2. Masukkan kode 6 digit dari aplikasi untuk menyelesaikan aktivasi:</p>
        <form method="POST" action="{{ route('keamanan.konfirmasi') }}">
            @csrf
            <input type="text" name="kode" inputmode="numeric" maxlength="6" placeholder="123456" autocomplete="one-time-code">
            <button type="submit">Aktifkan 2FA</button>
        </form>
    @else
        <p>Status: <strong>BELUM AKTIF</strong>.</p>
        <form method="POST" action="{{ route('keamanan.mulai') }}">
            @csrf
            <button type="submit">Mulai Aktivasi 2FA</button>
        </form>
    @endif

    <hr style="margin: 20px 0; border: 0; border-top: 1px solid #e5e7eb;">
    <a href="{{ route('home') }}">Kembali ke beranda</a> &middot;
    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
        @csrf
        <button type="submit" class="link">Keluar</button>
    </form>
</div>
</body>
</html>