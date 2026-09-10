<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Achievement — SFAC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500&family=DM+Serif+Display&display=swap" rel="stylesheet">
</head>
<body class="bg-[#F8F7F5] font-sans text-slate-900">

<header class="bg-red-700 text-white shadow-sm border-b border-red-800">
    <div class="max-w-4xl mx-auto px-6 py-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-[10px] uppercase tracking-[0.3em] text-white/75">SFAC Admin Dashboard</p>
            <h1 class="text-3xl font-semibold">Create Achievement</h1>
        </div>
        <a href="{{ route('achievements.index') }}" class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-2 text-sm text-white transition hover:bg-white/25">
            <i class="fa-solid fa-arrow-left"></i> Back to Achievements
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

    <form method="POST" action="{{ route('achievements.store') }}" class="rounded-[2rem] border border-red-100 bg-white shadow-xl">
        @csrf

        <div class="grid gap-8 p-8">
            <section class="rounded-3xl border border-red-100 bg-red-50 p-6">
                <h2 class="text-xl font-semibold text-red-900">Achievement details</h2>
                <p class="mt-2 text-sm text-red-700/80">Create a new achievement and specify the recipient, status, and visibility.</p>

                <div class="mt-6 grid gap-6 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-red-900">Select Student</label>
                        <select name="user_id" required class="mt-2 w-full rounded-3xl border border-red-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-red-600 focus:ring-2 focus:ring-red-100">
                            <option value="">-- Select Student --</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-red-900">Recipient Name</label>
                        <input type="text" name="recipient_name" value="{{ old('recipient_name') }}" placeholder="Awarded to" class="mt-2 w-full rounded-3xl border border-red-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-red-600 focus:ring-2 focus:ring-red-100" />
                    </div>
                </div>

                <div class="mt-6 grid gap-6 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-red-900">Title</label>
                        <input type="text" name="title" value="{{ old('title') }}" required class="mt-2 w-full rounded-3xl border border-red-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-red-600 focus:ring-2 focus:ring-red-100" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-red-900">Category</label>
                        <select name="category" required class="mt-2 w-full rounded-3xl border border-red-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-red-600 focus:ring-2 focus:ring-red-100">
                            <option value="">Select a category</option>
                            <option value="academic" {{ old('category') == 'academic' ? 'selected' : '' }}>Academic Award</option>
                            <option value="board" {{ old('category') == 'board' ? 'selected' : '' }}>Board Passers</option>
                            <option value="milestone" {{ old('category') == 'milestone' ? 'selected' : '' }}>Milestone</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6 grid gap-6 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-red-900">Status</label>
                        <select name="status" required class="mt-2 w-full rounded-3xl border border-red-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-red-600 focus:ring-2 focus:ring-red-100">
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="awarded" {{ old('status', 'awarded') == 'awarded' ? 'selected' : '' }}>Awarded</option>
                            <option value="revoked" {{ old('status') == 'revoked' ? 'selected' : '' }}>Revoked</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-red-900">Achievement Date</label>
                        <input type="date" name="achieved_at" value="{{ old('achieved_at', now()->format('Y-m-d')) }}" class="mt-2 w-full rounded-3xl border border-red-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-red-600 focus:ring-2 focus:ring-red-100" />
                    </div>
                </div>

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <label class="flex items-center gap-3 rounded-3xl border border-slate-200 bg-slate-50 px-4 py-4 text-sm text-slate-900">
                        <input type="hidden" name="is_published" value="0">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }} class="h-4 w-4 text-red-600 focus:ring-red-500" />
                        <span>Publish achievement</span>
                    </label>
                    <label class="flex items-center gap-3 rounded-3xl border border-slate-200 bg-slate-50 px-4 py-4 text-sm text-slate-900">
                        <input type="hidden" name="visible_to_all" value="0">
                        <input type="checkbox" name="visible_to_all" value="1" {{ old('visible_to_all', true) ? 'checked' : '' }} class="h-4 w-4 text-red-600 focus:ring-red-500" id="visibleToAllToggle" />
                        <span>Visible to all students</span>
                    </label>
                </div>

                <div id="visibilitySelector" class="mt-6 space-y-3 {{ old('visible_to_all', true) ? 'hidden' : '' }}">
                    <label class="block text-sm font-medium text-slate-900">Select specific students</label>
                    <div class="grid gap-3 rounded-3xl border border-slate-200 bg-white p-4">
                        @foreach ($users->where('role', 'student') as $student)
                            <label class="flex items-center gap-3 rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900">
                                <input type="checkbox" name="visibility_user_ids[]" value="{{ $student->id }}" {{ in_array($student->id, old('visibility_user_ids', [])) ? 'checked' : '' }} class="h-4 w-4 text-red-600 focus:ring-red-500" />
                                <span>{{ $student->name }} ({{ $student->email }})</span>
                            </label>
                        @endforeach
                    </div>
                    <p class="text-xs text-slate-500">Only selected students will see this achievement when the toggle is off.</p>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-red-900">Description (optional)</label>
                    <textarea name="description" rows="4" class="mt-2 w-full rounded-3xl border border-red-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-red-600 focus:ring-2 focus:ring-red-100">{{ old('description') }}</textarea>
                </div>
            </section>
        </div>

        <div class="flex flex-col gap-3 border-t border-red-100 bg-slate-50 p-6 sm:flex-row sm:items-center sm:justify-between">
            <a href="{{ route('achievements.index') }}" class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-white px-5 py-3 text-sm font-medium text-red-700 transition hover:bg-red-50">
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
                    <i class="fa-solid fa-save"></i> Create Achievement
                </button>
            </div>
        </div>
    </form>
</main>

<script>
    document.getElementById('visibleToAllToggle').addEventListener('change', function () {
        const selector = document.getElementById('visibilitySelector');
        selector.classList.toggle('hidden', this.checked);
    });
</script>

</body>
</html>