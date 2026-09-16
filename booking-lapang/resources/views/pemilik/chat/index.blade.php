<x-partner-layout :title="'Chat - Booking Lapang Partner'">

    <x-partner-sidebar active="chat" />

    <header class="fixed top-0 right-0 left-72 z-20 flex justify-between items-center px-8 h-16 bg-[#FBF7F0]/90 backdrop-blur-md border-b">
        <div class="flex items-center gap-2.5">
            <span class="text-title-md font-bold text-primary tracking-tight">Chat</span>
            <span class="text-outline-variant">/</span>
            <span class="text-label-md font-semibold text-on-surface-variant">{{ $lawanNama }}</span>
        </div>
    </header>

    <main class="ml-72 pt-16 flex-1 min-h-screen bg-background p-8">
        <div class="max-w-[800px] mx-auto space-y-4">

            <div class="bg-white rounded-3xl border border-surface-container-highest shadow-sm flex flex-col h-[600px]">

                <div id="chat-box" class="flex-1 overflow-y-auto p-6 space-y-3">
                    @foreach($percakapan->pesans as $pesan)
                        <div class="flex {{ $pesan->pengirim_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div class="px-4 py-2.5 rounded-2xl max-w-[70%] text-label-md
                                {{ $pesan->pengirim_id === auth()->id()
                                    ? 'bg-primary text-white'
                                    : 'bg-surface-container-highest text-on-surface' }}">
                                {{ $pesan->isi }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <form id="form-chat" class="flex items-center gap-3 p-4 border-t border-surface-container-highest">
                    <input id="input-pesan" type="text"
                        class="flex-1 px-4 py-2.5 rounded-xl border border-surface-container-highest text-label-md focus:outline-none focus:ring-2 focus:ring-primary"
                        placeholder="Ketik pesan...">
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-primary hover:opacity-90 text-white text-label-md font-semibold">
                        <span class="material-symbols-outlined text-lg">send</span>
                        Kirim
                    </button>
                </form>

            </div>

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
        auth: {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }
    });

    const percakapanId = {{ $percakapan->id }};

    window.Echo.private('percakapan.' + percakapanId)
        .listen('.PesanDikirim', (data) => {
            const chatBox = document.getElementById('chat-box');
            const isSender = data.pengirim_id === {{ auth()->id() }};

            const wrapper = document.createElement('div');
            wrapper.className = 'flex ' + (isSender ? 'justify-end' : 'justify-start');

            const bubble = document.createElement('div');
            bubble.className = 'px-4 py-2.5 rounded-2xl max-w-[70%] text-label-md ' +
                (isSender ? 'bg-primary text-white' : 'bg-surface-container-highest text-on-surface');
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
        bubble.className = 'px-4 py-2.5 rounded-2xl max-w-[70%] text-label-md bg-primary text-white';
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

</x-partner-layout>