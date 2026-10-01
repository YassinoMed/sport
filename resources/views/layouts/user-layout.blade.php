<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @if(app()->getLocale() == 'ar') dir="rtl" @endif>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Système de gestion de salle de sport moderne et professionnel">
    <base href="{{ \URL::to('/') }}">
    
    <!-- Google Fonts - Inter & Cairo for modern typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/vendor/datatables/css/jquery.dataTables.min.css') }}">
    <!-- Font Awesome 6 for better icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fontawesome/css/all.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
    <!-- Custom Premium Styles -->
    <link href="{{ asset('css/custom-styles.css') }}" rel="stylesheet">
    <!-- Modern UI Design System -->
    <link href="{{ asset('css/modern-ui.css') }}" rel="stylesheet">
    <!-- Design Upgrade v2.0 — Responsive, Modern, Premium -->
    <link href="{{ asset('css/design-upgrade.css') }}" rel="stylesheet">
    <!-- Intro.js CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intro.js@7.2.0/introjs.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- SweetAlert2 -->
    <script src="{{ asset('assets/vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
    @yield('dataTable')
    @stack('styles')
    <!-- PWA Support -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#6366f1">
    <link rel="apple-touch-icon" href="{{ asset('imgs/gym-icon.png') }}">

    <title>{{ __('Gym System') }}</title>
    <script>
        // Immediate theme application to prevent flash
        (function() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark-mode');
                document.write('<style id="theme-flash-fix">body { background-color: #0f172a !important; color: #d1d5db !important; }</style>');
            }
        })();
    </script>
</head>
<style>
:root {
    /* Premium Color Palette */
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --primary-color: #667eea;
    --primary-dark: #5a67d8;
    --primary-light: #7c8ff0;
    --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --success-color: #48bb78;
    --warning-color: #ed8936;
    --danger-color: #f56565;
    --info-color: #4299e1;
    
    /* Neutral Colors */
    --dark-bg: #1a202c;
    --darker-bg: #0f1419;
    --light-bg: #f7fafc;
    --text-primary: #2d3748;
    --text-secondary: #718096;
    --text-muted: #a0aec0;
    --border-color: #e2e8f0;
    
    /* Shadows */
    --shadow-sm: 0 2px 4px rgba(0,0,0,0.05);
    --shadow-md: 0 4px 12px rgba(0,0,0,0.1);
    --shadow-lg: 0 10px 30px rgba(0,0,0,0.15);
    --shadow-xl: 0 20px 60px rgba(0,0,0,0.2);
    
    /* Sidebar */
    --sidebar-width: 260px;
    --sidebar-bg: linear-gradient(180deg, #2d3748 0%, #1a202c 100%);
    --sidebar-item-hover: rgba(255, 255, 255, 0.1);
}

/* Sidebar Item Colors */
.nav-icon.text-indigo { color: #818cf8 !important; }
.nav-icon.text-emerald { color: #34d399 !important; }
.nav-icon.text-amber { color: #fbbf24 !important; }
.nav-icon.text-rose { color: #fb7185 !important; }
.nav-icon.text-sky { color: #38bdf8 !important; }
.nav-icon.text-violet { color: #a78bfa !important; }
.nav-icon.text-teal { color: #2dd4bf !important; }
.nav-icon.text-purple { color: #a855f7 !important; }
.nav-icon.text-orange { color: #f97316 !important; }
.nav-icon.text-pink { color: #ec4899 !important; }

* {
    box-sizing: border-box;
}

body {
        font-family: {!! ($globalSettings ?? [])['font_family'] ?? "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif" !!};
        position: relative;
        background: #f7fafc;
        color: var(--text-primary);
        font-size: {{ ($globalSettings ?? [])['font_size'] ?? '14' }}px;
        line-height: 1.6;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    /* Accessibility Focus States */
    :focus-visible {
        outline: 2px solid var(--primary-color);
        outline-offset: 2px;
    }

/* Force font family on all elements that might override it */
h1, h2, h3, h4, h5, h6, 
.h1, .h2, .h3, .h4, .h5, .h6,
input, button, select, optgroup, textarea,
.btn, .form-control, .nav-link, .brand-text, .card-title {
    font-family: {!! $globalSettings['font_family'] ?? "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif" !!};
}

/* Scale headings based on base font size if needed, but usually browser defaults or framework styles are okay if body is set. 
   However, forcing the font-family is the most important part for "applying to all styles". */

/* Enhanced Icons */
.fa, .fas, .far, .fab {
    font-size: .9rem !important;
}

/* DataTables Styling */
.dataTables_wrapper .dataTables_length {
    margin-left: 20px !important;
    margin-top: 10px !important;
}

.dataTables_wrapper .dataTables_filter,
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_paginate {
    margin: 10px 20px !important;
}

.dataTables_wrapper .dataTables_filter input {
    border: 2px solid var(--border-color);
    border-radius: 8px;
    padding: 8px 16px;
    transition: all 0.3s ease;
    font-size: 0.95rem; /* Ensure legible font size */
}

.dataTables_wrapper .dataTables_filter input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    outline: none;
}

/* Layout Adjustments */
@media (min-width: 768px) {
    body:not(.sidebar-mini-md):not(.sidebar-mini-xs):not(.layout-top-nav) .content-wrapper,
    body:not(.sidebar-mini-md):not(.sidebar-mini-xs):not(.layout-top-nav) .main-footer,
    body:not(.sidebar-mini-md):not(.sidebar-mini-xs):not(.layout-top-nav) .main-header {
        margin-left: var(--sidebar-width);
    }
}

/* Arabic Text Support */
.ar-text {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    direction: rtl;
    unicode-bidi: embed;
}

/* ============================================
   NAVBAR - Premium Header Design
   ============================================ */
.main-header.navbar {
    background: #ffffff !important;
    border-bottom: 1px solid var(--border-color);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    backdrop-filter: blur(10px);
    padding: 0.75rem 1.5rem;
    z-index: 1030;
}

.main-header .nav-link {
    color: var(--text-primary) !important;
    font-weight: 500;
    transition: all 0.3s ease;
    padding: 0.5rem 1rem;
    border-radius: 8px;
}

.main-header .nav-link:hover {
    background: var(--light-bg);
    color: var(--primary-color) !important;
}

.main-header .nav-link i {
    font-size: 1.1rem;
}

/* User Dropdown Enhanced */
.dropdown.user.user-menu {
    position: relative;
}

.dropdown.user.user-menu .media {
    padding: 0.5rem 1rem;
    border-radius: 10px;
    transition: all 0.3s ease;
    cursor: pointer;
}

.dropdown.user.user-menu .media:hover {
    background: var(--light-bg);
}

.dropdown.user.user-menu .img-circle {
    border: 2px solid var(--primary-color);
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.2);
}

.dropdown.user.user-menu .dropdown-menu {
    border: none;
    box-shadow: var(--shadow-lg);
    border-radius: 12px;
    padding: 0.5rem;
    margin-top: 0.5rem;
    min-width: 220px;
}

.dropdown.user.user-menu .user-header {
    background: var(--primary-gradient) !important;
    border-radius: 8px;
    padding: 1.5rem;
    text-align: center;
}

.dropdown.user.user-menu .user-header p {
    color: white;
    font-weight: 600;
}

.dropdown.user.user-menu .user-footer {
    background: transparent;
    padding: 0.75rem;
}

.dropdown.user.user-menu .btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
    border: 2px solid var(--border-color);
}

.dropdown.user.user-menu .btn:hover {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
    transform: translateY(-1px);
}

/* ============================================
   SIDEBAR - Modern Dark Design with Glassmorphism
   ============================================ */
.main-sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    overflow-y: auto;
    overflow-x: hidden;
    width: var(--sidebar-width);
    background: var(--sidebar-bg);
    box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
    z-index: 1040;
    transition: all 0.3s ease;
}

/* Custom Scrollbar */
.main-sidebar::-webkit-scrollbar {
    width: 6px;
}

.main-sidebar::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
}

.main-sidebar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 3px;
}

.main-sidebar::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.3);
}

/* Brand Logo */
.brand-link {
    display: flex;
    align-items: center;
    padding: 1.25rem 1.5rem;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}

.brand-link:hover {
    background: rgba(255, 255, 255, 0.08);
    text-decoration: none;
}

.brand-link i {
    font-size: 1.5rem;
    color: var(--primary-light);
    margin-right: 0.75rem;
}

.brand-text {
    color: white !important;
    font-weight: 600;
    font-size: 0.95rem;
}

/* Sidebar Navigation */
.nav-sidebar {
    padding: 1rem 0.75rem;
}

.nav-sidebar .nav-header {
    color: rgba(255, 255, 255, 0.5);
    text-transform: uppercase;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 1px;
    padding: 1.5rem 1rem 0.5rem;
    margin-top: 1rem;
}

.nav-sidebar .nav-item {
    margin-bottom: 0.25rem;
}

.nav-sidebar .nav-link {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    border-radius: 10px;
    color: rgba(255, 255, 255, 0.8);
    font-weight: 500;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.nav-sidebar .nav-link::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 3px;
    background: var(--primary-color);
    transform: scaleY(0);
    transition: transform 0.3s ease;
}

.nav-sidebar .nav-link:hover {
    background: var(--sidebar-item-hover);
    color: white;
    transform: translateX(4px);
}

.nav-sidebar .nav-link:hover::before {
    transform: scaleY(1);
}

.nav-sidebar .nav-link.active {
    background: var(--sidebar-item-active);
    color: white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.nav-sidebar .nav-link.active::before {
    transform: scaleY(1);
}

.nav-sidebar .nav-link .nav-icon,
.nav-sidebar .nav-link i {
    width: 24px;
    text-align: center;
    margin-right: 12px;
    font-size: 1rem;
    color: var(--primary-light);
}

.nav-sidebar .nav-link p {
    margin: 0;
    flex: 1;
    font-size: 0.9rem;
}

.nav-sidebar .nav-link .right {
    margin-left: auto;
    transition: transform 0.3s ease;
}

/* Menu Tree Animation */
.nav-item.menu-open > .nav-link .right {
    transform: rotate(-90deg);
}

/* Treeview Submenu */
.nav-treeview {
    padding-left: 0;
    background: rgba(0, 0, 0, 0.15);
    border-radius: 8px;
    margin: 0.25rem 0.5rem;
}

.nav-treeview .nav-link {
    padding: 0.6rem 1rem 0.6rem 2.5rem;
    font-size: 0.85rem;
}

.nav-treeview .nav-icon {
    font-size: 0.75rem;
    opacity: 0.7;
}

/* Enhanced Icon Styles */
.nav-icon {
    width: 20px;
    text-align: center;
    margin-right: 10px;
}

.nav-treeview .nav-icon {
    font-size: 0.8rem;
}

/* ============================================
   CONTENT WRAPPER
   ============================================ */
.content-wrapper {
    background: #f7fafc;
    min-height: calc(100vh - 57px);
    padding: 1.5rem;
}

/* ============================================
   FOOTER
   ============================================ */
.main-footer {
    background: white;
    border-top: 1px solid var(--border-color);
    padding: 1rem;
    font-size: 0.875rem;
}

.main-footer .bg-primary {
    background: var(--primary-gradient) !important;
    border-radius: 6px;
    font-weight: 600;
}

/* ============================================
   CARDS & COMPONENTS
   ============================================ */
.card {
    border: none;
    border-radius: 12px;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.card-header {
    background: white;
    border-bottom: 1px solid var(--border-color);
    border-radius: 12px 12px 0 0;
    padding: 1.25rem;
    font-weight: 600;
}

/* ============================================
   BUTTONS
   ============================================ */
.btn {
    border-radius: 8px;
    font-weight: 500;
    padding: 0.5rem 1.25rem;
    transition: all 0.3s ease;
}

.btn-primary {
    background: var(--primary-gradient);
    border: none;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
}

/* ============================================
   PRELOADER
   ============================================ */
.preloader {
    background: var(--primary-gradient) !important;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
    display: flex; /* Always display flex but hide via opacity/visiblity logic if needed, but AdminLTE overrides display. */
}

.preloader.visible {
    opacity: 1;
    pointer-events: auto;
}

.animation__shake {
    animation: shake 1s infinite;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-10px) rotate(-5deg); }
    75% { transform: translateX(10px) rotate(5deg); }
}

/* Global Search Improvements */
.global-search-spinner {
    width: 20px;
    height: 20px;
    border: 2px solid rgba(0,0,0,0.1);
    border-top-color: var(--primary-color);
    border-radius: 50%;
    animation: spinner 0.6s linear infinite;
    display: none;
}
.global-search-spinner.active {
    display: block;
}
body.dark-mode .global-search-spinner {
    border-color: rgba(255,255,255,0.1);
    border-top-color: var(--primary-light);
}
@keyframes spinner { to { transform: rotate(360deg); } }

.global-search-item.focused {
    background: rgba(102, 126, 234, 0.15);
    border-left: 3px solid var(--primary-color);
    padding-left: calc(0.8rem - 3px);
}
body.dark-mode .global-search-item.focused {
    background: rgba(102, 126, 234, 0.25);
}

.search-highlight {
    background-color: rgba(255, 230, 0, 0.3);
    border-radius: 2px;
    color: inherit;
    padding: 0 1px;
}
body.dark-mode .search-highlight {
    background-color: rgba(255, 230, 0, 0.4);
    color: #fff;
}

/* ============================================
   RESPONSIVE DESIGN
   ============================================ */
@media (max-width: 767px) {
    .main-sidebar {
        transform: translateX(-100%);
    }
    
    body.sidebar-open .main-sidebar {
        transform: translateX(0);
    }
    
    .main-header,
    .content-wrapper,
    .main-footer {
        margin-left: 0 !important;
    }
}

/* ============================================
   ANIMATIONS
   ============================================ */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.nav-item {
    animation: fadeIn 0.3s ease forwards;
}

.nav-item:nth-child(1) { animation-delay: 0.05s; }
.nav-item:nth-child(2) { animation-delay: 0.1s; }
.nav-item:nth-child(3) { animation-delay: 0.15s; }
.nav-item:nth-child(4) { animation-delay: 0.2s; }
.nav-item:nth-child(5) { animation-delay: 0.25s; }

.global-search-overlay {
    position: fixed;
    inset: 0;
    z-index: 2000;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    padding: 8vh 1rem 1rem;
}

.global-search-overlay[hidden] {
    display: none;
}

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
    box-shadow: var(--shadow-xl);
    border: 1px solid rgba(255, 255, 255, 0.6);
    overflow: hidden;
    background: rgba(255, 255, 255, 0.98);
}

body.dark-mode .global-search-modal {
    background: rgba(33, 37, 41, 0.98);
    border-color: rgba(255, 255, 255, 0.08);
}

.global-search-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.9rem 1rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.08);
}

body.dark-mode .global-search-header {
    border-bottom-color: rgba(255, 255, 255, 0.08);
}

.global-search-input {
    flex: 1;
    border: 2px solid var(--border-color);
    border-radius: 12px;
    padding: 0.75rem 0.9rem;
    font-size: 0.95rem;
    outline: none;
}

body.dark-mode .global-search-input {
    background: rgba(255, 255, 255, 0.06);
    border-color: rgba(255, 255, 255, 0.12);
    color: #fff;
}

.global-search-input:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.12);
}

