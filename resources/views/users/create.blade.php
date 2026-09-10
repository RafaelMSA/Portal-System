<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User — SFAC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500&family=DM+Serif+Display&display=swap" rel="stylesheet">
</head>
<body class="bg-[#F8F7F5] font-sans text-slate-900">

<header class="bg-red-700 text-white shadow-sm border-b border-red-800">
    <div class="max-w-4xl mx-auto px-6 py-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-[10px] uppercase tracking-[0.3em] text-white/75">SFAC Admin Dashboard</p>
            <h1 class="text-3xl font-semibold">Create New User</h1>
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

    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" class="rounded-[2rem] border border-red-100 bg-white shadow-xl">
        @csrf

        <div class="grid gap-8 p-8 md:grid-cols-[1.1fr_0.9fr]">
            <div class="space-y-8">
                <section class="rounded-3xl border border-red-100 bg-red-50 p-6">
                    <h2 class="text-xl font-semibold text-red-900">Account details</h2>
                    <p class="mt-2 text-sm text-red-700/80">Enter the new user’s information and choose whether to create a staff or student account.</p>

                    <div class="mt-6 grid gap-6">
                        <label class="block text-sm font-medium text-red-900">
                            Full name
                            <input name="name" value="{{ old('name') }}" required class="mt-2 w-full rounded-3xl border border-red-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-red-600 focus:ring-2 focus:ring-red-100" />
                        </label>

                        <label class="block text-sm font-medium text-red-900">
                            Username
                            <input name="username" value="{{ old('username') }}" required class="mt-2 w-full rounded-3xl border border-red-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-red-600 focus:ring-2 focus:ring-red-100" />
                            @error('username')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </label>

                        <label class="block text-sm font-medium text-red-900">
                            Email address
                            <input name="email" type="email" value="{{ old('email') }}" required class="mt-2 w-full rounded-3xl border border-red-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-red-600 focus:ring-2 focus:ring-red-100" />
                        </label>

                        <label class="block text-sm font-medium text-red-900">
                            Phone number
                            <input name="phone" type="tel" value="{{ old('phone') }}" class="mt-2 w-full rounded-3xl border border-red-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-red-600 focus:ring-2 focus:ring-red-100" />
                        </label>
                    </div>
                </section>

                <section class="rounded-3xl border border-slate-200 bg-white p-6">
                    <h2 class="text-xl font-semibold text-slate-900">Password</h2>
                    <p class="mt-2 text-sm text-slate-600">Set a secure password for the account. Passwords must be confirmed.</p>

                    <div class="mt-6 grid gap-6 md:grid-cols-2">
                        <label class="block text-sm font-medium text-slate-900">
                            Password
                            <input name="password" type="password" required class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-red-600 focus:ring-2 focus:ring-red-100" />
                        </label>

                        <label class="block text-sm font-medium text-slate-900">
                            Confirm password
                            <input name="password_confirmation" type="password" required class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-red-600 focus:ring-2 focus:ring-red-100" />
                        </label>
                    </div>
                </section>
            </div>

            <aside class="space-y-8">
                <section class="rounded-3xl border border-red-100 bg-red-50 p-6">
                    <h2 class="text-xl font-semibold text-red-900">Role</h2>
                    <p class="mt-2 text-sm text-red-700/80">Only staff and student accounts may be created here.</p>

                    <div class="mt-6 space-y-4">
                        <label class="flex items-center gap-3 rounded-3xl border border-red-200 bg-white px-4 py-4 shadow-sm transition hover:border-red-400">
                            <input type="radio" name="role" value="staff" {{ old('role') === 'staff' ? 'checked' : '' }} required class="h-4 w-4 text-red-600 focus:ring-red-500" />
                            <span class="text-sm font-medium text-slate-900">Staff</span>
                        </label>

                        <label class="flex items-center gap-3 rounded-3xl border border-red-200 bg-white px-4 py-4 shadow-sm transition hover:border-red-400">
                            <input type="radio" name="role" value="student" {{ old('role') === 'student' ? 'checked' : '' }} required class="h-4 w-4 text-red-600 focus:ring-red-500" />
                            <span class="text-sm font-medium text-slate-900">Student</span>
                        </label>
                    </div>
                </section>

                <section class="rounded-3xl border border-slate-200 bg-white p-6">
                    <h2 class="text-xl font-semibold text-slate-900">Profile photo</h2>
                    <p class="mt-2 text-sm text-slate-600">Optionally upload a profile photo to personalize the account.</p>

                    <label class="mt-6 flex cursor-pointer items-center justify-center rounded-3xl border border-dashed border-red-200 bg-red-50 px-4 py-8 text-center text-sm text-red-700 transition hover:border-red-400 hover:bg-red-100">
                        <input type="file" name="profile_photo" accept="image/*" class="hidden" />
                        <div>
                            <i class="fa-solid fa-upload mb-3 block text-2xl"></i>
                            <p>Upload photo</p>
                            <p class="text-xs text-red-700/70">PNG, JPG, WEBP • max 2MB</p>
                        </div>
                    </label>
                </section>

                <div class="rounded-3xl border border-slate-200 bg-white p-6 text-sm text-slate-700">
                    <p class="font-semibold text-slate-900">Quick notes</p>
                    <ul class="mt-4 space-y-3 list-disc pl-5">
                        <li>Use staff accounts for faculty and employees.</li>
                        <li>Student accounts may access learner-facing pages only.</li>
                        <li>Leave password fields blank only when updating existing users.</li>
                    </ul>
                </div>
            </aside>
        </div>

        <div class="flex flex-col gap-3 border-t border-red-100 bg-slate-50 p-6 sm:flex-row sm:items-center sm:justify-between">
            <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-white px-4 py-3 text-sm font-medium text-red-700 transition hover:bg-red-50">
                <i class="fa-solid fa-arrow-left"></i> Cancel
            </a>
            <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-red-700 px-6 py-3 text-sm font-semibold text-white transition hover:bg-red-800">
                <i class="fa-solid fa-user-plus"></i> Create User
            </button>
        </div>
    </form>
</main>

</body>
</html>
