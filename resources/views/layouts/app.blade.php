<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('messages.gym_system') }} - {{ __('messages.gym_system_subtitle') }}</title>

    <!-- Google Fonts - Inter for modern typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Cairo:wght@400;600;700&family=Montserrat:wght@400;600;700&family=Open+Sans:wght@400;600;700&family=Roboto:wght@400;700&family=Tajawal:wght@400;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @if (file_exists(public_path('js/app.js')))
        <script src="{{ asset('js/app.js') }}" defer></script>
    @endif

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    
    <!-- Scripts & Libraries -->
    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
    
    <!-- Styles -->
    @if (file_exists(public_path('css/app.css')))
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @endif
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --primary-color: #667eea;
            --primary-dark: #5a67d8;
            --text-primary: #2d3748;
            --text-secondary: #718096;
            --border-color: #e2e8f0;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* --- PRELOADER ALTERNATIF (Top Bar + Minimalist) --- */
        .premium-preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 999999;
            transition: opacity 0.5s ease, visibility 0.5s;
        }

        .dark-mode .premium-preloader {
            background-color: rgba(15, 23, 42, 0.9);
        }

        /* Barre de progression en haut */
        .loader-top-bar {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            width: 0;
            background: linear-gradient(90deg, #6366f1, #a855f7);
            box-shadow: 0 0 10px rgba(99, 102, 241, 0.5);
            z-index: 1000000;
            animation: progress-animation 2s infinite ease-in-out;
        }

        .preloader-inner {
            text-align: center;
        }

        .logo-container {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            animation: float-animation 3s infinite ease-in-out;
        }

        .preloader-logo {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 10px 15px rgba(0,0,0,0.1));
        }

        .loader-text {
            font-family: 'Inter', 'Cairo', sans-serif;
            font-size: 0.9rem;
            font-weight: 500;
            color: #6366f1;
            text-transform: uppercase;
            letter-spacing: 2px;
            opacity: 0.8;
            animation: blink-animation 1.5s infinite;
        }

        @keyframes progress-animation {
            0% { width: 0; left: 0; }
            50% { width: 70%; left: 15%; }
            100% { width: 0; left: 100%; }
        }

        @keyframes float-animation {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        @keyframes blink-animation {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 1; }
        }

        .preloader-hidden {
            opacity: 0;
            visibility: hidden;
        }
        /* --- FIN PRELOADER ALTERNATIF --- */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: {!! $globalSettings['font_family'] ?? "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif" !!} !important;
            background: #f7fafc;
            color: var(--text-primary);
            font-size: {{ $globalSettings['font_size'] ?? '16' }}px !important;
        }

        /* Force font family on all elements that might override it */
        h1, h2, h3, h4, h5, h6, 
        .h1, .h2, .h3, .h4, .h5, .h6,
        input, button, select, optgroup, textarea,
        .btn, .form-control, .nav-link, .navbar-brand {
            font-family: {!! $globalSettings['font_family'] ?? "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif" !!} !important;
        }

        /* Modern Navbar */
        .navbar {
            background: white !important;
            box-shadow: var(--shadow-sm);
            padding: 1rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.25rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .navbar-nav .nav-link {
            font-weight: 500;
            color: var(--text-primary);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            background: #f7fafc;
            color: var(--primary-color);
        }

        .dropdown-menu {
            border: none;
            box-shadow: var(--shadow-md);
            border-radius: 12px;
            padding: 0.5rem;
            margin-top: 0.5rem;
        }

        .dropdown-item {
            border-radius: 8px;
            padding: 0.6rem 1rem;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background: #f7fafc;
            color: var(--primary-color);
        }

        main {
            min-height: calc(100vh - 80px);
        }

        /* Global Search Styles */
        .global-search-overlay {
            position: fixed;
            inset: 0;
            z-index: 2000;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 8vh 1rem 1rem;
        }
        .global-search-overlay[hidden] { display: none; }
        .global-search-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.35);
            backdrop-filter: blur(4px);
        }
        .global-search-modal {
            position: relative;
            width: min(720px, 100%);
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(255, 255, 255, 0.6);
            overflow: hidden;
            background: rgba(255, 255, 255, 0.98);
        }
        .global-search-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.9rem 1rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        }
        .global-search-input {
            flex: 1;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 0.75rem 0.9rem;
            font-size: 0.95rem;
            outline: none;
        }
        .global-search-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.12);
        }
        .global-search-spinner {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(0,0,0,0.1);
            border-top-color: var(--primary-color);
            border-radius: 50%;
            animation: global-search-spinner 0.6s linear infinite;
            display: none;
        }
        .global-search-spinner.active { display: block; }
        @keyframes global-search-spinner { to { transform: rotate(360deg); } }
        .global-search-close {
            width: 40px;
            height: 40px;
            border: none;
            border-radius: 12px;
            background: rgba(0, 0, 0, 0.04);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .global-search-results {
            max-height: 55vh;
            overflow: auto;
            padding: 0.5rem;
        }
        .global-search-item {
            display: flex;
            gap: 0.9rem;
            padding: 0.75rem 0.8rem;
            border-radius: 12px;
            text-decoration: none;
            color: inherit;
            transition: all 0.2s ease;
        }
        .global-search-item:hover, .global-search-item.focused {
            background: rgba(102, 126, 234, 0.08);
            transform: translateY(-1px);
        }
        .global-search-item.focused {
            border-left: 3px solid var(--primary-color);
            padding-left: calc(0.8rem - 3px);
        }
        .global-search-meta {
            min-width: 96px;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--primary-color);
            text-transform: uppercase;
        }
        .global-search-title { font-weight: 700; margin: 0; }
        .global-search-subtitle { font-size: 0.85rem; color: var(--text-secondary); margin: 0.2rem 0 0; }
        .global-search-empty { padding: 1rem; text-align: center; color: var(--text-secondary); }
        .search-highlight { background: rgba(255, 230, 0, 0.3); border-radius: 2px; }
    </style>
    
    @stack('styles')