.global-search-close {
    width: 40px;
    height: 40px;
    border: none;
    border-radius: 12px;
    background: rgba(0, 0, 0, 0.04);
    color: var(--text-dark);
    display: flex;
    align-items: center;
    justify-content: center;
}

body.dark-mode .global-search-close {
    background: rgba(255, 255, 255, 0.06);
    color: rgba(255, 255, 255, 0.9);
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
    transition: background 0.15s ease, transform 0.15s ease;
}

.global-search-item:hover {
    background: rgba(102, 126, 234, 0.08);
    transform: translateY(-1px);
}

body.dark-mode .global-search-item:hover {
    background: rgba(102, 126, 234, 0.18);
}

.global-search-meta {
    min-width: 96px;
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--primary);
    letter-spacing: 0.6px;
    text-transform: uppercase;
    padding-top: 0.1rem;
}

body.dark-mode .global-search-meta {
    color: rgba(167, 182, 255, 0.95);
}

.global-search-title {
    font-weight: 700;
    font-size: 0.95rem;
    margin: 0;
    line-height: 1.2;
}

.global-search-subtitle {
    margin: 0.2rem 0 0;
    font-size: 0.85rem;
    color: var(--text-muted);
    line-height: 1.2;
}

body.dark-mode .global-search-subtitle {
    color: rgba(255, 255, 255, 0.7);
}

.global-search-empty {
    padding: 1rem;
    color: var(--text-muted);
    text-align: center;
    font-weight: 600;
}

/* Cleanup Utilities */
.fs-14 { font-size: 14px !important; }
.fs-13 { font-size: 13px !important; }
.cursor-pointer { cursor: pointer; }

/* Enhanced Sidebar Separators */
    .nav-sidebar .nav-header {
        color: rgba(255, 255, 255, 0.7) !important;
        background: rgba(0, 0, 0, 0.2);
        padding: 0.75rem 1rem;
        margin-top: 1rem;
        margin-bottom: 0.5rem;
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        border-top: 1px solid rgba(255,255,255,0.05);
        border-bottom: 1px solid rgba(255,255,255,0.05);
        border-radius: 4px;
    }

    /* --- PREMIUM PRELOADER ALTERNATIF (Top Bar + Minimalist) --- */
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
    /* --- FIN PREMIUM PRELOADER ALTERNATIF --- */
</style>
@yield('stripeStyle')

