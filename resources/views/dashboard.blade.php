<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ?? "SFAC Central Dashboard" }}</title>

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
                <i class="fa-solid fa-dove text-white text-sm"></i>
            </div>
            <div>
                <h1 class="font-display text-base font-normal leading-tight tracking-wide">SFAC Dashboard</h1>
                <p class="text-xs text-white/60 leading-none mt-0.5">Student Portal</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <span class="bg-sfacgold-400 text-yellow-900 text-xs font-medium px-3 py-1 rounded-full">AY {{ $academicYear }}</span>
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
                            <form id="logoutForm" method="POST" action="{{ route("logout") }}" class="m-0">
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

<main class="max-w-screen-xl mx-auto px-6 py-8 space-y-8">

    <section>
        <p class="text-xs font-medium text-crimson-600 uppercase tracking-widest mb-3">Quick Access</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4">
            @foreach ($schools as $school)
                <a
                    href="{{ $school->url }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="group bg-white rounded-xl border border-gray-200 p-5 flex flex-col gap-3 hover:border-crimson-600 transition-colors duration-200"
                >
                    <div class="w-10 h-10 rounded-lg bg-crimson-50 flex items-center justify-center">
                        <i class="{{ $school->icon }} text-crimson-600 text-base"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800 leading-snug mb-1">{{ $school->name }}</p>
                        <p class="text-xs text-gray-500 leading-relaxed">{{ $school->description }}</p>
                    </div>
                    <div class="mt-auto flex items-center gap-1 text-xs text-crimson-600 opacity-0 group-hover:opacity-100 transition-opacity duration-150">
                        <span>Open</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                    </div>
                </a>
            @endforeach

            <a href="{{ route('messages.create') }}" class="group bg-white rounded-xl border border-gray-200 p-5 flex flex-col gap-3 hover:border-crimson-600 transition-colors duration-200">
                <div class="w-10 h-10 rounded-lg bg-crimson-50 flex items-center justify-center">
                    <i class="fa-solid fa-paper-plane text-crimson-600 text-base"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-800 leading-snug mb-1">Send Concern</p>
                    <p class="text-xs text-gray-500 leading-relaxed">Message the admin or staff team directly.</p>
                </div>
                <div class="mt-auto flex items-center gap-1 text-xs text-crimson-600">
                    <span>Open</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </div>
            </a>
        </div>
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <section>
            <p class="text-xs font-medium text-crimson-600 uppercase tracking-widest mb-3">Campus Announcements</p>
            <div class="bg-white rounded-xl border border-gray-200 divide-y divide-gray-100">
                @forelse ($announcements as $announcement)
                    @php
                        $typeClasses = [
                            'news' => 'bg-blue-50 text-blue-700',
                            'alert' => 'bg-red-50 text-red-700',
                            'event' => 'bg-amber-50 text-amber-700',
                            'notice' => 'bg-slate-100 text-slate-700',
                        ];
                        $urgencyClasses = $announcement->urgency === 'high'
                            ? 'bg-red-50 text-red-700'
                            : ($announcement->urgency === 'medium'
                                ? 'bg-amber-50 text-amber-700'
                                : 'bg-slate-100 text-slate-700');
                    @endphp
                    <div class="p-4 flex gap-3">
                        <div class="flex-shrink-0 w-1 self-stretch rounded-full bg-sfacgold-400"></div>
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($announcement->published_at)->format("M d, Y") }}</span>
                                <span class="rounded-full px-2 py-1 text-[10px] font-semibold {{ $typeClasses[$announcement->type] ?? 'bg-blue-50 text-blue-700' }}">{{ ucfirst($announcement->type) }}</span>
                                <span class="rounded-full px-2 py-1 text-[10px] font-semibold {{ $urgencyClasses }}">{{ ucfirst($announcement->urgency) }}</span>
                                <span class="rounded-full {{ $announcement->is_published ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-700' }} px-2 py-1 text-[10px] font-semibold">{{ $announcement->is_published ? 'Published' : 'Pending' }}</span>
                            </div>
                            <p class="text-sm font-medium text-gray-800 mb-1 leading-snug">{{ $announcement->title }}</p>
                            <p class="text-xs text-gray-500 leading-relaxed">{{ Str::limit($announcement->body, 180) }}</p>
                            <div class="mt-3">
                                <a href="{{ route('announcements.show', $announcement) }}" class="inline-flex items-center gap-2 text-xs font-semibold text-crimson-600 hover:underline">
                                    View details
                                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-xs text-gray-400">No announcements at this time.</div>
                @endforelse
            </div>
        </section>

        <section>
            <p class="text-xs font-medium text-crimson-600 uppercase tracking-widest mb-3">Achievements</p>
            <div class="bg-white rounded-xl border border-gray-200 divide-y divide-gray-100">
                @forelse ($achievements as $achievement)
                    @php
                        $badgeClasses = match($achievement->category) {
                            "academic" => "bg-crimson-50 text-crimson-600",
                            "board" => "bg-sfacgold-50 text-yellow-800",
                            default => "bg-gray-100 text-gray-600",
                        };
                    @endphp
                    <div class="p-4 flex gap-3 items-start">
                        <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                                    <span class="text-[10px] font-medium px-2.5 py-0.5 rounded-full {{ $badgeClasses }}">{{ $achievement->category_label }}</span>
                                    <span class="text-[10px] text-gray-400">{{ \Carbon\Carbon::parse($achievement->achieved_at)->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-gray-800 leading-relaxed">{{ $achievement->title }}</p>
                                <p class="text-xs text-gray-500 mt-1">Awarded to: {{ $achievement->recipient_name ?? $achievement->user?->name ?? 'Unknown' }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('achievements.show', $achievement) }}" class="text-sm text-crimson-600 hover:underline flex items-center gap-2">
                                    <i class="fa-solid fa-eye"></i> View
                                </a>
                            </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-xs text-gray-400">No achievements posted yet.</div>
                @endforelse
            </div>
        </section>
    </div>

</main>

<footer class="mt-12 border-t border-gray-200 py-5">
    <div class="max-w-screen-xl mx-auto px-6 flex items-center justify-between text-xs text-gray-400">
        <span>© {{ date("Y") }} Saint Francis of Assisi College. All rights reserved.</span>
        <span>Central Dashboard v1.0</span>
    </div>
</footer>

{{-- Chatbot popup and minimal JS --}}
<div id="chatbot-popup" class="hidden fixed right-5 bottom-20 z-50 w-72 max-w-full rounded-3xl border border-red-100 bg-white shadow-xl">
    <div class="flex items-center justify-between rounded-t-3xl border-b border-red-100 bg-red-700 px-3 py-2 text-white">
        <span class="text-sm font-semibold">STACY</span>
        <button onclick="closeChatbot()" class="text-white/80 transition hover:text-white">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div id="chatbot-messages" class="h-64 overflow-y-auto px-3 py-3 flex flex-col gap-3 text-sm" aria-live="polite"></div>
    <div class="border-t border-red-100 px-3 py-3 flex gap-2">
        <input id="chatbot-input" type="text" placeholder="Ask STACY..." class="flex-1 rounded-2xl border border-red-100 px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-red-100" />
        <button id="chatbot-send" class="rounded-2xl bg-red-700 px-3 text-xs font-semibold text-white transition hover:bg-red-800">Send</button>
    </div>
</div>

<button onclick="openChatbot()" class="fixed right-5 bottom-5 z-40 inline-flex h-11 w-11 items-center justify-center rounded-full border border-red-100 bg-white text-red-700 shadow-sm transition hover:bg-red-50" aria-label="Open chat">
    <i class="fa-solid fa-comments"></i>
</button>

<script>
// Profile dropdown - define outside DOMContentLoaded so onclick works immediately
function toggleProfileDropdown() {
    const dropdown = document.getElementById("profileDropdown");
    if (dropdown) {
        dropdown.classList.toggle("hidden");
    }
}

document.addEventListener("click", function(e) {
    const btn = document.getElementById("profileBtn");
    const dropdown = document.getElementById("profileDropdown");
    if (dropdown && btn && !dropdown.contains(e.target) && e.target !== btn) {
        dropdown.classList.add("hidden");
    }
});

// Rule-based chatbot script with STACY rules
document.addEventListener('DOMContentLoaded', function () {
    const CHAT_HISTORY_KEY = 'stacyChatHistory';
    const messagesDiv = document.getElementById('chatbot-messages');
    const input = document.getElementById('chatbot-input');
    const sendBtn = document.getElementById('chatbot-send');
    let chatHistory = [];

    function escapeHtml(str) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(str));
        return d.innerHTML;
    }

    function linkify(text) {
        return text.replace(/(https?:\/\/[^\s\)]+)/g, function(url) {
            const safe = escapeHtml(url);
            return '<a href="' + safe + '" target="_blank" rel="noopener noreferrer">' + safe + '</a>';
        });
    }

    function loadChatHistory() {
        try {
            const raw = localStorage.getItem(CHAT_HISTORY_KEY);
            const parsed = raw ? JSON.parse(raw) : [];
            return Array.isArray(parsed) ? parsed : [];
        } catch (error) {
            return [];
        }
    }

    function saveChatHistory() {
        localStorage.setItem(CHAT_HISTORY_KEY, JSON.stringify(chatHistory));
    }

    function appendMessage(sender, text, save = true) {
        if (!messagesDiv) return;
        const row = document.createElement('div');
        row.className = sender === 'You' ? 'flex justify-end' : 'flex justify-start';

        const bubble = document.createElement('div');
        bubble.className = sender === 'You'
            ? 'max-w-[80%] bg-crimson-600 text-white rounded-xl px-3 py-2 text-sm'
            : 'max-w-[80%] bg-gray-100 text-gray-800 rounded-xl px-3 py-2 text-sm';

        if (sender === 'Bot') {
            bubble.innerHTML = '<strong class="sr-only">STACY:</strong> ' + linkify(escapeHtml(text));
        } else {
            bubble.textContent = text;
        }

        row.appendChild(bubble);
        messagesDiv.appendChild(row);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;

        if (save) {
            chatHistory.push({ sender, text });
            saveChatHistory();
        }
    }

    function renderChatHistory() {
        if (!messagesDiv) return;
        messagesDiv.innerHTML = '';
        chatHistory.forEach(entry => {
            appendMessage(entry.sender, entry.text, false);
        });
    }

    function normalizeText(value) {
        return (value || '')
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-z0-9\s]/g, ' ')
            .replace(/\s+/g, ' ')
            .trim();
    }

    function containsAny(value, keywords) {
        const normalizedValue = normalizeText(value);
        return keywords.some(keyword => normalizedValue.includes(normalizeText(keyword)));
    }

    function chatbotReply(message) {
        if (!message) return "Hello! How can I help you?";
        const msg = normalizeText(message);

        if (containsAny(msg, ['hello', 'hi', 'hey', 'good morning', 'good afternoon', 'good evening', 'greetings'])) {
            return "Hello! Welcome to Saint Francis Of Assisi College Bacoor Campus. I am STACY, your virtual assistant. How can I help you?";
        } else if (containsAny(msg, ['admission', 'admissions', 'enroll', 'enrollment', 'enrolment', 'register', 'registration', 'apply', 'apply now', 'application'])) {
            return "Enrollment for SY 2024-2025 is now open! You may visit our campus or apply online via https://sfac.edu.ph/. For inquiries, contact our admissions office.";
        } else if (containsAny(msg, ['program', 'programs', 'course', 'courses', 'strand', 'strands', 'major', 'majors', 'degree', 'curriculum', 'subjects', 'subject'])) {
            return "We offer Pre-School (Nursery, Kinder, Prep), Elementary (Grades 1-6), Junior High School (Grades 7-10), Senior High School (Grades 11-12, Academic & Technical-Vocational Tracks), and College programs (Business Administration, IT, Education, Hospitality Management, and more).";
        } else if (containsAny(msg, ['tuition', 'fees', 'fee', 'payment', 'cost', 'school fee', 'semester fee', 'tuition fee', 'miscellaneous'])) {
            return "Tuition fees depend on the program and grade level. For details, please contact our admissions office at (046) 476-6217 or (02) 8521-0835.";
        } else if (containsAny(msg, ['location', 'where', 'address', 'campus', 'school location', 'site', 'building', 'school address'])) {
            return "Our campus is located at 96 Bayanan, City of Bacoor, Cavite.";
        } else if (containsAny(msg, ['contact', 'phone', 'telephone', 'number', 'email', 'gmail', 'hotline', 'contact us', 'reach us'])) {
            return "You can reach us at (046) 476-6217, (02) 8521-0835, or email bacoor@sfac.edu.ph.";
        } else if (containsAny(msg, ['requirement', 'requirements', 'document', 'documents', 'papers', 'requirements for admission', 'needed requirements', 'credential', 'credentials'])) {
            return "Admission requirements: Birth Certificate, Report Card, Certificate of Good Moral Character, 2x2 Photo, and other relevant documents.";
        } else if (containsAny(msg, ['website', 'site', 'url', 'homepage', 'official website', 'school website'])) {
            return "Visit our official website: https://sfac.edu.ph/";
        } else if (containsAny(msg, ['school', 'campus', 'sfac', 'college', 'saint francis', 'bacoor campus'])) {
            return "Saint Francis of Assisi College Bacoor Campus offers elementary, junior high, senior high, and college programs with a student-centered learning environment.";
        } else {
            return "Thank you for your message! If you have specific questions about our school, programs, or admissions, feel free to ask. - STACY";
        }
    }

    function clearChatHistory() {
        localStorage.removeItem(CHAT_HISTORY_KEY);
        chatHistory = [];
    }

    window.openChatbot = function() {
        const popup = document.getElementById('chatbot-popup');
        if (!popup) return;
        popup.classList.remove('hidden');

        if (chatHistory.length > 0) {
            renderChatHistory();
        } else {
            appendMessage('Bot', "Hello! Welcome to Saint Francis Of Assisi College Bacoor Campus. I am STACY, your virtual assistant. How can I help you?");
        }

        input && input.focus();
    };

    window.closeChatbot = function() {
        const popup = document.getElementById('chatbot-popup');
        if (!popup) return;
        popup.classList.add('hidden');
    };

    const logoutForms = document.querySelectorAll('#logoutForm, #logout-form');
    logoutForms.forEach(form => {
        form.addEventListener('submit', clearChatHistory);
    });

    if (sendBtn && input) {
        sendBtn.addEventListener('click', function() {
            const userMsg = input.value.trim();
            if (!userMsg) return;
            appendMessage('You', userMsg);
            input.value = '';
            setTimeout(function() {
                appendMessage('Bot', chatbotReply(userMsg));
            }, 500);
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') sendBtn.click();
        });
    }

    chatHistory = loadChatHistory();
});
</script>

</body>
</html>