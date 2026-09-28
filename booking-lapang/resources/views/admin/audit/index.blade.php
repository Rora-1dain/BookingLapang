<x-partner-layout :title="'Audit Log - Booking Lapang Admin'">

    <x-admin-sidebar active="audit" />

    <div class="flex-1 flex flex-col min-h-screen ml-64 bg-background">
        <header class="sticky top-0 right-0 h-16 w-full bg-surface-container-lowest border-b border-outline-variant shadow-sm z-30 flex items-center px-8">
            <nav class="flex items-center gap-2 text-label-md text-on-surface-variant">
                <span>Booking Lapang Admin</span>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <span class="text-primary font-bold">Audit Log</span>
            </nav>
        </header>

        <main class="flex-1 p-8 space-y-6">

            <div>
                <h1 class="text-headline-md text-on-surface font-bold tracking-tight">Audit Log</h1>
                <p class="text-body-md text-on-surface-variant mt-1">
                    Jejak aksi sensitif (refund, payout, persetujuan, verifikasi, komisi): siapa, kapan, dari mana, dan apa yang berubah.
                    Log bersifat <span class="font-semibold">append-only</span> &mdash; tidak bisa diubah maupun dihapus dari aplikasi.
                </p>
            </div>

            {{-- Filter --}}
            <form method="GET" action="{{ route('admin.audit.index') }}"
                  class="bg-surface-container-lowest p-4 rounded-2xl border border-outline-variant grid grid-cols-1 md:grid-cols-5 gap-3 items-end">
                <label class="block">
                    <span class="text-label-sm text-outline uppercase tracking-wider block mb-1">Pelaku</span>
                    <select name="pelaku" class="w-full rounded-xl border border-outline-variant bg-surface px-3 py-2 text-body-sm">
                        <option value="">Semua</option>
                        <option value="sistem" @selected(($filter['pelaku'] ?? '') === 'sistem')>Sistem</option>
                        @foreach ($daftarPelaku as $p)
                            <option value="{{ $p->id }}" @selected(($filter['pelaku'] ?? '') == $p->id)>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="block">
                    <span class="text-label-sm text-outline uppercase tracking-wider block mb-1">Jenis Aksi</span>
                    <select name="aksi" class="w-full rounded-xl border border-outline-variant bg-surface px-3 py-2 text-body-sm">
                        <option value="">Semua</option>
                        @foreach ($daftarAksi as $aksi)
                            <option value="{{ $aksi }}" @selected(($filter['aksi'] ?? '') === $aksi)>{{ $aksi }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="block">
                    <span class="text-label-sm text-outline uppercase tracking-wider block mb-1">Dari Tanggal</span>
                    <input type="date" name="dari" value="{{ $filter['dari'] ?? '' }}"
                           class="w-full rounded-xl border border-outline-variant bg-surface px-3 py-2 text-body-sm">
                </label>

                <label class="block">
                    <span class="text-label-sm text-outline uppercase tracking-wider block mb-1">Sampai Tanggal</span>
                    <input type="date" name="sampai" value="{{ $filter['sampai'] ?? '' }}"
                           class="w-full rounded-xl border border-outline-variant bg-surface px-3 py-2 text-body-sm">
                </label>

                <div class="flex items-center gap-2">
                    <button type="submit" class="px-4 py-2 rounded-xl bg-primary text-on-primary text-label-md font-semibold shadow-sm">
                        Terapkan
                    </button>
                    <a href="{{ route('admin.audit.index') }}" class="px-4 py-2 rounded-xl text-label-md font-semibold text-on-surface-variant hover:bg-surface-container">
                        Reset
                    </a>
                </div>
            </form>

            @if ($errors->any())
                <div class="bg-error-container border border-error/30 text-error p-4 rounded-xl text-sm font-semibold">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Tabel log --}}
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm overflow-x-auto">
                <table class="w-full text-left text-body-sm">
                    <thead class="bg-surface-container-low text-label-sm text-outline uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3">Waktu</th>
                            <th class="px-4 py-3">Pelaku</th>
                            <th class="px-4 py-3">Aksi</th>
                            <th class="px-4 py-3">Objek</th>
                            <th class="px-4 py-3">IP</th>
                            <th class="px-4 py-3">Perubahan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/60">
                        @forelse ($logs as $log)
                            @php
                                $badge = match (true) {
                                    str_starts_with($log->aksi, 'refund'), str_starts_with($log->aksi, 'payout') => 'bg-tertiary-fixed text-tertiary',
                                    str_starts_with($log->aksi, 'lapangan'), str_starts_with($log->aksi, 'verifikasi') => 'bg-primary-fixed text-on-primary-fixed',
                                    str_starts_with($log->aksi, 'komisi') => 'bg-secondary-fixed text-on-secondary-fixed-variant',
                                    default => 'bg-surface-container text-on-surface-variant',
                                };
                                $json = fn ($d) => json_encode($d, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                            @endphp
                            <tr class="align-top">
                                <td class="px-4 py-3 whitespace-nowrap text-on-surface">
                                    {{ $log->dicatat_pada->translatedFormat('d M Y') }}
                                    <span class="block text-outline">{{ $log->dicatat_pada->format('H:i:s') }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    @if ($log->pelaku)
                                        <span class="font-semibold text-on-surface">{{ $log->pelaku->name }}</span>
                                    @else
                                        <span class="italic text-outline">Sistem</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold {{ $badge }}">{{ $log->aksi }}</span>
                                </td>
                                <td class="px-4 py-3 text-on-surface-variant whitespace-nowrap">
                                    @if ($log->objek_type)
                                        {{ class_basename($log->objek_type) }} #{{ $log->objek_id }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-mono text-outline whitespace-nowrap" title="{{ $log->user_agent }}">{{ $log->ip_address ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    @if ($log->data_sebelum || $log->data_sesudah)
                                        <details>
                                            <summary class="cursor-pointer text-primary font-semibold">Lihat</summary>
                                            <div class="mt-2 grid grid-cols-1 gap-2 min-w-[220px]">
                                                <div>
                                                    <span class="text-label-sm text-outline uppercase block">Sebelum</span>
                                                    <pre class="text-[11px] bg-surface p-2 rounded-lg whitespace-pre-wrap break-all">{{ $log->data_sebelum ? $json($log->data_sebelum) : '-' }}</pre>
                                                </div>
                                                <div>
                                                    <span class="text-label-sm text-outline uppercase block">Sesudah</span>
                                                    <pre class="text-[11px] bg-surface p-2 rounded-lg whitespace-pre-wrap break-all">{{ $log->data_sesudah ? $json($log->data_sesudah) : '-' }}</pre>
                                                </div>
                                            </div>
                                        </details>
                                    @else
                                        <span class="text-outline">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-on-surface-variant">
                                    Belum ada catatan audit yang cocok dengan filter ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($logs->hasPages())
                <div>{{ $logs->onEachSide(1)->links() }}</div>
            @endif
        </main>
    </div>
</x-partner-layout>
