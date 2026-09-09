@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4>Galeri Foto - {{ $lapangan->nama_lapangan }}</h4>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @error('foto')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    @error('foto.*')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    {{-- Form upload, multiple file, testing skenario 1 --}}
    <form action="{{ route('lapangan.foto.store', $lapangan) }}" method="POST" enctype="multipart/form-data" class="mb-4">
        @csrf
        <div class="mb-2">
            <input type="file" name="foto[]" multiple accept=".jpg,.jpeg,.png" class="form-control">
            <small class="text-muted">Pilih beberapa file sekaligus buat test batas 8 foto (misal pilih 9 file).</small>
        </div>
        <button type="submit" class="btn btn-primary">Unggah Foto</button>
    </form>

    <p>Jumlah foto saat ini: {{ $lapangan->fotos()->count() }} / 8</p>

    {{-- Grid galeri, testing skenario 2 --}}
    <div class="row g-3">
        @forelse ($lapangan->fotos()->orderBy('urutan')->get() as $foto)
            <div class="col-md-3">
                <div class="card {{ $foto->is_utama ? 'border-warning border-3' : '' }}">
                    <img src="{{ Storage::url($foto->path_file) }}" class="card-img-top" style="height:150px;object-fit:cover;">
                    <div class="card-body p-2">
                        @if ($foto->is_utama)
                            <span class="badge bg-warning text-dark">Foto Utama</span>
                        @else
                            <form action="{{ route('lapangan.foto.utama', $foto) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-secondary">Jadikan Utama</button>
                            </form>
                        @endif

                        <form action="{{ route('lapangan.foto.destroy', $foto) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Hapus foto ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p>Belum ada foto.</p>
        @endforelse
    </div>
</div>
@endsection