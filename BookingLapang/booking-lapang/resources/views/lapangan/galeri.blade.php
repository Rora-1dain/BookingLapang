@extends('layouts.app')

@section('content')
<style>
    .galeri-wrap { max-width: 960px; margin: 2rem auto; padding: 0 1rem; }
    .galeri-title { font-size: 1.5rem; font-weight: 600; margin-bottom: .25rem; }
    .galeri-sub { color: #6b7280; margin-bottom: 1.5rem; }
    .alert-box { padding: .75rem 1rem; border-radius: .5rem; margin-bottom: 1rem; font-size: .9rem; }
    .alert-success { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
    .alert-error { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
    .upload-card { background: #fff; border: 1px solid #e5e7eb; border-radius: .75rem; padding: 1.25rem; margin-bottom: 1.5rem; }
    .upload-row { display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; }
    .file-input { flex: 1; min-width: 220px; }
    .file-input input[type=file] { width: 100%; padding: .5rem; border: 1px dashed #d1d5db; border-radius: .5rem; background: #f9fafb; font-size: .875rem; }
    .btn-primary { background: #2563eb; color: #fff; border: none; padding: .55rem 1.1rem; border-radius: .5rem; font-size: .9rem; cursor: pointer; }
    .btn-primary:hover { background: #1d4ed8; }
    .counter { font-size: .875rem; color: #6b7280; margin-bottom: 1rem; }
    .galeri-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1rem; }
    .foto-card { background: #fff; border: 1px solid #e5e7eb; border-radius: .75rem; overflow: hidden; position: relative; }
    .foto-card.utama { border: 2px solid #f59e0b; }
    .foto-card img { width: 100%; height: 140px; object-fit: cover; display: block; }
    .foto-body { padding: .6rem; display: flex; flex-direction: column; gap: .4rem; }
    .badge-utama { display: inline-block; background: #fef3c7; color: #92400e; font-size: .75rem; font-weight: 600; padding: .2rem .5rem; border-radius: .375rem; }
    .foto-actions { display: flex; gap: .4rem; }
    .btn-sm { flex: 1; font-size: .78rem; padding: .35rem .5rem; border-radius: .375rem; border: 1px solid #d1d5db; background: #fff; cursor: pointer; }
    .btn-sm.danger { color: #dc2626; border-color: #fecaca; }
    .btn-sm.danger:hover { background: #fef2f2; }
    .btn-sm.outline:hover { background: #f3f4f6; }
    .empty-state { color: #9ca3af; padding: 2rem 0; text-align: center; }
</style>

<div class="galeri-wrap">
    <div class="galeri-title">Galeri Foto - {{ $lapangan->nama_lapangan }}</div>
    <div class="galeri-sub">Maksimal 8 foto per lapangan.</div>

    @if (session('success'))
        <div class="alert-box alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert-box alert-error">{{ session('error') }}</div>
    @endif
    @error('foto')
        <div class="alert-box alert-error">{{ $message }}</div>
    @enderror
    @error('foto.*')
        <div class="alert-box alert-error">{{ $message }}</div>
    @enderror

    <div class="upload-card">
        <form action="{{ route('lapangan.foto.store', $lapangan) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="upload-row">
                <div class="file-input">
                    <input type="file" name="foto[]" multiple accept=".jpg,.jpeg,.png">
                </div>
                <button type="submit" class="btn-primary">Unggah Foto</button>
            </div>
        </form>
    </div>

    <div class="counter">Jumlah foto saat ini: {{ $lapangan->fotos()->count() }} / 8</div>

    <div class="galeri-grid">
        @forelse ($lapangan->fotos()->orderBy('urutan')->get() as $foto)
            <div class="foto-card {{ $foto->is_utama ? 'utama' : '' }}">
                <img src="{{ Storage::url($foto->path_file) }}" alt="Foto lapangan">
                <div class="foto-body">
                    @if ($foto->is_utama)
                        <span class="badge-utama">Foto Utama</span>
                    @else
                        <form action="{{ route('lapangan.foto.utama', $foto) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-sm outline" style="width:100%">Jadikan Utama</button>
                        </form>
                    @endif

                    <form action="{{ route('lapangan.foto.destroy', $foto) }}" method="POST"
                          onsubmit="return confirm('Hapus foto ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-sm danger" style="width:100%">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty-state">Belum ada foto.</div>
        @endforelse
    </div>
</div>
@endsection