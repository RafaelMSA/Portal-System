<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Profile — SFAC Central Dashboard</title>
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
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 hover:opacity-90">
                <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center">
                    <i class="fa-solid fa-dove text-white text-sm"></i>
                </div>
                <div>
                    <h1 class="font-display text-base font-normal leading-tight tracking-wide">Saint Francis of Assisi College</h1>
                    <p class="text-xs text-white/60 leading-none mt-0.5">Central Dashboard Portal</p>
                </div>
            </a>
        </div>

        <div class="flex items-center gap-3">
            <span class="bg-sfacgold-400 text-yellow-900 text-xs font-medium px-3 py-1 rounded-full">AY 2025–2026</span>
            <div class="relative">
                <button id="profileBtn" onclick="toggleProfileDropdown()" class="w-8 h-8 rounded-full bg-white/15 hover:bg-white/25 flex items-center justify-center transition-colors duration-200 focus:outline-none" aria-label="User account">
                    <i class="fa-solid fa-user text-white text-sm"></i>
                </button>
                <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-xl shadow-lg z-50 py-2">
                    @auth
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-medium text-gray-800">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                            <span class="inline-block text-[10px] font-medium px-2 py-1 mt-2 rounded-full bg-sfacgold-50 text-yellow-900">{{ ucfirst(auth()->user()->role) }}</span>
                        </div>
                        <div class="px-2 py-2">
                            <a href="{{ route('profile.edit', auth()->user()) }}" class="w-full text-left text-sm text-gray-700 hover:bg-gray-50 px-3 py-2 rounded-md flex items-center gap-2 block">
                                <i class="fa-solid fa-user-pen text-xs"></i> Edit Personal Info
                            </a>
                        </div>
                        <div class="border-t border-gray-100 px-2 py-2">
                            <form id="logoutForm" method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="w-full text-left text-sm text-crimson-600 hover:bg-crimson-50 px-3 py-2 rounded-md flex items-center gap-2">
                                    <i class="fa-solid fa-sign-out-alt text-xs"></i> Logout
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="px-4 py-3 text-sm text-gray-600">Not signed in</div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</header>

<main class="max-w-4xl mx-auto px-6 py-8">

    {{-- Success Message --}}
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm">
            <i class="fa-solid fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    {{-- Page Title --}}
    <div class="mb-8">
            <h1 class="text-4xl font-display font-bold text-gray-900 mb-2">{{ auth()->id() === $user->id ? 'Edit Your Profile' : 'Edit Profile for ' . $user->name }}</h1>
    </div>

    {{-- Profile Edit Form --}}
    <form action="{{ route('profile.update', $user) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        @csrf
        @method('PUT')

        <div class="p-8 space-y-8">

            {{-- Profile Photo Section --}}
            <div class="flex items-start gap-8">
                <div class="flex-shrink-0">
                    <img id="photoPreview" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-full object-cover border-2 border-gray-200">
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Profile Photo</h3>
                    <p class="text-sm text-gray-600 mb-4">Upload a new profile photo (JPG, PNG, or WebP - Max 2MB)</p>
                    <div class="flex items-center gap-4">
                        @if(auth()->user()->role === 'admin' || (auth()->id() === $user->id && auth()->user()->role === 'student'))
                            <input type="file" id="profile_photo" name="profile_photo" accept="image/*" class="hidden" onchange="previewPhoto(event)">
                            <button type="button" onclick="document.getElementById('profile_photo').click()" class="bg-crimson-600 hover:bg-crimson-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                                <i class="fa-solid fa-upload mr-2"></i>Change Photo
                            </button>
                            @error('profile_photo')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        @else
                            <button type="button" disabled class="bg-gray-100 text-gray-500 text-sm font-semibold px-4 py-2 rounded-lg border border-gray-200" title="Contact an admin to change your photo">
                                <i class="fa-solid fa-upload mr-2"></i>Change Photo
                            </button>
                            <p class="text-xs text-gray-500">Only admins or students themselves can change profile photos. Contact an administrator to update this photo.</p>
                        @endif
                    </div>
                </div>
            </div>

            <hr class="border-gray-200">

            {{-- Name Field --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-crimson-600 focus:border-transparent @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            @if (auth()->user()->role === 'admin')
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-crimson-600 focus:border-transparent @error('username') border-red-500 @enderror">
                    @error('username')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-crimson-600 focus:border-transparent @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Phone Field --}}
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+63 9XX XXX XXXX" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-crimson-600 focus:border-transparent @error('phone') border-red-500 @enderror">
                @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <hr class="border-gray-200">

            {{-- Password Section --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Change Password (Optional)</h3>
                <div class="space-y-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                        <input type="password" id="password" name="password" placeholder="Leave blank to keep current password" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-crimson-600 focus:border-transparent @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Minimum 8 characters</p>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-crimson-600 focus:border-transparent @error('password_confirmation') border-red-500 @enderror">
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

        </div>

        {{-- Form Actions --}}
        <div class="bg-gray-50 px-8 py-4 border-t border-gray-200 flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900 font-medium text-sm">
                <i class="fa-solid fa-arrow-left mr-2"></i>Back to Dashboard
            </a>
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="px-6 py-2 text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50 font-medium text-sm transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-crimson-600 hover:bg-crimson-700 text-white rounded-lg font-medium text-sm transition-colors">
                    <i class="fa-solid fa-save mr-2"></i>Save Changes
                </button>
            </div>
        </div>

    </form>

</main>

<script>
    function toggleProfileDropdown() {
        const dropdown = document.getElementById('profileDropdown');
        dropdown.classList.toggle('hidden');
    }

    function previewPhoto(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('photoPreview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const profileBtn = document.getElementById('profileBtn');
        const profileDropdown = document.getElementById('profileDropdown');
        if (!profileBtn.contains(event.target) && !profileDropdown.contains(event.target)) {
            profileDropdown.classList.add('hidden');
        }
    });
</script>

</body>
</html>
