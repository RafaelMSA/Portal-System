<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Announcement — SFAC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500&family=DM+Serif+Display&display=swap" rel="stylesheet">
</head>
<body class="bg-[#F8F7F5] font-sans text-slate-900">

<header class="bg-red-700 text-white shadow-sm border-b border-red-800">
    <div class="max-w-4xl mx-auto px-6 py-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-[10px] uppercase tracking-[0.3em] text-white/75">SFAC Admin Dashboard</p>
            <h1 class="text-3xl font-semibold">Create Announcement</h1>
        </div>
        <a href="{{ route('announcements.index') }}" class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-2 text-sm text-white transition hover:bg-white/25">
            <i class="fa-solid fa-arrow-left"></i> Back to Announcements
        </a>
    </div>
</header>

<main class="max-w-4xl mx-auto px-6 py-10">
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

    <form method="POST" action="{{ route('announcements.store') }}" class="rounded-[2rem] border border-red-100 bg-white shadow-xl">
        @csrf

        <div class="grid gap-8 p-8">
            <section class="rounded-3xl border border-red-100 bg-red-50 p-6">
                <h2 class="text-xl font-semibold text-red-900">Announcement details</h2>
                <p class="mt-2 text-sm text-red-700/80">Create a new announcement and choose how it should be published.</p>

                <div class="mt-6 space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-red-900">Title</label>
                        <input type="text" name="title" value="{{ old('title') }}" required class="mt-2 w-full rounded-3xl border border-red-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-red-600 focus:ring-2 focus:ring-red-100" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-red-900">Body</label>
                        <textarea name="body" rows="6" required class="mt-2 w-full rounded-3xl border border-red-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-red-600 focus:ring-2 focus:ring-red-100">{{ old('body') }}</textarea>
                    </div>
                </div>
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-6">
                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-900">Announcement type</label>
                        <div class="mt-3 grid gap-3">
                            @php
                                $types = ['news' => 'News', 'alert' => 'Alert', 'event' => 'Event', 'notice' => 'Notice'];
                            @endphp
                            @foreach ($types as $key => $label)
                                <label class="flex items-center gap-3 rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 transition hover:border-red-300">
                                    <input type="radio" name="type" value="{{ $key }}" {{ old('type', 'news') === $key ? 'checked' : '' }} class="h-4 w-4 text-red-600 focus:ring-red-500" />
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-900">Urgency</label>
                            <select name="urgency" required class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-red-600 focus:ring-2 focus:ring-red-100">
                                <option value="low" {{ old('urgency') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('urgency') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('urgency') == 'high' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-900">Published date</label>
                            <input type="datetime-local" name="published_at" value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-red-600 focus:ring-2 focus:ring-red-100" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-900">Publication deadline</label>
                            <input type="datetime-local" name="deadline" value="{{ old('deadline') }}" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-red-600 focus:ring-2 focus:ring-red-100" />
                            <p class="mt-2 text-xs text-slate-500">If set, the announcement stays unpublished until the deadline is reached.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <label class="flex items-center gap-3 rounded-3xl border border-slate-200 bg-slate-50 px-4 py-4 text-sm text-slate-900">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }} class="h-4 w-4 text-red-600 focus:ring-red-500" />
                        <span>Publish immediately</span>
                    </label>
                </div>
            </section>
        </div>

        <div class="flex flex-col gap-3 border-t border-red-100 bg-slate-50 p-6 sm:flex-row sm:items-center sm:justify-between">
            <a href="{{ route('announcements.index') }}" class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-white px-5 py-3 text-sm font-medium text-red-700 transition hover:bg-red-50">
                <i class="fa-solid fa-arrow-left"></i> Cancel
            </a>
            <div class="flex flex-wrap gap-3">
                @php
                    $dashboardRoute = auth()->user()->role === 'admin' ? 'admin' : (in_array(auth()->user()->role, ['staff', 'faculty']) ? 'staff' : 'dashboard');
                @endphp
                <a href="{{ route($dashboardRoute) }}" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100">
                    <i class="fa-solid fa-house"></i> Return to Home
                </a>
                <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-red-700 px-6 py-3 text-sm font-semibold text-white transition hover:bg-red-800">
                    <i class="fa-solid fa-save"></i> Create Announcement
                </button>
            </div>
        </div>
    </form>
</main>

</body>
</html>
