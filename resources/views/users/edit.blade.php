<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit User — SFAC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500&family=DM+Serif+Display&display=swap" rel="stylesheet">
</head>
<body class="bg-[#F8F7F5] font-sans text-slate-900">

<header class="bg-red-700 text-white shadow-sm border-b border-red-800">
    <div class="max-w-4xl mx-auto px-6 py-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-[10px] uppercase tracking-[0.3em] text-white/75">SFAC Admin Dashboard</p>
            <h1 class="text-3xl font-semibold">Edit User Profile</h1>
        </div>
        <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-2 text-sm text-white transition hover:bg-white/25">
            <i class="fa-solid fa-arrow-left"></i> Back to User Management
        </a>
    </div>
</header>

<main class="max-w-4xl mx-auto px-6 py-10">
    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-5 text-sm text-red-700">
            <h2 class="font-semibold mb-2">There are problems with your submission</h2>
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data" class="rounded-[2rem] border border-red-100 bg-white shadow-xl">
        @csrf
        @method('PUT')

        <div class="grid gap-8 p-8 md:grid-cols-[1.1fr_0.9fr]">
            <div class="space-y-8">
                <section class="rounded-3xl border border-red-100 bg-red-50 p-6">
                    <h2 class="text-xl font-semibold text-red-900">Account details</h2>
                    <p class="mt-2 text-sm text-red-700/80">Update the user’s information and role settings.</p>

                    <div class="mt-6 grid gap-6">
                        <label class="block text-sm font-medium text-red-900">
                            Full name
                            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required class="mt-2 w-full rounded-3xl border border-red-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-red-600 focus:ring-2 focus:ring-red-100" />
                        </label>

                        <label class="block text-sm font-medium text-red-900">
                            Username
                            <input id="username" name="username" type="text" value="{{ old('username', $user->username) }}" required class="mt-2 w-full rounded-3xl border border-red-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-red-600 focus:ring-2 focus:ring-red-100" />
                            @error('username')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </label>

                        <label class="block text-sm font-medium text-red-900">
                            Email address
                            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required class="mt-2 w-full rounded-3xl border border-red-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-red-600 focus:ring-2 focus:ring-red-100" />
                        </label>

                        <label class="block text-sm font-medium text-red-900">
                            Phone number
                            <input id="phone" name="phone" type="tel" value="{{ old('phone', $user->phone) }}" class="mt-2 w-full rounded-3xl border border-red-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-red-600 focus:ring-2 focus:ring-red-100" />
                        </label>
                    </div>
                </section>

                <section class="rounded-3xl border border-slate-200 bg-white p-6">
                    <h2 class="text-xl font-semibold text-slate-900">Password</h2>
                    <p class="mt-2 text-sm text-slate-600">Set a new password if you want to change it.</p>

                    <div class="mt-6 grid gap-6 md:grid-cols-2">
                        <label class="block text-sm font-medium text-slate-900">
                            New password
                            <input id="password" name="password" type="password" autocomplete="new-password" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-red-600 focus:ring-2 focus:ring-red-100" />
                        </label>

                        <label class="block text-sm font-medium text-slate-900">
                            Confirm password
                            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-red-600 focus:ring-2 focus:ring-red-100" />
                        </label>
                    </div>

                    <p class="mt-3 text-sm text-slate-500">Leave both fields blank to keep the current password.</p>
                </section>
            </div>

            <aside class="space-y-8">
                <section class="rounded-3xl border border-red-100 bg-red-50 p-6">
                    <h2 class="text-xl font-semibold text-red-900">Role</h2>
                    <p class="mt-2 text-sm text-red-700/80">Only admins can assign or change roles for any account.</p>

                    <div class="mt-6 space-y-4">
                        @php
                            $roles = ['admin' => 'Admin', 'staff' => 'Staff', 'faculty' => 'Faculty', 'student' => 'Student'];
                        @endphp
                        @foreach ($roles as $value => $label)
                            <label class="flex items-center gap-3 rounded-3xl border border-red-200 bg-white px-4 py-4 shadow-sm transition hover:border-red-400">
                                <input type="radio" name="role" value="{{ $value }}" {{ old('role', $user->role) === $value ? 'checked' : '' }} required class="h-4 w-4 text-red-600 focus:ring-red-500" />
                                <span class="text-sm font-medium text-slate-900">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </section>

                <section class="rounded-3xl border border-slate-200 bg-white p-6">
                    <h2 class="text-xl font-semibold text-slate-900">Profile photo</h2>
                    <p class="mt-2 text-sm text-slate-600">Upload a new photo or keep the current profile image.</p>

                    <div class="mt-6 flex items-center gap-4">
                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-20 h-20 rounded-full object-cover border border-slate-200" />
                        <label class="flex-1 cursor-pointer rounded-3xl border border-dashed border-red-200 bg-red-50 px-4 py-5 text-center text-sm text-red-700 transition hover:border-red-400 hover:bg-red-100">
                            <input type="file" name="profile_photo" accept="image/*" class="hidden" />
                            <div class="space-y-2">
                                <i class="fa-solid fa-upload block text-2xl"></i>
                                <p>Upload new photo</p>
                                <p class="text-xs text-red-700/70">PNG, JPG, or WEBP — max 2MB</p>
                            </div>
                        </label>
                    </div>
                </section>

                <div class="rounded-3xl border border-slate-200 bg-white p-6 text-sm text-slate-700">
                    <p class="font-semibold text-slate-900">Helpful notes</p>
                    <ul class="mt-4 space-y-3 list-disc pl-5">
                        <li>Use staff for employees and instructors.</li>
                        <li>Use student for learners only.</li>
                        <li>Only admins may change roles.</li>
                    </ul>
                </div>
            </aside>
        </div>

        <div class="flex flex-col gap-3 border-t border-red-100 bg-slate-50 p-6 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-white px-4 py-3 text-sm font-medium text-red-700 transition hover:bg-red-50">
                    <i class="fa-solid fa-arrow-left"></i> Cancel
                </a>

                @if(auth()->user()->role === 'admin')
                    <button type="button" onclick="if(confirm('Warning — Permanently delete this user?\n\nThis action cannot be undone. Continue?')){ const f = document.createElement('form'); f.method = 'POST'; f.action = '{{ route('users.destroy', $user) }}'; const csrf = document.createElement('input'); csrf.type = 'hidden'; csrf.name = '_token'; csrf.value = '{{ csrf_token() }}'; f.appendChild(csrf); const m = document.createElement('input'); m.type = 'hidden'; m.name = '_method'; m.value = 'DELETE'; f.appendChild(m); document.body.appendChild(f); f.submit(); }" class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-white px-4 py-3 text-sm font-medium text-red-700 transition hover:bg-red-50">
                        <i class="fa-solid fa-trash"></i> Delete User
                    </button>
                @endif
            </div>

            <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-red-700 px-6 py-3 text-sm font-semibold text-white transition hover:bg-red-800">
                <i class="fa-solid fa-user-pen"></i> Save Changes
            </button>
        </div>
    </form>
</main>

</body>
</html>
