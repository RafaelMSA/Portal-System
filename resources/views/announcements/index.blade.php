<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements — SFAC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500&family=DM+Serif+Display&display=swap" rel="stylesheet">
</head>
<body class="bg-[#F8F7F5] font-sans text-slate-900">

<header class="bg-red-700 text-white shadow-sm border-b border-red-800">
    <div class="max-w-6xl mx-auto px-6 py-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            @php
                $dashboardLabel = match(auth()->user()->role) {
                    'admin' => 'SFAC Admin Dashboard',
                    'staff', 'faculty' => 'SFAC Faculty Dashboard',
                    default => 'SFAC Dashboard',
                };
            @endphp
            <p class="text-[10px] uppercase tracking-[0.3em] text-white/75">{{ $dashboardLabel }}</p>
            <h1 class="text-3xl font-semibold">Announcements</h1>
        </div>
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-sm text-white transition hover:bg-white/20">
            <i class="fa-solid fa-angle-left"></i> Back to Dashboard
        </a>
    </div>
</header>

<main class="max-w-6xl mx-auto px-6 py-10">
    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-5 text-sm text-red-700">
            <h2 class="font-semibold mb-2">Please fix the following</h2>
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 p-5 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @php
        $isEditor = in_array(auth()->user()->role, ['admin', 'staff', 'faculty']);
    @endphp
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden">
        <div class="flex flex-col gap-6 border-b border-slate-200 bg-white px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900">Announcements</h2>
                <p class="text-sm text-slate-600">View campus announcements with type, urgency, and status details.</p>
            </div>
            @if ($isEditor)
                <a href="{{ route('announcements.create') }}" class="inline-flex items-center gap-2 rounded-full bg-red-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-800">
                    <i class="fa-solid fa-plus"></i> New Announcement
                </a>
            @endif
        </div>

        @forelse ($announcements as $announcement)
            <div class="border-b border-slate-200 p-6 hover:bg-slate-50 transition">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <h3 class="text-lg font-semibold text-slate-900">{{ $announcement->title }}</h3>
                            @php
                                $typeColors = [
                                    'news' => 'bg-blue-100 text-blue-700',
                                    'alert' => 'bg-red-100 text-red-700',
                                    'event' => 'bg-amber-100 text-amber-700',
                                    'notice' => 'bg-slate-100 text-slate-700',
                                ];
                            @endphp
                            <span class="rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $typeColors[$announcement->type] ?? 'bg-blue-100 text-blue-700' }}">{{ ucfirst($announcement->type) }}</span>
                            <span class="rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $announcement->urgency === 'high' ? 'bg-red-100 text-red-700' : ($announcement->urgency === 'medium' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-700') }}">{{ ucfirst($announcement->urgency) }}</span>
                            @if (!$announcement->is_published)
                                <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-700">Draft</span>
                            @endif
                        </div>
                        <p class="text-sm text-slate-600 mb-3">{{ \Illuminate\Support\Str::limit($announcement->body, 180) }}</p>
                        <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500">
                            <span><i class="fa-solid fa-calendar mr-1"></i> {{ $announcement->published_at?->format('M d, Y') ?? 'Unpublished' }}</span>
                            <span><i class="fa-solid fa-user mr-1"></i> {{ $announcement->user?->name ?? 'Unknown author' }}</span>
                            @if ($announcement->deadline)
                                <span><i class="fa-solid fa-hourglass-half mr-1"></i> Deadline: {{ $announcement->deadline->format('M d, Y') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 text-slate-600">
                        <a href="{{ route('announcements.show', $announcement) }}" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-2 text-sm hover:bg-slate-50">
                            <i class="fa-solid fa-eye"></i> View
                        </a>
                        @if ($isEditor)
                            <a href="{{ route('announcements.edit', $announcement) }}" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-2 text-sm text-blue-700 hover:bg-slate-50">
                                <i class="fa-solid fa-edit"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('announcements.destroy', $announcement) }}" onsubmit="return confirm('Are you sure?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700 hover:bg-red-100">
                                    <i class="fa-solid fa-trash"></i> Delete
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="p-10 text-center text-slate-500">
                <p class="text-xl font-medium mb-2"><i class="fa-solid fa-inbox text-3xl"></i></p>
                <p>No announcements yet.</p>
            </div>
        @endforelse
    </div>

    @if ($announcements->hasPages())
        <div class="mt-6">
            {{ $announcements->links() }}
        </div>
    @endif
</main>

</body>
</html>
