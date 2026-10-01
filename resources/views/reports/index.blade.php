@extends('layouts.user-layout')
@section('content')
<div class="content-wrapper">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');
        
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --primary-light: #818cf8;
            --secondary: #0ea5e9;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --surface: #ffffff;
            --surface-alt: #f8fafc;
            --border: #e2e8f0;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.07), 0 2px 4px -2px rgb(0 0 0 / 0.05);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.08), 0 4px 6px -4px rgb(0 0 0 / 0.05);
            --radius: 12px;
            --radius-lg: 16px;
        }
        
        .content-wrapper {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #f0f4ff 0%, #faf5ff 50%, #f0fdfa 100%);
            min-height: 100vh;
            padding: 0;
        }
        
        /* Premium Header Section */
        .reports-header {
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 50%, #06b6d4 100%);
            padding: 3rem 2rem 7rem;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .header-shapes {
            position: absolute;
            inset: 0;
            overflow: hidden;
            z-index: 0;
            pointer-events: none;
        }
        
        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            border-radius: 50%;
            animation: float 20s infinite ease-in-out alternate;
        }
        
        .shape-1 { width: 400px; height: 400px; top: -150px; right: -100px; background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%); }
        .shape-2 { width: 500px; height: 500px; bottom: -250px; left: -150px; background: radial-gradient(circle, rgba(139,92,246,0.3) 0%, transparent 70%); animation-delay: -5s; }
        .shape-3 { width: 200px; height: 200px; top: 10%; left: 40%; background: radial-gradient(circle, rgba(56,189,248,0.3) 0%, transparent 70%); animation-delay: -10s; }
        
        @keyframes float {
            0% { transform: translateY(0) scale(1) rotate(0deg); }
            50% { transform: translateY(-20px) scale(1.05) rotate(5deg); }
            100% { transform: translateY(0) scale(1) rotate(-5deg); }
        }
        
        .reports-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 80px;
            background: linear-gradient(135deg, #f0f4ff 0%, #faf5ff 50%, #f0fdfa 100%);
            border-radius: 40px 40px 0 0;
            z-index: 1;
        }
        
        .header-content {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .glass-panel {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 1.5rem;
            padding: 1.5rem 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            width: 100%;
        }
        
        .header-icon-wrapper {
            background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(255,255,255,0.7));
            width: 64px;
            height: 64px;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--primary);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            transform: rotate(-5deg);
            transition: all 0.3s ease;
        }
        
        .glass-panel:hover .header-icon-wrapper {
            transform: rotate(0) scale(1.05);
        }
        
        .header-text {
            flex: 1;
            min-width: 250px;
        }
        
        .header-text h1 {
            color: #ffffff;
            font-size: 2.25rem;
            font-weight: 800;
            margin: 0 0 0.25rem 0;
            letter-spacing: -0.025em;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .header-text p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.05rem;
            margin: 0;
            font-weight: 500;
        }
        
        .header-stats-mini {
            display: flex;
            gap: 1.25rem;
            background: rgba(0, 0, 0, 0.15);
            padding: 0.75rem 1.25rem;
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .stat-mini {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }
        
        .stat-mini:not(:last-child) {
            padding-right: 1.25rem;
            border-right: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .stat-mini-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 600;
        }
        
        .stat-mini-value {
            font-size: 0.95rem;
            font-weight: 700;
            color: white;
            font-family: 'JetBrains Mono', monospace;
        }
        
        .text-success-light { color: #6ee7b7 !important; }
        
        @media (max-width: 768px) {
            .glass-panel {
                flex-direction: column;
                align-items: flex-start;
                gap: 1.5rem;
            }
            .header-stats-mini { width: 100%; justify-content: space-between; }
            .header-text h1 { font-size: 1.75rem; }
        }
        
        /* Main Content */
        .reports-content {
            padding: 0 2rem 2rem;
            margin-top: -4rem;
            position: relative;
            z-index: 2;
        }
        
        /* Filter Card */
        .filter-card {
            background: var(--surface);
            border-radius: var(--radius-lg);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        
        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            position: relative;
        }
        
        .filter-group label {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .filter-group input,
        .filter-group select {
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-primary);
            background: #f8fafc;
            transition: all 0.25s ease;
            appearance: none;
            -webkit-appearance: none;
        }
        
        .filter-group select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1.25rem;
            padding-right: 2.5rem;
        }
        
        .filter-group input:hover,
        .filter-group select:hover {
            border-color: #cbd5e1;
            background: #ffffff;
        }
        
        .filter-group input:focus,
        .filter-group select:focus {
            outline: none;
            border-color: var(--primary);
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }
        
        .filter-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1.25rem;
            padding-top: 1.5rem;
            border-top: 1px dashed var(--border);
        }
        
        .btn-apply {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 0.75rem 1.75rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2), 0 2px 4px -1px rgba(79, 70, 229, 0.1);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-apply:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3), 0 4px 6px -2px rgba(79, 70, 229, 0.15);
            background: var(--primary-dark);
        }
        
        .export-btns {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }
        
        .btn-export {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            border: 1px solid var(--border);
            background: var(--surface);
            color: var(--text-secondary);
            cursor: pointer;
        }
        
        .btn-export:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        
        .btn-export.excel:hover { border-color: #10b981; color: #10b981; background: #f0fdf4; }
        .btn-export.pdf:hover { border-color: #ef4444; color: #ef4444; background: #fef2f2; }
        
        /* Toggle Section */
        .toggle-section {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1.25rem;
            margin-top: 1.5rem;
            border: 1px solid #e2e8f0;
        }
        
        .toggle-section h6 {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .toggle-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 1.25rem;
        }
        
        .toggle-item {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.75rem;
            background: #ffffff;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            transition: all 0.2s ease;
        }
        
        .toggle-item:hover {
            border-color: var(--primary);
        }
        
        .toggle-switch {
            position: relative;
            width: 40px;
            height: 22px;
        }
        
        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .toggle-slider {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background: #cbd5e1;
            border-radius: 20px;
            transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .toggle-slider::before {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            left: 3px;
            bottom: 3px;
            background: #fff;
            border-radius: 50%;
            transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .toggle-switch input:checked + .toggle-slider {
            background: var(--primary);
        }
        
        .toggle-switch input:checked + .toggle-slider::before {
            transform: translateX(18px);
        }
        
        .toggle-label {
            font-size: 0.85rem;
            color: var(--text-primary);
            font-weight: 600;
            user-select: none;
            cursor: pointer;
        }
        
        /* Active Filters */
        .active-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        
        .filter-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.1) 0%, rgba(124, 58, 237, 0.1) 100%);
            color: var(--primary);
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        /* Stats Summary */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        @media (max-width: 1200px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
        
        @media (max-width: 640px) {
            .stats-grid { grid-template-columns: 1fr; }
        }
        
        .stat-card {
            background: var(--surface);
            border-radius: var(--radius);
            padding: 1.25rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            transition: all 0.2s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .stat-card.primary { border-left: 4px solid var(--primary); }
        .stat-card.secondary { border-left: 4px solid var(--secondary); }
        .stat-card.danger { border-left: 4px solid var(--danger); }
        .stat-card.success { border-left: 4px solid var(--success); }
        
        .stat-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }
        
        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            font-family: 'JetBrains Mono', monospace;
        }
        
        .stat-card.primary .stat-value { color: var(--primary); }
        .stat-card.secondary .stat-value { color: var(--secondary); }
        .stat-card.danger .stat-value { color: var(--danger); }
        .stat-card.success .stat-value { color: var(--success); }
        
        /* Summary Card */
        .summary-card {
            background: var(--surface);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }
        
        .summary-header {
            background: linear-gradient(135deg, var(--surface-alt) 0%, #fff 100%);
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .summary-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .summary-title-ar {
            font-size: 0.875rem;
            color: var(--text-muted);
            font-weight: 400;
        }
        
        .summary-body {
            padding: 1.25rem 1.5rem;
        }
        
        .summary-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 0.625rem;
        }
        
        .summary-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.5rem 0.875rem;
            background: var(--surface-alt);
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 0.8125rem;
            color: var(--text-secondary);
        }
        
        .summary-badge strong {
            color: var(--text-primary);
            font-weight: 600;
        }
        
        /* Data Cards */
        .data-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        @media (max-width: 1024px) {
            .data-grid { grid-template-columns: 1fr; }
        }
        
        .data-card {
            background: var(--surface);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            overflow: hidden;
            transition: all 0.2s ease;
        }
        
        .data-card:hover {
            box-shadow: var(--shadow-lg);
        }
        
        .data-card-header {
            background: linear-gradient(135deg, var(--primary) 0%, #7c3aed 100%);
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .data-card-title {
            font-size: 0.9375rem;
            font-weight: 600;
            color: #fff;
        }
        
        .data-card-body {
            padding: 0;
        }

        .data-card-body .dataTables_wrapper {
            padding: 1rem;
        }

        .data-card-body .dataTables_wrapper .data-table {
            margin-top: 0.75rem;
        }
        
        /* Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .data-table thead {
            background: var(--surface-alt);
        }
        
        .data-table th {
            padding: 0.875rem 1rem;
            text-align: left;
            font-size: 0.6875rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--border);
        }
        
        .data-table td {
            padding: 0.875rem 1rem;
            font-size: 0.875rem;
            color: var(--text-primary);
            border-bottom: 1px solid var(--border);
        }
        
        .data-table tbody tr {
            transition: background 0.15s ease;
        }
        
        .data-table tbody tr:hover {
            background: var(--surface-alt);
        }
        
        .data-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .data-table tfoot {
            background: linear-gradient(135deg, var(--surface-alt) 0%, #fff 100%);
        }
        
        .data-table tfoot th {
            padding: 1rem;
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--text-primary);
            border-top: 2px solid var(--border);
            text-transform: none;
            letter-spacing: normal;
        }
        
        .text-mono {
            font-family: 'JetBrains Mono', monospace;
        }
        
        .empty-state {
            text-align: center;
            padding: 2rem;
            color: var(--text-muted);
            font-size: 0.875rem;
        }
        
        /* Status Badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.625rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .status-badge.paid {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
        }
        
        .status-badge.due {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }
        
        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
        
        .loading-content {
            text-align: center;
        }
        
        .loading-spinner {
            width: 48px;
            height: 48px;
            border: 3px solid var(--border);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .loading-text {
            margin-top: 1rem;
            font-size: 0.875rem;
            color: var(--text-secondary);
            font-weight: 500;
        }
        
        /* Full Width Card */
        .full-width {
            grid-column: 1 / -1;
        }
        
        /* Print Styles */
        @media print {
            body * { visibility: hidden; }
            
            .main-sidebar, .main-header, .main-footer, form, .no-print, .btn, 
            .dataTables_filter, .dataTables_paginate, .dataTables_info, 
            .dataTables_length, .filter-card, .toggle-section { 
                display: none !important; 
            }
            
            .content-wrapper, .reports-content, .data-grid, .data-card, 
            .summary-card, .stats-grid, .stat-card, .data-table, .data-table * {
                visibility: visible;
            }
            
            .content-wrapper {
                margin-left: 0 !important;
                padding: 0 !important;
                background: white !important;
            }
            
            .reports-header { display: none !important; }
            
            .reports-content {
                margin-top: 0;
                padding: 1rem;
            }
            
            .data-card, .summary-card, .stat-card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
                break-inside: avoid;
            }
            
            .data-grid {
                grid-template-columns: 1fr !important;
            }
            
            .d-none { display: none !important; }
        }
    </style>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loading-overlay">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <div class="loading-text">@lang('messages.loading')</div>
        </div>
    </div>

    <!-- Header -->
    <header class="reports-header">
        <div class="header-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>
        <div class="header-content">
            <div class="glass-panel">
                <div class="d-flex align-items-center" style="gap: 1.5rem;">
                    <div class="header-icon-wrapper">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div class="header-text">
                        <h1>{{ __('messages.reports_dashboard') }}</h1>
                        <p>{{ __('messages.filter_by_month_gym') }}</p>
                    </div>
                </div>
                
                <div class="header-stats-mini mt-3 mt-md-0">
                    <div class="stat-mini">
                        <span class="stat-mini-label">Mois en cours</span>
                        <span class="stat-mini-value">{{ \Carbon\Carbon::parse($month)->translatedFormat('F Y') }}</span>
                    </div>
                    <div class="stat-mini">
                        <span class="stat-mini-label">Connexion</span>
                        <span class="stat-mini-value text-success-light"><i class="fas fa-circle" style="font-size: 8px; vertical-align: middle; margin-right: 4px;"></i>Active</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="reports-content">
        <!-- Filter Card -->
        <div class="filter-card">
            <form method="GET" action="{{ route('reports.index') }}" id="filter-form">
                <div class="filter-grid">
                    <div class="filter-group">
                        <label for="month"><i class="fas fa-calendar-alt text-primary opacity-75"></i> {{ __('messages.month') }}</label>
                        <input id="month" type="month" name="month" value="{{ $month }}">
                    </div>
                    <div class="filter-group">
                        <label for="gym_id"><i class="fas fa-dumbbell text-primary opacity-75"></i> {{ __('messages.gym') }}</label>
                        <select id="gym_id" name="gym_id" onchange="this.form.submit()">
                            <option value="">{{ __('messages.all_gyms') }}</option>
                            @foreach($gyms as $gym)
                                <option value="{{ $gym->id }}" {{ (string)$selectedGymId === (string)$gym->id ? 'selected' : '' }}>{{ $gym->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if(count($cashRegisters ?? []) > 0)
                    <div class="filter-group">
                        <label for="cash_register_id">
                            <i class="fas fa-cash-register text-primary opacity-75"></i>
                            <span class="label-fr">Caisse</span>
                            <span class="label-ar text-muted ms-1">الصندوق</span>
                        </label>
                        <select id="cash_register_id" name="cash_register_id">
                            <option value="">
                                <span class="label-fr">Toutes les caisses</span>
                                <span class="label-ar">جميع الصناديق</span>
                            </option>
                            @foreach($cashRegisters as $register)
                                <option value="{{ $register->id }}" {{ (string)$selectedCashRegisterId === (string)$register->id ? 'selected' : '' }}>
                                    {{ $register->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="filter-group">
                        <label for="city_id"><i class="fas fa-city text-primary opacity-75"></i> {{ __('messages.city') }}</label>
                        <select id="city_id" name="city_id">
                            <option value="">{{ __('messages.all_cities') }}</option>
                            @foreach(($cities ?? []) as $city)
                                <option value="{{ $city->id }}" {{ (string)($selectedCityId ?? '') === (string)$city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="payment_status"><i class="fas fa-credit-card text-primary opacity-75"></i> {{ __('messages.user_payment_status') }}</label>
                        <select id="payment_status" name="payment_status">
                            <option value="" {{ empty($paymentStatus) ? 'selected' : '' }}>{{ __('messages.all') }}</option>
                            <option value="paid" {{ ($paymentStatus ?? '') === 'paid' ? 'selected' : '' }}>{{ __('messages.paid') }}</option>
                            <option value="due" {{ ($paymentStatus ?? '') === 'due' ? 'selected' : '' }}>{{ __('messages.due') }}</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="coach_id"><i class="fas fa-user-ninja text-primary opacity-75"></i> {{ Lang::has('messages.coach') ? __('messages.coach') : 'Coach' }}</label>
                        <select id="coach_id" name="coach_id">
                            <option value="">{{ __('messages.all') }}</option>
                            @foreach(($coaches ?? []) as $coach)
                                <option value="{{ $coach->id }}" {{ (string)($selectedCoachId ?? '') === (string)$coach->id ? 'selected' : '' }}>{{ $coach->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="course_id"><i class="fas fa-book text-primary opacity-75"></i> {{ Lang::has('messages.course') ? __('messages.course') : 'Cours' }}</label>
                        <select id="course_id" name="course_id">
                            <option value="">{{ Lang::has('messages.all_courses') ? __('messages.all_courses') : 'Tous les cours' }}</option>
                            @foreach(($courses ?? []) as $course)
                                <option value="{{ $course->id }}" {{ (string)($selectedCourseId ?? '') === (string)$course->id ? 'selected' : '' }}>{{ $course->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="attendance_status"><i class="fas fa-user-check text-primary opacity-75"></i> {{ __('messages.attendance') }}</label>
                        <select id="attendance_status" name="attendance_status">
                            <option value="" {{ empty($attendanceStatus) ? 'selected' : '' }}>{{ __('messages.all') }}</option>
                            <option value="present" {{ ($attendanceStatus ?? '') === 'present' ? 'selected' : '' }}>{{ __('messages.present') }}</option>
                            <option value="absent" {{ ($attendanceStatus ?? '') === 'absent' ? 'selected' : '' }}>{{ __('messages.absent') }}</option>
                            <option value="excused" {{ ($attendanceStatus ?? '') === 'excused' ? 'selected' : '' }}>{{ __('messages.excused') }}</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="user_status"><i class="fas fa-user text-primary opacity-75"></i> {{ Lang::has('messages.user_status') ? __('messages.user_status') : 'Statut Utilisateur' }}</label>
                        <select id="user_status" name="user_status">
                            <option value="" {{ empty($userStatus) ? 'selected' : '' }}>{{ __('messages.all') }}</option>
                            <option value="active" {{ ($userStatus ?? '') === 'active' ? 'selected' : '' }}>{{ __('messages.active_users') }}</option>
                            <option value="banned" {{ ($userStatus ?? '') === 'banned' ? 'selected' : '' }}>{{ __('messages.banned_users') }}</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label><i class="fas fa-coins text-primary opacity-75"></i> {{ __('messages.amount_tnd') }} (Min)</label>
                        <input type="number" step="0.01" name="amount_min" placeholder="{{ __('messages.min') }}" value="{{ $amountMin ?? '' }}">
                    </div>
                    <div class="filter-group">
                        <label><i class="fas fa-coins text-primary opacity-75"></i> {{ __('messages.amount_tnd') }} (Max)</label>
                        <input type="number" step="0.01" name="amount_max" placeholder="{{ __('messages.max') }}" value="{{ $amountMax ?? '' }}">
                    </div>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-apply">
                        <i class="fas fa-filter"></i> {{ __('messages.apply') }}
                    </button>
                    <div class="export-btns">
                        <a id="exportExcelLink" class="btn-export excel" href="{{ route('reports.export.excel', request()->query()) }}">
                            <i class="fas fa-file-excel"></i> {{ __('messages.export_excel') }}
                        </a>
                        <a id="exportPdfLink" class="btn-export pdf" href="{{ route('reports.export.pdf', request()->query()) }}">
                            <i class="fas fa-file-pdf"></i> {{ __('messages.export_pdf') }}
                        </a>
                        <button type="button" class="btn-export" onclick="window.print()">
                            <i class="fas fa-print"></i> {{ __('messages.print') }}
                        </button>
                        <a class="btn-export" href="{{ route('reports.index', ['month' => $month]) }}">
                            <i class="fas fa-rotate-left"></i> {{ __('messages.reset') }}
                        </a>
                    </div>
                </div>

                <!-- Display Toggles -->
                <div class="toggle-section no-print">
                    <h6><i class="fas fa-eye text-primary"></i> {{ __('messages.display_params') }}</h6>
                    <div class="toggle-grid">
                        <div class="toggle-item" onclick="document.getElementById('toggle-summary').click()">
                            <label class="toggle-switch">
                                <input type="checkbox" class="section-toggle" id="toggle-summary" data-target="sec-summary" checked>
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-label">{{ __('messages.summary') }}</span>
                        </div>
                        <div class="toggle-item" onclick="document.getElementById('toggle-revenue').click()">
                            <label class="toggle-switch">
                                <input type="checkbox" class="section-toggle" id="toggle-revenue" data-target="sec-revenue" checked>
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-label">{{ __('messages.revenues') }}</span>
                        </div>
                        <div class="toggle-item" onclick="document.getElementById('toggle-attendance').click()">
                            <label class="toggle-switch">
                                <input type="checkbox" class="section-toggle" id="toggle-attendance" data-target="sec-attendance" checked>
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-label">{{ __('messages.attendance') }}</span>
                        </div>
                        <div class="toggle-item" onclick="document.getElementById('toggle-expenses').click()">
                            <label class="toggle-switch">
                                <input type="checkbox" class="section-toggle" id="toggle-expenses" data-target="sec-expenses" checked>
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-label">{{ __('messages.expenses') }}</span>
                        </div>
                        <div class="toggle-item" onclick="document.getElementById('toggle-coach-pay').click()">
                            <label class="toggle-switch">
                                <input type="checkbox" class="section-toggle" id="toggle-coach-pay" data-target="sec-coach-pay" checked>
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-label">{{ Lang::has('messages.coach_payments') ? __('messages.coach_payments') : 'Paiements Coachs' }}</span>
                        </div>
                        <div class="toggle-item" onclick="document.getElementById('toggle-user-pay').click()">
                            <label class="toggle-switch">
                                <input type="checkbox" class="section-toggle" id="toggle-user-pay" data-target="sec-user-pay" checked>
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-label">{{ Lang::has('messages.user_payments') ? __('messages.user_payments') : 'Paiements Utilisateurs' }}</span>
                        </div>
                        <div class="toggle-item" onclick="document.getElementById('toggle-top-coaches').click()">
                            <label class="toggle-switch">
                                <input type="checkbox" class="section-toggle" id="toggle-top-coaches" data-target="sec-top-coaches" checked>
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-label">{{ Lang::has('messages.top_coaches') ? __('messages.top_coaches') : 'Top Coaches' }}</span>
                        </div>
                        <div class="toggle-item" onclick="document.getElementById('toggle-top-gyms').click()">
                            <label class="toggle-switch">
                                <input type="checkbox" class="section-toggle" id="toggle-top-gyms" data-target="sec-top-gyms" checked>
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-label">{{ Lang::has('messages.top_gyms') ? __('messages.top_gyms') : 'Top Salles' }}</span>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Active Filters -->
            @php
                $cityName = ($selectedCityId && isset($cities)) ? optional(collect($cities)->firstWhere('id',$selectedCityId))->name : null;
                $gymName = ($selectedGymId && isset($gyms)) ? optional(collect($gyms)->firstWhere('id',$selectedGymId))->name : null;
                $coachName = ($selectedCoachId && isset($coaches)) ? optional(collect($coaches)->firstWhere('id',$selectedCoachId))->name : null;
                $courseName = ($selectedCourseId && isset($courses)) ? optional(collect($courses)->firstWhere('id',$selectedCourseId))->name : null;
                $cashRegisterName = ($selectedCashRegisterId && isset($cashRegisters)) ? optional(collect($cashRegisters)->firstWhere('id',$selectedCashRegisterId))->name : null;
            @endphp
            @if($cityName || $gymName || $coachName || $courseName || $cashRegisterName || !empty($attendanceStatus) || !empty($paymentStatus) || !empty($userStatus) || (isset($amountMin) && $amountMin !== '') || (isset($amountMax) && $amountMax !== ''))
            <div class="active-filters">
                @if($cityName)<span class="filter-badge"><i class="fas fa-map-marker-alt"></i> {{ $cityName }}</span>@endif
                @if($gymName)<span class="filter-badge"><i class="fas fa-dumbbell"></i> {{ $gymName }}</span>@endif
                @if($cashRegisterName)<span class="filter-badge"><i class="fas fa-cash-register"></i> {{ $cashRegisterName }}</span>@endif
                @if($coachName)<span class="filter-badge"><i class="fas fa-user-tie"></i> {{ $coachName }}</span>@endif
                @if($courseName)<span class="filter-badge"><i class="fas fa-book"></i> {{ $courseName }}</span>@endif
                @if(!empty($attendanceStatus))<span class="filter-badge"><i class="fas fa-clipboard-check"></i> {{ $attendanceStatus }}</span>@endif
                @if(!empty($paymentStatus))<span class="filter-badge"><i class="fas fa-credit-card"></i> {{ $paymentStatus }}</span>@endif
                @if(!empty($userStatus))<span class="filter-badge"><i class="fas fa-user"></i> {{ $userStatus }}</span>@endif
                @if(isset($amountMin) && $amountMin !== '')<span class="filter-badge"><i class="fas fa-arrow-up"></i> Min: {{ $amountMin }}</span>@endif
                @if(isset($amountMax) && $amountMax !== '')<span class="filter-badge"><i class="fas fa-arrow-down"></i> Max: {{ $amountMax }}</span>@endif
            </div>
            @endif
        </div>

        <!-- Stats Summary -->
        <div class="stats-grid">
            <div class="stat-card primary">
                <div class="stat-label">{{ __('messages.total_revenue') }}</div>
                <div class="stat-value text-mono">{{ number_format($revenue['total_cents']/100, 2, ',', ' ') }} <small>TND</small></div>
            </div>
            <div class="stat-card secondary">
                <div class="stat-label">{{ __('messages.transactions') }}</div>
                <div class="stat-value text-mono">{{ collect($revenue['sources'])->sum('transactions') }}</div>
            </div>
            <div class="stat-card danger">
                <div class="stat-label">{{ __('messages.total_expenses') }}</div>
                <div class="stat-value text-mono">{{ number_format(($expense['total_cents'] ?? 0)/100, 2, ',', ' ') }} <small>TND</small></div>
            </div>
            <div class="stat-card success">
                <div class="stat-label">{{ __('messages.net_revenue') }}</div>
                <div class="stat-value text-mono">{{ number_format(($expense['net_total_cents'] ?? 0)/100, 2, ',', ' ') }} <small>TND</small></div>
            </div>
        </div>

        <!-- Global Summary -->
        <div class="summary-card" id="sec-summary">
            <div class="summary-header">
                <div class="summary-title">
                    <i class="fas fa-chart-pie"></i>
                    {{ __('messages.global_summary') }}
                    <span class="summary-title-ar">/ {{ __('messages.global_summary', [], 'ar') }}</span>
                </div>
            </div>
            <div class="summary-body">
                <div class="summary-badges">
                    <span class="summary-badge"><i class="fas fa-users"></i> {{ __('messages.users') }}: <strong>{{ $summary['users_total'] ?? 0 }}</strong></span>
                    <span class="summary-badge"><i class="fas fa-user-check"></i> {{ __('messages.active_users') }}: <strong>{{ $summary['users_active'] ?? 0 }}</strong></span>
                    <span class="summary-badge"><i class="fas fa-user-slash"></i> {{ __('messages.banned_users') }}: <strong>{{ $summary['users_banned'] ?? 0 }}</strong></span>
                    <span class="summary-badge"><i class="fas fa-user-tie"></i> {{ __('messages.coaches') }}: <strong>{{ $summary['coaches_total'] ?? 0 }}</strong></span>
                    <span class="summary-badge"><i class="fas fa-dumbbell"></i> {{ __('messages.gyms') }}: <strong>{{ $summary['gyms_total'] ?? 0 }}</strong></span>
                    <span class="summary-badge"><i class="fas fa-book"></i> {{ __('messages.courses') }}: <strong>{{ $summary['courses_total'] ?? 0 }}</strong></span>
                    <span class="summary-badge"><i class="fas fa-calendar-alt"></i> {{ __('messages.events') }}: <strong>{{ $summary['events_total'] ?? 0 }}</strong></span>
                    <span class="summary-badge"><i class="fas fa-ticket-alt"></i> {{ __('messages.event_registrations') }}: <strong>{{ $summary['event_regs_total'] ?? 0 }}</strong></span>
                </div>
            </div>
        </div>

        <!-- Revenue & Attendance -->
        <div class="data-grid">
            <div class="data-card" id="sec-revenue">
                <div class="data-card-header">
                    <span class="data-card-title">{{ __('messages.total_revenue_source') }} ({{ $month }})</span>
                </div>
                <div class="data-card-body">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>{{ __('messages.source') }}</th>
                                <th>{{ __('messages.transactions') }}</th>
                                <th>{{ __('messages.amount_tnd') }}</th>
                                <th>
                                    <span class="label-fr">Caisse</span>
                                    <span class="label-ar">الصندوق</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($revenue['sources'] as $src)
                            <tr>
                                <td>{{ $src['label'] }}</td>
                                <td>{{ $src['transactions'] }}</td>
                                <td>{{ number_format($src['amount_cents']/100, 2, ',', ' ') }}</td>
                                <td>{{ optional(collect($cashRegisters)->firstWhere('id', $selectedCashRegisterId))->name ?? '-' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>{{ __('messages.total') }}</th>
                                <th class="text-mono">{{ collect($revenue['sources'])->sum('transactions') }}</th>
                                <th class="text-mono">{{ number_format($revenue['total_cents']/100, 2, ',', ' ') }}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="data-card" id="sec-attendance">
                <div class="data-card-header">
                    <span class="data-card-title">{{ __('messages.attendance_by_course') }} ({{ $month }})</span>
                </div>
                <div class="data-card-body">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>{{ __('messages.course') }}</th>
                                <th>{{ __('messages.gym') }}</th>
                                <th>{{ __('messages.sessions') }}</th>
                                <th>{{ __('messages.presents') }}</th>
                                <th>{{ __('messages.absents') }}</th>
                                <th>{{ __('messages.excused') }}</th>
                                <th>{{ __('messages.rate_percent') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendance as $row)
                            <tr>
                                <td>{{ $row['course_name'] }}</td>
                                <td>{{ $row['gym_name'] ?? '-' }}</td>
                                <td class="text-mono">{{ $row['total_sessions'] }}</td>
                                <td class="text-mono">{{ $row['presents'] }}</td>
                                <td class="text-mono">{{ $row['absents'] }}</td>
                                <td class="text-mono">{{ $row['excused'] }}</td>
                                <td class="text-mono">{{ number_format($row['presence_rate'], 2, ',', ' ') }}%</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="empty-state">{{ __('messages.no_attendance_data') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Expenses & Coach Payments -->
        <div class="data-grid">
            <div class="data-card" id="sec-expenses">
                <div class="data-card-header">
                    <span class="data-card-title">{{ __('messages.expenses_by_category') }} ({{ $month }})</span>
                </div>
                <div class="data-card-body">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>{{ __('messages.category') }}</th>
                                <th>{{ __('messages.amount_tnd') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($expense['by_category'] ?? []) as $row)
                            <tr>
                                <td>{{ $row['label'] }}</td>
                                <td class="text-mono">{{ number_format($row['amount_cents']/100, 2, ',', ' ') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="empty-state">{{ __('messages.no_expenses') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>{{ __('messages.total') }}</th>
                                <th class="text-mono">{{ number_format(($expense['total_cents'] ?? 0)/100, 2, ',', ' ') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="data-card" id="sec-coach-pay">
                <div class="data-card-header">
                    <span class="data-card-title">{{ __('messages.coach_payments') }} ({{ $month }})</span>
                </div>
                <div class="data-card-body">
                    <table class="data-table" id="coachPaymentsTable">
                        <thead>
                            <tr>
                                <th>{{ __('messages.coach') }}</th>
                                <th>{{ __('messages.gym') }}</th>
                                <th>{{ __('messages.category') }}</th>
                                <th>{{ __('messages.date') }}</th>
                                <th>{{ __('messages.method') }}</th>
                                <th>{{ __('messages.amount_tnd') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($coachPaymentsList ?? []) as $e)
                            <tr>
                                <td>{{ optional($e->coach)->name ?? '-' }}</td>
                                <td>{{ optional($e->gym)->name ?? '-' }}</td>
                                <td>{{ optional($e->category)->name ?? '-' }}</td>
                                <td class="text-mono">{{ $e->expense_date }}</td>
                                <td>{{ $e->method ?? '-' }}</td>
                                <td class="text-mono">{{ number_format(($e->amount_cents ?? 0)/100, 2, ',', ' ') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="empty-state">{{ __('messages.no_payments_found') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5">{{ __('messages.total') }}</th>
                                <th class="text-mono">{{ number_format(($payments['coaches']['total_cents'] ?? 0)/100, 2, ',', ' ') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- User Payments (Full Width) -->
        <div class="data-grid">
            <div class="data-card full-width" id="sec-user-pay">
                <div class="data-card-header">
                    <span class="data-card-title">{{ __('messages.user_payments') }} ({{ $month }}) / {{ __('messages.user_payments', [], 'ar') }}</span>
                </div>
                <div class="data-card-body">
                    <table class="data-table" id="userPaymentsTable">
                        <thead>
                            <tr>
                                <th>{{ __('messages.user') }}</th>
                                <th>{{ __('messages.email') }}</th>
                                <th>{{ __('messages.city') }}</th>
                                <th>{{ __('messages.gym') }}</th>
                                <th>
                                    <span class="label-fr">Caisse</span>
                                    <span class="label-ar">الصندوق</span>
                                </th>
                                <th>{{ __('messages.amount_tnd') }}</th>
                                <th>{{ __('messages.status') }}</th>
                                <th>{{ __('messages.method') }}</th>
                                <th>{{ __('messages.paid_at') }}</th>
                                <th>{{ __('messages.total_sessions') }}</th>
                                <th>{{ __('messages.remain_session') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($userPaymentsList ?? []) as $p)
                            <tr>
                                <td>{{ optional($p->user)->name }}</td>
                                <td>{{ optional($p->user)->email }}</td>
                                <td>{{ optional(optional($p->user)->city)->name ?? '-' }}</td>
                                <td>{{ optional(optional($p->user)->gym)->name ?? '-' }}</td>
                                <td>{{ optional($p->cashRegister)->name ?? '-' }}</td>
                                <td class="text-mono">{{ number_format(($p->amount_cents ?? 0)/100, 2, ',', ' ') }}</td>
                                <td><span class="status-badge {{ $p->status }}">{{ $p->status }}</span></td>
                                <td>{{ $p->method ?? '-' }}</td>
                                <td class="text-mono">{{ $p->paid_at ?? '-' }}</td>
                                <td class="text-mono">{{ optional($p->user)->total_sessions ?? '-' }}</td>
                                <td class="text-mono">{{ optional($p->user)->remain_session ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="empty-state">{{ __('messages.no_user_payments_found') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5">{{ __('messages.total') }}</th>
                                <th class="text-mono">{{ number_format(($payments['users']['total_cents'] ?? 0)/100, 2, ',', ' ') }}</th>
                                <th colspan="5"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Top Coaches & Top Gyms -->
        <div class="data-grid">
            <div class="data-card" id="sec-top-coaches">
                <div class="data-card-header">
                    <span class="data-card-title">{{ __('messages.top_coaches') }} / {{ __('messages.top_coaches', [], 'ar') }}</span>
                </div>
                <div class="data-card-body">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>{{ __('messages.name') }}</th>
                                <th>{{ __('messages.sessions') }}</th>
                                <th>{{ __('messages.attendances') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($coachesTop ?? []) as $c)
                            <tr>
                                <td>{{ $c->name }}</td>
                                <td class="text-mono">{{ $c->training_sessions_count ?? 0 }}</td>
                                <td class="text-mono">{{ $c->attendances_count ?? 0 }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="empty-state">{{ __('messages.no_coaches') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="data-card" id="sec-top-gyms">
                <div class="data-card-header">
                    <span class="data-card-title">{{ __('messages.top_gyms') }} / {{ __('messages.top_gyms', [], 'ar') }}</span>
                </div>
                <div class="data-card-body">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>{{ __('messages.gym') }}</th>
                                <th>{{ __('messages.users') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($gymsTop ?? []) as $g)
                            <tr>
                                <td>{{ $g->name }}</td>
                                <td class="text-mono">{{ $g->users_count ?? 0 }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="empty-state">{{ __('messages.no_gyms') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var overlay = document.getElementById('loading-overlay');
    var form = document.getElementById('filter-form');
    
    if (form) {
        form.addEventListener('submit', function() {
            if (overlay) {
                overlay.style.display = 'flex';
                setTimeout(function () {
                    overlay.style.display = 'none';
                }, 15000);
            }
        });
    }

    // Section toggles
    var toggles = document.querySelectorAll('.section-toggle');
    
    for (var i = 0; i < toggles.length; i++) {
        var t = toggles[i];
        var targetId = t.getAttribute('data-target');
        var saved = localStorage.getItem('report_show_' + targetId);
        if (saved === 'false') {
            t.checked = false;
            var el = document.getElementById(targetId);
            if (el) el.classList.add('d-none');
        }
    }

    function updateExportLinks() {
        var pdfLink = document.getElementById('exportPdfLink');
        var excelLink = document.getElementById('exportExcelLink');
        var links = [pdfLink, excelLink].filter(Boolean);
        if (links.length === 0) return;

        for (var j = 0; j < links.length; j++) {
            var link = links[j];
            var url = new URL(link.href);
            var params = url.searchParams;

            var summaryToggle = document.getElementById('toggle-summary');
            var revenueToggle = document.getElementById('toggle-revenue');
            var attendanceToggle = document.getElementById('toggle-attendance');
            var expensesToggle = document.getElementById('toggle-expenses');
            var coachPayToggle = document.getElementById('toggle-coach-pay');
            var userPayToggle = document.getElementById('toggle-user-pay');
            var topCoachesToggle = document.getElementById('toggle-top-coaches');
            var topGymsToggle = document.getElementById('toggle-top-gyms');

            params.set('show_summary', summaryToggle && summaryToggle.checked ? 1 : 0);
            params.set('show_revenue', revenueToggle && revenueToggle.checked ? 1 : 0);
            params.set('show_attendance', attendanceToggle && attendanceToggle.checked ? 1 : 0);
            params.set('show_expenses', expensesToggle && expensesToggle.checked ? 1 : 0);
            params.set('show_coach_pay', coachPayToggle && coachPayToggle.checked ? 1 : 0);
            params.set('show_user_pay', userPayToggle && userPayToggle.checked ? 1 : 0);
            params.set('show_top_coaches', topCoachesToggle && topCoachesToggle.checked ? 1 : 0);
            params.set('show_top_gyms', topGymsToggle && topGymsToggle.checked ? 1 : 0);

            link.href = url.toString();
        }
    }
    
    updateExportLinks();

    for (var k = 0; k < toggles.length; k++) {
        toggles[k].addEventListener('change', function() {
            var targetId = this.getAttribute('data-target');
            var el = document.getElementById(targetId);
            if (el) {
                if (this.checked) {
                    el.classList.remove('d-none');
                    localStorage.setItem('report_show_' + targetId, 'true');
                } else {
                    el.classList.add('d-none');
                    localStorage.setItem('report_show_' + targetId, 'false');
                }
            }
            updateExportLinks();
        });
    }
});
</script>
@endsection

@section('dataTableScript')
<script>
$(function() {
    var dtConfig = {
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        pageLength: 10,
        language: {
            search: "{{ __('messages.search') }}:",
            paginate: {
                previous: "<i class='fas fa-chevron-left'></i>",
                next: "<i class='fas fa-chevron-right'></i>"
            }
        }
    };
    
    if ($.fn.DataTable) {
        if ($('#coachPaymentsTable').length) {
            $('#coachPaymentsTable').DataTable($.extend({}, dtConfig, { order: [[3, 'desc']] }));
        }
        if ($('#userPaymentsTable').length) {
            $('#userPaymentsTable').DataTable($.extend({}, dtConfig, { order: [[4, 'desc']] }));
        }
    }
});
</script>
@endsection
