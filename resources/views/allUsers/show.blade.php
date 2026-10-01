@extends('layouts.user-layout')
@section('content')
<div class="content-wrapper pb-5 {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <!-- Header Section -->
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
                                <a href="{{ route('allUsers.list') }}" class="breadcrumb-link">
                                    {{ __('messages.users') }}
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                {{ __('messages.profile') }}
                            </li>
                        </ol>
                    </nav>
                    <h1 class="page-title">
                        {{ __('messages.user_profile') }}
                        <span class="user-id-badge">#{{ $singleUser->id }}</span>
                    </h1>
                </div>
                <a href="{{ route('allUsers.list') }}" class="btn-ghost">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    {{ __('messages.back_to_list') }}
                </a>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert-modern alert-success mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="row g-4">
                <!-- Profile Card -->
                <div class="col-lg-4">
                    <div class="profile-card">
                        <div class="profile-header">
                            <div class="profile-avatar-wrapper {{ !$singleUser->profile_image ? 'initials-avatar' : '' }}">
                                @if($singleUser->profile_image)
                                    <img src="{{ asset($singleUser->profile_image) }}" alt="{{ $singleUser->name }}" class="profile-avatar" onerror="this.style.display='none'; this.parentElement.classList.add('initials-avatar');">
                                    <span class="profile-avatar-initials">{{ mb_substr($singleUser->name, 0, 1) }}</span>
                                @else
                                    <span class="profile-avatar-initials">{{ mb_substr($singleUser->name, 0, 1) }}</span>
                                @endif
                                <span class="avatar-status-indicator"></span>
                            </div>
                            <h2 class="profile-name">{{ $singleUser->name }}</h2>
                            <div class="profile-phone">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                {{ $singleUser->phone }}
                            </div>
                            @if(!$singleUser->has_insurance || !$singleUser->has_stage)
                                <div class="mt-3 d-flex flex-wrap justify-content-center gap-2">
                                    @if(!$singleUser->has_insurance)
                                        <span class="badge bg-danger text-white px-3 py-2 d-flex align-items-center gap-2" style="font-size: 0.8rem; border-radius: 8px;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                            {{ __('messages.has_insurance') }} : {{ __('messages.no') }}
                                        </span>
                                    @endif
                                    @if(!$singleUser->has_stage)
                                        <span class="badge bg-warning text-dark px-3 py-2 d-flex align-items-center gap-2" style="font-size: 0.8rem; border-radius: 8px;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                                            {{ __('messages.has_stage') }} : {{ __('messages.no') }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div class="profile-actions">
                            <a href="{{ route('allUsers.edit', $singleUser->id) }}" class="action-card action-edit">
                                <div class="action-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                </div>
                                <div class="action-text">
                                    {{ __('messages.edit') }}
                                </div>
                            </a>
                            @unless(auth()->user()->hasRole('coach') || $singleUser->hasRole('coach'))
                            <a href="{{ route('user-payments.create', $singleUser->id) }}" class="action-card action-payment">
                                <div class="action-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                                </div>
                                <div class="action-text">
                                    {{ __('messages.add_payment') }}
                                </div>
                            </a>
                            @endunless
                            <a href="{{ route('allUsers.report', $singleUser->id) }}" class="action-card action-report">
                                <div class="action-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
                                </div>
                                <div class="action-text">
                                    {{ __('messages.detailed_report') }}
                                </div>
                            </a>
                            <a href="{{ route('athlete-tests.user', $singleUser->id) }}" class="action-card action-test">
                                <div class="action-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><path d="M12 18v-6"/><path d="M8 18v-1"/><path d="M16 18v-3"/></svg>
                                </div>
                                <div class="action-text">
                                    {{ __('messages.tests') }}
                                </div>
                            </a>

                            <!-- QR Code for Attendance -->
                            <div class="action-card action-qr mt-3 p-3 text-center" style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; cursor: default;">
                                <div id="qrcode" class="d-flex justify-content-center mb-2"></div>
                                <div class="small text-muted font-weight-bold">QR Attendance</div>
                                <div class="mt-1">
                                    <button class="btn btn-sm btn-outline-primary" onclick="printQR()">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                        Imprimer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="col-lg-8">
                    <div class="modern-card h-100">
                        <div class="card-header-modern">
                            <div class="header-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                            </div>
                            <div>
                                <h3 class="header-title">
                                    {{ __('messages.informations') }}
                                </h3>
                            </div>
                        </div>
                        <div class="card-body-modern">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="info-block">
                                        <div class="info-item">
                                            <div class="info-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                            </div>
                                            <div class="info-content">
                                                <span class="info-label">
                                                    {{ __('messages.full_name') }}
                                                </span>
                                                <span class="info-value">{{ $singleUser->name }}</span>
                                            </div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                            </div>
                                            <div class="info-content">
                                                <span class="info-label">
                                                    {{ __('messages.phone') }}
                                                </span>
                                                <span class="info-value">{{ $singleUser->phone }}</span>
                                            </div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-icon">
                                                @if($singleUser->gender == 'male')
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="10" cy="14" r="5"/><path d="M19 5 13.6 10.4"/><path d="M15 5h4v4"/></svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="5"/><path d="M12 13v9"/><path d="M9 18h6"/></svg>
                                                @endif
                                            </div>
                                            <div class="info-content">
                                                <span class="info-label">
                                                    {{ __('messages.gender') }}
                                                </span>
                                                <span class="info-value">
                                                    @if($singleUser->gender == 'male')
                                                        <span class="gender-tag male">
                                                            {{ __('messages.male') }}
                                                        </span>
                                                    @elseif($singleUser->gender == 'female')
                                                        <span class="gender-tag female">
                                                            {{ __('messages.female') }}
                                                        </span>
                                                    @else
                                                        —
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                            </div>
                                            <div class="info-content">
                                                <span class="info-label">
                                                    {{ __('messages.birth_date') }}
                                                </span>
                                                <span class="info-value">{{ $singleUser->birth_date ? $singleUser->birth_date->format('d M Y') : '—' }}</span>
                                            </div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v17a1 1 0 0 1-1 1H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v6a1 1 0 0 1-1 1H3"/><path d="m16 19 2 2 4-4"/></svg>
                                            </div>
                                            <div class="info-content">
                                                <span class="info-label">
                                                    {{ __('messages.weight') }}
                                                </span>
                                                <span class="info-value">{{ $singleUser->weight_kg ? number_format($singleUser->weight_kg, 2) . ' kg' : '—' }}</span>
                                            </div>
                                        </div>
                                        @hasanyrole('admin|cityManager|gymManager')
                                        <div class="info-item">
                                            <div class="info-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                            </div>
                                            <div class="info-content">
                                                <span class="info-label">
                                                    {{ __('messages.num_dep') }}
                                                </span>
                                                <span class="info-value">{{ $singleUser->num_dep ?? '—' }}</span>
                                            </div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                            </div>
                                            <div class="info-content">
                                                <span class="info-label">
                                                    {{ __('messages.has_insurance') }}
                                                </span>
                                                <span class="info-value">
                                                    @if($singleUser->has_insurance)
                                                        <span class="badge bg-success-light text-success border">
                                                            {{ __('messages.yes') }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-danger text-white border-0 px-3">
                                                            {{ __('messages.no') }}
                                                        </span>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>

                                        <div class="info-item">
                                            <div class="info-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                                            </div>
                                            <div class="info-content">
                                                <span class="info-label">
                                                    {{ __('messages.has_stage') }}
                                                </span>
                                                <span class="info-value">
                                                    @if($singleUser->has_stage)
                                                        <span class="badge bg-success-light text-success border">
                                                            {{ __('messages.yes') }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning text-dark border-0 px-3">
                                                            {{ __('messages.no') }}
                                                        </span>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                        @endhasanyrole
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-block">
                                        <div class="info-item">
                                            <div class="info-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2H2v10l9.29 9.29c.94.94 2.48.94 3.42 0l6.58-6.58c.94-.94.94-2.48 0-3.42L12 2Z"/><path d="M7 7h.01"/></svg>
                                            </div>
                                            <div class="info-content">
                                                <span class="info-label">
                                                    {{ __('messages.category') }}
                                                </span>
                                                <span class="info-value">
                                                    @if($singleUser->category)
                                                        <span class="tag tag-category">{{ $singleUser->category->name }}</span>
                                                    @else
                                                        —
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                            </div>
                                            <div class="info-content">
                                                <span class="info-label">
                                                    {{ __('messages.group') }}
                                                </span>
                                                @php $currentGroup = ($singleUser->groups ?? collect())->first(); @endphp
                                                <span class="info-value mb-2">
                                                    @if($currentGroup)
                                                        <span class="tag tag-group">{{ $currentGroup->name }}</span>
                                                    @else
                                                        —
                                                    @endif
                                                </span>
                                                <form method="POST" action="{{ route('allUsers.group.assign', $singleUser->id) }}" class="group-assign-form">
                                                    @csrf
                                                    <select name="group_id" class="form-select-modern form-select-sm">
                                                        <option value="">
                                                            {{ __('messages.no_group') }}
                                                        </option>
                                                        @foreach(($groups ?? []) as $g)
                                                            <option value="{{ $g->id }}" {{ (string)old('group_id', $currentGroupId ?? '') === (string)$g->id ? 'selected' : '' }}>
                                                                {{ $g->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <button type="submit" class="btn-assign">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="info-item belt-info">
                                            <div class="info-icon belt-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>
                                            </div>
                                            <div class="info-content">
                                                <span class="info-label">
                                                    {{ __('messages.last_belt') }}
                                                </span>
                                                @php $lastBelt = ($singleUser->belts ?? collect())->sortByDesc('achieved_at')->first(); @endphp
                                                <span class="info-value">
                                                    @if($lastBelt)
                                                        <span class="belt-badge">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>
                                                            {{ $lastBelt->belt_name }}
                                                        </span>
                                                    @else
                                                        —
                                                    @endif
                                                </span>
                                                @if($lastBelt && $lastBelt->achieved_at)
                                                    <span class="belt-date">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                                        {{ __('messages.obtained_at') }} : {{ $lastBelt->achieved_at->format('d M Y') }}
                                                    </span>
                                                @endif
                                                <span class="belt-count">
                                                    {{ __('messages.total') }}: {{ ($singleUser->belts ?? collect())->count() }}
                                                    {{ __('messages.belts_count') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Payments -->
            @unless(auth()->user()->hasRole('coach') || $singleUser->hasRole('coach'))
            <div class="row g-4 mt-0">
                <div class="col-12">
                    <div class="modern-card">
                        <div class="card-header-modern">
                            <div class="header-icon payment-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                            </div>
                            <div>
                                <h3 class="header-title">
                                    {{ __('messages.recent_payments') }}
                                </h3>
                            </div>
                            <a href="{{ route('user-payments.index', $singleUser->id) }}" class="header-action">
                                {{ __('messages.view_all') }}
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </a>
                        </div>
                        <div class="table-container">
                            <table class="modern-table">
                                <thead>
                                    <tr>
                                        <th>
                                            {{ __('messages.month') }}
                                        </th>
                                        <th>
                                            {{ __('messages.amount') }}
                                        </th>
                                        <th>
                                            {{ __('messages.status') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(\Illuminate\Support\Facades\Schema::hasTable('user_payments'))
                                        @php $recent = $singleUser->payments()->orderByDesc('billing_month')->limit(5)->get(); @endphp
                                        @forelse($recent as $p)
                                            <tr>
                                                <td>
                                                    <div class="month-cell">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                                        {{ \Illuminate\Support\Carbon::parse($p->billing_month)->format('M Y') }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="amount-value">{{ number_format($p->amount_cents / 100, 2, ',', ' ') }} TND</span>
                                                </td>
                                                <td>
                                                    <span class="status-badge status-{{ strtolower($p->status) }}">
                                                        @if(strtolower($p->status) === 'paid')
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                                            <span class="label-status">
                                                                {{ __('messages.paid') }}
                                                            </span>
                                                        @else
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                                                            <span class="label-status">
                                                                {{ __('messages.pending') }}
                                                            </span>
                                                        @endif
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="empty-cell">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                                                    <span class="label-empty">
                                                        {{ __('messages.no_payment') }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforelse
                                    @else
                                        <tr>
                                            <td colspan="3" class="empty-cell warning">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                                                <span class="label-warning">
                                                    {{ __('messages.payment_module_not_migrated') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endunless
            
            <!-- Belts Section -->
            <div class="row g-4 mt-0">
                <div class="col-12">
                    <div class="modern-card">
                        <div class="card-header-modern">
                            <div class="header-icon belt-header-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>
                            </div>
                            <div>
                                <h3 class="header-title">
                                    {{ __('messages.belts') }}
                                </h3>
                            </div>
                            <div class="header-actions">
                                <a href="{{ route('belt.create') }}" class="btn-primary-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                                    {{ __('messages.add') }}
                                </a>
                                <a href="{{ route('allUsers.report', $singleUser->id) }}" class="btn-ghost-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                                    {{ __('messages.reports') }}
                                </a>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="modern-table">
                                <thead>
                                    <tr>
                                        <th>
                                            {{ __('messages.belt') }}
                                        </th>
                                        <th>
                                            {{ __('messages.obtainment') }}
                                        </th>
                                        @unless(auth()->user()->hasRole('coach') || $singleUser->hasRole('coach'))
                                        <th>
                                            {{ __('messages.price') }}
                                        </th>
                                        @endunless
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(($singleUser->belts ?? []) as $b)
                                        <tr>
                                            <td>
                                                <span class="belt-name">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>
                                                    {{ $b->belt_name }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="date-cell">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                                    {{ $b->achieved_at ? $b->achieved_at->format('d M Y') : '—' }}
                                                </div>
                                            </td>
                                            @unless(auth()->user()->hasRole('coach') || $singleUser->hasRole('coach'))
                                            <td>
                                                <span class="amount-value">{{ $b->price_cents !== null ? number_format($b->price_cents/100, 2, ',', ' ') . ' TND' : '—' }}</span>
                                            </td>
                                            @endunless
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ (auth()->user()->hasRole('coach') || $singleUser->hasRole('coach')) ? 2 : 3 }}" class="empty-cell">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>
                                                <span class="label-empty">
                                                    {{ __('messages.no_belt') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Events Participation Section -->
            <div class="row g-4 mt-0">
                <div class="col-12">
                    <div class="modern-card">
                        <div class="card-header-modern">
                            <div class="header-icon bg-info text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/><path d="M16 18h.01"/></svg>
                            </div>
                            <div>
                                <h3 class="header-title bilingual-label">
                                    <span>Événements & Compétitions</span>
                                    <span class="separator">/</span>
                                    <span class="lang-secondary" dir="rtl">الأحداث والمسابقات</span>
                                </h3>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="modern-table">
                                <thead>
                                    <tr>
                                        <th>
                                            <span class="bilingual-label">
                                                <span>Événement</span>
                                                <span class="separator">/</span>
                                                <span class="lang-secondary" dir="rtl">الحدث</span>
                                            </span>
                                        </th>
                                        <th>
                                            <span class="bilingual-label">
                                                <span>Date</span>
                                                <span class="separator">/</span>
                                                <span class="lang-secondary" dir="rtl">التاريخ</span>
                                            </span>
                                        </th>
                                        <th>
                                            <span class="bilingual-label">
                                                <span>Lieu</span>
                                                <span class="separator">/</span>
                                                <span class="lang-secondary" dir="rtl">المكان</span>
                                            </span>
                                        </th>
                                        <th>
                                            <span class="bilingual-label">
                                                <span>Paiement</span>
                                                <span class="separator">/</span>
                                                <span class="lang-secondary" dir="rtl">الدفع</span>
                                            </span>
                                        </th>
                                        <th>
                                            <span class="bilingual-label">
                                                <span>Statut</span>
                                                <span class="separator">/</span>
                                                <span class="lang-secondary" dir="rtl">الحالة</span>
                                            </span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($eventRegistrations ?? [] as $reg)
                                        <tr>
                                            <td>
                                                <a href="{{ route('events.show', optional($reg->event)->id) }}" class="course-name fw-bold">
                                                    {{ optional($reg->event)->name }}
                                                </a>
                                            </td>
                                            <td>
                                                <div class="date-cell">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                                    {{ optional($reg->event)->start_at ? optional($reg->event)->start_at->format('d M Y') : '—' }}
                                                </div>
                                            </td>
                                            <td>{{ optional($reg->event)->location ?? '—' }}</td>
                                            <td>
                                                <span class="amount-value text-nowrap">{{ number_format(($reg->paid_amount ?? 0)/100, 2, ',', ' ') }} TND</span>
                                                @if(optional($reg->event)->registration_fee > 0)
                                                    <small class="text-muted d-block" style="font-size: 0.75rem;">sur {{ number_format($reg->event->registration_fee/100, 2, ',', ' ') }} TND</small>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $isPaid = $reg->paid_amount >= (optional($reg->event)->registration_fee ?? 0);
                                                    $isPartial = $reg->paid_amount > 0 && $reg->paid_amount < (optional($reg->event)->registration_fee ?? 0);
                                                @endphp
                                                @if($isPaid)
                                                    <span class="badge bg-success-light text-success">
                                                        Payé / مدفوع
                                                    </span>
                                                @elseif($isPartial)
                                                    <span class="badge bg-info-light text-info">
                                                        Partiel / جزئي
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning-light text-warning">
                                                        Dû / مستحق
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="empty-cell">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                                                <span class="label-empty">
                                                    Aucune participation / لا توجد مشاركات
                                                </span>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 12 Months Status -->
            @unless(auth()->user()->hasRole('coach') || $singleUser->hasRole('coach'))
            @if(\Illuminate\Support\Facades\Schema::hasTable('user_payments'))
            <div class="row g-4 mt-0">
                <div class="col-12">
                    <div class="modern-card">
                        <div class="card-header-modern">
                            <div class="header-icon calendar-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/><path d="M16 18h.01"/></svg>
                            </div>
                            <div>
                                <h3 class="header-title">
                                    {{ __('messages.last_12_months_status') }}
                                </h3>
                            </div>
                            <button type="button" class="btn-icon-only ms-auto" onclick="toggleCard(this)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="transition: transform 0.3s ease;"><polyline points="6 9 12 15 18 9"/></svg>
                            </button>
                        </div>
                        <div class="card-body-modern">
                            <div class="months-grid">
                                @foreach(($last12Months ?? []) as $row)
                                    @php $paid = ($row['status'] ?? '') === 'paid'; @endphp
                                    <div class="month-status-card {{ $paid ? 'paid' : 'unpaid' }}">
                                        <span class="month-name">{{ $row['month'] }}</span>
                                        <span class="month-status">
                                            @if($paid)
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                                <span class="label-status">
                                                    {{ __('messages.paid') }}
                                                </span>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/></svg>
                                                <span class="label-status">
                                                    {{ __('messages.unpaid') }}
                                                </span>
                                            @endif
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Payment Stats -->
            @unless(auth()->user()->hasRole('coach') || $singleUser->hasRole('coach'))
            @if(isset($leastPaid) || isset($unpaidMonths))
            <div class="row g-4 mt-0">
                <div class="col-lg-6">
                    <div class="modern-card h-100">
                        <div class="card-header-modern">
                            <div class="header-icon warning-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
                            </div>
                            <div>
                                <h3 class="header-title">
                                    {{ __('messages.least_paid') }}
                                </h3>
                            </div>
                            <button type="button" class="btn-icon-only ms-auto" onclick="toggleCard(this)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="transition: transform 0.3s ease;"><polyline points="6 9 12 15 18 9"/></svg>
                            </button>
                        </div>
                        <div class="table-container">
                            <table class="modern-table">
                                <thead>
                                    <tr>
                                        <th>
                                            {{ __('messages.month') }}
                                        </th>
                                        <th>
                                            {{ __('messages.amount') }}
                                        </th>
                                        <th>
                                            {{ __('messages.status') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(($leastPaid ?? []) as $lp)
                                        <tr>
                                            <td>{{ \Illuminate\Support\Carbon::parse($lp->billing_month)->format('M Y') }}</td>
                                            <td><span class="amount-value">{{ number_format(($lp->amount_cents ?? 0)/100, 2, ',', ' ') }} TND</span></td>
                                            <td>
                                                <span class="status-badge status-{{ strtolower($lp->status) }}">
                                                    @if(strtolower($lp->status) === 'paid')
                                                        <span class="label-status">
                                                            {{ __('messages.paid') }}
                                                        </span>
                                                    @else
                                                        <span class="label-status">
                                                            {{ __('messages.pending') }}
                                                        </span>
                                                    @endif
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="empty-cell">—</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="modern-card h-100">
                        <div class="card-header-modern">
                            <div class="header-icon danger-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/></svg>
                            </div>
                            <div>
                                <h3 class="header-title">
                                    {{ __('messages.unpaid_since_creation') }}
                                </h3>
                            </div>
                            <button type="button" class="btn-icon-only ms-auto" onclick="toggleCard(this)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="transition: transform 0.3s ease;"><polyline points="6 9 12 15 18 9"/></svg>
                            </button>
                        </div>
                        <div class="card-body-modern">
                            @php $list = $unpaidMonths ?? []; @endphp
                            @if(!empty($list))
                                <div class="tags-wrap">
                                    @foreach($list as $m)
                                        <span class="tag tag-danger">{{ $m }}</span>
                                    @endforeach
                                </div>
                            @else
                                <div class="empty-message">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                    <span class="label-empty">
                                        {{ __('messages.no_unpaid_months') }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-0">
                <div class="col-12">
                    <div class="modern-card">
                        <div class="card-header-modern">
                            <div class="header-icon success-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            </div>
                            <div>
                                <h3 class="header-title">
                                    {{ __('messages.paid_since_creation') }}
                                </h3>
                            </div>
                            <button type="button" class="btn-icon-only ms-auto" onclick="toggleCard(this)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="transition: transform 0.3s ease;"><polyline points="6 9 12 15 18 9"/></svg>
                            </button>
                        </div>
                        <div class="card-body-modern">
                            @php $listPaid = $paidMonths ?? []; @endphp
                            @if(!empty($listPaid))
                                <div class="tags-wrap">
                                    @foreach($listPaid as $m)
                                        <span class="tag tag-success">{{ $m }}</span>
                                    @endforeach
                                </div>
                            @else
                                <div class="empty-message">
                                    <span class="label-empty">
                                        {{ __('messages.no_paid_months') }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @endunless
            @endunless

            <!-- Documents Section -->
            <div class="row g-4 mt-0">
                <div class="col-12">
                    <div class="modern-card">
                        <div class="card-header-modern">
                            <div class="header-icon folder-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.93a2 2 0 0 1-1.66-.9l-.82-1.2A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13c0 1.1.9 2 2 2Z"/></svg>
                            </div>
                            <div>
                                <h3 class="header-title">
                                    {{ __('messages.athlete_folder') }}
                                </h3>
                            </div>
                        </div>
                        <div class="card-body-modern">
                            <form action="{{ route('allUsers.documents.store', $singleUser->id) }}" method="post" enctype="multipart/form-data" class="documents-form">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-md-6 col-lg-4">
                                        <div class="document-upload-card">
                                            <div class="upload-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                            </div>
                                            <span class="upload-label">
                                                {{ __('messages.photo') }}
                                            </span>
                                            <input type="file" name="documents[photo]" class="upload-input">
                                            <input type="text" name="notes[photo]" class="form-control-modern mt-2" placeholder="{{ __('messages.notes') }}...">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="document-upload-card">
                                            <div class="upload-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="14" x="3" y="5" rx="2"/><path d="M21 8H3"/><path d="M7 15h.01"/><path d="M11 15h2"/></svg>
                                            </div>
                                            <span class="upload-label">
                                                {{ __('messages.cin') }}
                                            </span>
                                            <input type="file" name="documents[cin]" class="upload-input">
                                            <input type="text" name="notes[cin]" class="form-control-modern mt-2" placeholder="{{ __('messages.notes') }}...">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="document-upload-card">
                                            <div class="upload-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                                            </div>
                                            <span class="upload-label">
                                                {{ __('messages.medical_certificate') }}
                                            </span>
                                            <input type="file" name="documents[certificat_medical]" class="upload-input">
                                            <input type="text" name="notes[certificat_medical]" class="form-control-modern mt-2" placeholder="{{ __('messages.notes') }}...">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="document-upload-card">
                                            <div class="upload-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>
                                            </div>
                                            <span class="upload-label">
                                                {{ __('messages.belt_diploma') }}
                                            </span>
                                            <input type="file" name="documents[diplome_ceinture]" class="upload-input">
                                            <input type="text" name="notes[diplome_ceinture]" class="form-control-modern mt-2" placeholder="{{ __('messages.notes') }}...">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="document-upload-card">
                                            <div class="upload-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                                            </div>
                                            <span class="upload-label">
                                                {{ __('messages.internship_certificate') }}
                                            </span>
                                            <input type="file" name="documents[certificat_stage]" class="upload-input">
                                            <input type="text" name="notes[certificat_stage]" class="form-control-modern mt-2" placeholder="{{ __('messages.notes') }}...">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="document-upload-card">
                                            <div class="upload-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
                                            </div>
                                            <span class="upload-label">
                                                {{ __('messages.other_document') }} 1
                                            </span>
                                            <input type="file" name="documents[extra1]" class="upload-input">
                                            <input type="text" name="notes[extra1]" class="form-control-modern mt-2" placeholder="{{ __('messages.notes') }}...">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="document-upload-card">
                                            <div class="upload-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
                                            </div>
                                            <span class="upload-label">
                                                {{ __('messages.other_document') }} 2
                                            </span>
                                            <input type="file" name="documents[extra2]" class="upload-input">
                                            <input type="text" name="notes[extra2]" class="form-control-modern mt-2" placeholder="{{ __('messages.notes') }}...">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="document-upload-card">
                                            <div class="upload-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
                                            </div>
                                            <span class="upload-label">
                                                {{ __('messages.other_document') }} 3
                                            </span>
                                            <input type="file" name="documents[extra3]" class="upload-input">
                                            <input type="text" name="notes[extra3]" class="form-control-modern mt-2" placeholder="{{ __('messages.notes') }}...">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions mt-4">
                                    <button type="submit" class="btn-primary-modern">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                        {{ __('messages.save') }}
                                    </button>
                                </div>
                            </form>

                            <!-- Existing Documents -->
                            <div class="documents-list mt-5">
                                <h4 class="section-subtitle">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                                    {{ __('messages.saved_documents') }}
                                </h4>
                                <div class="table-container">
                                    <table class="modern-table">
                                        <thead>
                                            <tr>
                                                <th>
                                                    {{ __('messages.type') }}
                                                </th>
                                                <th>
                                                    {{ __('messages.document') }}
                                                </th>
                                                <th>
                                                    {{ __('messages.notes') }}
                                                </th>
                                                <th>
                                                    {{ __('messages.actions') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse(($singleUser->documents ?? []) as $doc)
                                                <tr>
                                                    <td>
                                                        <span class="doc-type">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                                                            {{ $doc->type }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @php $isImg = \Illuminate\Support\Str::endsWith(strtolower($doc->file_path), ['.png','.jpg','.jpeg']); @endphp
                                                        @php $docUrl = route('allUsers.documents.download', [$singleUser->id, $doc->id]); @endphp
                                                        @if($isImg)
                                                            <a href="{{ $docUrl }}" target="_blank" class="doc-preview">
                                                                <img src="{{ $docUrl }}" alt="{{ $doc->type }}">
                                                            </a>
                                                        @else
                                                            <a href="{{ $docUrl }}" target="_blank" class="btn-ghost-sm">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" x2="21" y1="14" y2="3"/></svg>
                                                                {{ __('messages.open') }}
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td><span class="doc-notes">{{ $doc->notes ?? '—' }}</span></td>
                                                    <td>
                                                        <form action="{{ route('allUsers.documents.delete', [$singleUser->id, $doc->id]) }}" method="POST" class="delete-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn-danger-sm" onclick="return confirm('{{ __('messages.confirm_delete_document') }}')">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                                                {{ __('messages.delete') }}
                                                            </button>
                                                        </form>
                                                    </td>

                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="empty-cell">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                                                        {{ __('messages.no_registered_document') }}
                                                    </td>

                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

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
    --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
    --transition: all 0.2s ease;
}

/* Page Header */
.page-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--gray-900);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin: 0;
}

.user-id-badge {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--primary);
    background: var(--primary-bg);
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
}

.page-subtitle {
    font-size: 0.875rem;
    color: var(--gray-500);
    margin: 0.25rem 0 0;
}

/* Breadcrumb */
.breadcrumb {
    padding: 0;
    margin: 0;
    background: transparent;
    font-size: 0.875rem;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    color: var(--gray-400);
}

.breadcrumb-link {
    color: var(--gray-500);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    transition: var(--transition);
}

.breadcrumb-link:hover {
    color: var(--primary);
}

/* Buttons */
.btn-primary-modern {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1.25rem;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    color: white;
    font-weight: 600;
    font-size: 0.875rem;
    border: none;
    border-radius: var(--radius-md);
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
    box-shadow: 0 4px 14px 0 rgba(99, 102, 241, 0.4);
}

.btn-primary-modern:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px 0 rgba(99, 102, 241, 0.5);
    color: white;
}

.btn-primary-sm {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.5rem 1rem;
    background: var(--primary);
    color: white;
    font-weight: 600;
    font-size: 0.8125rem;
    border: none;
    border-radius: var(--radius-sm);
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
}

.btn-primary-sm:hover {
    background: var(--primary-dark);
    color: white;
}

.btn-ghost {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1.25rem;
    background: transparent;
    color: var(--gray-600);
    font-weight: 500;
    font-size: 0.875rem;
    border: 1px solid var(--gray-300);
    border-radius: var(--radius-md);
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
}

.btn-ghost:hover {
    background: var(--gray-100);
    border-color: var(--gray-400);
    color: var(--gray-700);
}

.btn-ghost-sm {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.375rem 0.75rem;
    background: transparent;
    color: var(--gray-600);
    font-weight: 500;
    font-size: 0.8125rem;
    border: 1px solid var(--gray-300);
    border-radius: var(--radius-sm);
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
}

.btn-ghost-sm:hover {
    background: var(--gray-100);
    color: var(--gray-700);
}

.btn-danger-sm {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.375rem 0.75rem;
    background: var(--danger-light);
    color: var(--danger);
    font-weight: 600;
    font-size: 0.8125rem;
    border: none;
    border-radius: var(--radius-sm);
    cursor: pointer;
    transition: var(--transition);
}

.btn-danger-sm:hover {
    background: var(--danger);
    color: white;
}

/* Alert */
.alert-modern {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.25rem;
    border-radius: var(--radius-md);
    font-weight: 500;
}

.alert-success {
    background: var(--success-light);
    color: var(--success-dark);
}

/* Profile Card */
.profile-card {
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-lg);
    overflow: hidden;
}

.profile-header {
    background: linear-gradient(135deg, var(--primary) 0%, #818cf8 100%);
    padding: 2rem;
    text-align: center;
}

.profile-avatar-wrapper {
    position: relative;
    display: inline-block;
    margin-bottom: 1rem;
}

.profile-avatar-wrapper.initials-avatar {
    background: linear-gradient(135deg, var(--primary-100) 0%, var(--primary-200) 100%);
    width: 120px;
    height: 120px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 4px solid rgba(255,255,255,0.3);
    box-shadow: var(--shadow-xl);
}

.profile-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid rgba(255,255,255,0.3);
    box-shadow: var(--shadow-xl);
}

.profile-avatar-initials {
    display: none;
    font-weight: 700;
    color: var(--primary-600);
    font-size: 3rem;
    text-transform: uppercase;
}

.initials-avatar .profile-avatar-initials {
    display: block;
}

.avatar-status-indicator {
    position: absolute;
    bottom: 8px;
    right: 8px;
    width: 20px;
    height: 20px;
    background: var(--success);
    border: 3px solid white;
    border-radius: 50%;
}

.profile-name {
    color: white;
    font-size: 1.375rem;
    font-weight: 700;
    margin: 0 0 0.75rem;
}

.profile-phone {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255,255,255,0.2);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 500;
}

.profile-actions {
    padding: 1.25rem;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.action-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border-radius: var(--radius-md);
    text-decoration: none;
    transition: var(--transition);
}

.action-card:hover {
    transform: translateX(4px);
}

.action-icon {
    width: 44px;
    height: 44px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.action-text {
    display: flex;
    flex-direction: column;
}

.action-label {
    font-weight: 600;
    font-size: 0.9375rem;
}

.action-label-ar {
    font-size: 0.75rem;
    opacity: 0.7;
}

.action-edit {
    background: #fef3c7;
}
.action-edit .action-icon { background: #fde68a; color: #d97706; }
.action-edit .action-label { color: #92400e; }
.action-edit .action-label-ar { color: #b45309; }

.action-payment {
    background: #d1fae5;
}
.action-payment .action-icon { background: #a7f3d0; color: #059669; }
.action-payment .action-label { color: #065f46; }
.action-payment .action-label-ar { color: #047857; }

.action-report {
    background: var(--gray-100);
}
.action-report .action-icon { background: var(--gray-200); color: var(--gray-600); }
.action-report .action-label { color: var(--gray-700); }
.action-report .action-label-ar { color: var(--gray-500); }

.action-test {
    background: #cffafe;
}
.action-test .action-icon { background: #a5f3fc; color: #0891b2; }
.action-test .action-label { color: #155e75; }
.action-test .action-label-ar { color: #0e7490; }

/* Modern Card */
.modern-card {
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-lg);
    overflow: hidden;
}

.card-header-modern {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--gray-100);
}

.header-icon {
    width: 44px;
    height: 44px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--primary-bg);
    color: var(--primary);
}

.payment-icon { background: #d1fae5; color: var(--success); }
.belt-header-icon { background: #fef3c7; color: #d97706; }
.calendar-icon { background: #e0e7ff; color: #4f46e5; }
.warning-icon { background: var(--warning-light); color: var(--warning); }
.danger-icon { background: var(--danger-light); color: var(--danger); }
.success-icon { background: var(--success-light); color: var(--success); }
.folder-icon { background: #dbeafe; color: #2563eb; }

.header-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--gray-900);
    margin: 0;
}

.header-subtitle {
    font-size: 0.8125rem;
    color: var(--gray-500);
}

.header-action {
    margin-left: auto;
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    color: var(--primary);
    font-weight: 600;
    font-size: 0.875rem;
    text-decoration: none;
    transition: var(--transition);
}

.header-action:hover {
    color: var(--primary-dark);
}

.header-actions {
    margin-left: auto;
    display: flex;
    gap: 0.5rem;
}

.card-body-modern {
    padding: 1.5rem;
}

/* Info Block */
.info-block {
    background: var(--gray-50);
    border-radius: var(--radius-md);
    padding: 1.25rem;
    height: 100%;
}

.info-item {
    display: flex;
    gap: 1rem;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--gray-200);
}

.info-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.info-item:first-child {
    padding-top: 0;
}

.info-icon {
    width: 36px;
    height: 36px;
    background: white;
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gray-500);
    flex-shrink: 0;
    box-shadow: var(--shadow-sm);
}

.info-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.info-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.info-value {
    font-weight: 600;
    color: var(--gray-900);
}

/* Gender Tags */
.gender-tag {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.625rem;
    border-radius: 9999px;
    font-size: 0.8125rem;
    font-weight: 600;
}

.gender-tag.male {
    background: #dbeafe;
    color: #1d4ed8;
}

.gender-tag.female {
    background: #fce7f3;
    color: #be185d;
}

/* Tags */
.tag {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.625rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
}

.tag-category {
    background: var(--info-light);
    color: #0e7490;
}

.tag-group {
    background: var(--primary-bg);
    color: var(--primary);
}

.tag-success {
    background: var(--success-light);
    color: var(--success-dark);
}

.tag-danger {
    background: var(--danger-light);
    color: var(--danger);
}

.tags-wrap {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

/* Group Assign Form */
.group-assign-form {
    display: flex;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.form-select-modern,
.form-control-modern {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    border: 1px solid var(--gray-300);
    border-radius: var(--radius-sm);
    transition: var(--transition);
    background: white;
}

.form-select-modern:focus,
.form-control-modern:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
}

.form-select-sm {
    flex: 1;
}

.btn-assign {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--primary);
    color: white;
    border: none;
    border-radius: var(--radius-sm);
    cursor: pointer;
    transition: var(--transition);
}

.btn-assign:hover {
    background: var(--primary-dark);
}

/* Belt Info */
.belt-info .info-icon {
    color: #d97706;
}

.belt-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.375rem 0.75rem;
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #92400e;
    font-weight: 600;
    border-radius: var(--radius-sm);
}

.belt-date {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    font-size: 0.8125rem;
    color: var(--gray-500);
    margin-top: 0.25rem;
}

.belt-count {
    font-size: 0.8125rem;
    color: var(--gray-500);
    padding-top: 0.5rem;
    margin-top: 0.5rem;
    border-top: 1px dashed var(--gray-300);
}

/* Table */
.table-container {
    overflow-x: auto;
}

.modern-table {
    width: 100%;
    border-collapse: collapse;
}

.modern-table thead {
    background: var(--gray-50);
}

.modern-table th {
    padding: 0.875rem 1.25rem;
    text-align: left;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--gray-600);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 2px solid var(--gray-200);
}

.modern-table td {
    padding: 0.875rem 1.25rem;
    border-bottom: 1px solid var(--gray-100);
    vertical-align: middle;
}

.modern-table tbody tr {
    transition: var(--transition);
}

.modern-table tbody tr:hover {
    background: var(--gray-50);
}

.month-cell {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--gray-700);
}

.month-cell svg {
    color: var(--gray-400);
}

.date-cell {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: var(--gray-600);
}

.date-cell svg {
    color: var(--gray-400);
}

.amount-value {
    font-weight: 600;
    color: var(--gray-900);
}

.belt-name {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    color: #92400e;
}

.belt-name svg {
    color: #d97706;
}

/* Status Badge */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.375rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status-paid {
    background: var(--success-light);
    color: var(--success-dark);
}

.status-unpaid,
.status-pending {
    background: var(--warning-light);
    color: #92400e;
}

/* Empty Cell */
.empty-cell {
    text-align: center;
    padding: 2rem !important;
    color: var(--gray-400);
}

.empty-cell svg {
    margin-bottom: 0.5rem;
    opacity: 0.5;
}

.empty-cell.warning {
    color: var(--warning);
    background: var(--warning-light);
}

/* Months Grid */
.months-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 1rem;
}

.month-status-card {
    padding: 1rem;
    border-radius: var(--radius-md);
    text-align: center;
    transition: var(--transition);
}

.month-status-card.paid {
    background: var(--success-light);
}

.month-status-card.unpaid {
    background: var(--danger-light);
}

.month-name {
    display: block;
    font-weight: 600;
    font-size: 0.875rem;
    margin-bottom: 0.375rem;
}

.month-status-card.paid .month-name { color: var(--success-dark); }
.month-status-card.unpaid .month-name { color: var(--danger); }

.month-status {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.75rem;
    font-weight: 500;
}

.month-status-card.paid .month-status { color: var(--success); }
.month-status-card.unpaid .month-status { color: var(--danger); }

/* Empty Message */
.empty-message {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--gray-500);
    font-size: 0.9375rem;
}

.empty-message svg {
    color: var(--success);
}

/* Document Upload */
.documents-form {
    margin-bottom: 1rem;
}

.document-upload-card {
    background: var(--gray-50);
    border: 2px dashed var(--gray-300);
    border-radius: var(--radius-md);
    padding: 1.25rem;
    text-align: center;
    transition: var(--transition);
}

.document-upload-card:hover {
    border-color: var(--primary);
    background: var(--primary-bg);
}

.upload-icon {
    width: 48px;
    height: 48px;
    background: white;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.75rem;
    color: var(--gray-500);
    box-shadow: var(--shadow-sm);
}

.upload-label {
    display: block;
    font-weight: 600;
    font-size: 0.8125rem;
    color: var(--gray-700);
    margin-bottom: 0.75rem;
}

.upload-input {
    display: block;
    width: 100%;
    font-size: 0.8125rem;
    color: var(--gray-600);
}

.upload-input::file-selector-button {
    padding: 0.375rem 0.75rem;
    background: var(--primary);
    color: white;
    border: none;
    border-radius: var(--radius-sm);
    font-weight: 500;
    cursor: pointer;
    margin-right: 0.5rem;
    transition: var(--transition);
}

.upload-input::file-selector-button:hover {
    background: var(--primary-dark);
}

.form-actions {
    display: flex;
    justify-content: flex-end;
}

/* Documents List */
.section-subtitle {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--gray-200);
}

.doc-type {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    color: var(--gray-700);
}

.doc-type svg {
    color: var(--gray-400);
}

.doc-preview {
    display: inline-block;
}

.doc-preview img {
    max-height: 60px;
    border-radius: var(--radius-sm);
    border: 1px solid var(--gray-200);
    transition: var(--transition);
}

.doc-preview img:hover {
    transform: scale(1.05);
    box-shadow: var(--shadow-md);
}

.doc-notes {
    color: var(--gray-600);
    font-size: 0.875rem;
}

/* Responsive */
@media (max-width: 992px) {
    .page-title {
        font-size: 1.375rem;
    }
    
    .profile-avatar {
        width: 100px;
        height: 100px;
    }
    
    .months-grid {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    }
}

@media (max-width: 768px) {
    .card-header-modern {
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    
    .header-actions {
        width: 100%;
        margin-left: 0;
        margin-top: 0.5rem;
    }
    
    .info-block {
        padding: 1rem;
    }
    
    .info-item {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .document-upload-card {
        padding: 1rem;
    }
}
</style>
@endpush
@push('scripts')
<script>
    function toggleCard(btn) {
        const card = btn.closest('.modern-card');
        const content = card.querySelector('.card-body-modern, .table-container');
        const icon = btn.querySelector('svg');
        
        if (content) {
            if (content.style.display === 'none') {
                content.style.display = '';
                icon.style.transform = 'rotate(0deg)';
            } else {
                content.style.display = 'none';
                icon.style.transform = 'rotate(180deg)';
            }
        }
    }
</script>
<style>
    .btn-icon-only {
        background: transparent;
        border: none;
        color: var(--gray-500);
        padding: 0.5rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.2s;
    }
    .btn-icon-only:hover {
        background: var(--gray-100);
        color: var(--primary);
    }
</style>
@endpush
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const qrContainer = document.getElementById("qrcode");
        if (qrContainer) {
            new QRCode(qrContainer, {
                text: "{{ $singleUser->id }}",
                width: 128,
                height: 128,
                colorDark: "#2d3748",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        }
    });

    function printQR() {
        const qrCanvas = document.querySelector('#qrcode canvas');
        if (!qrCanvas) {
            const qrImg = document.querySelector('#qrcode img');
            if (qrImg) {
                const win = window.open('', '_blank');
                win.document.write(`
                    <html>
                        <head>
                            <title>QR Attendance - {{ $singleUser->name }}</title>
                            <style>
                                body { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh; font-family: sans-serif; }
                                .container { text-align: center; border: 2px solid #eee; padding: 40px; border-radius: 20px; }
                                img { width: 250px; height: 250px; margin-bottom: 20px; }
                                h2 { margin: 0; color: #2d3748; }
                                p { color: #718096; margin: 5px 0 0; }
                            </style>
                        </head>
                        <body>
                            <div class="container">
                                <img src="${qrImg.src}">
                                <h2>{{ $singleUser->name }}</h2>
                                <p>ID: #{{ $singleUser->id }}</p>
                                <p>Attendance QR Code</p>
                            </div>
                            <script>
                                setTimeout(() => {
                                    window.print();
                                    window.close();
                                }, 500);
                            <\/script>
                        </body>
                    </html>
                `);
            }
            return;
        }
        
        const dataUrl = qrCanvas.toDataURL();
        const win = window.open('', '_blank');
        win.document.write(`
            <html>
                <head>
                    <title>QR Attendance - {{ $singleUser->name }}</title>
                    <style>
                        body { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh; font-family: sans-serif; }
                        .container { text-align: center; border: 2px solid #eee; padding: 40px; border-radius: 20px; }
                        img { width: 250px; height: 250px; margin-bottom: 20px; }
                        h2 { margin: 0; color: #2d3748; }
                        p { color: #718096; margin: 5px 0 0; }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <img src="${dataUrl}">
                        <h2>{{ $singleUser->name }}</h2>
                        <p>ID: #{{ $singleUser->id }}</p>
                        <p>Attendance QR Code</p>
                    </div>
                    <script>
                        setTimeout(() => {
                            window.print();
                            window.close();
                        }, 500);
                    <\/script>
                </body>
            </html>
        `);
    }
</script>
@endpush
@endsection