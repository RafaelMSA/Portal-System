<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $announcement->title }} - SFAC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500&family=DM+Serif+Display&display=swap" rel="stylesheet">
</head>
<body class="bg-[#F8F7F5] font-sans text-slate-900">

<header class="bg-red-700 text-white shadow-sm border-b border-red-800">
    <div class="max-w-4xl mx-auto px-6 py-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            @php
                $dashboardLabel = match(auth()->user()->role) {
                    'admin' => 'SFAC Admin Dashboard',
                    'staff', 'faculty' => 'SFAC Faculty Dashboard',
                    default => 'SFAC Dashboard',
                };
            @endphp
            <p class="text-[10px] uppercase tracking-[0.3em] text-white/75">{{ $dashboardLabel }}</p>
            <h1 class="text-3xl font-semibold">Announcement Details</h1>
        </div>
        <a href="{{ route('announcements.index') }}" class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-2 text-sm text-white transition hover:bg-white/25">
            <i class="fa-solid fa-arrow-left"></i> Back to Announcements
        </a>
    </div>
</header>

<main class="max-w-4xl mx-auto px-6 py-10">
    <div class="bg-white rounded-3xl border border-slate-200 p-8 space-y-6">
        <div>
            <h2 class="text-3xl font-bold text-slate-900 mb-4">{{ $announcement->title }}</h2>
            <div class="flex flex-wrap items-center gap-6 text-sm text-slate-500 pb-4 border-b border-slate-200">
                <span><i class="fa-solid fa-calendar mr-2"></i> {{ $announcement->published_at?->format('F d, Y') ?? 'Not yet published' }}</span>
                <span><i class="fa-solid fa-user mr-2"></i> {{ $announcement->user->name }}</span>
                @if ($announcement->deadline)
                    <span><i class="fa-solid fa-hourglass-half mr-2"></i> Deadline: {{ $announcement->deadline->format('F d, Y') }}</span>
                @endif
                <span><i class="fa-solid fa-clock mr-2"></i> {{ $announcement->updated_at->diffForHumans() }}</span>
            </div>
            <div class="mt-4 flex flex-wrap items-center gap-3">
                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">Type: {{ ucfirst($announcement->type) }}</span>
                <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-800">Urgency: {{ ucfirst($announcement->urgency) }}</span>
                <span class="rounded-full {{ $announcement->is_published ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-700' }} px-3 py-1 text-xs font-semibold">{{ $announcement->is_published ? 'Published' : 'Pending' }}</span>
            </div>
        </div>

        <div class="prose prose-sm max-w-none">
            <p class="text-slate-700 leading-relaxed whitespace-pre-wrap">{{ $announcement->body }}</p>
        </div>

        <div class="border-t border-slate-200 pt-6 space-y-4 md:flex md:items-center md:justify-between md:space-y-0">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-white px-4 py-3 text-sm font-medium text-red-700 transition hover:bg-red-50">
                <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
            </a>
            <div class="flex flex-wrap gap-3">
                @can('update', $announcement)
                    <a href="{{ route('announcements.edit', $announcement) }}" class="inline-flex items-center gap-2 rounded-full bg-red-700 px-4 py-3 text-sm font-medium text-white transition hover:bg-red-800">
                        <i class="fa-solid fa-edit"></i> Edit
                    </a>
                @endcan
                @can('delete', $announcement)
                    <form method="POST" action="{{ route('announcements.destroy', $announcement) }}" onsubmit="return confirm('Are you sure?')" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-white px-4 py-3 text-sm font-medium text-red-700 transition hover:bg-red-50">
                            <i class="fa-solid fa-trash"></i> Delete
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </div>
</main>

</body>
</html>
