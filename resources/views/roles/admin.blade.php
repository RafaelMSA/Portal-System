<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - SFAC</title>
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
                <i class="fa-solid fa-shield text-white text-sm"></i>
            </div>
            <div>
                <h1 class="font-display text-base font-normal leading-tight tracking-wide">SFAC Admin Dashboard</h1>
                <p class="text-xs text-white/60 leading-none mt-0.5">Administrator Portal</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <span class="text-sm text-white/80"><i class="fa-solid fa-user-shield mr-1"></i> Admin</span>
            <div class="relative">
                <button id="profileBtn" onclick="toggleProfileDropdown()" class="w-8 h-8 rounded-full bg-white/15 hover:bg-white/25 flex items-center justify-center transition-colors duration-200 focus:outline-none">
                    <i class="fa-solid fa-user text-white text-sm"></i>
                </button>
                <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-xl shadow-lg z-50 py-2">
                    <div class="px-4 py-3 border-b border-gray-100">
                        <p class="text-sm font-medium text-gray-800">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
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
                </div>
            </div>
        </div>
    </div>
</header>

<main class="max-w-screen-xl mx-auto px-6 py-8 space-y-8">

    <section>
        <h2 class="text-4xl font-bold text-gray-800 mb-4">Administrator Dashboard</h2>
        <p class="text-sm text-gray-600 mb-6">Welcome, {{ auth()->user()->name }} — you have full control of the portal.</p>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <a href="{{ route('announcements.index') }}" class="group bg-white rounded-xl border border-gray-200 p-6 hover:border-crimson-600 hover:shadow-md transition-all duration-200 flex flex-col h-full">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-12 h-12 rounded-lg bg-crimson-50 flex items-center justify-center">
                        <i class="fa-solid fa-newspaper text-crimson-600 text-lg"></i>
                    </div>
                    <i class="fa-solid fa-arrow-right text-crimson-600 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                </div>
                <h3 class="text-sm font-semibold text-gray-800 mb-1">Announcements</h3>
                <p class="text-xs text-gray-500">Create, edit, and manage campus announcements</p>
                <div class="text-xs text-crimson-600 font-medium mt-3">
                    <i class="fa-solid fa-square-plus mr-1"></i> Manage
                </div>
            </a>

            <a href="{{ route('achievements.index') }}" class="group bg-white rounded-xl border border-gray-200 p-6 hover:border-crimson-600 hover:shadow-md transition-all duration-200 flex flex-col h-full">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-12 h-12 rounded-lg bg-crimson-50 flex items-center justify-center">
                        <i class="fa-solid fa-trophy text-crimson-600 text-lg"></i>
                    </div>
                    <i class="fa-solid fa-arrow-right text-crimson-600 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                </div>
                <h3 class="text-sm font-semibold text-gray-800 mb-1">Achievements</h3>
                <p class="text-xs text-gray-500">Track student achievements and awards</p>
                <div class="text-xs text-crimson-600 font-medium mt-3">
                    <i class="fa-solid fa-square-plus mr-1"></i> Manage
                </div>
            </a>

            <a href="{{ route('messages.index') }}" class="group bg-white rounded-xl border border-gray-200 p-6 hover:border-crimson-600 hover:shadow-md transition-all duration-200 flex flex-col h-full">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-12 h-12 rounded-lg bg-crimson-50 flex items-center justify-center">
                        <i class="fa-solid fa-inbox text-crimson-600 text-lg"></i>
                    </div>
                    <i class="fa-solid fa-arrow-right text-crimson-600 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                </div>
                <h3 class="text-sm font-semibold text-gray-800 mb-1">Inbox</h3>
                <p class="text-xs text-gray-500">Read student concerns and reply directly from the portal.</p>
                <div class="text-xs text-crimson-600 font-medium mt-3">
                    <i class="fa-solid fa-reply mr-1"></i> Manage
                </div>
            </a>

            <a href="{{ route('users.index') }}" class="group bg-white rounded-xl border border-gray-200 p-6 hover:border-crimson-600 hover:shadow-md transition-all duration-200 flex flex-col h-full">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-12 h-12 rounded-lg bg-crimson-50 flex items-center justify-center">
                        <i class="fa-solid fa-users text-crimson-600 text-lg"></i>
                    </div>
                    <i class="fa-solid fa-arrow-right text-crimson-600 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                </div>
                <h3 class="text-sm font-semibold text-gray-800 mb-1">User Management</h3>
                <p class="text-xs text-gray-500">View and update staff, faculty, and student profiles.</p>
                <div class="text-xs text-crimson-600 font-medium mt-3">
                    <i class="fa-solid fa-user-gear mr-1"></i> Manage
                </div>
            </a>
        </div>
    </section>

    <section>
        <p class="text-xs font-medium text-crimson-600 uppercase tracking-widest mb-3">School Sites</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            @foreach ($schools->take(4) as $school)
                <a href="{{ $school->url }}" target="_blank" rel="noopener noreferrer" class="group bg-white rounded-xl border border-gray-200 p-5 flex flex-col gap-3 hover:border-crimson-600 transition-colors duration-200">
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
        </div>
    </section>

    <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-sm font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-bell text-crimson-600"></i> Recent Announcements
            </h3>
            <div class="space-y-3">
                @forelse ($announcements->take(3) as $ann)
                    <div class="text-sm border-l-2 border-crimson-200 pl-3">
                        <p class="font-medium text-gray-800">{{ $ann->title }}</p>
                        <p class="text-xs text-gray-500">{{ $ann->published_at->format('M d, Y') }}</p>
                    </div>
                @empty
                    <p class="text-xs text-gray-500 italic">No announcements yet</p>
                @endforelse
            </div>
            <a href="{{ route('announcements.index') }}" class="text-xs text-crimson-600 font-medium mt-3 inline-flex items-center gap-1">
                View All <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-sm font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-star text-amber-600"></i> Recent Achievements
            </h3>
            <div class="space-y-3">
                @forelse ($achievements->take(3) as $ach)
                    <div class="text-sm border-l-2 border-amber-200 pl-3">
                        <p class="font-medium text-gray-800">{{ $ach->title }}</p>
                        <p class="text-xs text-gray-500">{{ $ach->achieved_at->format('M d, Y') }}</p>
                    </div>
                @empty
                    <p class="text-xs text-gray-500 italic">No achievements yet</p>
                @endforelse
            </div>
            <a href="{{ route('achievements.index') }}" class="text-xs text-crimson-600 font-medium mt-3 inline-flex items-center gap-1">
                View All <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>
    </section>

</main>

<footer class="mt-12 border-t border-gray-200 py-5">
    <div class="max-w-screen-xl mx-auto px-6 flex items-center justify-between text-xs text-gray-400">
        <span>© {{ date("Y") }} Saint Francis of Assisi College. All rights reserved.</span>
        <span>Admin Portal v1.0</span>
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
