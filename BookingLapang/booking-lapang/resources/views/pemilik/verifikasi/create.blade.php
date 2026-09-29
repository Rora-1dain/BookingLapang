<x-partner-layout :title="'Verifikasi Arena - Booking Lapang Partner'">

    <x-partner-sidebar active="verifikasi" />

    <div class="flex-1 ml-72 flex flex-col min-h-screen">
        <header class="fixed top-0 right-0 left-72 z-20 flex justify-between items-center px-8 h-16 bg-surface border-b border-outline-variant/60 shadow-sm">
            <div class="flex items-center gap-2">
                <span class="text-title-lg text-primary font-extrabold tracking-tight">Booking Lapang Partner</span>
                <span class="text-outline-variant font-bold">/</span>
                <span class="text-label-lg text-on-surface-variant font-medium">Verifikasi Arena</span>
            </div>
        </header>

        <main class="ml-0 pt-20 pb-24 px-8 min-h-screen bg-background">
            <div class="max-w-4xl mx-auto space-y-8">

                @if (session('success'))
                    <div class="bg-primary/10 border border-primary/30 text-primary p-4 rounded-xl flex items-center gap-2 text-sm font-semibold">
                        <span class="material-symbols-outlined">check_circle</span> {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-error-container border border-error/30 text-error p-4 rounded-xl flex items-center gap-2 text-sm font-semibold">
                        <span class="material-symbols-outlined">error</span> {{ session('error') }}
                    </div>
                @endif

                <div>
                    <h2 class="text-headline-lg text-on-surface tracking-tight">Status Verifikasi Akun</h2>
                    <p class="text-body-md text-on-surface-variant mt-1">
                        Unggah dokumen identitas (KTP/SIM) supaya akun lu bisa mengajukan &amp; menayangkan lapangan ke publik.
                    </p>
                </div>

                {{-- Status Banner --}}
                @php
                    $status = auth()->user()->status_verifikasi ?? 'belum_verifikasi';
                    $banner = match($status) {
                        'terverifikasi' => ['bg-primary/10 border-primary/30', 'text-primary', 'check_circle', 'Akun lu sudah terverifikasi', 'Sekarang lu bisa mengajukan lapangan baru.'],
                        'menunggu' => ['bg-tertiary-fixed/30 border-tertiary-fixed-dim', 'text-tertiary', 'hourglass_top', 'Dokumen sedang ditinjau admin', 'Biasanya butuh beberapa saat. Halaman ini otomatis update kalau statusnya berubah.'],
                        'ditolak' => ['bg-[#FDF0ED] border-secondary/40', 'text-secondary', 'warning', 'Pengajuan ditolak, perlu diunggah ulang', auth()->user()->catatan_verifikasi ?? 'Silakan unggah ulang dokumen yang lebih jelas.'],
                        default => ['bg-surface-container border-outline-variant', 'text-on-surface-variant', 'info', 'Belum ada pengajuan verifikasi', 'Unggah dokumen identitas lu di bawah buat mulai proses verifikasi.'],
                    };
                @endphp
                <div class="relative overflow-hidden rounded-2xl border {{ $banner[0] }} p-6 shadow-athletic-md">
                    <div class="flex items-start gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-surface {{ $banner[1] }} flex items-center justify-center flex-shrink-0 shadow-md">
                            <span class="material-symbols-outlined text-3xl">{{ $banner[2] }}</span>
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-title-lg {{ $banner[1] }} font-bold">{{ $banner[3] }}</h3>
                            <p class="text-body-md text-on-surface-variant leading-relaxed">{{ $banner[4] }}</p>
                        </div>
                    </div>
                </div>

                {{-- Stepper sederhana sesuai status enum asli --}}
                <div class="bg-surface rounded-2xl border border-outline-variant/70 p-6 shadow-athletic-sm">
                    <h3 class="text-title-lg text-on-surface mb-4">Alur Verifikasi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @php
                            $steps = [
                                ['label' => 'Dokumen Diajukan', 'icon' => 'upload_file', 'done' => in_array($status, ['menunggu', 'terverifikasi', 'ditolak'])],
                                ['label' => 'Ditinjau Admin', 'icon' => 'fact_check', 'done' => in_array($status, ['terverifikasi', 'ditolak']), 'active' => $status === 'menunggu'],
                                ['label' => 'Terverifikasi', 'icon' => 'verified_user', 'done' => $status === 'terverifikasi'],
                            ];
                        @endphp
                        @foreach ($steps as $step)
                            <div class="relative bg-surface-container-low rounded-xl p-4 border-2 {{ $step['done'] ? 'border-primary-container/40' : ($step['active'] ?? false ? 'border-primary-container' : 'border-outline-variant/60 opacity-70') }} flex flex-col justify-between">
                                <div class="w-10 h-10 rounded-xl {{ $step['done'] ? 'bg-primary-container text-on-primary' : (($step['active'] ?? false) ? 'bg-primary text-on-primary' : 'bg-surface-container-highest text-outline') }} flex items-center justify-center shadow-sm mb-3">
                                    <span class="material-symbols-outlined text-xl">{{ $step['icon'] }}</span>
                                </div>
                                <h4 class="text-title-md text-on-surface">{{ $step['label'] }}</h4>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Dokumen yang sudah diunggah (preview) --}}
                @if (auth()->user()->path_dokumen_identitas)
                    <div class="bg-surface rounded-2xl border border-outline-variant/80 p-6 shadow-athletic-sm">
                        <h3 class="text-title-md text-on-surface font-bold mb-3">Dokumen Identitas Terakhir Diunggah</h3>
                        @if (str_starts_with(auth()->user()->path_dokumen_identitas, 'data:image'))
                            <img src="{{ auth()->user()->path_dokumen_identitas }}" alt="Dokumen identitas" class="max-w-xs rounded-xl border border-outline-variant">
                        @else
                            <a href="{{ auth()->user()->path_dokumen_identitas }}" target="_blank" class="inline-flex items-center gap-2 text-primary font-semibold hover:underline">
                                <span class="material-symbols-outlined">description</span> Lihat dokumen (PDF)
                            </a>
                        @endif
                    </div>
                @endif

                {{-- Form Upload --}}
                @if ($status !== 'terverifikasi')
                    <div class="bg-surface rounded-2xl border border-outline-variant/70 p-6 shadow-athletic-sm">
                        <h3 class="text-title-lg text-on-surface mb-1">{{ $status === 'ditolak' ? 'Unggah Ulang Dokumen' : 'Unggah Dokumen Identitas' }}</h3>
                        <p class="text-body-sm text-on-surface-variant mb-4">Format JPG, PNG, atau PDF, maksimal 2MB.</p>

                        <form action="{{ route('pemilik.verifikasi.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf

                            <label for="dokumen_identitas" class="block border-2 border-dashed border-outline-variant hover:border-primary-container rounded-2xl p-8 text-center bg-surface-container-low transition-colors cursor-pointer">
                                <div class="w-12 h-12 rounded-full bg-primary-container/10 text-primary-container mx-auto flex items-center justify-center mb-2">
                                    <span class="material-symbols-outlined text-2xl">cloud_upload</span>
                                </div>
                                <p class="text-label-md text-on-surface font-semibold">Klik buat pilih berkas KTP/SIM</p>
                                <p class="text-body-sm text-outline mt-0.5">JPG, PNG, atau PDF — maksimal 2MB</p>
                                <input id="dokumen_identitas" type="file" name="dokumen_identitas" accept=".jpg,.jpeg,.png,.pdf" required class="hidden" onchange="document.getElementById('nama-file').textContent = this.files[0]?.name ?? ''">
                                <p id="nama-file" class="text-label-sm text-primary font-semibold mt-2"></p>
                            </label>
                            @error('dokumen_identitas')
                                <p class="text-secondary text-sm">{{ $message }}</p>
                            @enderror

                            <button type="submit" {{ $status === 'menunggu' ? 'disabled' : '' }}
                                    class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-primary-container text-on-primary hover:bg-primary font-label-lg transition-all shadow-md active:scale-98 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span class="material-symbols-outlined text-xl">publish</span>
                                {{ $status === 'ditolak' ? 'Ajukan Ulang Verifikasi' : 'Ajukan Verifikasi' }}
                            </button>
                        </form>
                    </div>
                @else
                    <div class="bg-surface rounded-2xl border border-outline-variant/70 p-6 shadow-athletic-sm text-body-md text-on-surface-variant">
                        Akun lu sudah terverifikasi. Gak perlu unggah dokumen lagi.
                    </div>
                @endif
            </div>
        </main>
    </div>
</x-partner-layout>