</head>

<body>
    <!-- Premium Preloader Alternatif -->
    <div id="premium-preloader" class="premium-preloader">
        <div class="loader-top-bar"></div>
        <div class="preloader-inner">
            <div class="logo-container">
                <img src="{{ asset('imgs/gym-icon.png') }}" alt="Logo" class="preloader-logo">
            </div>
            <div class="loader-text">{{ __('messages.loading') ?? 'Chargement' }}...</div>
        </div>
    </div>

    <script>
        (function() {
            window.addEventListener('load', function() {
                const preloader = document.getElementById('premium-preloader');
                if (preloader) {
                    setTimeout(() => {
                        preloader.classList.add('preloader-hidden');
                    }, 400);
                }
            });
        })();
    </script>

    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ __('messages.gym_system') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('messages.toggle_navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        @hasanyrole('admin|cityManager|gymManager|coach')
                        <li class="nav-item">
                            <button id="global-search-open" class="nav-link border-0 bg-transparent" title="Rechercher (Ctrl+K)">
                                <i class="fas fa-search"></i>
                            </button>
                        </li>
                        @endhasanyrole
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('messages.login') }}</a>
                                </li>
                            @endif

                            {{-- @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif --}}
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    @hasanyrole('admin|cityManager|gymManager|coach')
    <div id="global-search-overlay" class="global-search-overlay" hidden>
        <div class="global-search-backdrop"></div>
        <div class="global-search-modal" role="dialog" aria-modal="true" aria-label="Recherche">
            <div class="global-search-header">
                <input id="global-search-input" class="global-search-input" type="search" autocomplete="off" placeholder="Rechercher… (Ctrl+K)">
                <div id="global-search-spinner" class="global-search-spinner"></div>
                <button id="global-search-close" class="global-search-close" type="button" aria-label="Fermer">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="global-search-results" class="global-search-results"></div>
        </div>
    </div>

    <script>
        (function () {
            const overlay = document.getElementById('global-search-overlay');
            const openBtn = document.getElementById('global-search-open');
            const closeBtn = document.getElementById('global-search-close');
            const input = document.getElementById('global-search-input');
            const resultsEl = document.getElementById('global-search-results');
            const spinner = document.getElementById('global-search-spinner');
            const backdrop = overlay ? overlay.querySelector('.global-search-backdrop') : null;

            if (!overlay || !closeBtn || !input || !resultsEl || !backdrop) return;

            let debounceTimer = null;
            let abortController = null;
            let selectedIndex = -1;
            let currentResults = [];

            function setOpen(isOpen) {
                overlay.hidden = !isOpen;
                document.body.style.overflow = isOpen ? 'hidden' : '';
                if (isOpen) {
                    input.focus();
                    input.select();
                }
            }

            function updateSelection() {
                const items = resultsEl.querySelectorAll('.global-search-item');
                items.forEach((item, index) => {
                    if (index === selectedIndex) {
                        item.classList.add('focused');
                        item.scrollIntoView({ block: 'nearest' });
                    } else {
                        item.classList.remove('focused');
                    }
                });
            }

            function highlightText(text, query) {
                if (!query || !text) return text;
                const escapedText = escapeHtml(text);
                const escapedQuery = escapeHtml(query);
                const regex = new RegExp(`(${escapedQuery})`, 'gi');
                return escapedText.replace(regex, '<span class="search-highlight">$1</span>');
            }

            function renderResults(results, query) {
                currentResults = results;
                selectedIndex = -1;
                
                if (!Array.isArray(results) || results.length === 0) {
                    resultsEl.innerHTML = '<div class="global-search-empty">Aucun résultat</div>';
                    return;
                }

                resultsEl.innerHTML = results.map((item, index) => {
                    const type = escapeHtml(item.type || '');
                    const titleRaw = item.title || '';
                    const subtitleRaw = item.subtitle || '';
                    const url = escapeAttr(item.url || '#');
                    
                    const titleHtml = highlightText(titleRaw, query);
                    const subtitleHtml = highlightText(subtitleRaw, query);

                    return `
                        <a class="global-search-item" href="${url}" data-index="${index}">
                            <div class="global-search-meta">${type}</div>
                            <div>
                                <p class="global-search-title">${titleHtml}</p>
                                ${subtitleRaw ? `<p class="global-search-subtitle">${subtitleHtml}</p>` : ''}
                            </div>
                        </a>
                    `;
                }).join('');
            }

            function requestResults(rawValue) {
                const q = (rawValue || '').trim();
                if (q.length < 2) {
                    resultsEl.innerHTML = '<div class="global-search-empty">Tapez au moins 2 caractères</div>';
                    if(spinner) spinner.classList.remove('active');
                    return;
                }

                if (abortController) abortController.abort();
                abortController = new AbortController();

                if(spinner) spinner.classList.add('active');

                fetch(`/global-search?q=${encodeURIComponent(q)}`, {
                    headers: { 'Accept': 'application/json' },
                    signal: abortController.signal
                })
                    .then(r => r.ok ? r.json() : Promise.reject(r))
                    .then(data => {
                        renderResults(data.results || [], q);
                    })
                    .catch(err => {
                        if (err && err.name === 'AbortError') return;
                        resultsEl.innerHTML = '<div class="global-search-empty">Erreur de recherche</div>';
                    })
                    .finally(() => {
                         if(spinner) spinner.classList.remove('active');
                    });
            }

            function debounceRequest() {
                if (debounceTimer) clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => requestResults(input.value), 250);
            }

            function escapeHtml(value) {
                return String(value)
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", '&#039;');
            }

            function escapeAttr(value) {
                return escapeHtml(value).replaceAll('`', '&#096;');
            }

            if (openBtn) openBtn.addEventListener('click', () => setOpen(true));
            closeBtn.addEventListener('click', () => setOpen(false));
            backdrop.addEventListener('click', () => setOpen(false));

            document.addEventListener('keydown', (e) => {
                const isMac = navigator.platform.toLowerCase().includes('mac');
                const metaOrCtrl = isMac ? e.metaKey : e.ctrlKey;
                
                if (metaOrCtrl && (e.key === 'k' || e.key === 'K')) {
                    e.preventDefault();
                    setOpen(true);
                    return;
                }
                
                if (overlay.hidden) return;

                if (e.key === 'Escape') {
                    e.preventDefault();
                    setOpen(false);
                    return;
                }

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (currentResults.length > 0) {
                        selectedIndex = (selectedIndex + 1) % currentResults.length;
                        updateSelection();
                    }
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (currentResults.length > 0) {
                        selectedIndex = (selectedIndex - 1 + currentResults.length) % currentResults.length;
                        updateSelection();
                    }
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    if (selectedIndex >= 0 && selectedIndex < currentResults.length) {
                        const link = resultsEl.querySelector(`[data-index="${selectedIndex}"]`);
                        if (link) link.click();
                    }
                }
            });

            input.addEventListener('input', () => {
                selectedIndex = -1;
                debounceRequest();
            });
            
            resultsEl.addEventListener('mousemove', (e) => {
                const item = e.target.closest('.global-search-item');
                if (item) {
                    const idx = parseInt(item.dataset.index);
                    if (!isNaN(idx)) {
                        selectedIndex = idx;
                        resultsEl.querySelectorAll('.global-search-item').forEach(el => el.classList.remove('focused'));
                        item.classList.add('focused');
                    }
                }
            });
        })();
    </script>
    @endhasanyrole

    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    
    <!-- Global Keyboard Shortcuts -->
    <script>
        $(function() {
            $(document).on('keydown', function(e) {
                // Ignore if user is typing in input, textarea or select
                if ($(e.target).is('input, textarea, select')) {
                    // Special case: Enter in modals to submit
                    if (e.key === 'Enter') {
                        const activeModal = $('.modal.show');
                        if (activeModal.length) {
                            const submitBtn = activeModal.find('button[type="submit"], .btn-primary:not([data-bs-dismiss="modal"])').first();
                            if (submitBtn.length && !submitBtn.prop('disabled')) {
                                e.preventDefault();
                                submitBtn.click();
                            }
                        }
                    }
                    return;
                }

                const key = e.key.toLowerCase();

                // P: Print
                if (key === 'p') {
                    e.preventDefault();
                    window.print();
                }

                // B: Back
                if (key === 'b') {
                    e.preventDefault();
                    window.history.back();
                }

                // H: Home
                if (key === 'h') {
                    e.preventDefault();
                    window.location.href = "{{ url('/') }}";
                }
            });
        });
    </script>
    @stack('scripts')

</body>

</html>
