<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management — SFAC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500&family=DM+Serif+Display&display=swap" rel="stylesheet">
</head>
<body class="bg-[#F8F7F5] font-sans">

<header class="bg-red-700 text-white shadow-sm border-b border-red-800">
    <div class="max-w-6xl mx-auto px-6 py-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center">
                <i class="fa-solid fa-user-shield text-white"></i>
            </div>
            <div>
                <p class="text-[10px] uppercase tracking-[0.3em] text-white/75">SFAC Admin Dashboard</p>
                <h1 class="text-xl font-semibold">User Management</h1>
            </div>
        </div>
        <a href="{{ route('admin') }}" class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-2 text-xs text-white transition hover:bg-white/20">
            <i class="fa-solid fa-angle-left"></i> Back to Admin
        </a>
    </div>
</header>

<main class="max-w-6xl mx-auto px-6 py-10">

    
    @if (session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden">
        <div class="flex flex-col gap-6 border-b border-slate-200 bg-white px-6 py-5 text-slate-900 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-semibold">Profiles</h2>
                <p class="text-sm text-slate-600">View and manage staff, faculty, and student accounts.</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <span class="inline-flex items-center rounded-full bg-red-50 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-red-700">
                    {{ $users->total() }} profiles
                </span>
                <a href="{{ route('users.create') }}" class="inline-flex items-center justify-center rounded-full bg-red-700 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-white shadow-sm shadow-red-200 transition hover:bg-red-800">
                    <i class="fa-solid fa-plus mr-2"></i> New User
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full border-separate border-spacing-0 text-left text-sm text-slate-700">
                <thead class="bg-slate-900 text-xs uppercase tracking-wide text-slate-100">
                    <tr>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Username</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Phone</th>
                        <th class="px-6 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($users as $user)
                        <tr class="transition hover:bg-slate-50">
                            <td class="px-6 py-4 font-medium text-slate-900">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $user->username }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold tracking-wide {{ $user->role === 'admin' ? 'bg-pink-100 text-pink-800' : ($user->role === 'staff' ? 'bg-blue-100 text-blue-800' : ($user->role === 'faculty' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700')) }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $user->phone ?? '—' }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('users.edit', $user) }}" class="inline-flex items-center gap-2 rounded-full border border-fuchsia-200 bg-fuchsia-50 px-4 py-2 text-xs font-semibold text-fuchsia-700 transition hover:bg-fuchsia-100">
                                    <i class="fa-solid fa-user-pen"></i> Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-500">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 bg-slate-50 border-t border-slate-100">
            {{ $users->links() }}
        </div>
    </div>
</main>

</body>
</html>
