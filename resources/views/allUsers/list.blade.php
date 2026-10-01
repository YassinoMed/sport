@extends('layouts.user-layout')

@section('content')
<div class="content-wrapper">
    @push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            /* Couleurs principales */
            --primary: #6366f1;
            --primary-light: #818cf8;
            --primary-dark: #4f46e5;
            --primary-50: #eef2ff;
            --primary-100: #e0e7ff;
            
            /* États */
            --success: #10b981;
            --success-light: #d1fae5;
            --warning: #f59e0b;
            --warning-light: #fef3c7;
            --danger: #ef4444;
            --danger-light: #fee2e2;
            --info: #06b6d4;
            --info-light: #cffafe;
            
            /* Neutres */
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            
            /* Effets */
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            
            /* Rayons */
            --radius-sm: 6px;
            --radius: 10px;
            --radius-lg: 14px;
            --radius-xl: 20px;
            --radius-full: 9999px;
            
            /* Transitions */
            --ease-smooth: cubic-bezier(0.4, 0, 0.2, 1);
            --ease-bounce: cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .content-wrapper {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, var(--gray-50) 0%, #eef2ff 100%);
            min-height: 100vh;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        /* ========== PAGE HEADER ========== */
        .page-header {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(99, 102, 241, 0.1);
            padding: 1.5rem 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
            animation: slideDown 0.5s var(--ease-smooth);
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            max-width: 1600px;
            margin: 0 auto;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gray-900);
            letter-spacing: -0.025em;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .page-title::before {
            content: '';
            width: 4px;
            height: 28px;
            background: linear-gradient(180deg, var(--primary) 0%, var(--primary-light) 100%);
            border-radius: 2px;
        }

        .page-title .lang-ar {
            color: var(--gray-400);
            font-weight: 500;
            font-size: 1rem;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8125rem;
            color: var(--gray-500);
            margin: 0.5rem 0 0;
            padding: 0;
            background: none;
            list-style: none;
        }

        .breadcrumb a {
            color: var(--gray-500);
            text-decoration: none;
            transition: color 0.2s var(--ease-smooth);
        }

        .breadcrumb a:hover {
            color: var(--primary);
        }

        .btn-add {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: var(--radius);
            text-decoration: none;
            box-shadow: 0 4px 14px 0 rgba(99, 102, 241, 0.35);
            transition: all 0.3s var(--ease-smooth);
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px 0 rgba(99, 102, 241, 0.45);
            color: #fff;
        }

        .btn-add:active {
            transform: translateY(0);
        }

        /* ========== MAIN CONTENT ========== */
        .page-content {
            padding: 1.5rem 2rem 3rem;
            max-width: 1600px;
            margin: 0 auto;
            animation: fadeInUp 0.6s var(--ease-smooth) 0.1s both;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ========== ALERTS ========== */
        .alert {
            display: flex;
            align-items: flex-start;
            gap: 0.875rem;
            padding: 1rem 1.25rem;
            border-radius: var(--radius-lg);
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            animation: slideIn 0.4s var(--ease-bounce);
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .alert-success {
            background: var(--success-light);
            color: var(--success);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .alert-danger {
            background: var(--danger-light);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .alert ul {
            margin: 0;
            padding-left: 1.25rem;
        }

        /* ========== STATS GRID ========== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 1200px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 640px) {
            .stats-grid { grid-template-columns: 1fr; }
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: var(--radius-xl);
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            transition: all 0.4s var(--ease-smooth);
            animation: fadeInUp 0.5s var(--ease-smooth) backwards;
        }

        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        .stat-card:nth-child(4) { animation-delay: 0.4s; }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: rgba(99, 102, 241, 0.2);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: transform 0.3s var(--ease-bounce);
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .stat-icon svg {
            width: 26px;
            height: 26px;
        }

        .stat-primary .stat-icon { background: var(--primary-50); color: var(--primary); }
        .stat-success .stat-icon { background: var(--success-light); color: var(--success); }
        .stat-warning .stat-icon { background: var(--warning-light); color: var(--warning); }
        .stat-info .stat-icon { background: var(--info-light); color: var(--info); }

        .stat-content {
            flex: 1;
        }

        .stat-value {
            display: block;
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--gray-900);
            line-height: 1.2;
            letter-spacing: -0.025em;
        }

        .stat-label {
            font-size: 0.8125rem;
            color: var(--gray-500);
            margin-top: 0.25rem;
        }

        .stat-label .lang-ar {
            color: var(--gray-400);
        }

        /* ========== CARDS ========== */
        .card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.9);
            border-radius: var(--radius-xl);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: all 0.4s var(--ease-smooth);
        }

        .card:hover {
            box-shadow: var(--shadow-lg);
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--gray-100);
            background: linear-gradient(135deg, rgba(249, 250, 251, 0.8) 0%, rgba(238, 242, 255, 0.5) 100%);
        }

        .card-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--gray-800);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.625rem;
        }

        .card-title svg {
            color: var(--primary);
        }

        .card-title .lang-ar {
            color: var(--gray-400);
            font-weight: 400;
        }

        .card-body {
            padding: 1.5rem;
        }

        .mb-4 {
            margin-bottom: 1.5rem;
        }

        /* ========== FILTER SECTION ========== */
        .filter-toggle {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.875rem;
            font-size: 0.8125rem;
            font-weight: 500;
            color: var(--gray-600);
            background: #fff;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius);
            cursor: pointer;
            transition: all 0.2s var(--ease-smooth);
        }

        .filter-toggle:hover {
            background: var(--gray-50);
            border-color: var(--primary);
            color: var(--primary);
        }

        .filter-toggle svg {
            transition: transform 0.3s var(--ease-smooth);
        }

        .filter-toggle.collapsed svg {
            transform: rotate(-90deg);
        }

        /* ========== FORM ELEMENTS ========== */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.25rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--gray-600);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .form-label .lang-ar {
            font-weight: 400;
            color: var(--gray-400);
            text-transform: none;
        }

        .form-input,
        .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            font-family: inherit;
            color: var(--gray-900);
            background: #fff;
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius);
            transition: all 0.3s var(--ease-smooth);
            outline: none;
        }

        .form-select {
            padding-right: 2.5rem;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            appearance: none;
            cursor: pointer;
        }

        .form-input:hover,
        .form-select:hover {
            border-color: var(--gray-300);
        }

        .form-input:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .form-input::placeholder {
            color: var(--gray-400);
        }

        .form-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.75rem;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--gray-100);
        }

        .btn-reset {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1rem;
            font-size: 0.8125rem;
            font-weight: 500;
            color: var(--gray-600);
            background: #fff;
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius);
            cursor: pointer;
            transition: all 0.2s var(--ease-smooth);
        }

        .btn-reset:hover {
            background: var(--gray-50);
            border-color: var(--gray-300);
        }

        .btn-filter {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            font-size: 0.8125rem;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: var(--radius);
            cursor: pointer;
            transition: all 0.3s var(--ease-smooth);
            box-shadow: 0 2px 8px rgba(99, 102, 241, 0.25);
        }

        .btn-filter:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.35);
        }

        /* ========== TABLE ========== */
        .table-container {
            overflow-x: auto;
            border-radius: var(--radius-lg);
        }

        .modern-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .modern-table thead {
            background: linear-gradient(135deg, var(--gray-50) 0%, rgba(238, 242, 255, 0.5) 100%);
        }

        .modern-table th {
            padding: 1rem 1.25rem;
            text-align: left;
            font-size: 0.6875rem;
            font-weight: 700;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            border-bottom: 1px solid var(--gray-200);
            white-space: nowrap;
        }

        .bilingual-label {
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .bilingual-label .separator {
            color: var(--gray-300);
        }

        .bilingual-label .lang-secondary {
            color: var(--gray-400);
            font-weight: 500;
        }

        .modern-table td {
            padding: 1rem 1.25rem;
            font-size: 0.875rem;
            color: var(--gray-700);
            border-bottom: 1px solid var(--gray-100);
            vertical-align: middle;
            transition: background 0.2s var(--ease-smooth);
        }

        .modern-table tbody tr {
            transition: all 0.2s var(--ease-smooth);
        }

        .modern-table tbody tr:hover {
            background: rgba(99, 102, 241, 0.03);
        }

        .modern-table tbody tr:hover td {
            border-bottom-color: rgba(99, 102, 241, 0.1);
        }

        .modern-table tbody tr.banned-row {
            background: var(--danger-light);
        }

        /* ========== USER CELL ========== */
        .user-cell {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            position: relative;
            width: 44px;
            height: 44px;
            border-radius: var(--radius-lg);
            overflow: hidden;
            background: linear-gradient(135deg, var(--primary-50) 0%, var(--primary-100) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border: 2px solid #fff;
            box-shadow: var(--shadow-sm);
        }

        .user-avatar.initials-avatar {
            background: linear-gradient(135deg, var(--primary-100) 0%, var(--primary-200) 100%);
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-initials {
            display: none;
            font-weight: 700;
            color: var(--primary-600);
            font-size: 1.25rem;
            text-transform: uppercase;
            line-height: 1;
            margin-top: 2px;
        }

        .initials-avatar .avatar-initials {
            display: block;
        }

        .avatar-status {
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 10px;
            height: 10px;
            background: var(--success);
            border: 2px solid #fff;
            border-radius: 50%;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            gap: 0.125rem;
        }

        .user-name {
            font-weight: 600;
            color: var(--gray-900);
        }

        .user-email {
            font-size: 0.8125rem;
            color: var(--gray-500);
        }

        .doc-warning-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.125rem 0.5rem;
            font-size: 0.625rem;
            font-weight: 600;
            border-radius: var(--radius-full);
            margin-top: 0.375rem;
        }

        .insurance-missing {
            background: var(--danger-light);
            color: var(--danger);
        }

        .stage-missing {
            background: var(--warning-light);
            color: var(--warning);
        }

        /* ========== GENDER BADGE ========== */
        .gender-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: var(--radius-full);
        }

        .gender-badge.male {
            background: var(--info-light);
            color: var(--info);
        }

        .gender-badge.female {
            background: linear-gradient(135deg, #fce7f3 0%, #fbcfe8 100%);
            color: #db2777;
        }

        /* ========== DATE CELL ========== */
        .date-cell {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--gray-600);
        }

        .date-cell svg {
            color: var(--gray-400);
        }

        /* ========== TAGS ========== */
        .tag {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.625rem;
            font-size: 0.6875rem;
            font-weight: 600;
            border-radius: var(--radius-full);
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .tag-info {
            background: var(--info-light);
            color: var(--info);
        }

        .tag-secondary {
            background: var(--gray-100);
            color: var(--gray-600);
        }

        .tags-container {
            display: flex;
            flex-wrap: wrap;
            gap: 0.375rem;
        }

        .text-muted-modern {
            color: var(--gray-400);
        }

        /* ========== BELT BADGE ========== */
        .belt-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            background: linear-gradient(135deg, var(--primary-50) 0%, var(--primary-100) 100%);
            color: var(--primary-dark);
            border-radius: var(--radius-full);
        }

        /* ========== ACTIONS ========== */
        .actions-cell {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            justify-content: flex-end;
        }

        .action-btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 10px;
            border: 1px solid transparent;
            background: var(--gray-100);
            color: var(--gray-600);
            cursor: pointer;
            transition: all 0.3s var(--ease-smooth);
            text-decoration: none;
        }

        .action-btn:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: var(--shadow-md);
        }

        .action-btn svg {
            width: 18px;
            height: 18px;
            transition: transform 0.3s var(--ease-smooth);
        }

        .action-btn:hover svg {
            transform: rotate(8deg);
        }

        /* View Profile */
        .action-btn.action-view {
            background: var(--info-light);
            color: var(--info);
        }
        .action-btn.action-view:hover {
            background: var(--info);
            color: #fff;
            box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3);
        }

        /* Edit */
        .action-btn.action-edit {
            background: var(--primary-50);
            color: var(--primary);
        }
        .action-btn.action-edit:hover {
            background: var(--primary);
            color: #fff;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        /* Delete */
        .action-btn.action-delete {
            background: var(--danger-light);
            color: var(--danger);
        }
        .action-btn.action-delete:hover {
            background: var(--danger);
            color: #fff;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        /* Ban */
        .action-btn.action-ban {
            background: var(--warning-light);
            color: var(--warning);
        }
        .action-btn.action-ban:hover {
            background: var(--warning);
            color: #fff;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        /* Unban */
        .action-btn.action-unban {
            background: var(--success-light);
            color: var(--success);
        }
        .action-btn.action-unban:hover {
            background: var(--success);
            color: #fff;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        /* Gym & Tests (Info) */
        .action-btn.action-gym {
            background: #f0fdf4;
            color: #16a34a;
        }
        .action-btn.action-gym:hover {
            background: #16a34a;
            color: #fff;
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
        }

        .action-btn.action-tests {
            background: #fdf4ff;
            color: #a21caf;
        }
        .action-btn.action-tests:hover {
            background: #a21caf;
            color: #fff;
            box-shadow: 0 4px 12px rgba(162, 28, 175, 0.3);
        }

        /* Report */
        .action-btn.action-report {
            background: #f8fafc;
            color: #475569;
            border-color: var(--gray-200);
        }
        .action-btn.action-report:hover {
            background: #475569;
            color: #fff;
            box-shadow: 0 4px 12px rgba(71, 85, 105, 0.3);
        }

        /* Tooltip */
        .action-btn[data-tooltip]::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: calc(100% + 10px);
            left: 50%;
            transform: translateX(-50%) translateY(5px);
            padding: 0.5rem 0.875rem;
            background: var(--gray-900);
            color: #fff;
            font-size: 0.75rem;
            font-weight: 600;
            white-space: nowrap;
            border-radius: 8px;
            opacity: 0;
            visibility: hidden;
            transition: all 0.25s var(--ease-smooth);
            z-index: 100;
            box-shadow: var(--shadow-lg);
        }

        .action-btn[data-tooltip]::before {
            content: '';
            position: absolute;
            bottom: calc(100% + 5px);
            left: 50%;
            transform: translateX(-50%);
            border: 6px solid transparent;
            border-top-color: var(--gray-900);
            opacity: 0;
            visibility: hidden;
            transition: all 0.25s var(--ease-smooth);
            z-index: 100;
        }

        .action-btn[data-tooltip]:hover::after,
        .action-btn[data-tooltip]:hover::before {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(0);
        }

        /* ========== PAGINATION ========== */
        .pagination-container {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.25rem;
            border-top: 1px solid var(--gray-100);
        }

        .pagination {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .pagination .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 0.625rem;
            font-size: 0.8125rem;
            font-weight: 500;
            color: var(--gray-600);
            background: #fff;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius);
            text-decoration: none;
            transition: all 0.2s var(--ease-smooth);
        }

        .pagination .page-link:hover {
            background: var(--primary-50);
            border-color: var(--primary);
            color: var(--primary);
        }

        .pagination .active .page-link {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-color: transparent;
            color: #fff;
            box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3);
        }

        .pagination .disabled .page-link {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* ========== EMPTY STATE ========== */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--gray-500);
        }

        .empty-state svg {
            width: 64px;
            height: 64px;
            color: var(--gray-300);
            margin-bottom: 1.5rem;
        }

        .empty-state h4 {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--gray-700);
            margin: 0 0 0.5rem;
        }

        .empty-state p {
            font-size: 0.875rem;
            margin: 0 0 1.5rem;
        }

        .btn-primary-modern {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: var(--radius);
            text-decoration: none;
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35);
            transition: all 0.3s var(--ease-smooth);
        }

        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.45);
            color: #fff;
        }

        /* ========== MODALS ========== */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(17, 24, 39, 0.6);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s var(--ease-smooth);
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-box {
            background: #fff;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-xl);
            width: 100%;
            max-width: 420px;
            padding: 2rem;
            text-align: center;
            transform: scale(0.9) translateY(20px);
            transition: transform 0.3s var(--ease-bounce);
        }

        .modal-overlay.active .modal-box {
            transform: scale(1) translateY(0);
        }

        .modal-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
        }

        .modal-icon.danger {
            background: var(--danger-light);
            color: var(--danger);
        }

        .modal-icon.warning {
            background: var(--warning-light);
            color: var(--warning);
        }

        .modal-icon svg {
            width: 30px;
            height: 30px;
        }

        .modal-box h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--gray-900);
            margin: 0 0 0.625rem;
        }

        .modal-box p {
            font-size: 0.9375rem;
            color: var(--gray-500);
            margin: 0 0 1.75rem;
            line-height: 1.6;
        }

        .modal-actions {
            display: flex;
            gap: 0.875rem;
        }

        .modal-actions button {
            flex: 1;
            padding: 0.75rem 1.25rem;
            font-size: 0.9375rem;
            font-weight: 600;
            border-radius: var(--radius);
            cursor: pointer;
            transition: all 0.2s var(--ease-smooth);
        }

        .btn-cancel {
            background: #fff;
            color: var(--gray-600);
            border: 1.5px solid var(--gray-200);
        }

        .btn-cancel:hover {
            background: var(--gray-50);
            border-color: var(--gray-300);
        }

        .btn-confirm-danger {
            background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
            color: #fff;
            border: none;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .btn-confirm-danger:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
        }

        .btn-confirm-warning {
            background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%);
            color: #fff;
            border: none;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .btn-confirm-warning:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(245, 158, 11, 0.4);
        }

        /* ========== TOAST ========== */
        .toast {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 1.5rem;
            background: var(--gray-900);
            color: #fff;
            border-radius: var(--radius-lg);
            font-size: 0.9375rem;
            font-weight: 500;
            box-shadow: var(--shadow-xl);
            transform: translateY(100px) scale(0.9);
            opacity: 0;
            transition: all 0.4s var(--ease-bounce);
            z-index: 1100;
        }

        .toast.show {
            transform: translateY(0) scale(1);
            opacity: 1;
        }

        .toast.success {
            background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
        }

        .toast.error {
            background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
        }

        /* ========== LOADER ========== */
        .table-loader {
            display: none;
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(4px);
            align-items: center;
            justify-content: center;
            z-index: 10;
            border-radius: var(--radius-lg);
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 3px solid var(--gray-200);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @keyframes fadeOutRow {
            to {
                opacity: 0;
                transform: translateX(-30px);
            }
        }

        /* ========== UTILITIES ========== */
        .text-end {
            text-align: right;
        }

        .justify-content-end {
            justify-content: flex-end;
        }

        .d-flex {
            display: flex;
        }

        .flex-wrap {
            flex-wrap: wrap;
        }

        .gap-1 {
            gap: 0.25rem;
        }

        .mt-3 {
            margin-top: 1rem;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 1024px) {
            .page-header {
                padding: 1.25rem 1.5rem;
            }

            .page-content {
                padding: 1.25rem 1.5rem 2rem;
            }
        }

        @media (max-width: 768px) {
            .page-header {
                padding: 1rem;
            }

            .page-content {
                padding: 1rem;
            }

            .header-inner {
                flex-direction: column;
                align-items: flex-start;
            }

            .btn-add {
                width: 100%;
                justify-content: center;
            }

            .form-actions {
                flex-direction: column;
            }

            .form-actions button {
                width: 100%;
            }

            /* Card View for Table */
            .modern-table thead {
                display: none;
            }

            .modern-table, .modern-table tbody, .modern-table tr, .modern-table td {
                display: block;
                width: 100%;
            }

            .modern-table tr {
                background: #fff;
                border-radius: var(--radius-lg);
                padding: 1rem;
                margin-bottom: 1rem;
                border: 1px solid var(--gray-200);
                box-shadow: var(--shadow-sm);
                transition: all 0.3s var(--ease-smooth);
            }

            .modern-table tr:hover {
                transform: translateY(-2px);
                box-shadow: var(--shadow-md);
                background: #fff;
            }

            .modern-table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.75rem 0;
                border-bottom: 1px solid var(--gray-50);
                text-align: right;
            }

            .modern-table td:last-child {
                border-bottom: none;
                padding-bottom: 0;
                justify-content: center;
                margin-top: 0.5rem;
                padding-top: 1rem;
                border-top: 1px solid var(--gray-100);
            }

            .modern-table td:first-child {
                padding-top: 0;
                border-bottom: 1.5px solid var(--gray-100);
                margin-bottom: 0.5rem;
                padding-bottom: 1rem;
                justify-content: flex-start;
                text-align: left;
            }

            .modern-table td::before {
                content: attr(data-label);
                font-weight: 700;
                font-size: 0.75rem;
                color: var(--gray-500);
                text-transform: uppercase;
                letter-spacing: 0.05em;
                margin-right: 1rem;
                text-align: left;
            }

            .modern-table td:first-child::before,
            .modern-table td:last-child::before {
                display: none;
            }

            .actions-cell {
                width: 100%;
                justify-content: center;
                gap: 0.75rem;
            }

            .user-cell {
                width: 100%;
            }

            .modal-box {
                margin: 1rem;
                padding: 1.5rem;
            }

            .toast {
                left: 1rem;
                right: 1rem;
                bottom: 1rem;
            }
        }

        /* ========== REDUCED MOTION ========== */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
    @endpush

    <!-- Page Header -->
    <header class="page-header">
        <div class="header-inner">
            <div>
                <h1 class="page-title">
                    {{ __('messages.user_management') }}
                </h1>
                <nav class="breadcrumb">
                    <a href="/">{{ __('messages.home') }}</a>
                    <span>/</span>
                    <span>{{ __('messages.user') }}s</span>
                </nav>
            </div>
            <a href="{{ route('allUsers.create') }}" class="btn-add">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                {{ __('messages.new_user') }}
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="page-content">
        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="stats-grid">
                            <div class="stat-card stat-primary">
                                <div class="stat-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                </div>
                                <div class="stat-content">
                                    <span class="stat-value">{{ $users->total() ?? count($users) }}</span>
                                    <span class="stat-label">Total {{ __('messages.user') }}s</span>
                                </div>
                            </div>
            <div class="stat-card stat-success">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"/><path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"/></svg>
                </div>
                <div class="stat-content">
                    <span class="stat-value">{{ count($gyms ?? []) }}</span>
                    <span class="stat-label">{{ __('messages.active_gyms') }}</span>
                </div>
            </div>
            <div class="stat-card stat-warning">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2H2v10l9.29 9.29c.94.94 2.48.94 3.42 0l6.58-6.58c.94-.94.94-2.48 0-3.42L12 2Z"/><path d="M7 7h.01"/></svg>
                </div>
                <div class="stat-content">
                    <span class="stat-value">{{ count($categories ?? []) }}</span>
                    <span class="stat-label">{{ __('messages.categories') }}</span>
                </div>
            </div>
            <div class="stat-card stat-info">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                </div>
                <div class="stat-content">
                    <span class="stat-value">{{ count($beltNames ?? []) }}</span>
                    <span class="stat-label">{{ __('messages.belt_levels') }}</span>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                    {{ __('messages.search_filters') }}
                </h3>
                <button class="filter-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
            </div>
            <div class="collapse show" id="filterCollapse">
                <div class="card-body">
                    <form id="filterForm" method="GET" action="{{ route('allUsers.list') }}">
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">{{ __('messages.search') }}</label>
                                <input type="text" id="q" name="q" class="form-input" value="{{ $filters['q'] ?? '' }}" placeholder="{{ __('messages.search_placeholder') }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{ __('messages.gym') }}</label>
                                <select id="gym_id" name="gym_id" class="form-select">
                                    <option value="">{{ __('messages.all_gyms') }}</option>
                                    @foreach(($gyms ?? []) as $gym)
                                        <option value="{{ $gym->id }}" {{ ($filters['gym_id'] ?? '') == $gym->id ? 'selected' : '' }}>{{ $gym->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{ __('messages.belt') }}</label>
                                <select id="belt_name" name="belt_name" class="form-select">
                                    <option value="">{{ __('messages.all_belts') }}</option>
                                    @foreach(($beltOptions ?? []) as $value => $label)
                                        <option value="{{ $value }}" {{ ($filters['belt_name'] ?? '') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{ __('messages.category') }}</label>
                                <select id="category_id" name="category_id" class="form-select">
                                    <option value="">{{ __('messages.all_categories') }}</option>
                                    @foreach(($categories ?? []) as $category)
                                        <option value="{{ $category->id }}" {{ ($filters['category_id'] ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{ __('messages.group') }}</label>
                                <select id="group_id" name="group_id" class="form-select">
                                    <option value="">{{ __('messages.all_groups') }}</option>
                                    @foreach(($groups ?? []) as $group)
                                        <option value="{{ $group->id }}" {{ ($filters['group_id'] ?? '') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{ __('messages.gender') }}</label>
                                <select id="gender" name="gender" class="form-select">
                                    <option value="">{{ __('messages.all_genders') }}</option>
                                    <option value="male" {{ ($filters['gender'] ?? '') == 'male' ? 'selected' : '' }}>{{ __('messages.male') }}</option>
                                    <option value="female" {{ ($filters['gender'] ?? '') == 'female' ? 'selected' : '' }}>{{ __('messages.female') }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{ __('messages.has_insurance') }}</label>
                                <select id="has_insurance" name="has_insurance" class="form-select">
                                    <option value="">{{ __('messages.all') }}</option>
                                    <option value="yes" {{ ($filters['has_insurance'] ?? '') == 'yes' ? 'selected' : '' }}>{{ __('messages.yes') }}</option>
                                    <option value="no" {{ ($filters['has_insurance'] ?? '') == 'no' ? 'selected' : '' }}>{{ __('messages.no') }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{ __('messages.has_stage') }}</label>
                                <select id="has_stage" name="has_stage" class="form-select">
                                    <option value="">{{ __('messages.all') }}</option>
                                    <option value="yes" {{ ($filters['has_stage'] ?? '') == 'yes' ? 'selected' : '' }}>{{ __('messages.yes') }}</option>
                                    <option value="no" {{ ($filters['has_stage'] ?? '') == 'no' ? 'selected' : '' }}>{{ __('messages.no') }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{ __('messages.birth_date_from') }}</label>
                                <input type="date" id="birth_date_from" name="birth_date_from" class="form-input" value="{{ $filters['birth_date_from'] ?? '' }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{ __('messages.birth_date_to') }}</label>
                                <input type="date" id="birth_date_to" name="birth_date_to" class="form-input" value="{{ $filters['birth_date_to'] ?? '' }}">
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="button" id="resetFilters" class="btn-reset">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
                                {{ __('messages.reset') }}
                            </button>
                            <button type="submit" class="btn-filter">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                                {{ __('messages.apply_filters') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    {{ __('messages.users_list') }}
                </h3>
            </div>
            <div id="table-wrapper" style="position: relative;">
                <div class="table-loader" id="table-loader">
                    <div class="spinner"></div>
                </div>
                <div id="table-content">
                    @fragment('table')
                        <div class="table-container">
                            <table class="modern-table" id="usersTable">
                                <thead>
                                    <tr>
                                        <th>{{ __('messages.user') }}</th>
                                        <th>{{ __('messages.gender') }}</th>
                                        <th>{{ __('messages.birth') }}</th>
                                        <th>{{ __('messages.category') }}</th>
                                        <th>{{ __('messages.group') }}</th>
                                        <th>{{ __('messages.belt') }}</th>
                                        <th class="text-end">{{ __('messages.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $user)
                                        @php $lastBelt = $user->belts->sortByDesc('achieved_at')->first(); @endphp
                                        <tr id="did{{ $user->id }}" class="{{ $user->isBanned() ? 'banned-row' : '' }}">
                                            <td data-label="{{ __('messages.user') }}">
                                                <div class="user-cell">
                                                    <div class="user-avatar {{ !$user->profile_image ? 'initials-avatar' : '' }}">
                                                        @if($user->profile_image)
                                                            <img src="{{ asset($user->profile_image) }}" alt="{{ $user->name }}" onerror="this.style.display='none'; this.parentElement.classList.add('initials-avatar');">
                                                            <span class="avatar-initials">{{ mb_substr($user->name, 0, 1) }}</span>
                                                        @else
                                                            <span class="avatar-initials">{{ mb_substr($user->name, 0, 1) }}</span>
                                                        @endif
                                                        <span class="avatar-status"></span>
                                                    </div>
                                                    <div class="user-info">
                                                        <span class="user-name">{{ $user->name }}</span>
                                                        <span class="user-email">{{ $user->email }}</span>
                                                        @if(!$user->has_insurance || !$user->has_stage)
                                                            <div class="d-flex flex-wrap gap-1">
                                                                @if(!$user->has_insurance)
                                                                    <span class="doc-warning-badge insurance-missing" title="{{ __('messages.has_insurance') }} : {{ __('messages.no') }}">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                                                        {{ __('messages.has_insurance') }} : {{ __('messages.no') }}
                                                                    </span>
                                                                @endif
                                                                @if(!$user->has_stage)
                                                                    <span class="doc-warning-badge stage-missing" title="{{ __('messages.has_stage') }} : {{ __('messages.no') }}">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                                                                        {{ __('messages.has_stage') }} : {{ __('messages.no') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td data-label="{{ __('messages.gender') }}">
                                                @if($user->gender == 'male')
                                                    <span class="gender-badge male">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="10" cy="14" r="5"/><path d="M19 5 13.6 10.4"/><path d="M15 5h4v4"/></svg>
                                                        {{ __('messages.male') }}
                                                    </span>
                                                @elseif($user->gender == 'female')
                                                    <span class="gender-badge female">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="5"/><path d="M12 13v9"/><path d="M9 18h6"/></svg>
                                                        {{ __('messages.female') }}
                                                    </span>
                                                @else
                                                    <span class="text-muted-modern">—</span>
                                                @endif
                                            </td>
                                            <td data-label="{{ __('messages.birth') }}">
                                                @if($user->birth_date)
                                                    <div class="date-cell">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                                        {{ $user->birth_date->format('d M Y') }}
                                                    </div>
                                                @else
                                                    <span class="text-muted-modern">—</span>
                                                @endif
                                            </td>
                                            <td data-label="{{ __('messages.category') }}">
                                                @if ($user->category)
                                                    <span class="tag tag-info">{{ $user->category->name }}</span>
                                                @else
                                                    <span class="text-muted-modern">—</span>
                                                @endif
                                            </td>
                                            <td data-label="{{ __('messages.group') }}">
                                                <div class="tags-container">
                                                    @forelse($user->groups as $group)
                                                        <span class="tag tag-secondary">{{ $group->name }}</span>
                                                    @empty
                                                        <span class="text-muted-modern">—</span>
                                                    @endforelse
                                                </div>
                                            </td>
                                            <td data-label="{{ __('messages.belt') }}">
                                                @if ($lastBelt)
                                                    <span class="belt-badge">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8a2.83 2.83 0 0 0 4 4 4 4 0 1 1-4-4"/></svg>
                                                        {{ $lastBelt->belt_name }}
                                                    </span>
                                                @else
                                                    <span class="text-muted-modern">—</span>
                                                @endif
                                            </td>
                                            <td data-label="{{ __('messages.actions') }}">
                                                <div class="actions-cell">
                                                    <a class="action-btn action-view" href="{{ route('allUsers.show', $user->id) }}" 
                                                       data-tooltip="{{ __('messages.view_profile') }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                                    </a>
                                                    <a class="action-btn action-edit" href="{{ route('allUsers.edit', $user->id) }}" 
                                                       data-tooltip="{{ __('messages.edit') }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                                    </a>
                                                    <button type="button" class="action-btn action-delete" onclick="deleteUser({{ $user->id }})" 
                                                            data-tooltip="{{ __('messages.delete') }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                                    </button>
                                                    @if($user->isBanned())
                                                        <button type="button" class="action-btn action-unban" onclick="banUser({{ $user->id }})" 
                                                                data-tooltip="{{ __('messages.unban') }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                                                        </button>
                                                    @else
                                                        <button type="button" class="action-btn action-ban" onclick="banUser({{ $user->id }})" 
                                                                data-tooltip="{{ __('messages.ban') }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m4.9 4.9 14.2 14.2"/></svg>
                                                        </button>
                                                    @endif
                                                    <a class="action-btn action-gym" href="{{ url('/allUsers/addGym', $user->id) }}" 
                                                       data-tooltip="{{ __('messages.assign_gym') }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6.5 6.5L17.5 17.5"/><path d="M6.5 17.5L17.5 6.5"/><path d="M12 3v3"/><path d="M12 18v3"/><path d="M3 12h3"/><path d="M18 12h3"/></svg>
                                                    </a>
                                                    <a class="action-btn action-report" href="{{ route('allUsers.report', $user->id) }}" 
                                                       data-tooltip="{{ __('messages.report') }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
                                                    </a>
                                                    <a class="action-btn action-tests" href="{{ route('athlete-tests.user', $user->id) }}" 
                                                       data-tooltip="{{ __('messages.tests') }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><path d="M12 18v-6"/><path d="M8 18v-1"/><path d="M16 18v-3"/></svg>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7">
                                                <div class="empty-state">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                                    <h4>
                                                        {{ __('messages.no_users_found') }}
                                                    </h4>
                                                    <p>
                                                        {{ __('messages.no_users_hint') }}
                                                    </p>
                                                    <a href="{{ route('allUsers.create') }}" class="btn-primary-modern mt-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                                                        {{ __('messages.add_user') }}
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if(method_exists($users, 'links'))
                            <div class="pagination-container">
                                {{ $users->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    @endfragment
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Delete Modal -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <div class="modal-icon danger">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
        </div>
        <h3>{{ __('messages.confirm_delete_title') }}</h3>
        <p>{{ __('messages.confirm_delete_msg') }}</p>
        <div class="modal-actions">
            <button class="btn-cancel" onclick="closeModal('deleteModal')">{{ __('messages.cancel') }}</button>
            <button class="btn-confirm-danger" id="confirmDeleteBtn">{{ __('messages.delete') }}</button>
        </div>
    </div>
</div>

<!-- Ban Modal -->
<div class="modal-overlay" id="banModal">
    <div class="modal-box">
        <div class="modal-icon warning">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m4.9 4.9 14.2 14.2"/></svg>
        </div>
        <h3>{{ __('messages.block_user_title') }}</h3>
        <p>{{ __('messages.block_user_msg') }}</p>
        <div class="modal-actions">
            <button class="btn-cancel" onclick="closeModal('banModal')">{{ __('messages.cancel') }}</button>
            <button class="btn-confirm-warning" id="confirmBanBtn">{{ __('messages.block') }}</button>
        </div>
    </div>
</div>

<script>
let pendingDeleteId = null;
let pendingBanId = null;

function deleteUser(id) {
    pendingDeleteId = id;
    document.getElementById('deleteModal').classList.add('active');
}

function banUser(id) {
    pendingBanId = id;
    document.getElementById('banModal').classList.add('active');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
    if (modalId === 'deleteModal') pendingDeleteId = null;
    if (modalId === 'banModal') pendingBanId = null;
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (!pendingDeleteId) return;
    const id = pendingDeleteId;
    closeModal('deleteModal');
    
    fetch('/allUsers/' + id, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}",
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    }).then(function(response) {
        if (!response.ok) throw new Error('Request failed');
        const row = document.getElementById('did' + id);
        if (row) {
            row.style.animation = 'fadeOutRow 0.4s var(--ease-smooth) forwards';
            setTimeout(() => row.remove(), 400);
        }
        showToast("{{ __('messages.user_deleted_success') }}", 'success');
    }).catch(function(err) {
        console.error(err);
        showToast("{{ __('messages.delete_failed') }}", 'error');
    });
});

document.getElementById('confirmBanBtn').addEventListener('click', function() {
    if (!pendingBanId) return;
    const id = pendingBanId;
    closeModal('banModal');
    
    fetch('/banUser/' + id, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}",
            'X-Requested-With': 'XMLHttpRequest'
        }
    }).then(function(response) {
        if (!response.ok) throw new Error('Request failed');
        const row = document.getElementById('did' + id);
        if (row) row.classList.add('banned-row');
        showToast("{{ __('messages.user_blocked_success') }}", 'success');
    }).catch(function(err) {
        console.error(err);
        showToast("{{ __('messages.block_failed') }}", 'error');
    });
});

function showToast(message, type) {
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            ${type === 'success' 
                ? '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>'
                : '<circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/>'}
        </svg>
        <span>${message}</span>
    `;
    document.body.appendChild(toast);
    setTimeout(() => toast.classList.add('show'), 50);
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 400);
    }, 3500);
}

// AJAX Filters
const filterForm = document.getElementById('filterForm');
const tableContent = document.getElementById('table-content');
const tableLoader = document.getElementById('table-loader');
const resetBtn = document.getElementById('resetFilters');

function updateTable(url) {
    tableLoader.style.display = 'flex';
    
    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(response => response.text())
    .then(html => {
        tableContent.innerHTML = html;
        attachPaginationListeners();
    })
    .catch(error => {
        console.error('Error:', error);
        showToast("{{ __('messages.search_failed') }}", 'error');
    })
    .finally(() => {
        tableLoader.style.display = 'none';
    });
}

function attachPaginationListeners() {
    document.querySelectorAll('#table-content .pagination a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            updateTable(this.href);
            window.history.pushState({}, '', this.href);
        });
    });
}

filterForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const params = new URLSearchParams(new FormData(this));
    const url = `${this.action}?${params.toString()}`;
    updateTable(url);
    window.history.pushState({}, '', url);
});

resetBtn.addEventListener('click', function() {
    filterForm.reset();
    filterForm.dispatchEvent(new Event('submit'));
});

let debounceTimer;
filterForm.querySelectorAll('input, select').forEach(input => {
    input.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => filterForm.dispatchEvent(new Event('submit')), 500);
    });
});

attachPaginationListeners();

// Close modals on overlay click or Escape
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function(e) {
        if (e.target === this) this.classList.remove('active');
    });
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.active').forEach(m => m.classList.remove('active'));
    }
});
</script>
@endsection