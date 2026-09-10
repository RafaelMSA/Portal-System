<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $message->subject }} — SFAC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body class="bg-[#F8F7F5] font-sans text-slate-900">

<header class="bg-red-700 text-white shadow-sm border-b border-red-800">
    <div class="max-w-5xl mx-auto px-6 py-4 flex items-center justify-between">
        <div>
            <p class="text-[10px] uppercase tracking-[0.3em] text-white/75">Support Center</p>
            <h1 class="text-3xl font-semibold">{{ $message->subject }}</h1>
        </div>
        <a href="{{ route('messages.index') }}" class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-sm text-white transition hover:bg-white/20">
            <i class="fa-solid fa-angle-left"></i> Back to Inbox
        </a>
    </div>
</header>

<main class="max-w-5xl mx-auto px-6 py-10">
    @if (session('success'))
        <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-5">
        @foreach ($thread as $entry)
            <div class="rounded-3xl border {{ $entry->sender_id === auth()->id() ? 'border-red-200 bg-red-50' : 'border-slate-200 bg-white' }} shadow-sm p-5">
                <div class="flex items-center justify-between gap-3 mb-3">
                    <div>
                        <p class="text-sm font-semibold text-slate-800">
                            {{ $entry->sender?->name ?? 'Unknown user' }}
                            <span class="text-xs font-normal text-slate-500">({{ ucfirst($entry->sender?->role ?? 'user') }})</span>
                        </p>
                    </div>
                    <span class="text-xs text-slate-500">{{ $entry->created_at->format('F j, Y g:i A') }}</span>
                </div>
                <p class="text-sm leading-7 text-slate-700 whitespace-pre-wrap">{{ $entry->body }}</p>
            </div>
        @endforeach
    </div>

    <form method="POST" action="{{ route('messages.reply', $message) }}" class="mt-8 bg-white rounded-3xl border border-slate-200 shadow-xl p-6">
        @csrf
        <label for="body" class="block text-sm font-medium text-slate-700 mb-2">Reply</label>
        <textarea name="body" id="body" rows="5" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-red-100" placeholder="Write a reply..." required></textarea>
        <div class="mt-4 flex justify-end">
            <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-red-700 px-5 py-3 text-sm font-semibold text-white hover:bg-red-800">
                <i class="fa-solid fa-reply"></i> Reply
            </button>
        </div>
    </form>

    <form method="POST" action="{{ route('messages.destroy', $message) }}" class="mt-4 flex justify-end" onsubmit="return confirm('Delete this entire conversation? This will remove it for both participants.');">
        @csrf
        @method('DELETE')
        <button type="submit" class="inline-flex items-center gap-2 rounded-full border border-red-200 px-5 py-3 text-sm font-semibold text-red-700 transition hover:bg-red-50">
            <i class="fa-solid fa-trash"></i> Delete Conversation
        </button>
    </form>
</main>

</body>
</html>
