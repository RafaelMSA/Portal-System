<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inbox — SFAC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body class="bg-[#F8F7F5] font-sans text-slate-900">

<header class="bg-red-700 text-white shadow-sm border-b border-red-800">
    <div class="max-w-6xl mx-auto px-6 py-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-[10px] uppercase tracking-[0.3em] text-white/75">Support Center</p>
            <h1 class="text-3xl font-semibold">Inbox</h1>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('messages.create') }}" class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-sm text-white transition hover:bg-white/20">
                <i class="fa-solid fa-paper-plane"></i> New Message
            </a>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-sm text-white transition hover:bg-white/20">
                <i class="fa-solid fa-angle-left"></i> Dashboard
            </a>
        </div>
    </div>
</header>

<main class="max-w-6xl mx-auto px-6 py-10">
    @if (session('success'))
        <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden">
        <div class="flex flex-col gap-4 border-b border-slate-200 bg-white px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900">Your Messages</h2>
                <p class="text-sm text-slate-600">Concerns, replies, and support conversations.</p>
            </div>
            @if ($unreadCount > 0)
                <span class="inline-flex items-center gap-2 rounded-full bg-red-100 text-red-700 px-3 py-1 text-xs font-semibold">
                    <i class="fa-solid fa-envelope"></i> {{ $unreadCount }} unread
                </span>
            @endif
        </div>

        @forelse ($conversations as $conversation)
            @php
                $isUnread = $conversation->recipient_id === auth()->id() && ! $conversation->is_read;
            @endphp
            <div class="border-b border-slate-200 p-5 transition hover:bg-slate-50">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <a href="{{ route('messages.show', $conversation) }}" class="flex min-w-0 flex-1 flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="text-lg font-semibold {{ $isUnread ? 'text-slate-900' : 'text-slate-700' }}">
                                {{ $conversation->subject }}
                            </h3>
                            @if ($isUnread)
                                <span class="rounded-full bg-red-100 px-2 py-0.5 text-[10px] font-semibold text-red-700">New</span>
                            @endif
                        </div>
                        <p class="text-sm text-slate-600 line-clamp-2">{{ \Illuminate\Support\Str::limit($conversation->body, 160) }}</p>
                    </div>
                    <div class="flex flex-col items-start md:items-end text-xs text-slate-500">
                        <span class="font-medium text-slate-700">
                            @if ($conversation->sender_id === auth()->id())
                                To: {{ $conversation->recipient?->name ?? 'Unknown user' }}
                            @else
                                From: {{ $conversation->sender?->name ?? 'Unknown user' }}
                            @endif
                        </span>
                        <span><i class="fa-solid fa-clock mr-1"></i>{{ $conversation->created_at->format('F j, Y g:i A') }}</span>
                    </div>
                    </a>
                    <form method="POST" action="{{ route('messages.destroy', $conversation) }}" onsubmit="return confirm('Delete this entire conversation? This will remove it for both participants.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-2 rounded-full border border-red-200 px-3 py-2 text-xs font-semibold text-red-700 transition hover:bg-red-50" aria-label="Delete conversation">
                            <i class="fa-solid fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="p-10 text-center text-slate-500">
                <p class="text-3xl mb-3"><i class="fa-solid fa-inbox"></i></p>
                <p>No messages yet.</p>
            </div>
        @endforelse
    </div>
</main>

</body>
</html>
