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

            {{-- Chatbot: compact card with popup chat interface --}}
            <section>
                <p class="text-xs font-medium text-crimson-600 uppercase tracking-widest mb-3">
                    Campus Assistant
                </p>

                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-crimson-50 flex items-center justify-center">
                            <i class="fa-solid fa-robot text-crimson-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800 mb-0.5">STACY — Virtual Assistant</p>
                            <p class="text-xs text-gray-500">Quick answers about enrollment, programs, and campus info.</p>
                        </div>
                        <div>
                            <button onclick="openChatbot()" class="bg-crimson-600 hover:bg-crimson-700 text-white text-xs font-medium px-3 py-2 rounded">Open Chat</button>
                        </div>
                    </div>
                </div>
            </section>
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

{{-- Chatbot popup and enhanced JS --}}
<div id="chatbot-popup" class="hidden fixed right-5 bottom-20 z-50 w-80 max-w-full rounded-3xl border border-gray-200 bg-white shadow-2xl">
    <div class="flex items-center justify-between rounded-t-3xl bg-crimson-600 px-4 py-3 text-white">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-robot text-sm"></i>
            <span class="text-sm font-medium">STACY</span>
        </div>
        <button onclick="closeChatbot()" class="text-white/80 hover:text-white">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div id="chatbot-messages" class="h-64 overflow-y-auto px-4 py-4 flex flex-col gap-3 bg-gray-50" aria-live="polite"></div>
    <div class="border-t border-gray-200 px-4 py-3 flex gap-2">
        <input id="chatbot-input" type="text" placeholder="Ask STACY a question..." class="flex-1 rounded-2xl border border-gray-200 px-3 py-2 text-sm focus:outline-none" />
        <button id="chatbot-send" class="rounded-2xl bg-crimson-600 px-4 text-xs font-semibold text-white hover:bg-crimson-700">Send</button>
    </div>
</div>

<button onclick="openChatbot()" class="fixed right-5 bottom-5 z-40 rounded-full bg-crimson-600 p-4 text-white shadow-lg hover:bg-crimson-700" aria-label="Open chat">
    <i class="fa-solid fa-comments"></i>
</button>

<script>
// Enhanced chatbot script — safe output, linkify, and graceful fallbacks
document.addEventListener('DOMContentLoaded', function () {
    const messagesDiv = document.getElementById('chatbot-messages');
    const input = document.getElementById('chatbot-input');
    const sendBtn = document.getElementById('chatbot-send');
    const CHAT_HISTORY_KEY = 'stacyChatHistory';
    let chatHistory = [];

    function escapeHtml(str) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(str));
        return d.innerHTML;
    }

    function linkify(text) {
        // Simple URL -> anchor conversion
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
            : 'max-w-[80%] bg-white text-gray-800 rounded-xl px-3 py-2 text-sm border border-gray-100';

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

    if (sendBtn && input) {
        sendBtn.addEventListener('click', function() {
            const userMsg = input.value.trim();
            if (!userMsg) return;
            appendMessage('You', userMsg);
            input.value = '';
            // Simulate thinking and reply
            setTimeout(function() {
                appendMessage('Bot', chatbotReply(userMsg));
            }, 500);
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') sendBtn.click();
        });
    }

    const logoutForms = document.querySelectorAll('#logoutForm, #logout-form');
    logoutForms.forEach(form => {
        form.addEventListener('submit', clearChatHistory);
    });

    chatHistory = loadChatHistory();

    // Simple slideshow logic for About Us section (guarded)
    try {
        const slides = document.querySelectorAll('.about-slide');
        if (slides && slides.length) {
            let currentSlide = 0;
            function showSlide(idx) {
                slides.forEach((img, i) => img.style.display = i === idx ? 'block' : 'none');
            }
            const prevBtn = document.getElementById('about-prev');
            const nextBtn = document.getElementById('about-next');
            prevBtn && (prevBtn.onclick = function() {
                currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                showSlide(currentSlide);
            });
            nextBtn && (nextBtn.onclick = function() {
                currentSlide = (currentSlide + 1) % slides.length;
                showSlide(currentSlide);
            });
            showSlide(currentSlide);
        }
    } catch (e) {
        // if DOM elements not present, silently ignore
    }
});
</script>

</body>
</html>