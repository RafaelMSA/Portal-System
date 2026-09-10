<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $achievement->title }} - SFAC Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500&family=DM+Serif+Display&display=swap" rel="stylesheet">
</head>
<body class="bg-[#F8F7F5] font-sans text-slate-900">

<header class="bg-red-700 text-white shadow-sm border-b border-red-800">
    <div class="max-w-4xl mx-auto px-6 py-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-[10px] uppercase tracking-[0.3em] text-white/75">SFAC Dashboard</p>
            <h1 class="text-3xl font-semibold">Achievement Details</h1>
        </div>
        <a href="{{ route('achievements.index') }}" class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-2 text-sm text-white transition hover:bg-white/25">
            <i class="fa-solid fa-arrow-left"></i> Back to Achievements
        </a>
    </div>
</header>

<main class="max-w-4xl mx-auto px-6 py-10">
    @php
        $badgeClasses = match($achievement->category) {
            'academic' => 'bg-crimson-50 text-crimson-600',
            'board' => 'bg-amber-50 text-amber-600',
            default => 'bg-gray-100 text-gray-600',
        };
    @endphp
    <div class="bg-white rounded-3xl border border-slate-200 p-8 space-y-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 mb-4">{{ $achievement->title }}</h2>
            <div class="flex flex-col gap-3 text-sm text-gray-600 pb-4 border-b border-gray-200 md:flex-row md:items-center md:justify-between">
                <div class="space-y-2">
                    <div class="inline-flex flex-wrap items-center gap-2">
                        <span class="inline-block px-2.5 py-0.5 rounded-full {{ $badgeClasses }}">{{ $achievement->category_label ?? ucfirst($achievement->category) }}</span>
                        <span class="inline-flex items-center gap-2"><i class="fa-solid fa-circle-check"></i> {{ ucfirst($achievement->status) }}</span>
                        @if (!$achievement->is_published)
                            <span class="inline-block px-2.5 py-0.5 rounded-full bg-gray-200 text-gray-700">Draft</span>
                        @endif
                    </div>
                    <div class="inline-flex flex-wrap items-center gap-4 text-xs text-gray-500">
                        <span><i class="fa-solid fa-calendar mr-1"></i> Awarded {{ $achievement->achieved_at->format('F d, Y') }}</span>
                        <span><i class="fa-solid fa-user mr-1"></i> Awarded to {{ $achievement->recipient_name ?? $achievement->user->name }}</span>
                        <span><i class="fa-solid fa-eye mr-1"></i> {{ $achievement->visible_to_all ? 'Visible to all students' : 'Visible to selected students only' }}</span>
                    </div>
                </div>
                <div class="inline-flex flex-wrap items-center gap-4 text-xs text-gray-500">
                    <span><i class="fa-solid fa-user-pen mr-1"></i> Created by {{ $achievement->createdBy?->name ?? 'System' }}</span>
                    <span><i class="fa-solid fa-clock mr-1"></i> Updated {{ $achievement->updated_at->diffForHumans() }}</span>
                </div>
            </div>

            @if ($achievement->description)
                <div class="space-y-3 text-gray-700">
                    <h3 class="text-xl font-semibold text-gray-900">Achievement Details</h3>
                    <p class="leading-relaxed">{{ $achievement->description }}</p>
                </div>
            @endif

            @if (!$achievement->visible_to_all && $achievement->visibleToStudents->count())
                <div class="space-y-3 text-gray-700">
                    <h3 class="text-xl font-semibold text-gray-900">Visible to Selected Students</h3>
                    <div class="grid gap-2 sm:grid-cols-2">
                        @foreach ($achievement->visibleToStudents as $student)
                            <span class="inline-flex items-center gap-2 rounded-full bg-gray-100 px-3 py-2 text-sm text-gray-700">{{ $student->name }} ({{ $student->email }})</span>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        <div class="border-t border-slate-200 pt-6 space-y-4 md:flex md:items-center md:justify-between md:space-y-0">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-white px-4 py-3 text-sm font-medium text-red-700 transition hover:bg-red-50">
                <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
            </a>
            <div class="flex flex-wrap gap-3">
                @can('update', $achievement)
                    <a href="{{ route('achievements.edit', $achievement) }}" class="inline-flex items-center gap-2 rounded-full bg-red-700 px-4 py-3 text-sm font-medium text-white transition hover:bg-red-800">
                        <i class="fa-solid fa-edit"></i> Edit
                    </a>
                @endcan
                @can('delete', $achievement)
                    <form method="POST" action="{{ route('achievements.destroy', $achievement) }}" onsubmit="return confirm('Are you sure?')" style="display: inline;">
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
