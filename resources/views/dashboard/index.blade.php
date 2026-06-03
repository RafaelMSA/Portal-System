<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ?? 'SFAC Central Dashboard' }}</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500&family=DM+Serif+Display&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        crimson: {
                            50:  '#fef2f2',
                            100: '#fee2e2',
                            600: '#8B1A1A',
                            700: '#7A1515',
                            800: '#6B1010',
                        },
                        sfacgold: {
                            400: '#D4A017',
                            500: '#C49010',
                            50:  '#FEF9E7',
                        },
                    },
                    fontFamily: {
                        sans:    ['"DM Sans"', 'sans-serif'],
                        display: ['"DM Serif Display"', 'serif'],
                    },
                },
            },
        }
    </script>

    <style>
        body { background-color: #F8F7F5; }
    </style>
</head>
<body class="font-sans text-gray-800 antialiased min-h-screen">

{{-- ─── TOP NAVIGATION BAR ─────────────────────────────────────────────────── --}}
<header class="bg-crimson-600 text-white shadow-sm">
    <div class="max-w-screen-xl mx-auto px-6 py-3 flex items-center justify-between">

        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center">
                <i class="fa-solid fa-dove text-white text-sm"></i>
            </div>
            <div>
                <h1 class="font-display text-base font-normal leading-tight tracking-wide">
                    Saint Francis of Assisi College
                </h1>
                <p class="text-xs text-white/60 leading-none mt-0.5">Central Dashboard Portal</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <span class="bg-sfacgold-400 text-yellow-900 text-xs font-medium px-3 py-1 rounded-full">
                AY {{ $academicYear ?? '2026–2027' }}
            </span>

            <div class="relative group">
                <button class="w-8 h-8 rounded-full bg-white/15 hover:bg-white/25 flex items-center justify-center transition-colors duration-150">
                    <i class="fa-solid fa-user text-white text-sm"></i>
                </button>
            </div>
        </div>
    </div>
</header>

{{-- ─── MAIN CONTENT ────────────────────────────────────────────────────────── --}}
<main class="max-w-screen-xl mx-auto px-6 py-8 space-y-8">

    {{-- ─── SECTION 1: SITE ROUTER / APPLICATION GRID ──────────────────────── --}}
    <section>
        <p class="text-xs font-medium text-crimson-600 uppercase tracking-widest mb-3">
            Quick Access
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            @foreach ($schools as $school)
                <a
                    href="{{ $school->url }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="group bg-white rounded-xl border border-gray-200 p-5 flex flex-col gap-3
                           hover:border-crimson-600 transition-colors duration-200 hover:shadow-sm"
                >
                    <div class="w-10 h-10 rounded-lg bg-crimson-50 flex items-center justify-center">
                        <i class="{{ $school->icon ?? 'fa-solid fa-link' }} text-crimson-600 text-base"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800 leading-snug mb-1">
                            {{ $school->name }}
                        </p>
                        <p class="text-xs text-gray-500 leading-relaxed">
                            {{ $school->description }}
                        </p>
                    </div>
                    <div class="mt-auto flex items-center gap-1 text-xs text-crimson-600 opacity-0 group-hover:opacity-100 transition-opacity duration-150">
                        <span>Open</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    {{-- ─── COLUMNS FOR ANNOUNCEMENTS, ACHIEVEMENTS, CHATBOT ───────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- LEFT COLUMN: ANNOUNCEMENTS (lg:col-span-7) --}}
        <div class="lg:col-span-7 space-y-6">
            <section>
                <p class="text-xs font-medium text-crimson-600 uppercase tracking-widest mb-3">
                    Campus Announcements
                </p>

                <div class="bg-white rounded-xl border border-gray-200 divide-y divide-gray-100 shadow-sm">
                    @forelse ($announcements as $announcement)
                        <div class="p-4 flex gap-3">
                            <div class="flex-shrink-0 w-1 self-stretch rounded-full bg-sfacgold-400"></div>

                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-400 mb-0.5">
                                    {{ isset($announcement->published_at) ? \Carbon\Carbon::parse($announcement->published_at)->format('M d, Y') : date('M d, Y') }}
                                </p>
                                <p class="text-sm font-medium text-gray-800 mb-1 leading-snug">
                                    {{ $announcement->title }}
                                </p>
                                <p class="text-xs text-gray-500 leading-relaxed">
                                    {{ $announcement->body }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-xs text-gray-400">
                            No announcements at this time.
                        </div>
                    @endforelse
                </div>
            </section>
        </div>

        {{-- RIGHT COLUMN: ACHIEVEMENTS & EMBEDDED CHATBOT (lg:col-span-5) --}}
        <div class="lg:col-span-5 space-y-6">
            
            {{-- SECTION 3: REAL-TIME ACCOMPLISHMENT FEED --}}
            <section>
                <p class="text-xs font-medium text-crimson-600 uppercase tracking-widest mb-3">
                    Achievements &amp; Milestones
                </p>

                <div class="bg-white rounded-xl border border-gray-200 divide-y divide-gray-100 shadow-sm">
                    @forelse ($achievements as $achievement)
                        <div class="p-4 flex gap-3 items-start">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                                    {{-- Safe Fallback check to prevent Undefined Property exceptions --}}
                                    @php
                                        $category = $achievement->category ?? 'default';
                                        $badgeClasses = match($category) {
                                            'board'     => 'bg-sfacgold-50 text-yellow-800',
                                            'academic'  => 'bg-crimson-50 text-crimson-600',
                                            'milestone' => 'bg-gray-100 text-gray-600',
                                            default     => 'bg-gray-100 text-gray-600',
                                        };
                                    @endphp
                                    <span class="text-[10px] font-medium px-2.5 py-0.5 rounded-full {{ $badgeClasses }}">
                                        {{ $achievement->category_label ?? 'Notification' }}
                                    </span>
                                    <span class="text-[10px] text-gray-400">
                                        {{ isset($achievement->achieved_at) ? \Carbon\Carbon::parse($achievement->achieved_at)->diffForHumans() : '' }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-800 leading-relaxed">
                                    {{ $achievement->title }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-xs text-gray-400">
                            No achievements posted yet.
                        </div>
                    @endforelse
                </div>
            </section>

            {{-- Chatbot removed — inline assistant UI and popup were removed. --}}
        </div>
    </div>
</main>

{{-- ─── FOOTER ─────────────────────────────────────────────────────────────── --}}
<footer class="mt-12 border-t border-gray-200 py-5">
    <div class="max-w-screen-xl mx-auto px-6 flex items-center justify-between text-xs text-gray-400">
        <span>© {{ date('Y') }} Saint Francis of Assisi College. All rights reserved.</span>
        <span>Central Dashboard v1.0</span>
    </div>
</footer>

{{-- Chatbot scripts and popup removed. --}}

</body>
</html>