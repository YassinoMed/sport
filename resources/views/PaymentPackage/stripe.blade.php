@extends('layouts.user-layout')

@section('content')
<div class="checkout-page" 
     dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
     data-lang-not-selected="{{ __('messages.not_selected') }}"
     data-lang-not-selected-f="{{ __('messages.not_selected_f') }}"
     data-lang-sessions="{{ __('messages.sessions') }}"
     data-lang-confirm="{{ __('messages.confirm') }}"
     data-lang-confirm-purchase="{{ __('messages.confirm_purchase') }}">

    @push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --c-primary: #6366f1;
            --c-primary-dark: #4f46e5;
            --c-primary-light: #818cf8;
            --c-primary-bg: #eef2ff;
            --c-secondary: #0f172a;
            --c-success: #10b981;
            --c-success-dark: #059669;
            --c-success-bg: #ecfdf5;
            --c-warning: #f59e0b;
            --c-warning-bg: #fffbeb;
            --c-danger: #ef4444;
            --c-danger-bg: #fef2f2;
            --c-bg: #f8fafc;
            --c-surface: #ffffff;
            --c-border: #e2e8f0;
            --c-border-light: #f1f5f9;
            --c-text-main: #1e293b;
            --c-text-muted: #64748b;
            --c-text-light: #94a3b8;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
            --shadow-xs: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-sm: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            --shadow-primary: 0 4px 14px 0 rgba(99, 102, 241, 0.35);
            --shadow-success: 0 4px 14px 0 rgba(16, 185, 129, 0.35);
            --transition-fast: 150ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-base: 200ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-smooth: 300ms cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Base Setup */
        .checkout-page {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            min-height: 100vh;
            color: var(--c-text-main);
            padding-bottom: 5rem;
        }

        /* Header */
        .page-header {
            background: var(--c-surface);
            border-bottom: 1px solid var(--c-border);
            padding: 1.75rem 0;
            margin-bottom: 2.5rem;
            box-shadow: var(--shadow-sm);
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--c-primary) 0%, var(--c-primary-light) 50%, var(--c-success) 100%);
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1.5rem;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .header-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, var(--c-primary) 0%, var(--c-primary-dark) 100%);
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: var(--shadow-primary);
        }

        .page-title {
            font-size: 1.625rem;
            font-weight: 800;
            color: var(--c-secondary);
            margin: 0;
            letter-spacing: -0.025em;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: var(--c-text-muted);
            margin-top: 0.375rem;
        }
        
        .breadcrumb a {
            color: var(--c-text-muted);
            text-decoration: none;
            transition: color var(--transition-fast);
        }
        
        .breadcrumb a:hover {
            color: var(--c-primary);
        }

        .breadcrumb svg {
            width: 14px;
            height: 14px;
            opacity: 0.5;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--c-text-main);
            background: var(--c-surface);
            border: 2px solid var(--c-border);
            border-radius: var(--radius-md);
            text-decoration: none;
            transition: all var(--transition-base);
        }

        .btn-back:hover {
            background: var(--c-bg);
            border-color: var(--c-primary);
            color: var(--c-primary);
            transform: translateX(-4px);
        }

        /* Layout */
        .checkout-layout {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 2.5rem;
            align-items: start;
        }

        @media (max-width: 1024px) {
            .checkout-layout {
                grid-template-columns: 1fr;
            }
            .summary-pane {
                order: -1;
            }
        }

        /* Cards */
        .card {
            background: var(--c-surface);
            border-radius: var(--radius-xl);
            border: 1px solid var(--c-border);
            box-shadow: var(--shadow-md);
            overflow: hidden;
            transition: box-shadow var(--transition-smooth);
        }

        .card-body {
            padding: 2.5rem;
        }

        /* Form Sections */
        .form-section {
            margin-bottom: 3rem;
            position: relative;
            padding-bottom: 2.5rem;
            border-bottom: 1px solid var(--c-border-light);
        }
        
        .form-section:last-of-type {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        
        .section-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.75rem;
        }

        .step-badge {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .step-number {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--c-primary) 0%, var(--c-primary-dark) 100%);
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            box-shadow: var(--shadow-primary);
            flex-shrink: 0;
        }

        .step-line {
            flex: 1;
            height: 2px;
            background: linear-gradient(90deg, var(--c-primary) 0%, transparent 100%);
            opacity: 0.3;
        }

        .section-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--c-secondary);
        }

        .section-subtitle {
            font-size: 0.875rem;
            color: var(--c-text-muted);
            margin-top: 0.25rem;
        }

        /* Inputs */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--c-text-main);
            margin-bottom: 0.625rem;
        }

        .form-label svg {
            width: 16px;
            height: 16px;
            color: var(--c-text-muted);
        }

        .select-wrapper {
            position: relative;
        }

        .select-wrapper::after {
            content: '';
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 6px solid var(--c-text-muted);
            pointer-events: none;
            transition: transform var(--transition-fast);
        }

        .select-wrapper:focus-within::after {
            transform: translateY(-50%) rotate(180deg);
        }

        .form-select {
            width: 100%;
            padding: 1rem 2.5rem 1rem 1rem;
            font-size: 0.9375rem;
            font-weight: 500;
            border: 2px solid var(--c-border);
            border-radius: var(--radius-md);
            background-color: var(--c-surface);
            color: var(--c-text-main);
            transition: all var(--transition-base);
            cursor: pointer;
            appearance: none;
        }

        .form-select:hover {
            border-color: var(--c-text-light);
        }

        .form-select:focus {
            outline: none;
            border-color: var(--c-primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .form-select option {
            padding: 0.75rem;
        }

        .form-hint {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.8125rem;
            color: var(--c-text-muted);
            margin-top: 0.5rem;
        }

        .form-hint svg {
            width: 14px;
            height: 14px;
            flex-shrink: 0;
        }

        /* Package Grid */
        .pkg-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 1rem;
            margin-top: 1.25rem;
        }

        .pkg-card {
            background: var(--c-surface);
            border: 2px solid var(--c-border);
            border-radius: var(--radius-lg);
            padding: 1.5rem 1.25rem;
            cursor: pointer;
            transition: all var(--transition-smooth);
            position: relative;
            text-align: center;
            overflow: hidden;
        }

        .pkg-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--c-primary) 0%, var(--c-primary-light) 100%);
            transform: scaleX(0);
            transition: transform var(--transition-smooth);
        }

        .pkg-card:hover {
            border-color: var(--c-primary-light);
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .pkg-card:hover::before {
            transform: scaleX(1);
        }

        .pkg-card.selected {
            border-color: var(--c-primary);
            background: linear-gradient(135deg, var(--c-primary-bg) 0%, #f5f3ff 100%);
            box-shadow: var(--shadow-primary);
        }

        .pkg-card.selected::before {
            transform: scaleX(1);
        }

        .pkg-card.selected::after {
            content: '';
            position: absolute;
            top: 12px;
            right: 12px;
            width: 24px;
            height: 24px;
            background: var(--c-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-sm);
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='20 6 9 17 4 12'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: center;
            animation: popIn 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        @keyframes popIn {
            0% { transform: scale(0); }
            100% { transform: scale(1); }
        }

        .pkg-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--c-primary-bg) 0%, #ddd6fe 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            transition: all var(--transition-base);
        }

        .pkg-card:hover .pkg-icon,
        .pkg-card.selected .pkg-icon {
            background: linear-gradient(135deg, var(--c-primary) 0%, var(--c-primary-dark) 100%);
            transform: scale(1.1);
        }

        .pkg-card:hover .pkg-icon svg,
        .pkg-card.selected .pkg-icon svg {
            color: white;
        }

        .pkg-icon svg {
            width: 24px;
            height: 24px;
            color: var(--c-primary);
            transition: color var(--transition-base);
        }

        .pkg-sessions {
            font-size: 0.6875rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--c-primary);
            font-weight: 700;
            margin-bottom: 0.375rem;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.75rem;
            background: var(--c-primary-bg);
            border-radius: 9999px;
        }

        .pkg-name {
            font-weight: 700;
            font-size: 1rem;
            color: var(--c-secondary);
            margin-bottom: 0.625rem;
        }

        .pkg-price {
            font-size: 1.375rem;
            font-weight: 800;
            color: var(--c-success);
        }

        .pkg-price small {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--c-text-muted);
        }

        /* Summary */
        .summary-card {
            position: sticky;
            top: 2rem;
            background: var(--c-surface);
            border-radius: var(--radius-xl);
            border: 1px solid var(--c-border);
            box-shadow: var(--shadow-xl);
            overflow: hidden;
        }

        .summary-header {
            background: linear-gradient(135deg, var(--c-secondary) 0%, #1e293b 100%);
            padding: 1.75rem;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .summary-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
        }

        .summary-title {
            font-size: 1.125rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            position: relative;
        }

        .summary-title-icon {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.15);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .summary-body {
            padding: 1.75rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px dashed var(--c-border);
            transition: background var(--transition-fast);
        }

        .summary-row:hover {
            background: var(--c-bg);
            margin: 0 -1.75rem;
            padding: 1rem 1.75rem;
        }
        
        .summary-row:last-child {
            border-bottom: none;
        }

        .summary-row .label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--c-text-muted);
            font-size: 0.875rem;
            font-weight: 500;
        }

        .summary-row .label svg {
            width: 16px;
            height: 16px;
        }

        .summary-row .value {
            font-weight: 600;
            text-align: right;
            font-size: 0.9375rem;
            color: var(--c-secondary);
            max-width: 60%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .summary-row .value.placeholder {
            color: var(--c-text-light);
            font-weight: 500;
            font-style: italic;
        }

        .summary-row .value.filled {
            color: var(--c-primary);
        }

        .total-box {
            background: linear-gradient(135deg, var(--c-success-bg) 0%, #d1fae5 100%);
            padding: 1.5rem;
            border-radius: var(--radius-lg);
            margin-top: 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 2px solid #a7f3d0;
            position: relative;
            overflow: hidden;
        }

        .total-box::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, rgba(16,185,129,0.15) 0%, transparent 70%);
        }

        .total-label {
            font-weight: 700;
            color: var(--c-success-dark);
            font-size: 0.9375rem;
        }

        .total-amount {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--c-success-dark);
            display: flex;
            align-items: baseline;
            gap: 0.25rem;
        }

        .total-amount .currency {
            font-size: 1rem;
            font-weight: 600;
        }

        /* Actions */
        .action-area {
            margin-top: 2.5rem;
            padding-top: 2rem;
            border-top: 2px solid var(--c-border-light);
        }

        .checkbox-container {
            display: flex;
            align-items: flex-start;
            gap: 0.875rem;
            margin-bottom: 1.75rem;
            cursor: pointer;
            padding: 1rem;
            background: var(--c-bg);
            border-radius: var(--radius-md);
            border: 2px solid transparent;
            transition: all var(--transition-base);
        }

        .checkbox-container:hover {
            border-color: var(--c-primary-light);
        }

        .checkbox-container:has(input:checked) {
            background: var(--c-primary-bg);
            border-color: var(--c-primary);
        }

        .custom-checkbox {
            width: 22px;
            height: 22px;
            border: 2px solid var(--c-border);
            border-radius: 6px;
            display: grid;
            place-content: center;
            transition: all var(--transition-base);
            margin-top: 0.1rem;
            flex-shrink: 0;
            background: var(--c-surface);
        }
        
        input[type="checkbox"]:checked + .custom-checkbox {
            background: var(--c-primary);
            border-color: var(--c-primary);
        }
        
        input[type="checkbox"]:checked + .custom-checkbox::after {
            content: '';
            width: 12px;
            height: 12px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='20 6 9 17 4 12'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: center;
        }

        .checkbox-text {
            font-size: 0.9375rem;
            font-weight: 600;
            color: var(--c-text-main);
            line-height: 1.4;
        }

        .checkbox-text small {
            display: block;
            font-weight: 500;
            color: var(--c-text-muted);
            font-size: 0.8125rem;
            margin-top: 0.25rem;
        }

        .btn-pay {
            width: 100%;
            background: var(--c-secondary);
            color: white;
            padding: 1.125rem 1.5rem;
            border-radius: var(--radius-lg);
            font-weight: 700;
            font-size: 1rem;
            border: none;
            cursor: not-allowed;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            transition: all var(--transition-smooth);
            opacity: 0.6;
            position: relative;
            overflow: hidden;
        }

        .btn-pay::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-pay:not(:disabled) {
            background: linear-gradient(135deg, var(--c-primary) 0%, var(--c-primary-dark) 100%);
            opacity: 1;
            cursor: pointer;
            box-shadow: var(--shadow-primary);
        }

        .btn-pay:not(:disabled):hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.45);
        }

        .btn-pay:not(:disabled):hover::before {
            left: 100%;
        }

        .btn-pay:not(:disabled):active {
            transform: translateY(0);
        }

        .btn-pay svg {
            width: 22px;
            height: 22px;
        }

        .secure-badge {
            text-align: center;
            margin-top: 1.25rem;
            color: var(--c-text-muted);
            font-size: 0.8125rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .secure-badge svg {
            width: 16px;
            height: 16px;
            color: var(--c-success);
        }

        /* Alerts */
        .alert {
            padding: 1.25rem 1.5rem;
            border-radius: var(--radius-lg);
            margin-bottom: 1.75rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .alert-success {
            background: linear-gradient(135deg, var(--c-success-bg) 0%, #d1fae5 100%);
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-success .alert-icon {
            background: var(--c-success);
            color: white;
        }
        
        .alert-error {
            background: linear-gradient(135deg, var(--c-danger-bg) 0%, #fee2e2 100%);
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .alert-error .alert-icon {
            background: var(--c-danger);
            color: white;
        }

        .alert-content strong {
            display: block;
            margin-bottom: 0.25rem;
        }

        .alert-content ul {
            margin-top: 0.5rem;
            padding-left: 1.25rem;
            list-style-type: disc;
        }

        /* Progress Steps Indicator */
        .progress-steps {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 2rem;
            padding: 1rem;
            background: var(--c-bg);
            border-radius: var(--radius-lg);
        }

        .progress-step {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: var(--radius-md);
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--c-text-muted);
            transition: all var(--transition-base);
        }

        .progress-step.active {
            background: var(--c-primary);
            color: white;
        }

        .progress-step.completed {
            background: var(--c-success-bg);
            color: var(--c-success);
        }

        .progress-step-number {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            background: var(--c-border);
            color: var(--c-text-muted);
        }

        .progress-step.active .progress-step-number {
            background: rgba(255,255,255,0.25);
            color: white;
        }

        .progress-step.completed .progress-step-number {
            background: var(--c-success);
            color: white;
        }

        .progress-connector {
            width: 40px;
            height: 2px;
            background: var(--c-border);
            align-self: center;
        }

        .progress-connector.active {
            background: var(--c-primary);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header {
                padding: 1.25rem 0;
            }

            .header-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .btn-back {
                align-self: flex-start;
            }

            .card-body {
                padding: 1.5rem;
            }

            .pkg-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .progress-steps {
                flex-wrap: wrap;
            }

            .progress-connector {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .pkg-grid {
                grid-template-columns: 1fr;
            }

            .header-icon {
                width: 48px;
                height: 48px;
            }

            .page-title {
                font-size: 1.375rem;
            }
        }
    </style>
    @endpush

    <!-- Header -->
    <header class="page-header">
        <div class="container header-content">
            <div class="header-left">
                <div class="header-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                </div>
                <div>
                    <h1 class="page-title">{{ __('messages.buy_package') }}</h1>
                    <nav class="breadcrumb">
                        <a href="/">{{ __('messages.home') }}</a>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                        <a href="{{ route('allUsers.list') }}">{{ __('messages.users') }}</a>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                        <span>{{ __('messages.package_purchase') }}</span>
                    </nav>
                </div>
            </div>
            <a href="{{ route('allUsers.list') }}" class="btn-back">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                {{ __('messages.back_to_list') }}
            </a>
        </div>
    </header>

    <div class="container">
        <!-- Alerts -->
        @if (Session::has('success'))
            <div class="alert alert-success">
                <div class="alert-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
                <div class="alert-content">
                    <span>{{ Session::get('success') }}</span>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <div class="alert-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                </div>
                <div class="alert-content">
                    <strong>{{ __('messages.error') }}</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="checkout-layout">
            <!-- Main Form -->
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('stripe.post') }}" method="POST" id="main-form">
                        @csrf
                        
                        <!-- Step 1: User -->
                        <div class="form-section" id="section-user">
                            <div class="section-header">
                                <div class="step-badge">
                                    <span class="step-number">1</span>
                                </div>
                                <div>
                                    <h3 class="section-title">{{ __('messages.select_user') }}</h3>
                                    <p class="section-subtitle">{{ __('messages.select_athlete_hint') }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    {{ __('messages.user') }}
                                </label>
                                <div class="select-wrapper">
                                    <select id="user_id" name="user_id" class="form-select" required>
                                        <option value="">{{ __('messages.select_user_placeholder') }}</option>
                                        @foreach(App\Models\User::role('user')->orderBy('name')->get() as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} {{ $user->phone ? '— '.$user->phone : '' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Gym -->
                        <div class="form-section" id="section-gym">
                            <div class="section-header">
                                <div class="step-badge">
                                    <span class="step-number">2</span>
                                </div>
                                <div>
                                    <h3 class="section-title">{{ __('messages.select_gym') }}</h3>
                                    <p class="section-subtitle">{{ __('messages.select_gym_hint') ?? 'Choisissez la salle d\'entraînement' }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" x2="6" y1="1" y2="4"/><line x1="10" x2="10" y1="1" y2="4"/><line x1="14" x2="14" y1="1" y2="4"/></svg>
                                    {{ __('messages.gym') }}
                                </label>
                                <div class="select-wrapper">
                                    <select id="gym_id" name="gym_id" class="form-select" required>
                                        <option value="">{{ __('messages.select_gym_placeholder') }}</option>
                                        @foreach(App\Models\Gym::with('city')->get() as $gym)
                                            <option value="{{ $gym->id }} | {{ $gym->city->name }}">
                                                {{ $gym->name }} — {{ $gym->city->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group mt-3" id="cash_register_group" style="display: none;">
                                <label class="form-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M7 15h0M2 9.5h20"/><path d="M12 15h0M17 15h0"/></svg>
                                    <span class="label-fr">Caisse</span>
                                    <span class="label-ar">الصندوق</span>
                                </label>
                                <div class="select-wrapper">
                                    <select id="cash_register_id" name="cash_register_id" class="form-select">
                                        <option value="">{{ __('messages.select_cash_register') ?? 'Choisir une caisse...' }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Package -->
                        <div class="form-section" id="section-package">
                            <div class="section-header">
                                <div class="step-badge">
                                    <span class="step-number">3</span>
                                </div>
                                <div>
                                    <h3 class="section-title">{{ __('messages.choose_package') }}</h3>
                                    <p class="section-subtitle">{{ __('messages.choose_package_hint') ?? 'Sélectionnez un forfait d\'entraînement' }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <select id="package_id" name="package_id" class="form-select" required style="display: none;">
                                    <option value="">{{ __('messages.select_package_placeholder') }}</option>
                                    @foreach(App\Models\TrainingPackage::get() as $package)
                                        <option value="{{ $package->id }} | {{ $package->price }}" 
                                                data-price="{{ $package->price }}" 
                                                data-sessions="{{ $package->sessions_number }}"
                                                data-name="{{ $package->name }}">
                                            {{ $package->name }}
                                        </option>
                                    @endforeach
                                </select>
                                
                                <div class="pkg-grid">
                                    @foreach(App\Models\TrainingPackage::take(6)->get() as $package)
                                    <div class="pkg-card" onclick="selectCardPackage(this, '{{ $package->id }} | {{ $package->price }}')">
                                        <div class="pkg-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                        </div>
                                        <div class="pkg-sessions">{{ $package->sessions_number }} {{ __('messages.sessions') }}</div>
                                        <div class="pkg-name">{{ $package->name }}</div>
                                        <div class="pkg-price">{{ number_format($package->price / 100, 2) }} <small>TND</small></div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Confirmation -->
                        <div class="action-area">
                            <label class="checkbox-container">
                                <input type="checkbox" id="terms" required hidden>
                                <span class="custom-checkbox"></span>
                                <span class="checkbox-text">
                                    {{ __('messages.confirm_and_pay') }}
                                    <small>{{ __('messages.confirm_terms_hint') ?? 'En confirmant, vous acceptez les conditions de vente' }}</small>
                                </span>
                            </label>

                            <button type="submit" id="pay-button" class="btn-pay" disabled>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                                <span>{{ __('messages.confirm_purchase') }}</span>
                            </button>
                            
                            <div class="secure-badge">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                {{ __('messages.secure_payment') }}
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Summary Sidebar -->
            <aside class="summary-pane">
                <div class="summary-card">
                    <div class="summary-header">
                        <h3 class="summary-title">
                            <div class="summary-title-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                            </div>
                            {{ __('messages.order_summary') }}
                        </h3>
                    </div>
                    <div class="summary-body">
                        <div class="summary-row">
                            <span class="label">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                {{ __('messages.user') }}
                            </span>
                            <span class="value placeholder" id="summary-user">{{ __('messages.not_selected') }}</span>
                        </div>
                        <div class="summary-row">
                            <span class="label">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" x2="6" y1="1" y2="4"/><line x1="10" x2="10" y1="1" y2="4"/><line x1="14" x2="14" y1="1" y2="4"/></svg>
                                {{ __('messages.gym') }}
                            </span>
                            <span class="value placeholder" id="summary-gym">{{ __('messages.not_selected_f') }}</span>
                        </div>
                        <div class="summary-row">
                            <span class="label">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                {{ __('messages.package') }}
                            </span>
                            <span class="value placeholder" id="summary-pkg">{{ __('messages.not_selected') }}</span>
                        </div>
                        <div class="summary-row">
                            <span class="label">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                {{ __('messages.sessions') }}
                            </span>
                            <span class="value placeholder" id="summary-sessions">—</span>
                        </div>

                        <div class="total-box">
                            <span class="total-label">{{ __('messages.total_price') }}</span>
                            <span class="total-amount" id="summary-total">0.00 <span class="currency">TND</span></span>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const userSelect = document.getElementById('user_id');
    const gymSelect = document.getElementById('gym_id');
    const cashRegisterSelect = document.getElementById('cash_register_id');
    const cashRegisterGroup = document.getElementById('cash_register_group');
    const pkgSelect = document.getElementById('package_id');
    const termsCheck = document.getElementById('terms');
    const payBtn = document.getElementById('pay-button');
    const payBtnText = payBtn.querySelector('span');
    
    // Summary Elements
    const sUser = document.getElementById('summary-user');
    const sGym = document.getElementById('summary-gym');
    const sPkg = document.getElementById('summary-pkg');
    const sSess = document.getElementById('summary-sessions');
    const sTotal = document.getElementById('summary-total');
    
    // Data attributes
    const container = document.querySelector('.checkout-page');
    const msgs = {
        notSelected: container.dataset.langNotSelected,
        notSelectedF: container.dataset.langNotSelectedF,
        sessions: container.dataset.langSessions,
        confirm: container.dataset.langConfirm,
        confirmPurchase: container.dataset.langConfirmPurchase
    };

    function updateState() {
        // User
        if (userSelect.value) {
            const txt = userSelect.options[userSelect.selectedIndex].text;
            sUser.textContent = txt.split('—')[0].trim();
            sUser.classList.remove('placeholder');
            sUser.classList.add('filled');
        } else {
            sUser.textContent = msgs.notSelected;
            sUser.classList.add('placeholder');
            sUser.classList.remove('filled');
        }

        // Gym
        if (gymSelect.value) {
            const txt = gymSelect.options[gymSelect.selectedIndex].text;
            sGym.textContent = txt.trim();
            sGym.classList.remove('placeholder');
            sGym.classList.add('filled');
        } else {
            sGym.textContent = msgs.notSelectedF;
            sGym.classList.add('placeholder');
            sGym.classList.remove('filled');
        }

        // Package
        let price = 0;
        if (pkgSelect.value) {
            const opt = pkgSelect.options[pkgSelect.selectedIndex];
            price = parseFloat(opt.getAttribute('data-price'));
            
            sPkg.textContent = opt.getAttribute('data-name');
            sPkg.classList.remove('placeholder');
            sPkg.classList.add('filled');
            
            sSess.textContent = opt.getAttribute('data-sessions') + ' ' + msgs.sessions;
            sSess.classList.remove('placeholder');
            sSess.classList.add('filled');
            
            sTotal.innerHTML = (price / 100).toFixed(2) + ' <span class="currency">TND</span>';
            
            payBtnText.textContent = msgs.confirm + ' — ' + (price / 100).toFixed(2) + ' TND';
        } else {
            sPkg.textContent = msgs.notSelected;
            sPkg.classList.add('placeholder');
            sPkg.classList.remove('filled');
            sSess.textContent = '—';
            sSess.classList.add('placeholder');
            sSess.classList.remove('filled');
            sTotal.innerHTML = '0.00 <span class="currency">TND</span>';
            payBtnText.textContent = msgs.confirmPurchase;
        }

        // Button state
        const valid = userSelect.value && gymSelect.value && pkgSelect.value && termsCheck.checked;
        payBtn.disabled = !valid;
    }

    // Attach events
    userSelect.addEventListener('change', updateState);
    
    gymSelect.addEventListener('change', function() {
        updateState();
        
        const gymVal = this.value;
        if (!gymVal) {
            cashRegisterGroup.style.display = 'none';
            cashRegisterSelect.innerHTML = '<option value="">' + (msgs.selectCashRegister || 'Choisir une caisse...') + '</option>';
            return;
        }
        
        const gymId = gymVal.split('|')[0].trim();
        
        // Fetch cash registers
        fetch(`/cash-registers/by-gym/${gymId}`)
            .then(response => response.json())
            .then(data => {
                let html = '<option value="">' + (msgs.selectCashRegister || 'Choisir une caisse...') + '</option>';
                data.forEach(reg => {
                    html += `<option value="${reg.id}">${reg.name}</option>`;
                });
                cashRegisterSelect.innerHTML = html;
                cashRegisterGroup.style.display = data.length > 0 ? 'block' : 'none';
            })
            .catch(err => {
                console.error('Error fetching cash registers:', err);
                cashRegisterGroup.style.display = 'none';
            });
    });

    pkgSelect.addEventListener('change', updateState);
    termsCheck.addEventListener('change', updateState);
    
    // Global function for card selection
    window.selectCardPackage = function(el, val) {
        pkgSelect.value = val;
        pkgSelect.dispatchEvent(new Event('change'));
        
        // Visual selection
        document.querySelectorAll('.pkg-card').forEach(c => c.classList.remove('selected'));
        el.classList.add('selected');
    };

    // Initialize
    updateState();
});
</script>
@endsection