@extends('layouts.frontend')
@section('title', 'Chat - Booking Lapang')
@section('content')

<main class="flex-grow max-w-3xl w-full mx-auto px-6 md:px-12 py-8">
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm flex flex-col h-[600px]">

        <div class="p-4 border-b border-outline-variant/60">
            <h3 class="text-title-lg font-bold text-on-surface">Chat - {{ $lawanNama }}</h3>
        </div>

        <div id="chat-box" class="flex-1 overflow-y-auto p-5 space-y-3">
            @foreach($percakapan->pesans as $pesan)
                <div class="flex {{ $pesan->pengirim_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="px-4 py-2.5 rounded-2xl max-w-[70%] text-sm
                        {{ $pesan->pengirim_id === auth()->id()
                            ? 'bg-primary-container text-white'
                            : 'bg-surface-container-highest text-on-surface' }}">
                        {{ $pesan->isi }}
                    </div>
                </div>
            @endforeach
        </div>

        <form id="form-chat" class="flex items-center gap-3 p-4 border-t border-outline-variant/60">
            <input id="input-pesan" type="text"
                class="flex-1 px-4 py-2.5 rounded-xl border border-outline-variant text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                placeholder="Ketik pesan...">
            <button type="submit" class="bg-primary-container text-white px-4 py-2.5 rounded-xl font-bold flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">send</span>
                Kirim
            </button>
        </form>

    </div>
</main>

<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>
<script>
    window.Pusher = Pusher;
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: '{{ config('broadcasting.connections.pusher.key') }}',
        cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
        forceTLS: true,
        authEndpoint: '/broadcasting/auth',
        auth: { headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } }
    });

    const percakapanId = {{ $percakapan->id }};

    window.Echo.private('percakapan.' + percakapanId)
        .listen('PesanDikirim', (data) => {
            const chatBox = document.getElementById('chat-box');
            const isSender = data.pengirim_id === {{ auth()->id() }};
            const wrapper = document.createElement('div');
            wrapper.className = 'flex ' + (isSender ? 'justify-end' : 'justify-start');
            const bubble = document.createElement('div');
            bubble.className = 'px-4 py-2.5 rounded-2xl max-w-[70%] text-sm ' +
                (isSender ? 'bg-primary-container text-white' : 'bg-surface-container-highest text-on-surface');
            bubble.textContent = data.isi;
            wrapper.appendChild(bubble);
            chatBox.appendChild(wrapper);
            chatBox.scrollTop = chatBox.scrollHeight;
        });

    document.getElementById('form-chat').addEventListener('submit', async function(e) {
        e.preventDefault();
        const input = document.getElementById('input-pesan');
        const isi = input.value.trim();
        if (!isi) return;

        const chatBox = document.getElementById('chat-box');
        const wrapper = document.createElement('div');
        wrapper.className = 'flex justify-end';
        const bubble = document.createElement('div');
        bubble.className = 'px-4 py-2.5 rounded-2xl max-w-[70%] text-sm bg-primary-container text-white';
        bubble.textContent = isi;
        wrapper.appendChild(bubble);
        chatBox.appendChild(wrapper);
        chatBox.scrollTop = chatBox.scrollHeight;

        await fetch("{{ route('chat.kirim', $percakapan->id) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ isi })
        });

        input.value = '';
    });
</script>

@endsection