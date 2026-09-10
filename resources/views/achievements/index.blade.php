<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Achievements — SFAC Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500&family=DM+Serif+Display&display=swap" rel="stylesheet">
</head>
<body class="bg-[#F8F7F5] font-sans text-slate-900">

<header class="bg-red-700 text-white shadow-sm border-b border-red-800">
    <div class="max-w-6xl mx-auto px-6 py-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-[10px] uppercase tracking-[0.3em] text-white/75">SFAC Dashboard</p>
            <h1 class="text-3xl font-semibold">Achievements</h1>
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

    <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden">
        <div class="flex flex-col gap-6 border-b border-slate-200 bg-white px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900">All Achievements</h2>
                <p class="text-sm text-slate-600">Create, review, and manage achievement records in one place.</p>
            </div>
            <a href="{{ route('achievements.create') }}" class="inline-flex items-center gap-2 rounded-full bg-red-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-800">
                <i class="fa-solid fa-plus"></i> New Achievement
            </a>
        </div>

        @forelse ($achievements as $achievement)
            @php
                $badgeClasses = match($achievement->category) {
                    'academic' => 'bg-red-100 text-red-700',
                    'board' => 'bg-amber-100 text-amber-700',
                    default => 'bg-slate-100 text-slate-700',
                };
                $statusClasses = match($achievement->status) {
                    'pending' => 'bg-yellow-100 text-yellow-700',
                    'awarded' => 'bg-green-100 text-green-700',
                    'revoked' => 'bg-red-100 text-red-700',
                    default => 'bg-slate-100 text-slate-700',
                };
            @endphp
            <div class="border-b border-slate-200 p-6 hover:bg-slate-50 transition">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <h3 class="text-lg font-semibold text-slate-900">{{ $achievement->title }}</h3>
                            <span class="rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $badgeClasses }}">{{ $achievement->category_label ?? ucfirst($achievement->category) }}</span>
                            <span class="rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $statusClasses }}">{{ ucfirst($achievement->status) }}</span>
                            @if (!$achievement->is_published)
                                <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-700">Draft</span>
                            @endif
                        </div>
                        <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500">
                            <span><i class="fa-solid fa-user mr-1"></i> <strong>Awarded to:</strong> {{ $achievement->recipient_name ?? $achievement->user?->name ?? 'Unknown' }}</span>
                            <span><i class="fa-solid fa-calendar mr-1"></i> {{ $achievement->achieved_at->format('M d, Y') }}</span>
                            <span><i class="fa-solid fa-user-plus mr-1"></i> <strong>By:</strong> {{ $achievement->createdBy?->name ?? 'System' }}</span>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 text-slate-600">
                        <a href="{{ route('achievements.show', $achievement) }}" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-2 text-sm hover:bg-slate-50">
                            <i class="fa-solid fa-eye"></i> View
                        </a>
                        @can('update', $achievement)
                            <a href="{{ route('achievements.edit', $achievement) }}" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-2 text-sm text-blue-700 hover:bg-slate-50">
                                <i class="fa-solid fa-edit"></i> Edit
                            </a>
                        @endcan
                        @can('delete', $achievement)
                            <form method="POST" action="{{ route('achievements.destroy', $achievement) }}" onsubmit="return confirm('Are you sure?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700 hover:bg-red-100">
                                    <i class="fa-solid fa-trash"></i> Delete
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        @empty
            <div class="p-10 text-center text-slate-500">
                <p class="text-xl font-medium mb-2"><i class="fa-solid fa-inbox text-3xl"></i></p>
                <p>No achievements yet.</p>
            </div>
        @endforelse
    </div>

    @if ($achievements->hasPages())
        <div class="mt-6">
            {{ $achievements->links() }}
        </div>
    @endif
</main>

</body>
</html>