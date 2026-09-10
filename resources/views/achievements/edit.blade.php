<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Achievement - SFAC Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500&family=DM+Serif+Display&display=swap" rel="stylesheet">
</head>
<body class="bg-[#F8F7F5] font-sans text-slate-900">

<header class="bg-red-700 text-white shadow-sm border-b border-red-800">
    <div class="max-w-4xl mx-auto px-6 py-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-[10px] uppercase tracking-[0.3em] text-white/75">SFAC Admin Dashboard</p>
            <h1 class="text-3xl font-semibold">Edit Achievement</h1>
        </div>
        <a href="{{ route('achievements.index') }}" class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-2 text-sm text-white transition hover:bg-white/25">
            <i class="fa-solid fa-arrow-left"></i> Back to Achievements
        </a>
    </div>
</header>

<main class="max-w-4xl mx-auto px-6 py-10">
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <h3 class="text-red-800 font-semibold mb-2">Errors:</h3>
            <ul class="text-red-700 text-sm list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('achievements.update', $achievement) }}" class="rounded-[2rem] border border-red-100 bg-white shadow-xl">
        @csrf
        @method('PUT')

        <div class="grid gap-8 p-8">
            <div class="space-y-8">
                <section class="rounded-3xl border border-red-100 bg-red-50 p-6">
                    <h2 class="text-xl font-semibold text-red-900">Achievement details</h2>
                    <p class="mt-2 text-sm text-red-700/80">Update the achievement record and visibility.</p>

                    <div class="mt-6 grid gap-6">
                        <label class="block text-sm font-medium text-red-900">
                            Select Student
                            <select name="user_id" required class="mt-2 w-full rounded-3xl border border-red-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-red-600 focus:ring-2 focus:ring-red-100">
                                <option value="">-- Select Student --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id', $achievement->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </label>

                        <label class="block text-sm font-medium text-red-900">
                            Title
                            <input type="text" name="title" value="{{ old('title', $achievement->title) }}" required class="mt-2 w-full rounded-3xl border border-red-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-red-600 focus:ring-2 focus:ring-red-100" />
                        </label>

                        <label class="block text-sm font-medium text-red-900">
                            Recipient Name
                            <input type="text" name="recipient_name" value="{{ old('recipient_name', $achievement->recipient_name) }}" class="mt-2 w-full rounded-3xl border border-red-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-red-600 focus:ring-2 focus:ring-red-100" placeholder="Awarded to" />
                        </label>
                    </div>
                </section>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-900">Category</label>
                <select name="category" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-red-600 focus:ring-2 focus:ring-red-100" required>
                    <option value="">Select a category</option>
                    <option value="academic" {{ old('category', $achievement->category) === 'academic' ? 'selected' : '' }}>Academic Award</option>
                    <option value="board" {{ old('category', $achievement->category) === 'board' ? 'selected' : '' }}>Board Passers</option>
                    <option value="milestone" {{ old('category', $achievement->category) === 'milestone' ? 'selected' : '' }}>Milestone</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-900">Status</label>
                <select name="status" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-red-600 focus:ring-2 focus:ring-red-100" required>
                    <option value="pending" {{ old('status', $achievement->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="awarded" {{ old('status', $achievement->status) == 'awarded' ? 'selected' : '' }}>Awarded</option>
                    <option value="revoked" {{ old('status', $achievement->status) == 'revoked' ? 'selected' : '' }}>Revoked</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-900">Achievement Date</label>
            <input type="date" name="achieved_at" value="{{ old('achieved_at', $achievement->achieved_at->format('Y-m-d')) }}" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-red-600 focus:ring-2 focus:ring-red-100">
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="flex items-center gap-2">
                    <input type="hidden" name="is_published" value="0">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', $achievement->is_published) ? 'checked' : '' }} class="h-4 w-4 text-red-600 focus:ring-red-500">
                    <span class="text-sm font-medium text-slate-900">Publish achievement</span>
                </label>
            </div>
            <div>
                <label class="flex items-center gap-2">
                    <input type="hidden" name="visible_to_all" value="0">
                    <input type="checkbox" name="visible_to_all" value="1" {{ old('visible_to_all', $achievement->visible_to_all) ? 'checked' : '' }} class="h-4 w-4 text-red-600 focus:ring-red-500" id="visibleToAllToggle">
                    <span class="text-sm font-medium text-slate-900">Visible to all students</span>
                </label>
            </div>
        </div>

        <div id="visibilitySelector" class="space-y-3 {{ old('visible_to_all', $achievement->visible_to_all) ? 'hidden' : '' }}">
            <label class="block text-sm font-medium text-slate-900">Select specific students</label>
            <div class="grid gap-3 rounded-3xl border border-slate-200 bg-white p-4">
                @foreach ($users->where('role', 'student') as $student)
                    <label class="flex items-center gap-3 rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900">
                        <input type="checkbox" name="visibility_user_ids[]" value="{{ $student->id }}" {{ in_array($student->id, old('visibility_user_ids', $achievement->visibleToStudents->pluck('id')->toArray())) ? 'checked' : '' }} class="h-4 w-4 text-red-600 focus:ring-red-500" />
                        <span>{{ $student->name }} ({{ $student->email }})</span>
                    </label>
                @endforeach
            </div>
            <p class="text-xs text-slate-500">Only selected students will see this achievement when the toggle is off.</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-900 mb-2">Description (optional)</label>
            <textarea name="description" rows="4" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-red-600 focus:ring-2 focus:ring-red-100">{{ old('description', $achievement->description) }}</textarea>
        </div>

        <script>
            document.getElementById('visibleToAllToggle').addEventListener('change', function () {
                document.getElementById('visibilitySelector').classList.toggle('hidden', this.checked);
            });
        </script>

        <div class="flex flex-col gap-3 border-t border-red-100 bg-slate-50 p-6 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <a href="{{ route('achievements.index') }}" class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-white px-4 py-3 text-sm font-medium text-red-700 transition hover:bg-red-50">
                    <i class="fa-solid fa-arrow-left"></i> Cancel
                </a>
            </div>

            <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-red-700 px-6 py-3 text-sm font-semibold text-white transition hover:bg-red-800">
                <i class="fa-solid fa-save"></i> Update Achievement
            </button>
        </div>
    </form>
</main>

</body>
</html>
