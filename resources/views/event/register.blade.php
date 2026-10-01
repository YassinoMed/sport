@extends('layouts.user-layout')

@section('content')
<div class="content-wrapper pb-5">
    <!-- En-tête -->
    <section class="content-header py-4">
        <div class="container-fluid">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-2">
                            <li class="breadcrumb-item">
                                <a href="/" class="breadcrumb-link">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                    {{ __('messages.home') }}
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('events.index') }}" class="breadcrumb-link">{{ __('messages.events') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('messages.registration') }}</li>
                        </ol>
                    </nav>
                    <h1 class="page-title">{{ __('messages.event_registration_title') }}</h1>
                    <p class="page-subtitle">{{ $event->name }}</p>
                </div>
                <a href="{{ route('events.show', $event->id) }}" class="btn-ghost">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    {{ __('messages.back_to_event') }}
                </a>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Alertes -->
            @if ($errors->any())
                <div class="alert-modern alert-error mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                    <div>
                        <strong>{{ __('messages.error') }}!</strong>
                        <ul class="mb-0 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="row g-4">
                <!-- Colonne gauche - Résumé de l'événement -->
                <div class="col-lg-4">
                    <div class="event-card">
                        <div class="event-header">
                            <div class="event-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><path d="m9 16 2 2 4-4"/></svg>
                            </div>
                            <h2 class="event-name">{{ $event->name }}</h2>
                        </div>

                        <div class="event-details">
                            <div class="detail-item">
                                <div class="detail-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                </div>
                                <div class="detail-content">
                                    <span class="detail-label">{{ __('messages.date_time') }}</span>
                                    <span class="detail-value">{{ $event->start_at->format('d/m/Y') }} {{ __('messages.at_time') }} {{ $event->start_at->format('H:i') }}</span>
                                </div>
                            </div>

                            <div class="detail-item">
                                <div class="detail-icon price-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                </div>
                                <div class="detail-content">
                                    <span class="detail-label">{{ __('messages.registration_fee') }}</span>
                                    <span class="detail-value price">{{ number_format($event->registration_fee / 100, 2) }} {{ __('messages.amount_tnd') }}</span>
                                </div>
                            </div>

                            <div class="detail-item">
                                <div class="detail-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                </div>
                                <div class="detail-content">
                                    <span class="detail-label">{{ __('messages.capacity') }}</span>
                                    <span class="detail-value">{{ $event->capacity > 0 ? $event->capacity . ' ' . __('messages.places') : __('messages.unlimited') }}</span>
                                </div>
                            </div>

                            <div class="detail-item">
                                <div class="detail-icon success-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                </div>
                                <div class="detail-content">
                                    <span class="detail-label">{{ __('messages.already_registered') }}</span>
                                    <span class="detail-value">{{ $event->registrations_count }} {{ __('messages.participants') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Jauge de remplissage -->
                        @if($event->capacity > 0)
                            @php($percentage = min(100, ($event->registrations_count / $event->capacity) * 100))
                            <div class="capacity-gauge">
                                <div class="gauge-header">
                                    <span>{{ __('messages.filling') }}</span>
                                    <span class="gauge-percentage">{{ round($percentage) }}%</span>
                                </div>
                                <div class="gauge-bar">
                                    <div class="gauge-fill {{ $percentage >= 90 ? 'danger' : ($percentage >= 70 ? 'warning' : '') }}" style="width: {{ $percentage }}%"></div>
                                </div>
                                <div class="gauge-info">
                                    <span>{{ $event->registrations_count }} / {{ $event->capacity }}</span>
                                    <span>{{ $event->capacity - $event->registrations_count }} {{ __('messages.remaining_places') }}</span>
                                </div>
                            </div>
                        @endif

                        <!-- Compteur de sélection -->
                        <div class="selection-counter" id="selection-counter">
                            <div class="counter-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                            </div>
                            <div class="counter-content">
                                <span class="counter-label">{{ __('messages.selected_athletes') }}</span>
                                <span class="counter-value" id="selected-count">0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Colonne droite - Formulaire d'inscription -->
                <div class="col-lg-8">
                    <!-- Filtres -->
                    <div class="modern-card mb-4">
                        <div class="card-header-modern card-header-compact">
                            <div class="header-icon header-icon-info">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                            </div>
                            <h3 class="header-title">{{ __('messages.filter_athletes') }}</h3>
                            <button type="button" class="collapse-btn" onclick="toggleFilters()">
                                <svg id="filter-chevron" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                            </button>
                        </div>
                        <div class="filter-content" id="filter-content">
                            <form method="GET" action="{{ route('events.register', $event->id) }}">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label-modern">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                                            {{ __('messages.search') }}
                                        </label>
                                        <input type="text" name="q" class="form-control-modern" value="{{ $filters['q'] ?? '' }}" placeholder="{{ __('messages.athlete_name') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label-modern">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                            {{ __('messages.birth_date_from') }}
                                        </label>
                                        <input type="date" name="birth_from" class="form-control-modern" value="{{ $filters['birth_from'] ?? '' }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label-modern">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                            {{ __('messages.birth_date_to') }}
                                        </label>
                                        <input type="date" name="birth_to" class="form-control-modern" value="{{ $filters['birth_to'] ?? '' }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label-modern">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                            {{ __('messages.category') }}
                                        </label>
                                        <select name="category_id" class="form-select-modern">
                                            <option value="">{{ __('messages.all') }}</option>
                                            @foreach(($categories ?? []) as $cat)
                                                <option value="{{ $cat->id }}" {{ (string)($filters['category_id'] ?? '') === (string)$cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label-modern">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
                                            {{ __('messages.belt') }}
                                        </label>
                                        <select name="belt_name" class="form-select-modern">
                                            <option value="">{{ __('messages.all') }}</option>
                                            @foreach(($beltNames ?? []) as $bn)
                                                <option value="{{ $bn }}" {{ ($filters['belt_name'] ?? '') === $bn ? 'selected' : '' }}>{{ $bn }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label-modern">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v17a1 1 0 0 1-1 1H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v6a1 1 0 0 1-1 1H3"/></svg>
                                            {{ __('messages.min_weight') }}
                                        </label>
                                        <input type="number" step="0.1" name="weight_min" class="form-control-modern" value="{{ $filters['weight_min'] ?? '' }}" placeholder="kg">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label-modern">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v17a1 1 0 0 1-1 1H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v6a1 1 0 0 1-1 1H3"/></svg>
                                            {{ __('messages.max_weight') }}
                                        </label>
                                        <input type="number" step="0.1" name="weight_max" class="form-control-modern" value="{{ $filters['weight_max'] ?? '' }}" placeholder="kg">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end gap-2">
                                        <button type="submit" class="btn-filter">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                                            {{ __('messages.filter') }}
                                        </button>
                                        <a href="{{ route('events.register', $event->id) }}" class="btn-reset">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Formulaire d'inscription -->
                    <div class="modern-card">
                        <div class="card-header-modern">
                            <div class="header-icon header-icon-success">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="header-title">{{ __('messages.select_athletes') }}</h3>
                                <span class="header-subtitle">{{ __('messages.select_athletes_desc') }}</span>
                            </div>
                            <!-- Toggle View Mode -->
                            <div class="view-toggle">
                                <button type="button" class="toggle-btn active" id="btn-dropdown" onclick="setViewMode('dropdown')" title="{{ __('messages.dropdown_list') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                                </button>
                                <button type="button" class="toggle-btn" id="btn-checkbox" onclick="setViewMode('checkbox')" title="{{ __('messages.checkboxes') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                                </button>
                            </div>
                        </div>

                        <div class="card-body-modern">
                            <form action="{{ route('events.storeRegistration', $event->id) }}" method="POST" id="registration-form">
                                @csrf

                                <!-- Dropdown View -->
                                <div class="form-group-modern mb-4" id="dropdown-view">
                                    <label class="form-label-modern" for="user_ids_select">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                        {{ __('messages.select_athletes_multi') }}
                                    </label>
                                    <div class="multiselect-wrapper">
                                        <select class="form-select-modern multiselect" id="user_ids_select" name="user_ids[]" multiple>
                                            @foreach($users as $user)
                                                @php($paid = optional($user->payments->first())->status === 'paid')
                                                @php($cat = optional($user->category)->name)
                                                @php($belt = optional($user->belts->first())->belt_name)
                                                @php($birth = optional($user->birth_date)->format('d/m/Y'))
                                                @php($w = $user->weight_kg)
                                                <option value="{{ $user->id }}" data-paid="{{ $paid ? '1' : '0' }}" data-category="{{ $cat }}" data-belt="{{ $belt }}">
                                                    {{ $user->name }} — {{ $birth ?: '-' }} — {{ $cat ?: '-' }} — {{ $belt ?: '-' }} — {{ $w ? $w.' kg' : '-' }}{{ $paid ? '' : ' — ' . __('messages.non_paid') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <p class="form-hint">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                                        {{ __('messages.select_multi_desc') }}
                                    </p>
                                </div>

                                <!-- Checkbox View -->
                                <div class="form-group-modern mb-4" id="checkbox-view" style="display: none;">
                                    <label class="form-label-modern">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                        {{ __('messages.select_athletes') }}
                                    </label>
                                    
                                    <!-- Quick Actions -->
                                    <div class="quick-actions mb-3">
                                        <button type="button" class="btn-quick" onclick="selectAll()">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                                            {{ __('messages.select_all') }}
                                        </button>
                                        <button type="button" class="btn-quick" onclick="deselectAll()">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/></svg>
                                            {{ __('messages.deselect_all') }}
                                        </button>
                                    </div>

                                    <!-- Search in checkboxes -->
                                    <div class="checkbox-search mb-3">
                                        <input type="text" class="form-control-modern" id="checkbox-search" placeholder="{{ __('messages.search_athlete') }}" oninput="filterCheckboxes()">
                                    </div>

                                    <div class="checkbox-list" id="checkbox-list">
                                        @foreach($users as $user)
                                            @php($paid = optional($user->payments->first())->status === 'paid')
                                            @php($cat = optional($user->category)->name)
                                            @php($belt = optional($user->belts->first())->belt_name)
                                            @php($birth = optional($user->birth_date)->format('d/m/Y'))
                                            @php($w = $user->weight_kg)
                                            <label class="checkbox-item" data-name="{{ strtolower($user->name) }}">
                                                <input type="checkbox" class="checkbox-modern athlete-checkbox" name="user_ids[]" value="{{ $user->id }}" onchange="updateSelectionCount()">
                                                <div class="checkbox-content">
                                                    <div class="athlete-avatar">
                                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                                    </div>
                                                    <div class="athlete-info">
                                                        <span class="athlete-name">{{ $user->name }}</span>
                                                        <div class="athlete-meta">
                                                            <span>{{ $birth ?: '-' }}</span>
                                                            <span>{{ $cat ?: '-' }}</span>
                                                            <span>{{ $belt ?: '-' }}</span>
                                                            <span>{{ $w ? $w.' kg' : '-' }}</span>
                                                            @unless(auth()->user()->hasRole('coach'))
                                                                @if(!$paid)
                                                                    <span class="badge-unpaid">{{ __('messages.non_paid') }}</span>
                                                                @endif
                                                            @endunless
                                                        </div>
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="form-group-modern mb-4">
                                    <label class="form-label-modern" for="notes">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/></svg>
                                        {{ __('messages.notes_optional') }}
                                    </label>
                                    <textarea class="form-textarea-modern" id="notes" name="notes" rows="3" placeholder="{{ __('messages.additional_info') }}"></textarea>
                                </div>

                                <div class="terms-checkbox mb-4">
                                    <input type="checkbox" class="checkbox-modern" id="terms" required>
                                    <label for="terms" class="terms-label">
                                        {{ __('messages.i_accept_the') }} <a href="#" class="terms-link">{{ __('messages.registration_terms') }}</a> {{ __('messages.to_this_event') }}
                                    </label>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn-submit" id="submit-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                        <span id="submit-text">{{ __('messages.confirm_registration') }}</span>
                                    </button>
                                    <a href="{{ route('events.show', $event->id) }}" class="btn-ghost">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                        {{ __('messages.cancel') }}
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
let viewMode = 'dropdown';

function toggleFilters() {
    const content = document.getElementById('filter-content');
    const chevron = document.getElementById('filter-chevron');
    content.classList.toggle('collapsed');
    chevron.style.transform = content.classList.contains('collapsed') ? 'rotate(-90deg)' : '';
}

function setViewMode(mode) {
    viewMode = mode;
    const dropdownView = document.getElementById('dropdown-view');
    const checkboxView = document.getElementById('checkbox-view');
    const btnDropdown = document.getElementById('btn-dropdown');
    const btnCheckbox = document.getElementById('btn-checkbox');
    
    if (mode === 'dropdown') {
        dropdownView.style.display = 'block';
        checkboxView.style.display = 'none';
        btnDropdown.classList.add('active');
        btnCheckbox.classList.remove('active');
        
        // Sync selection from checkboxes to dropdown
        syncCheckboxesToDropdown();
    } else {
        dropdownView.style.display = 'none';
        checkboxView.style.display = 'block';
        btnDropdown.classList.remove('active');
        btnCheckbox.classList.add('active');
        
        // Sync selection from dropdown to checkboxes
        syncDropdownToCheckboxes();
    }
    updateSelectionCount();
}

function syncDropdownToCheckboxes() {
    const select = document.getElementById('user_ids_select');
    const checkboxes = document.querySelectorAll('.athlete-checkbox');
    const selectedValues = Array.from(select.selectedOptions).map(opt => opt.value);
    
    checkboxes.forEach(cb => {
        cb.checked = selectedValues.includes(cb.value);
    });
}

function syncCheckboxesToDropdown() {
    const select = document.getElementById('user_ids_select');
    const checkboxes = document.querySelectorAll('.athlete-checkbox:checked');
    const selectedValues = Array.from(checkboxes).map(cb => cb.value);
    
    Array.from(select.options).forEach(opt => {
        opt.selected = selectedValues.includes(opt.value);
    });
}

function updateSelectionCount() {
    let count = 0;
    if (viewMode === 'dropdown') {
        count = document.getElementById('user_ids_select').selectedOptions.length;
    } else {
        count = document.querySelectorAll('.athlete-checkbox:checked').length;
    }
    
    document.getElementById('selected-count').textContent = count;
    document.getElementById('submit-text').textContent = count > 1 
        ? `{{ __('messages.confirm_n_registrations', ['count' => ':count']) }}`.replace(':count', count)
        : `{{ __('messages.confirm_registration') }}`;
    
    // Update counter style
    const counter = document.getElementById('selection-counter');
    if (count > 0) {
        counter.classList.add('has-selection');
    } else {
        counter.classList.remove('has-selection');
    }
}

function selectAll() {
    document.querySelectorAll('.athlete-checkbox').forEach(cb => {
        if (cb.closest('.checkbox-item').style.display !== 'none') {
            cb.checked = true;
        }
    });
    updateSelectionCount();
}

function deselectAll() {
    document.querySelectorAll('.athlete-checkbox').forEach(cb => cb.checked = false);
    updateSelectionCount();
}

function filterCheckboxes() {
    const search = document.getElementById('checkbox-search').value.toLowerCase();
    document.querySelectorAll('.checkbox-item').forEach(item => {
        const name = item.dataset.name;
        item.style.display = name.includes(search) ? '' : 'none';
    });
}

// Listen for dropdown changes
document.getElementById('user_ids_select').addEventListener('change', updateSelectionCount);

// Initial count
updateSelectionCount();
</script>

@push('styles')
<style>
:root {
    --primary: #6366f1;
    --primary-dark: #4f46e5;
    --primary-light: #a5b4fc;
    --primary-bg: #eef2ff;
    --success: #10b981;
    --success-light: #d1fae5;
    --success-dark: #059669;
    --warning: #f59e0b;
    --warning-light: #fef3c7;
    --danger: #ef4444;
    --danger-light: #fee2e2;
    --info: #06b6d4;
    --info-light: #cffafe;
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
    --radius-sm: 6px;
    --radius-md: 10px;
    --radius-lg: 16px;
    --radius-xl: 24px;
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    --transition: all 0.2s ease;
}

/* En-tête */
.page-title { font-size: 1.75rem; font-weight: 700; color: var(--gray-900); margin: 0; }
.page-subtitle { font-size: 1rem; color: var(--primary); font-weight: 600; margin: 0.25rem 0 0; }
.breadcrumb { padding: 0; margin: 0; background: transparent; font-size: 0.875rem; }
.breadcrumb-item + .breadcrumb-item::before { content: "›"; color: var(--gray-400); }
.breadcrumb-link { color: var(--gray-500); text-decoration: none; display: inline-flex; align-items: center; gap: 0.375rem; transition: var(--transition); }
.breadcrumb-link:hover { color: var(--primary); }

/* Alertes */
.alert-modern { display: flex; align-items: flex-start; gap: 0.75rem; padding: 1rem 1.25rem; border-radius: var(--radius-md); font-weight: 500; }
.alert-error { background: var(--danger-light); color: #991b1b; }

/* Boutons */
.btn-ghost { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1.25rem; background: transparent; color: var(--gray-600); font-weight: 500; font-size: 0.875rem; border: 1px solid var(--gray-300); border-radius: var(--radius-md); cursor: pointer; transition: var(--transition); text-decoration: none; }
.btn-ghost:hover { background: var(--gray-100); color: var(--gray-700); }

.btn-submit { display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: linear-gradient(135deg, var(--success) 0%, var(--success-dark) 100%); color: white; font-weight: 600; font-size: 0.9375rem; border: none; border-radius: var(--radius-md); cursor: pointer; transition: var(--transition); box-shadow: 0 4px 14px 0 rgba(16, 185, 129, 0.4); }
.btn-submit:hover { transform: translateY(-2px); box-shadow: 0 6px 20px 0 rgba(16, 185, 129, 0.5); }

.btn-filter { display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.5rem 1rem; background: var(--primary); color: white; font-weight: 600; font-size: 0.8125rem; border: none; border-radius: var(--radius-md); cursor: pointer; transition: var(--transition); }
.btn-filter:hover { background: var(--primary-dark); }

.btn-reset { display: inline-flex; align-items: center; justify-content: center; width: 38px; height: 38px; background: var(--gray-100); color: var(--gray-600); border: none; border-radius: var(--radius-md); cursor: pointer; transition: var(--transition); text-decoration: none; }
.btn-reset:hover { background: var(--gray-200); color: var(--gray-700); }

.btn-quick { display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.375rem 0.75rem; background: var(--gray-100); color: var(--gray-700); font-weight: 500; font-size: 0.75rem; border: 1px solid var(--gray-200); border-radius: var(--radius-sm); cursor: pointer; transition: var(--transition); }
.btn-quick:hover { background: var(--primary-bg); color: var(--primary); border-color: var(--primary-light); }

/* Carte événement */
.event-card { background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); overflow: hidden; }
.event-header { background: linear-gradient(135deg, var(--primary) 0%, #818cf8 100%); padding: 2rem; text-align: center; }
.event-icon { width: 64px; height: 64px; background: rgba(255,255,255,0.2); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; color: white; margin: 0 auto 1rem; }
.event-name { color: white; font-size: 1.25rem; font-weight: 700; margin: 0; }
.event-details { padding: 1.25rem; }
.detail-item { display: flex; align-items: center; gap: 1rem; padding: 0.875rem 0; border-bottom: 1px solid var(--gray-100); }
.detail-item:last-child { border-bottom: none; }
.detail-icon { width: 40px; height: 40px; background: var(--gray-100); border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; color: var(--gray-600); flex-shrink: 0; }
.detail-icon.price-icon { background: var(--success-light); color: var(--success); }
.detail-icon.success-icon { background: var(--primary-bg); color: var(--primary); }
.detail-content { display: flex; flex-direction: column; }
.detail-label { font-size: 0.75rem; color: var(--gray-500); font-weight: 600; text-transform: uppercase; letter-spacing: 0.025em; }
.detail-value { font-weight: 600; color: var(--gray-900); }
.detail-value.price { color: var(--success-dark); font-size: 1.125rem; }

/* Jauge de capacité */
.capacity-gauge { padding: 1.25rem; background: var(--gray-50); border-top: 1px solid var(--gray-100); }
.gauge-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; font-size: 0.8125rem; font-weight: 600; color: var(--gray-700); }
.gauge-percentage { color: var(--primary); }
.gauge-bar { height: 8px; background: var(--gray-200); border-radius: 9999px; overflow: hidden; }
.gauge-fill { height: 100%; background: linear-gradient(90deg, var(--success) 0%, #34d399 100%); border-radius: 9999px; transition: width 0.5s ease; }
.gauge-fill.warning { background: linear-gradient(90deg, var(--warning) 0%, #fbbf24 100%); }
.gauge-fill.danger { background: linear-gradient(90deg, var(--danger) 0%, #f87171 100%); }
.gauge-info { display: flex; justify-content: space-between; margin-top: 0.5rem; font-size: 0.75rem; color: var(--gray-500); }

/* Selection counter */
.selection-counter { display: flex; align-items: center; gap: 1rem; padding: 1rem 1.25rem; background: var(--gray-50); border-top: 1px solid var(--gray-100); transition: var(--transition); }
.selection-counter.has-selection { background: var(--success-light); }
.counter-icon { width: 40px; height: 40px; background: white; border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; color: var(--gray-500); box-shadow: var(--shadow-sm); }
.selection-counter.has-selection .counter-icon { color: var(--success); }
.counter-content { display: flex; flex-direction: column; }
.counter-label { font-size: 0.75rem; color: var(--gray-500); font-weight: 600; text-transform: uppercase; letter-spacing: 0.025em; }
.counter-value { font-size: 1.5rem; font-weight: 700; color: var(--gray-900); }
.selection-counter.has-selection .counter-value { color: var(--success-dark); }

/* Carte moderne */
.modern-card { background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); overflow: hidden; }
.card-header-modern { display: flex; align-items: center; gap: 1rem; padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--gray-100); }
.card-header-compact { padding: 1rem 1.25rem; }
.header-icon { width: 44px; height: 44px; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.header-icon-success { background: var(--success-light); color: var(--success); }
.header-icon-info { background: var(--info-light); color: var(--info); }
.header-title { font-size: 1.125rem; font-weight: 700; color: var(--gray-900); margin: 0; }
.header-subtitle { font-size: 0.8125rem; color: var(--gray-500); }
.collapse-btn { margin-left: auto; background: none; border: none; cursor: pointer; color: var(--gray-400); padding: 0.25rem; transition: var(--transition); }
.collapse-btn:hover { color: var(--gray-600); }
#filter-chevron { transition: transform 0.3s ease; }

/* View Toggle */
.view-toggle { display: flex; gap: 0.25rem; background: var(--gray-100); padding: 0.25rem; border-radius: var(--radius-sm); }
.toggle-btn { display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; background: transparent; border: none; border-radius: var(--radius-sm); cursor: pointer; color: var(--gray-500); transition: var(--transition); }
.toggle-btn:hover { color: var(--gray-700); }
.toggle-btn.active { background: white; color: var(--primary); box-shadow: var(--shadow-sm); }

/* Contenu des filtres */
.filter-content { padding: 1.25rem; transition: max-height 0.3s ease, padding 0.3s ease; overflow: hidden; max-height: 500px; }
.filter-content.collapsed { max-height: 0; padding-top: 0; padding-bottom: 0; }

.card-body-modern { padding: 1.5rem; }

/* Formulaire */
.form-group-modern { margin-bottom: 0; }
.form-label-modern { display: flex; align-items: center; gap: 0.5rem; font-size: 0.8125rem; font-weight: 600; color: var(--gray-700); margin-bottom: 0.5rem; }
.form-label-modern svg { color: var(--gray-400); }

.form-control-modern, .form-select-modern, .form-textarea-modern { width: 100%; padding: 0.625rem 0.875rem; font-size: 0.875rem; color: var(--gray-900); background: white; border: 2px solid var(--gray-200); border-radius: var(--radius-md); transition: var(--transition); }
.form-control-modern:hover, .form-select-modern:hover, .form-textarea-modern:hover { border-color: var(--gray-300); }
.form-control-modern:focus, .form-select-modern:focus, .form-textarea-modern:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15); }
.form-textarea-modern { resize: vertical; min-height: 80px; }

/* Multi-select styling */
.multiselect-wrapper { position: relative; }
.multiselect { min-height: 150px; cursor: pointer; }
.multiselect option { padding: 0.5rem; }
.multiselect option:checked { background: var(--primary-bg); color: var(--primary-dark); }

.form-hint { display: flex; align-items: center; gap: 0.375rem; margin-top: 0.5rem; font-size: 0.8125rem; color: var(--gray-500); }
.form-hint svg { flex-shrink: 0; }

/* Checkbox list styling */
.checkbox-list { max-height: 400px; overflow-y: auto; border: 2px solid var(--gray-200); border-radius: var(--radius-md); }
.checkbox-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-bottom: 1px solid var(--gray-100); cursor: pointer; transition: var(--transition); }
.checkbox-item:last-child { border-bottom: none; }
.checkbox-item:hover { background: var(--gray-50); }
.checkbox-item:has(.checkbox-modern:checked) { background: var(--primary-bg); }

.checkbox-content { display: flex; align-items: center; gap: 0.75rem; flex: 1; }
.athlete-avatar { width: 40px; height: 40px; background: linear-gradient(135deg, var(--primary) 0%, #818cf8 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.75rem; flex-shrink: 0; }
.athlete-info { display: flex; flex-direction: column; min-width: 0; }
.athlete-name { font-weight: 600; color: var(--gray-900); }
.athlete-meta { display: flex; flex-wrap: wrap; gap: 0.5rem; font-size: 0.75rem; color: var(--gray-500); }
.athlete-meta span { white-space: nowrap; }
.badge-unpaid { background: var(--danger-light); color: var(--danger); padding: 0.125rem 0.375rem; border-radius: var(--radius-sm); font-weight: 600; }

.quick-actions { display: flex; gap: 0.5rem; flex-wrap: wrap; }

.checkbox-search { position: relative; }
.checkbox-search input { padding-left: 2.5rem; }
.checkbox-search::before { content: ''; position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%239ca3af' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cpath d='m21 21-4.3-4.3'/%3E%3C/svg%3E"); background-repeat: no-repeat; }

/* Checkbox */
.terms-checkbox { display: flex; align-items: flex-start; gap: 0.75rem; }
.checkbox-modern { width: 20px; height: 20px; accent-color: var(--primary); cursor: pointer; flex-shrink: 0; margin-top: 0.125rem; }
.terms-label { font-size: 0.9375rem; color: var(--gray-600); line-height: 1.5; cursor: pointer; }
.terms-link { color: var(--primary); text-decoration: none; font-weight: 500; }
.terms-link:hover { text-decoration: underline; }

/* Actions */
.form-actions { display: flex; gap: 1rem; flex-wrap: wrap; }

/* Scrollbar styling */
.checkbox-list::-webkit-scrollbar { width: 6px; }
.checkbox-list::-webkit-scrollbar-track { background: var(--gray-100); border-radius: 3px; }
.checkbox-list::-webkit-scrollbar-thumb { background: var(--gray-300); border-radius: 3px; }
.checkbox-list::-webkit-scrollbar-thumb:hover { background: var(--gray-400); }

/* Responsive */
@media (max-width: 992px) {
    .page-title { font-size: 1.375rem; }
    .event-header { padding: 1.5rem; }
    .event-icon { width: 52px; height: 52px; }
}

@media (max-width: 768px) {
    .form-actions { flex-direction: column; }
    .form-actions .btn-submit, .form-actions .btn-ghost { width: 100%; justify-content: center; }
    .filter-content .row > div { margin-bottom: 0.5rem; }
    .card-header-modern { flex-wrap: wrap; gap: 0.75rem; }
    .view-toggle { order: 3; width: 100%; justify-content: center; }
}
</style>
@endpush
@endsection