<body class="sidebar-mini layout-fixed">
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

    <!-- Page Loading Overlay -->
    <div id="pageLoader" class="page-loading-overlay">
        <div class="spinner-modern"></div>
    </div>

    <!-- UX Components -->
    <div class="scroll-progress-container"><div class="scroll-progress-bar" id="scrollProgress"></div></div>
    <button class="back-to-top" id="backToTop" title="{{ __('Back to Top') }}"><i class="fas fa-chevron-up"></i></button>

    <!-- Keyboard Shortcuts Help Modal -->
    <div class="modal fade" id="shortcutsHelpModal" tabindex="-1" aria-labelledby="shortcutsHelpModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-modern-lg">
                <div class="modal-header bg-dark text-white border-0 py-3 px-4">
                    <h5 class="modal-title fw-bold d-flex align-items-center" id="shortcutsHelpModalLabel">
                        <div class="bg-primary p-2 rounded-3 me-3">
                            <i class="fas fa-keyboard text-white"></i>
                        </div>
                        {{ __('Keyboard Shortcuts') }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <!-- Navigation -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <span class="bg-primary-light p-2 rounded-modern me-2">
                                    <i class="fas fa-directions text-primary"></i>
                                </span>
                                <h6 class="fw-bold mb-0 shortcut-modal-header text-dark">{{ __('Navigation') }}</h6>
                            </div>
                            <div class="d-flex flex-column gap-2">
                                <div class="shortcut-item">
                                    <span class="shortcut-key">H</span>
                                    <span class="shortcut-desc"><i class="fas fa-home me-2"></i> {{ __('Dashboard') }}</span>
                                </div>
                                <div class="shortcut-item">
                                    <span class="shortcut-key">B</span>
                                    <span class="shortcut-desc"><i class="fas fa-arrow-left me-2"></i> {{ __('Back') }}</span>
                                </div>
                                <div class="shortcut-item">
                                    <span class="shortcut-key">L</span>
                                    <span class="shortcut-desc"><i class="fas fa-list me-2"></i> {{ __('List / View All') }}</span>
                                </div>
                                <div class="shortcut-item">
                                    <span class="shortcut-key">A</span>
                                    <span class="shortcut-desc"><i class="fas fa-users me-2"></i> {{ __('Athletes') }}</span>
                                </div>
                                <div class="shortcut-item">
                                    <span class="shortcut-key">G</span>
                                    <span class="shortcut-desc"><i class="fas fa-dumbbell me-2"></i> {{ __('Gyms') }}</span>
                                </div>
                                <div class="shortcut-item">
                                    <span class="shortcut-key">O</span>
                                    <span class="shortcut-desc"><i class="fas fa-chalkboard-teacher me-2"></i> {{ __('Coaches') }}</span>
                                </div>
                                <div class="shortcut-item">
                                    <span class="shortcut-key">K</span>
                                    <span class="shortcut-desc"><i class="fas fa-book me-2"></i> {{ __('Courses') }}</span>
                                </div>
                                <div class="shortcut-item">
                                    <span class="shortcut-key">D</span>
                                    <span class="shortcut-desc"><i class="fas fa-money-check-alt me-2"></i> {{ __('Expenses') }}</span>
                                </div>
                                <div class="shortcut-item">
                                    <span class="shortcut-key">V</span>
                                    <span class="shortcut-desc"><i class="fas fa-city me-2"></i> {{ __('Cities') }}</span>
                                </div>
                                <div class="shortcut-item">
                                    <span class="shortcut-key">N</span>
                                    <span class="shortcut-desc"><i class="fas fa-calendar-alt me-2"></i> {{ __('Schedule') }}</span>
                                </div>
                                <div class="shortcut-item">
                                    <span class="shortcut-key">M</span>
                                    <span class="shortcut-desc"><i class="fas fa-bars me-2"></i> {{ __('Menu (Sidebar)') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <span class="bg-success-light p-2 rounded-modern me-2">
                                    <i class="fas fa-bolt text-success"></i>
                                </span>
                                <h6 class="fw-bold mb-0 shortcut-modal-header text-dark">{{ __('Actions') }}</h6>
                            </div>
                            <div class="d-flex flex-column gap-2">
                                <div class="shortcut-item">
                                    <span class="shortcut-key">R</span>
                                    <span class="shortcut-desc"><i class="fas fa-plus-circle me-2"></i> {{ __('New / Create') }}</span>
                                </div>
                                <div class="shortcut-item">
                                    <span class="shortcut-key">S</span>
                                    <span class="shortcut-desc"><i class="fas fa-save me-2"></i> {{ __('Save Form') }}</span>
                                </div>
                                <div class="shortcut-item">
                                    <span class="shortcut-key">T</span>
                                    <span class="shortcut-desc"><i class="fab fa-stripe me-2"></i> {{ __('Stripe Payment') }}</span>
                                </div>
                                <div class="shortcut-item">
                                    <span class="shortcut-key">J</span>
                                    <span class="shortcut-desc"><i class="fas fa-history me-2"></i> {{ __('Invoicing / Journal') }}</span>
                                </div>
                                <div class="shortcut-item">
                                    <span class="shortcut-key">I</span>
                                    <span class="shortcut-desc"><i class="fas fa-file-contract me-2"></i> {{ __('Reports') }}</span>
                                </div>
                                <div class="shortcut-item">
                                    <span class="shortcut-key">E</span>
                                    <span class="shortcut-desc"><i class="fas fa-edit me-2"></i> {{ __('Edit (if available)') }}</span>
                                </div>
                                <div class="shortcut-item">
                                    <span class="shortcut-key">P</span>
                                    <span class="shortcut-desc"><i class="fas fa-print me-2"></i> {{ __('Print / PDF') }}</span>
                                </div>
                                <div class="shortcut-item">
                                    <span class="shortcut-key">F</span>
                                    <span class="shortcut-desc"><i class="fas fa-search me-2"></i> {{ __('Search / Focus') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Système Footer -->
                        <div class="col-12 mt-2">
                            <div class="p-4 bg-shortcut-footer rounded-modern-lg d-flex align-items-center justify-content-between flex-wrap gap-3">
                                <div class="d-flex gap-3">
                                    <div class="shortcut-item bg-white shortcut-footer-card border shadow-sm px-3">
                                        <span class="shortcut-key sm me-2">C</span>
                                        <span class="shortcut-desc">{{ __('Close / Cancel') }}</span>
                                    </div>
                                    <div class="shortcut-item bg-white shortcut-footer-card border shadow-sm px-3">
                                        <span class="shortcut-key sm me-2">?</span>
                                        <span class="shortcut-desc">{{ __('Help (Shift + /)') }}</span>
                                    </div>
                                    <div class="shortcut-item bg-white shortcut-footer-card border shadow-sm px-3">
                                        <span class="shortcut-key sm me-2">Z</span>
                                        <span class="shortcut-desc">{{ __('Dark Mode') }}</span>
                                    </div>
                                </div>
                                <div class="form-check form-switch mb-0 bg-white shortcut-footer-card p-2 px-3 rounded-pill border shadow-sm d-flex align-items-center gap-2">
                                    <input class="form-check-input cursor-pointer ms-0" type="checkbox" id="dontShowShortcutsAgain">
                                    <label class="form-check-label small text-muted cursor-pointer fw-bold" for="dontShowShortcutsAgain">
                                        {{ __("Don't show again on startup") }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Preloader -->
    <div id="global-loader" class="preloader flex-column justify-content-center align-items-center" style="display:none;">
        <img class="animation__shake" src="imgs/gym-icon.png" alt="GymSystemLogo" height="150" width="150">
    </div>
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light fs-14">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            @role('admin|cityManager|gymManager|coach|user')
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button" id="asideIcon"><i
                        class="fas fa-bars"></i></a>
            </li>
            @endrole
            <li class="nav-item d-none d-sm-inline-block">
                <a href="#" class="nav-link">
                    <i class="fas fa-home me-1"></i>
                    {{ __('Dashboard') }}
                </a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <button id="dark-mode-toggle" class="nav-link btn btn-link" title="{{ __('Toggle Dark Mode') }}">
                    <i class="fas fa-moon dark-mode-icon"></i>
                    <i class="fas fa-sun light-mode-icon" style="display: none;"></i>
                </button>
            </li>
            @auth
            <li class="dropdown user user-menu cursor-pointer">
                <div class="media align-items-center">
                    <img src="{{asset(auth()->user()->profileImageFile)}}" alt="User Avatar"
                        class="mr-2 mt-1 img-size-32 img-circle mr-2">
                    <div class="media-body">
                        <h6 class="dropdown-item-title text-dark fs-14">
                            {{ auth()->user()->name }}
                        </h6>
                    </div>
                </div>
                <ul class="dropdown-menu" style="width:200px">
                    <li class="user-header mb-1" style="height: 140px;">
                        <img class="profile-user-img img-fluid img-circle"
                            src="{{asset(auth()->user()->profileImageFile)}}" alt="User profile picture">
                        <p class="mb-0">
                            {{ auth()->user()->name }}
                        </p>
                    </li>
                    @hasanyrole('admin|cityManager|gymManager|coach')
                    <li class="nav-header">
                        <i class="fas fa-vials mr-1"></i> {{ __('Tests') }}
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('athlete-tests.index') }}" class="nav-link {{ request()->routeIs('athlete-tests.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-vial text-indigo"></i>
                            <p>
                                {{ __('Athlete Tests') }}
                            </p>
                        </a>
                    </li>
                    @endhasanyrole
                    <li class="user-footer d-flex justify-content-between">
                        <div class="pull-left">
                            <a href="{{ route('user.admin_profile', auth()->user()->id) }}"
                                class="btn btn-default btn-flat">
                                <i class="fas fa-user-circle me-1"></i>
                                {{ __('Profile') }}
                            </a>
                        </div>
                        <div class="pull-left mx-1">
                            @hasanyrole('admin|cityManager|gymManager|coach')
                            <button onclick="window.restartTour()" class="btn btn-default btn-flat text-primary">
                                <i class="fas fa-question-circle me-1"></i> {{ __('Help') }}
                            </button>
                            @endhasanyrole
                        </div>
                        <div class="pull-right">
                            <a href="{{ route('logout') }}" class="btn btn-default btn-flat" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-1"></i>
                                {{ __('Logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            </li>
            @else
            <li class="nav-item">
                <a href="{{ route('login') }}" class="nav-link btn btn-outline-primary btn-sm px-3">
                    <i class="fas fa-sign-in-alt me-1"></i> {{ __('Login') }}
                </a>
            </li>
            @endauth
            @hasanyrole('admin|cityManager|gymManager|coach')
            <li class="nav-item d-none d-md-flex align-items-center ms-2">
                <button id="global-search-open" class="nav-link btn btn-link d-flex align-items-center px-3" 
                    style="border: 1px solid var(--border-color); background: var(--light-bg); border-radius: 8px; height: 38px; color: var(--text-secondary) !important;" 
                    title="{{ __('Search...') }} (Ctrl+K)">
                    <i class="fas fa-search me-2" style="font-size: 0.9rem;"></i>
                    <span style="font-size: 0.85rem; font-weight: 500;">{{ __('Search...') }}</span>
                    <span class="ms-3 badge bg-white text-muted border" style="font-size: 0.7rem; font-weight: 600;">
                        <span class="d-none d-lg-inline">Ctrl</span><span class="d-lg-none">⌘</span> K
                    </span>
                </button>
            </li>
            <li class="nav-item d-md-none">
                <button id="global-search-open-mobile" class="nav-link btn btn-link" style="border: none; background: transparent;" title="{{ __('Search') }}">
                    <i class="fas fa-search"></i>
                </button>
            </li>
            @endhasanyrole
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="langDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-globe me-2"></i>
                    <span class="d-none d-md-inline">{{ __('Language') }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm" aria-labelledby="langDropdown" style="border-radius: 12px;">
                    <li>
                        <a class="dropdown-item d-flex align-items-center py-2 {{ app()->getLocale() == 'fr' ? 'active bg-light text-primary' : '' }}" href="{{ route('lang.switch', 'fr') }}">
                            <span class="me-2">🇫🇷</span> Français
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center py-2 {{ app()->getLocale() == 'ar' ? 'active bg-light text-primary' : '' }}" href="{{ route('lang.switch', 'ar') }}">
                            <span class="me-2">🇲🇦</span> العربية
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center py-2 {{ app()->getLocale() == 'en' ? 'active bg-light text-primary' : '' }}" href="{{ route('lang.switch', 'en') }}">
                            <span class="me-2">🇺🇸</span> English
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <button id="dark-mode-toggle" class="nav-link btn btn-link" style="border: none; background: transparent;" title="{{ __('Dark Mode') }}">
                    <i class="fas fa-moon" id="dark-mode-icon"></i>
                </button>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button" title="{{ __('Fullscreen') }}">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Toast Notification Container (SweetAlert2 is used but this is a fallback placeholder if needed) -->
    <div id="toast-container" aria-live="polite" aria-atomic="true" style="position: fixed; top: 1rem; right: 1rem; z-index: 9999;"></div>

    @hasanyrole('admin|cityManager|gymManager|coach')
    <div id="global-search-overlay" class="global-search-overlay" hidden>
        <div class="global-search-backdrop"></div>
        <div class="global-search-modal" role="dialog" aria-modal="true" aria-label="Recherche">
            <div class="global-search-header">
                <input id="global-search-input" class="global-search-input" type="search" autocomplete="off" placeholder="Rechercher… (Ctrl+K) / بحث…">
                <div id="global-search-spinner" class="global-search-spinner"></div>
                <button id="global-search-close" class="global-search-close" type="button" aria-label="Fermer">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="global-search-results" class="global-search-results"></div>
        </div>
    </div>
    @endhasanyrole

    <!-- Persistence Script -->
    <script>
        (function() {
            const body = document.body;
            const toggleBtn = document.getElementById('dark-mode-toggle');
            const icon = document.getElementById('dark-mode-icon');
            
            // Sync initial state
            if (localStorage.getItem('theme') === 'dark' || 
                (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                body.classList.add('dark-mode');
                if (icon) {
                    icon.classList.remove('fa-moon');
                    icon.classList.add('fa-sun');
                }
            }

            if (toggleBtn) {
                toggleBtn.addEventListener('click', () => {
                    body.classList.toggle('dark-mode');
                    const isDark = body.classList.contains('dark-mode');
                    localStorage.setItem('theme', isDark ? 'dark' : 'light');
                    
                    if (icon) {
                        if (isDark) {
                            icon.classList.remove('fa-moon');
                            icon.classList.add('fa-sun');
                        } else {
                            icon.classList.remove('fa-sun');
                            icon.classList.add('fa-moon');
                        }
                    }

                    // Remove initial flash fix style if present
                    const flashFix = document.getElementById('theme-flash-fix');
                    if (flashFix) flashFix.remove();
                });
            }

            // PWA Service Worker Registration
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/service-worker.js')
                        .then(reg => console.log('SW Registered', reg))
                        .catch(err => console.log('SW Register Failed', err));
                });
            }
        })();
    </script>

    @hasanyrole('admin|cityManager|gymManager|coach')
    <script>
        (function() {
            // =========================
            // Sidebar Persistence
            // =========================
            const pushMenuBtn = document.querySelector('[data-widget="pushmenu"]');
            const body = document.body;
            
            // Apply saved state
            const savedSidebarState = localStorage.getItem('sidebar-state');
            if (savedSidebarState === 'collapsed') {
                body.classList.add('sidebar-collapse');
            }

            if (pushMenuBtn) {
                pushMenuBtn.addEventListener('click', function() {
                    // Slight delay to allow AdminLTE to toggle the class first, or just invert current state check
                    setTimeout(() => {
                        const isCollapsed = body.classList.contains('sidebar-collapse');
                        localStorage.setItem('sidebar-state', isCollapsed ? 'collapsed' : 'expanded');
                    }, 100);
                });
            }

            // =========================
            // Preloader Logic
            // =========================
            const loader = document.getElementById('global-loader');
            if (loader) {
                // Only show if loading takes more than 500ms
                const loadTimeout = setTimeout(() => {
                    if (document.readyState !== 'complete') {
                        loader.style.display = 'flex';
                        // Force reflow
                        void loader.offsetWidth; 
                        loader.classList.add('visible');
                    }
                }, 500);

                window.addEventListener('load', () => {
                    clearTimeout(loadTimeout);
                    if (loader.classList.contains('visible')) {
                        loader.classList.remove('visible');
                        setTimeout(() => {
                            loader.style.display = 'none';
                        }, 300);
                    } else {
                         loader.style.display = 'none'; // Ensure it's hidden if updated before 500ms
                    }
                });
            }
        })();

        // =========================
        // Global Search Logic
        // =========================
        (function () {
            const overlay = document.getElementById('global-search-overlay');
            const openBtn = document.getElementById('global-search-open');
            const openBtnMobile = document.getElementById('global-search-open-mobile');
            const closeBtn = document.getElementById('global-search-close');
            const input = document.getElementById('global-search-input');
            const resultsEl = document.getElementById('global-search-results');
            const spinner = document.getElementById('global-search-spinner');
            const backdrop = overlay ? overlay.querySelector('.global-search-backdrop') : null;
            const endpoint = @json(url('/global-search'));

            if (!overlay || !openBtn || !closeBtn || !input || !resultsEl || !backdrop) return;

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
                    // Don't clear immediately if we want to remember last search, 
                    // but standard behavior is usually fresh start or keep existing.
                    if (input.value.trim().length >= 2 && currentResults.length === 0) {
                        requestResults(input.value);
                    }
                } else {
                    // Optional: clear results on close
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
                const safeQuery = String(query).replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                const regex = new RegExp(`(${safeQuery})`, 'gi');
                return escapedText.replace(regex, '<span class="search-highlight">$1</span>');
            }

            function renderResults(results, query) {
                currentResults = results;
                selectedIndex = -1; // Reset selection
                
                if (!Array.isArray(results) || results.length === 0) {
                    resultsEl.innerHTML = '<div class="global-search-empty">Aucun résultat</div>';
                    return;
                }

                resultsEl.innerHTML = results.map((item, index) => {
                    const type = escapeHtml(item.type || '');
                    const titleRaw = item.title || '';
                    const subtitleRaw = item.subtitle || '';
                    const url = escapeAttr(item.url || '#');
                    
                    const titleTitle = highlightText(titleRaw, query);
                    const subtitleHtml = highlightText(subtitleRaw, query);

                    return `
                        <a class="global-search-item" href="${url}" data-index="${index}">
                            <div class="global-search-meta">${type}</div>
                            <div>
                                <p class="global-search-title">${titleTitle}</p>
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

                fetch(`${endpoint}?q=${encodeURIComponent(q)}`, {
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

            // Events
            if (openBtn) openBtn.addEventListener('click', () => setOpen(true));
            if (openBtnMobile) openBtnMobile.addEventListener('click', () => setOpen(true));
            closeBtn.addEventListener('click', () => setOpen(false));
            backdrop.addEventListener('click', () => setOpen(false));

            document.addEventListener('keydown', (e) => {
                const isMac = navigator.platform.toLowerCase().includes('mac');
                const metaOrCtrl = isMac ? e.metaKey : e.ctrlKey;
                
                // Toggle Search
                if (metaOrCtrl && (e.key === 'k' || e.key === 'K')) {
                    e.preventDefault();
                    setOpen(true);
                    return;
                }
                
                if (overlay.hidden) return;

                // Close on Escape
                if (e.key === 'Escape') {
                    e.preventDefault();
                    setOpen(false);
                    return;
                }

                // Keyboard Navigation
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
                selectedIndex = -1; // Reset selection on typing
                debounceRequest();
            });
            
            // Hover logic to sync with keyboard
            resultsEl.addEventListener('mousemove', (e) => {
                const item = e.target.closest('.global-search-item');
                if (item) {
                    const idx = parseInt(item.dataset.index);
                    if (!isNaN(idx)) {
                        selectedIndex = idx;
                        // We do not call updateSelection here to avoid fighting with scroll
                        // but we can add the class manualy
                        resultsEl.querySelectorAll('.global-search-item').forEach(el => el.classList.remove('focused'));
                        item.classList.add('focused');
                    }
                }
            });
        })();
    </script>
    @endhasanyrole
    <!-- Main Sidebar Container -->
    @role('admin|cityManager|gymManager|coach|user')

        <aside class="main-sidebar sidebar-dark-primary elevation-4 fs-14">
            <!-- Brand Logo -->
            <a href="{{ route('welcome') }}" class="brand-link">
                <i class="fas fa-dumbbell me-2"></i>
                <span class="brand-text font-weight-light">
                    {{ __('Gym System') }}
                </span>
            </a>
            <!-- Sidebar -->
            
            <!-- Sidebar Menu -->
            <nav class="mt-0">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    @hasanyrole('admin|cityManager|gymManager|coach')
                    {{-- # ======================================= # Dashboard # ======================================= # --}}
                    <li class="nav-item">
                        <a href="/" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-line text-indigo"></i>
                            <p>
                                {{ __('Dashboard') }}
                            </p>
                        </a>
                    </li>
                    @endhasanyrole

                    @hasanyrole('admin|cityManager|gymManager')
                    {{-- # ======================================= # Dépenses # ======================================= # --}}
                    <li class="nav-item">
                        <a href="{{ route('expenses.index') }}" class="nav-link {{ request()->routeIs('expenses.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-money-check-alt text-rose"></i>
                            <p>
                                {{ __('Expenses') }}
                            </p>
                        </a>
                    </li>
                    {{-- # ======================================= # Reports # ======================================= # --}}
                    <li class="nav-item">
                        <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-contract text-sky"></i>
                            <p>
                                {{ __('Reports') }}
                            </p>
                        </a>
                    </li>
                    @endhasanyrole
                    {{-- # ======================================= # Villes # ======================================= # --}}
                    @role('admin')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-city text-sky"></i>
                            <p>
                                {{ __('Cities') }}
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('city.list') }}" class="nav-link {{ request()->routeIs('city.list') ? 'active' : '' }}">
                                    <i class="fas fa-list nav-icon text-sky"></i>
                                    <p>{{ __('List') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('city.create') }}" class="nav-link">
                                    <i class="fas fa-plus-circle nav-icon text-sky"></i>
                                    <p>{{ __('Add') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('city.showDeleted') }}" class="nav-link">
                                    <i class="fas fa-trash-restore nav-icon text-sky"></i>
                                    <p>{{ __('Restore') }}</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    {{-- # ======================================= # Gestionnaires de ville # ======================================= # --}}
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-user-tie text-sky"></i>
                            <p>
                                {{ __('City Managers') }}
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('cityManager.list') }}" class="nav-link">
                                    <i class="fas fa-list nav-icon text-sky"></i>
                                    <p>{{ __('List') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('cityManager.create') }}" class="nav-link">
                                    <i class="fas fa-user-plus nav-icon text-sky"></i>
                                    <p>{{ __('Add') }}</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endrole
                    {{-- # ======================================= # Salles # ======================================= # --}}
                    @role('admin|cityManager')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-dumbbell text-amber"></i>
                            <p>
                                {{ __('Gyms') }}
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/gym/list" class="nav-link {{ request()->is('gym/list') ? 'active' : '' }}">
                                    <i class="fas fa-list nav-icon text-amber"></i>
                                    <p>{{ __('List') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/gym/create" class="nav-link">
                                    <i class="fas fa-plus-circle nav-icon text-amber"></i>
                                    <p>{{ __('Add') }}</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    {{-- # ======================================= # Gym Managers # ======================================= # --}}
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-user-cog text-amber"></i>
                            <p>
                                {{ __('Gym Managers') }}
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="gymManager/list" class="nav-link">
                                    <i class="fas fa-list nav-icon text-amber"></i>
                                    <p>{{ __('List') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="gymManager/create" class="nav-link">
                                    <i class="fas fa-user-plus nav-icon text-amber"></i>
                                    <p>{{ __('Add') }}</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endrole
                    {{-- # ======================================= # Caisses # ======================================= # --}}
                    @hasanyrole('admin|cityManager|gymManager')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cash-register text-emerald"></i>
                            <p>
                                {{ __('Cash Registers') }}
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('cash-registers.index') }}" class="nav-link {{ request()->routeIs('cash-registers.index') ? 'active' : '' }}">
                                    <i class="fas fa-list nav-icon text-emerald"></i>
                                    <p>{{ __('List') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('cash-registers.create') }}" class="nav-link {{ request()->routeIs('cash-registers.create') ? 'active' : '' }}">
                                    <i class="fas fa-plus-circle nav-icon text-emerald"></i>
                                    <p>{{ __('Add') }}</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endhasanyrole
                    {{-- # ======================================= # Coachs # ======================================= # --}}
                    @hasanyrole('admin|cityManager|gymManager')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-chalkboard-teacher text-purple"></i>
                            <p>
                                {{ __('Coaches') }}
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="coach/list" class="nav-link {{ request()->is('coach/list') ? 'active' : '' }}">
                                    <i class="fas fa-list nav-icon text-purple"></i>
                                    <p>{{ __('List') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="coach/create" class="nav-link">
                                    <i class="fas fa-user-plus nav-icon text-purple"></i>
                                    <p>{{ __('Add') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('coach.list') }}" class="nav-link">
                                    <i class="fas fa-chart-bar nav-icon text-purple"></i>
                                    <p>{{ __('Reports') }}</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endhasanyrole
                    {{-- # ======================================= # Athlètes # ======================================= # --}}
                    @hasanyrole('admin|cityManager|gymManager|coach')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-users text-teal"></i>
                            <p>
                                {{ __('Athletes') }}
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('allUsers.list') }}" class="nav-link {{ request()->routeIs('allUsers.list') ? 'active' : '' }}">
                                    <i class="fas fa-list nav-icon text-teal"></i>
                                    <p>{{ __('List') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('allUsers.stages') }}" class="nav-link {{ request()->routeIs('allUsers.stages') ? 'active' : '' }}">
                                    <i class="fas fa-graduation-cap nav-icon text-teal"></i>
                                    <p>{{ __('Internships') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('allUsers.create') }}" class="nav-link">
                                    <i class="fas fa-user-plus nav-icon text-teal"></i>
                                    <p>{{ __('Add') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('category.list') }}" class="nav-link">
                                    <i class="fas fa-tags nav-icon text-teal"></i>
                                    <p>{{ __('Categories') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('allUsers.list') }}" class="nav-link">
                                    <i class="fas fa-tasks nav-icon text-teal"></i>
                                    <p>{{ __('Tests') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('allUsers.list') }}" class="nav-link">
                                    <i class="fas fa-file-invoice nav-icon text-teal"></i>
                                    <p>{{ __('Reports') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('group.index') }}" class="nav-link {{ request()->routeIs('group.index') ? 'active' : '' }}">
                                    <i class="fas fa-users-cog nav-icon text-teal"></i>
                                    <p>{{ __('Groups') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('schedules.index') }}" class="nav-link {{ request()->routeIs('schedules.*') ? 'active' : '' }}">
                                    <i class="fas fa-calendar-alt nav-icon text-teal"></i>
                                    <p>{{ __('Schedule') }}</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endhasanyrole
                    {{-- # ======================================= # Paiement # ======================================= # --}}
                    @hasanyrole('admin|cityManager|gymManager')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-money-bill-wave text-emerald"></i>
                            <p>
                                {{ __('Payment') }}
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('PaymentPackage.stripe') }}" class="nav-link {{ request()->routeIs('PaymentPackage.stripe') ? 'active' : '' }}">
                                    <i class="fas fa-plus-circle nav-icon text-emerald"></i>
                                    <p>{{ __('Add') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('PaymentPackage.purchase_history') }}" class="nav-link {{ request()->routeIs('PaymentPackage.purchase_history') ? 'active' : '' }}">
                                    <i class="fas fa-file-invoice-dollar nav-icon text-emerald"></i>
                                    <p>{{ __('Invoicing') }}</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endhasanyrole
                    {{-- # ======================================= # Séances # ======================================= # --}}
                    
                    <li class="nav-item">
                        <a href="{{ route('schedules.index') }}" class="nav-link {{ request()->routeIs('schedules.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calendar-alt text-sky"></i>
                            <p>
                                {{ __('Schedules') }}
                            </p>
                        </a>
                    </li>
                   
                    {{-- # ======================================= # Cours # ======================================= # --}}
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-graduation-cap text-purple"></i>
                            <p>
                                {{ __('Courses') }}
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('course.list') }}" class="nav-link {{ request()->routeIs('course.list') ? 'active' : '' }}">
                                    <i class="fas fa-list nav-icon text-purple"></i>
                                    <p>{{ __('List') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('course.create') }}" class="nav-link">
                                    <i class="fas fa-plus-circle nav-icon text-purple"></i>
                                    <p>{{ __('Add') }}</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    {{-- # ======================================= # Ceintures # ======================================= # --}}
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-award text-amber"></i>
                            <p>
                                {{ __('Belts') }}
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('belt.list') }}" class="nav-link {{ request()->routeIs('belt.list') ? 'active' : '' }}">
                                    <i class="fas fa-list nav-icon text-amber"></i>
                                    <p>{{ __('List') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('belt.create') }}" class="nav-link">
                                    <i class="fas fa-plus-circle nav-icon text-amber"></i>
                                    <p>{{ __('Add') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('belt.grading') }}" class="nav-link {{ request()->routeIs('belt.grading') ? 'active' : '' }}">
                                    <i class="fas fa-graduation-cap nav-icon text-amber"></i>
                                    <p>{{ __('Grading') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('belt.notes.index') }}" class="nav-link {{ request()->routeIs('belt.notes.*') ? 'active' : '' }}">
                                    <i class="fas fa-sticky-note nav-icon text-amber"></i>
                                    <p>{{ __('Notes') }}</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    {{-- # ======================================= # Participation # ======================================= # --}}
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-user-check text-success"></i>
                            <p>
                                {{ __('Participation') }}
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('participation.list') }}" class="nav-link {{ request()->routeIs('participation.list') ? 'active' : '' }}">
                                    <i class="fas fa-list nav-icon text-success"></i>
                                    <p>{{ __('List') }}</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    {{-- # ======================================= # Événements # ======================================= # --}}
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-calendar-check text-danger"></i>
                            <p>
                                {{ __('Events') }}
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('events.index') }}" class="nav-link {{ request()->routeIs('events.index') ? 'active' : '' }}">
                                    <i class="fas fa-calendar nav-icon text-danger"></i>
                                    <p>{{ __('All Events') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('combats.index') }}" class="nav-link {{ request()->routeIs('combats.*') ? 'active' : '' }}">
                                    <i class="fas fa-hand-rock nav-icon text-danger"></i>
                                    <p>{{ __('Combats') }}</p>
                                </a>
                            </li>
                            @role('admin')
                            <li class="nav-item">
                                <a href="{{ route('events.create') }}" class="nav-link">
                                    <i class="fas fa-plus-circle nav-icon text-danger"></i>
                                    <p>{{ __('Add') }}</p>
                                </a>
                            </li>
                            @endrole
                        </ul>
                    </li>
                    {{-- # ======================================= # Présence # ======================================= # --}}
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-clipboard-list text-teal"></i>
                            <p>
                                {{ __('Attendance') }}
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('course-attendance.index') }}" class="nav-link {{ request()->routeIs('course-attendance.index') ? 'active' : '' }}">
                                    <i class="fas fa-user-check nav-icon text-teal"></i>
                                    <p>{{ __('Course Attendance') }}</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-header"><i class="fas fa-vials mr-1"></i> {{ __('Tests') }}</li>
                    <li class="nav-item">
                        <a href="{{ route('athlete-tests.index') }}" class="nav-link {{ request()->routeIs('athlete-tests.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-microscope text-indigo"></i>
                            <p>
                                {{ __('Athlete Tests') }}
                            </p>
                        </a>
                    </li>
                    {{-- # ======================================= # Utilisateurs Bannis # ======================================= # --}}
                    @role('admin')
                    <li class="nav-item">
                        <a href="{{ route('user.listBanned') }}" class="nav-link">
                            <i class="nav-icon fas fa-user-slash text-danger"></i>
                            <p>
                                {{ __('Banned Users') }}
                            </p>
                        </a>
                    </li>
                    @endrole
                    {{-- # ======================================= # Administration # ======================================= # --}}
                    @role('admin')
                    <li class="nav-header"><i class="fas fa-shield-alt mr-1"></i> {{ __('Administration') }}</li>
                    <li class="nav-item">
                        <a href="{{ route('activity-logs.index') }}" class="nav-link {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-history text-info"></i>
                            <p>
                                {{ __('Activity Logs') }}
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cogs text-purple"></i>
                            <p>
                                {{ __('Settings') }}
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.index') ? 'active' : '' }}">
                                    <i class="fas fa-palette nav-icon text-purple"></i>
                                    <p>{{ __('Appearance') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('settings.custom-folders.index') }}" class="nav-link {{ request()->routeIs('settings.custom-folders.*') ? 'active' : '' }}">
                                    <i class="fas fa-folder-open nav-icon text-purple"></i>
                                    <p>{{ __('Custom folders') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('roles.index') }}" class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                                    <i class="fas fa-shield-alt nav-icon text-purple"></i>
                                    <p>{{ __('Roles & Permissions') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('moderators.index') }}" class="nav-link {{ request()->routeIs('moderators.*') ? 'active' : '' }}">
                                    <i class="fas fa-user-shield nav-icon text-purple"></i>
                                    <p>{{ __('Moderation Accounts') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('expense-categories.index') }}" class="nav-link {{ request()->routeIs('expense-categories.*') ? 'active' : '' }}">
                                    <i class="fas fa-folder nav-icon text-purple"></i>
                                    <p>{{ __('Expense Categories') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('trainingPackeges.listPackeges') }}" class="nav-link {{ request()->routeIs('trainingPackeges.*') ? 'active' : '' }}">
                                    <i class="fas fa-box nav-icon text-purple"></i>
                                    <p>{{ __('Packages') }}</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endrole

                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
    @endrole
    @yield('content')
    <div id="sidebar-overlay"></div>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <footer class="main-footer d-flex justify-content-center fs-13">
        <span>Copyright &copy; 2025 <span class="bg-primary px-2 py-1">MouradBenYahya</span></span> 
        <span class="ms-2">{{ __('All rights reserved.') }}</span>
    </footer>
    <!-- jQuery -->
    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script>
    $.widget.bridge('uibutton', $.ui.button)
    </script>
    <script src="{{ asset('plugins/sparklines/sparkline.js') }}"></script>
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('dist/js/adminlte.js') }}"></script>
    <script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('dist/js/main.js') }}"></script>
    <script src="{{ asset('js/bilingual.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
    $(function() {
        $("#proj").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#proj_wrapper .col-md-6:eq(0)');
    });
    </script>
    @yield('dataTableScript')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    
    <!-- Flash Messages (Toasts) -->
    <script>
        $(function() {
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            @if(session('success'))
                Toast.fire({
                    icon: 'success',
                    title: '{{ session('success') }}'
                });
            @endif

            @if(session('error'))
                Toast.fire({
                    icon: 'error',
                    title: '{{ session('error') }}'
                });
            @endif

            @if(session('warning'))
                Toast.fire({
                    icon: 'warning',
                    title: '{{ session('warning') }}'
                });
            @endif

            @if(session('info'))
                Toast.fire({
                    icon: 'info',
                    title: '{{ session('info') }}'
                });
            @endif
            
            // Allow triggering via window event
            window.addEventListener('flash-message', event => {
                Toast.fire({
                    icon: event.detail.type || 'info',
                    title: event.detail.message
                });
            });
        });
    </script>
    <!-- Global Keyboard Shortcuts & UX Logic -->
    <script>
        $(function() {
            // 0. Hide Page Loader
            const $pageLoader = $('#pageLoader');
            if ($pageLoader.length) {
                setTimeout(() => {
                    $pageLoader.addClass('fade-out');
                    setTimeout(() => $pageLoader.remove(), 500);
                }, 300);
            }

            // =========================
            // UX Features Initialization
            // =========================
            
            // 1. Initialize Tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // 1.5 Dark Mode Toggle Logic
            const $darkModeToggle = $('#dark-mode-toggle');
            const $darkIcon = $('.dark-mode-icon');
            const $lightIcon = $('.light-mode-icon');
            
            function updateDarkModeIcons(isDark) {
                if (isDark) {
                    $darkIcon.hide();
                    $lightIcon.show();
                } else {
                    $darkIcon.show();
                    $lightIcon.hide();
                }
            }

            // Initial icon state
            updateDarkModeIcons(document.documentElement.classList.contains('dark-mode'));

            $darkModeToggle.on('click', function() {
                const isDark = document.documentElement.classList.toggle('dark-mode');
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
                updateDarkModeIcons(isDark);
                
                // Dispatch event for other components (like charts) to react
                window.dispatchEvent(new CustomEvent('theme-changed', { detail: { theme: isDark ? 'dark' : 'light' } }));
                
                // Show toast
                window.dispatchEvent(new CustomEvent('flash-message', {
                    detail: {
                        type: 'info',
                        message: isDark ? '{{ __('Dark Mode Activated') }}' : '{{ __('Light Mode Activated') }}'
                    }
                }));
            });

            // 2. Scroll Progress Bar
            $(window).on('scroll', function() {
                var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
                var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                var scrolled = (winScroll / height) * 100;
                $('#scrollProgress').css('width', scrolled + "%");
                
                // 3. Back to Top visibility
                if (winScroll > 300) {
                    $('#backToTop').addClass('visible');
                } else {
                    $('#backToTop').removeClass('visible');
                }
            });

            // 4. Back to Top Click
            $('#backToTop').on('click', function() {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });

            // 5. Global Keyboard Shortcuts
            const showShortcutToast = (key, desc) => {
                const toastId = 'shortcut-toast-' + Date.now();
                const toastHtml = `
                    <div id="${toastId}" class="position-fixed bottom-0 start-50 translate-middle-x mb-4 animate-fadeIn" style="z-index: 9999; pointer-events: none;">
                        <div class="bg-dark text-white px-3 py-2 rounded-pill shadow-lg d-flex align-items-center gap-2" style="opacity: 0.9;">
                            <span class="badge bg-primary text-white rounded-sm fw-bold px-2" style="font-size: 0.9rem; min-width: 25px; border-bottom: 2px solid rgba(0,0,0,0.2);">${key.toUpperCase()}</span>
                            <span class="small fw-medium">${desc}</span>
                        </div>
                    </div>
                `;
                $('body').append(toastHtml);
                setTimeout(() => {
                    $(`#${toastId}`).fadeOut(300, function() { $(this).remove(); });
                }, 1500);
            };

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

                // P: Print or Export PDF
                if (key === 'p') {
                    // Check for specialized print buttons first
                    const pdfBtn = $('a[href*="export-pdf"], .btn-print, .btn-export-pdf').first();
                    if (pdfBtn.length) {
                        e.preventDefault();
                        showShortcutToast('P', '{{ __('Export PDF') }}...');
                        window.location.href = pdfBtn.attr('href');
                    } else {
                        e.preventDefault();
                        showShortcutToast('P', '{{ __('Printing') }}...');
                        window.print();
                    }
                }

                // F: Focus Search
                if (key === 'f') {
                    const searchInput = $('#participantSearch, input[name="month"], #searchInput, .dataTables_filter input, input[type="search"]').first();
                    if (searchInput.length) {
                        e.preventDefault();
                        showShortcutToast('F', '{{ __('Search') }}');
                        searchInput.focus().select();
                    }
                }

                // B: Back
                if (key === 'b') {
                    const backBtn = $('.btn-back, a:contains("Retour"), a:contains("Back")').first();
                    if (backBtn.length && backBtn.attr('href') !== '#') {
                        e.preventDefault();
                        showShortcutToast('B', '{{ __('Back') }}');
                        window.location.href = backBtn.attr('href');
                    } else {
                        e.preventDefault();
                        showShortcutToast('B', '{{ __('Back') }}');
                        window.history.back();
                    }
                }

                // H: Home / Dashboard
                if (key === 'h') {
                    e.preventDefault();
                    showShortcutToast('H', '{{ __('Dashboard') }}');
                    window.location.href = "{{ url('/') }}";
                }

                // K: Cours
                if (key === 'k') {
                    e.preventDefault();
                    showShortcutToast('K', '{{ __('Courses') }}');
                    window.location.href = "{{ url('/cours') }}";
                }

                // T: Stripe Payment
                if (key === 't') {
                    e.preventDefault();
                    showShortcutToast('T', '{{ __('Payment') }}');
                    window.location.href = "{{ route('PaymentPackage.stripe') }}";
                }

                // A: Athlètes
                if (key === 'a') {
                    e.preventDefault();
                    showShortcutToast('A', '{{ __('Athletes') }}');
                    window.location.href = "{{ route('allUsers.list') }}";
                }

                // G: Gyms
                if (key === 'g') {
                    e.preventDefault();
                    showShortcutToast('G', '{{ __('Gyms') }}');
                    window.location.href = "{{ url('/gym/list') }}";
                }

                // O: Coachs
                if (key === 'o') {
                    e.preventDefault();
                    showShortcutToast('O', '{{ __('Coaches') }}');
                    window.location.href = "{{ url('/coach/list') }}";
                }

                // I: Rapports
                 if (key === 'i') {
                     e.preventDefault();
                     showShortcutToast('I', '{{ __('Reports') }}');
                     window.location.href = "{{ route('reports.index') }}";
                 }

                 // D: Dépenses
                 if (key === 'd') {
                     e.preventDefault();
                     showShortcutToast('D', '{{ __('Expenses') }}');
                     window.location.href = "{{ route('expenses.index') }}";
                 }

                 // V: Villes
                 if (key === 'v') {
                     e.preventDefault();
                     showShortcutToast('V', '{{ __('Cities') }}');
                     window.location.href = "{{ route('city.list') }}";
                 }

                 // N: Planning
                 if (key === 'n') {
                     e.preventDefault();
                     showShortcutToast('N', '{{ __('Schedule') }}');
                     window.location.href = "{{ route('schedules.index') }}";
                 }

                 // J: Journal / Facturation
                 if (key === 'j') {
                     e.preventDefault();
                     showShortcutToast('J', '{{ __('Invoicing') }}');
                     window.location.href = "{{ route('PaymentPackage.purchase_history') }}";
                 }

                 // Z: Toggle Dark Mode
                 if (key === 'z') {
                     e.preventDefault();
                     showShortcutToast('Z', '{{ __('Dark Mode') }}');
                     $('#dark-mode-toggle').click();
                 }

                // R: Register / Create (context dependent)
                if (key === 'r') {
                    const createBtn = $('a[href*="/create"], a[href*="/register"], .btn-create, .btn-add').first();
                    if (createBtn.length) {
                        e.preventDefault();
                        showShortcutToast('R', '{{ __('New') }}');
                        window.location.href = createBtn.attr('href');
                    }
                }

                // S: Save / Submit Form
                if (key === 's') {
                    const submitBtn = $('form button[type="submit"], form input[type="submit"], .btn-save').first();
                    if (submitBtn.length && !submitBtn.prop('disabled')) {
                        e.preventDefault();
                        showShortcutToast('S', '{{ __('Save') }}');
                        submitBtn.click();
                    }
                }

                // E: Edit (context dependent)
                if (key === 'e') {
                    const editBtn = $('a[href*="/edit"], .btn-edit').first();
                    if (editBtn.length) {
                        e.preventDefault();
                        showShortcutToast('E', '{{ __('Edit') }}');
                        window.location.href = editBtn.attr('href');
                    }
                }

                // L: List / All
                if (key === 'l') {
                    const listBtn = $('a[href$="/list"], a[href$="/index"], .btn-list, a:contains("Liste"), a:contains("All")').first();
                    if (listBtn.length) {
                        e.preventDefault();
                        showShortcutToast('L', '{{ __('List') }}');
                        window.location.href = listBtn.attr('href');
                    }
                }

                // M: Menu Toggle (Sidebar)
                if (key === 'm') {
                    e.preventDefault();
                    showShortcutToast('M', '{{ __('Menu') }}');
                    $('[data-widget="pushmenu"]').first().click();
                }

                // C: Cancel / Close Modal
                if (key === 'c') {
                    const activeModal = $('.modal.show');
                    if (activeModal.length) {
                        e.preventDefault();
                        showShortcutToast('C', '{{ __('Close') }}');
                        const modalInstance = bootstrap.Modal.getInstance(activeModal[0]);
                        if (modalInstance) {
                            modalInstance.hide();
                        } else {
                            activeModal.modal('hide');
                        }
                    } else {
                        const cancelBtn = $('.btn-cancel, a:contains("Annuler"), a:contains("Cancel")').first();
                        if (cancelBtn.length && cancelBtn.attr('href') !== '#') {
                            e.preventDefault();
                            showShortcutToast('C', '{{ __('Cancel') }}');
                            window.location.href = cancelBtn.attr('href');
                        }
                    }
                }

                // ?: Help Modal (Shift + /)
                if (e.key === '?' || e.key === '/') {
                    // Check if it's really the question mark (depends on keyboard layout, but usually e.key is enough)
                    if (e.key === '?' || (e.key === '/' && e.shiftKey)) {
                        e.preventDefault();
                        const helpModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('shortcutsHelpModal'));
                        helpModal.show();
                    }
                }
            });

            // 6. Global Copy to Clipboard Helper
            window.copyToClipboard = function(text, element) {
                navigator.clipboard.writeText(text).then(() => {
                    // Use the central Toast system
                    window.dispatchEvent(new CustomEvent('flash-message', {
                        detail: {
                            type: 'success',
                            message: '{{ __('Copied to clipboard!') }}'
                        }
                    }));
                    
                    // Add a quick visual feedback on the element itself
                    const $el = $(element);
                    const originalHtml = $el.html();
                    $el.html('<i class="fas fa-check"></i>');
                    setTimeout(() => $el.html(originalHtml), 2000);
                }).catch(err => {
                    window.dispatchEvent(new CustomEvent('flash-message', {
                        detail: {
                            type: 'error',
                            message: '{{ __('Error during copy') }}'
                        }
                    }));
                });
            };

            // 7. Auto-sticky headers for tables with class .modern-table
            $('.modern-table').each(function() {
                if ($(this).find('thead').length) {
                    $(this).addClass('sticky-header');
                }
            });

            // 8. Auto-focus first input in modals
            $(document).on('shown.bs.modal', '.modal', function () {
                $(this).find('input:not([type="hidden"]), select, textarea').first().focus();
            });

            // 9. Loading state for buttons on form submission
            $(document).on('submit', 'form', function() {
                const submitBtn = $(this).find('button[type="submit"]');
                if (submitBtn.length && !submitBtn.hasClass('no-loader')) {
                    submitBtn.addClass('btn-loading').prop('disabled', true);
                    // Fallback to enable if something goes wrong (e.g. validation fails client-side)
                    setTimeout(() => {
                        if (submitBtn.hasClass('btn-loading')) {
                            submitBtn.removeClass('btn-loading').prop('disabled', false);
                        }
                    }, 10000);
                }
            });

            // 10. Re-initialize tooltips for dynamic content
            $(document).ajaxComplete(function() {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    if (!bootstrap.Tooltip.getInstance(tooltipTriggerEl)) {
                        new bootstrap.Tooltip(tooltipTriggerEl);
                    }
                });
            });
        });
    </script>
    @hasanyrole('admin|cityManager|gymManager')
    <!-- Quick Actions Floating Menu -->
    <div class="quick-actions-container" id="quickActionsContainer">
        <div class="quick-actions-menu" id="quickActionsMenu">
            <a href="{{ route('attendance.create') }}" class="quick-action-item" title="{{ __('Mark Attendance') }} / تسجيل الحضور">
                <span class="quick-action-label">{{ __('Mark Attendance') }} / تسجيل الحضور</span>
                <div class="quick-action-btn success"><i class="fas fa-check-circle"></i></div>
            </a>
            <a href="{{ route('expenses.create') }}" class="quick-action-item" title="{{ __('Record Expense') }} / تسجيل مصاريف">
                <span class="quick-action-label">{{ __('Record Expense') }} / تسجيل مصاريف</span>
                <div class="quick-action-btn warning"><i class="fas fa-file-invoice-dollar"></i></div>
            </a>
            <a href="{{ route('allUsers.create') }}" class="quick-action-item" title="{{ __('New Registration') }} / تسجيل جديد">
                <span class="quick-action-label">{{ __('New Registration') }} / تسجيل جديد</span>
                <div class="quick-action-btn primary"><i class="fas fa-user-plus"></i></div>
            </a>
        </div>
        <button class="fab-main" id="fabMain" title="{{ __('Quick Actions') }} / إجراءات سريعة">
            <i class="fas fa-plus fab-icon"></i>
        </button>
    </div>

    <!-- Intro.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/intro.js@7.2.0/intro.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Quick Actions Toggle
        const fabMain = document.getElementById('fabMain');
        const quickActionsContainer = document.getElementById('quickActionsContainer');
        
        if (fabMain && quickActionsContainer) {
            fabMain.addEventListener('click', function() {
                quickActionsContainer.classList.toggle('active');
                const icon = fabMain.querySelector('.fab-icon');
                if (quickActionsContainer.classList.contains('active')) {
                    icon.classList.remove('fa-plus');
                    icon.classList.add('fa-times');
                } else {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-plus');
                }
            });

            const quickActionLinks = quickActionsContainer.querySelectorAll('.quick-action-item[href]');
            quickActionLinks.forEach((link) => {
                link.addEventListener('click', (event) => {
                    const href = link.getAttribute('href');
                    if (!href || href === '#') return;
                    if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey || event.button !== 0) return;
                    event.preventDefault();
                    window.location.assign(href);
                }, true);
            });

            // Close when clicking outside
            document.addEventListener('click', function(event) {
                if (!quickActionsContainer.contains(event.target)) {
                    quickActionsContainer.classList.remove('active');
                    const icon = fabMain.querySelector('.fab-icon');
                    if (icon) {
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-plus');
                    }
                }
            });
        }

        // Product Tour (Intro.js)
        const startTour = () => {
            const intro = introJs();
            intro.setOptions({
                nextLabel: '{{ __('Next') }} / التالي',
                prevLabel: '{{ __('Previous') }} / السابق',
                doneLabel: '{{ __('Done') }} / تم',
                dontShowAgain: true,
                steps: [
                    {
                        title: '{{ __('Welcome!') }} 👋',
                        intro: "{{ __('Welcome to your new management dashboard. Let us show you the key features.') }} / مرحبًا بك في لوحة التحكم الجديدة. دعنا نوضح لك الميزات الرئيسية."
                    },
                    {
                        element: document.querySelector('.main-sidebar'),
                        intro: "{{ __('Here you will find all your navigation menus to manage members, gyms, and coaches.') }} / ستجد هنا جميع قوائم التنقل لإدارة الأعضاء والقاعات والمدربين.",
                        position: 'right'
                    },
                    {
                        element: document.querySelector('#global-search-open'),
                        intro: "{{ __('Use global search (Ctrl+K) to quickly find any member or information.') }} / استخدم البحث الشامل (Ctrl+K) للعثور بسرعة على أي عضو أو معلومة.",
                        position: 'bottom'
                    },
                    {
                        element: document.querySelector('#fabMain'),
                        intro: "{{ __('New! Quickly access frequent tasks like attendance or registrations via this floating button.') }} / جديد! يمكنك الوصول بسرعة إلى المهام المتكررة مثل الحضور أو التسجيلات عبر هذا الزر العائم.",
                        position: 'left'
                    },
                    {
                        element: document.querySelector('.quick-actions-grid'),
                        intro: "{{ __('View your key indicators and access the new interactive calendar here.') }} / عرض مؤشراتك الرئيسية والوصول إلى التقويم التفاعلي الجديد هنا.",
                        position: 'bottom'
                    },
                    {
                        element: document.querySelector('.alert-modern.alert-info'),
                        intro: "{{ __('Keep an eye on memberships expiring soon to follow up with your members.') }} / راقب الاشتراكات التي ستنتهي صلاحيتها قريبًا لمتابعة أعضائك.",
                        position: 'bottom'
                    },
                    {
                        element: document.querySelector('.card-header h5')?.closest('.card'),
                        intro: "{{ __('Consult your daily priorities tailored to your role (daily sessions for coaches, unpaid for managers).') }} / استشر أولوياتك اليومية المصممة خصيصًا لدورك (جلسات يومية للمدربين، غير مدفوعة للمديرين).",
                        position: 'top'
                    },
                    {
                        element: document.querySelector('#peakHoursChart')?.closest('.chart-card'),
                        intro: "{{ __('Analyze your gym\'s peak hours to optimize your schedules.') }} / قم بتحليل ساعات الذروة في قاعتك لتحسين جداولك.",
                        position: 'top'
                    },
                    {
                        element: document.querySelector('.user-menu'),
                        intro: "{{ __('Manage your profile and log out securely from here.') }} / قم بإدارة ملفك الشخصي وتسجيل الخروج بأمان من هنا.",
                        position: 'bottom'
                    }
                ]
            });
            
            intro.onbeforechange(function(element) {
                // S'assurer que les éléments sont visibles avant de les cibler
                if (element && element.id === 'fabMain') {
                    // Optionnel: On pourrait ouvrir le FAB ici si nécessaire
                }
            });

            intro.start();
        };

        // Auto-start tour for new users (check localStorage)
        if (!localStorage.getItem('tour_completed')) {
            setTimeout(startTour, 2000);
            localStorage.setItem('tour_completed', 'true');
        }

        // Add a way to restart the tour (could be a button in profile or footer)
        window.restartTour = startTour;
    });
    </script>
    @endhasanyrole

    @stack('scripts')
</body>
@yield('stripeScript')

</html>
