<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Message — SFAC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body class="bg-[#F8F7F5] font-sans text-slate-900">

<header class="bg-red-700 text-white shadow-sm border-b border-red-800">
    <div class="max-w-4xl mx-auto px-6 py-4 flex items-center justify-between">
        <div>
            <p class="text-[10px] uppercase tracking-[0.3em] text-white/75">Support Center</p>
            <h1 class="text-3xl font-semibold">Send a Concern</h1>
        </div>
        <a href="{{ route('messages.index') }}" class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-sm text-white transition hover:bg-white/20">
            <i class="fa-solid fa-angle-left"></i> Back to Inbox
        </a>
    </div>
</header>

<main class="max-w-4xl mx-auto px-6 py-10">
    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-5 text-sm text-red-700">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('messages.store') }}" class="bg-white rounded-3xl border border-slate-200 shadow-xl p-6 space-y-6">
        @csrf

        <div>
            <label for="recipient_id" class="block text-sm font-medium text-slate-700 mb-2">Send to</label>
            <select name="recipient_id" id="recipient_id" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-red-100" required>
                <option value="">Select recipient</option>
                @foreach ($recipients as $recipient)
                    <option value="{{ $recipient->id }}">{{ $recipient->name }} ({{ ucfirst($recipient->role) }})</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="subject" class="block text-sm font-medium text-slate-700 mb-2">Subject</label>
            <input type="text" name="subject" id="subject" value="{{ old('subject') }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-red-100" placeholder="Concern about my enrollment" required>
        </div>

        <div>
            <label for="body" class="block text-sm font-medium text-slate-700 mb-2">Message</label>
            <textarea name="body" id="body" rows="8" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-red-100" placeholder="Describe your concern or question..." required>{{ old('body') }}</textarea>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-red-700 px-5 py-3 text-sm font-semibold text-white hover:bg-red-800">
                <i class="fa-solid fa-paper-plane"></i> Send Message
            </button>
        </div>
    </form>
</main>

</body>
</html>
