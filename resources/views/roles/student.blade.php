<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Student Dashboard - SFAC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        crimson: {
                            50: '#fef2f2',
                            100: '#fee2e2',
                            600: '#8B1A1A',
                            700: '#7A1515',
                            800: '#6B1010',
                        },
                        sfacgold: {
                            50: '#FEF9E7',
                            400: '#D4A017',
                        },
                        pagebg: '#F8F7F5',
                    },
                    fontFamily: {
                        sans: ['"DM Sans"', 'sans-serif'],
                        display: ['"DM Serif Display"', 'serif'],
                    },
                },
            },
        }
    </script>
</head>
<body class="font-sans text-gray-800 antialiased min-h-screen bg-pagebg">

<header class="bg-crimson-600 text-white">
    <div class="max-w-screen-xl mx-auto px-6 py-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center">
                <i class="fa-solid fa-user-graduate text-white text-sm"></i>
            </div>
            <div>
                <h1 class="font-display text-base font-normal leading-tight tracking-wide">SFAC Dashboard</h1>
                <p class="text-xs text-white/60 leading-none mt-0.5">Student Portal</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <span class="text-sm text-white/80"><i class="fa-solid fa-user mr-1"></i> Student</span>
            <div class="relative">
                <button id="profileBtn" onclick="toggleProfileDropdown()" class="w-8 h-8 rounded-full bg-white/15 hover:bg-white/25 flex items-center justify-center transition-colors duration-200 focus:outline-none">
                    <i class="fa-solid fa-user text-white text-sm"></i>
                </button>
                <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-xl shadow-lg z-50 py-2">
                    <div class="px-4 py-3 border-b border-gray-100">
                        <p class="text-sm font-medium text-gray-800">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                    </div>
                    <div class="border-t border-gray-100 px-2 py-2">
                        <form id="logoutForm" method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="w-full text-left text-sm text-crimson-600 hover:bg-crimson-50 px-3 py-2 rounded-md flex items-center gap-2">
                                <i class="fa-solid fa-sign-out-alt text-xs"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<main class="max-w-screen-xl mx-auto px-6 py-8 space-y-8">
    <section>
        <h2 class="text-4xl font-bold text-gray-800 mb-4">Student Dashboard</h2>
        <p class="text-sm text-gray-600 mb-6">Hi, {{ auth()->user()->name }} — you're logged in as a <strong class="text-crimson-600">Student</strong>.</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('announcements.index') }}" class="group bg-white rounded-xl border border-gray-200 p-6 hover:border-crimson-600 hover:shadow-md transition-all duration-200">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-12 h-12 rounded-lg bg-crimson-50 flex items-center justify-center">
                        <i class="fa-solid fa-bell text-crimson-600 text-lg"></i>
                    </div>
                    <i class="fa-solid fa-arrow-right text-crimson-600 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                </div>
                <h3 class="text-sm font-semibold text-gray-800 mb-1">Announcements</h3>
                <p class="text-xs text-gray-500">View important campus announcements and updates.</p>
                <div class="text-xs text-crimson-600 font-medium mt-3">
                    <i class="fa-solid fa-square-plus mr-1"></i> View
                </div>
            </a>

            <a href="{{ route('achievements.index') }}" class="group bg-white rounded-xl border border-gray-200 p-6 hover:border-crimson-600 hover:shadow-md transition-all duration-200">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-12 h-12 rounded-lg bg-sfacgold-50 flex items-center justify-center">
                        <i class="fa-solid fa-trophy text-amber-600 text-lg"></i>
                    </div>
                    <i class="fa-solid fa-arrow-right text-crimson-600 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                </div>
                <h3 class="text-sm font-semibold text-gray-800 mb-1">Achievements</h3>
                <p class="text-xs text-gray-500">View your achievements and awards.</p>
                <div class="text-xs text-crimson-600 font-medium mt-3">
                    <i class="fa-solid fa-square-plus mr-1"></i> View
                </div>
            </a>

            <a href="{{ route('messages.create') }}" class="group bg-white rounded-xl border border-gray-200 p-6 hover:border-crimson-600 hover:shadow-md transition-all duration-200">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-12 h-12 rounded-lg bg-crimson-50 flex items-center justify-center">
                        <i class="fa-solid fa-paper-plane text-crimson-600 text-lg"></i>
                    </div>
                    <i class="fa-solid fa-arrow-right text-crimson-600 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                </div>
                <h3 class="text-sm font-semibold text-gray-800 mb-1">Send Concern</h3>
                <p class="text-xs text-gray-500">Send a concern or question to the admin or staff team.</p>
                <div class="text-xs text-crimson-600 font-medium mt-3">
                    <i class="fa-solid fa-envelope mr-1"></i> Contact
                </div>
            </a>
        </div>
    </section>
</main>

<footer class="mt-12 border-t border-gray-200 py-5">
    <div class="max-w-screen-xl mx-auto px-6 flex items-center justify-between text-xs text-gray-400">
        <span>© {{ date("Y") }} Saint Francis of Assisi College. All rights reserved.</span>
        <span>Student Portal v1.0</span>
    </div>
</footer>

<script>
function toggleProfileDropdown() {
    const dropdown = document.getElementById("profileDropdown");
    dropdown.classList.toggle("hidden");
}

document.addEventListener("click", function(e) {
    const btn = document.getElementById("profileBtn");
    const dropdown = document.getElementById("profileDropdown");
    if (!dropdown.contains(e.target) && e.target !== btn) {
        dropdown.classList.add("hidden");
    }
});
</script>

</body>
</html>
