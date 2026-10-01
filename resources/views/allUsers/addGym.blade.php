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
                                {{ __('messages.add_gym') }}
                            </li>
                        </ol>
                    </nav>
                    <h1 class="page-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        {{ __('messages.add_gym_to_user') }}
                    </h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @if ($errors->any())
                <div class="alert-modern alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('status'))
                <div class="alert-modern alert-success mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <form action="{{ url('/allUsers/submitGym', $user->id) }}" method="post">
                        @csrf
                        <div class="modern-card shadow-sm">
                            <div class="card-header-modern">
                                <div class="header-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                </div>
                                <div>
                                    <h3 class="header-title">
                                        {{ __('messages.gym_selection') }}
                                    </h3>
                                    <p class="header-subtitle">
                                        {{ __('messages.assign_gym_to', ['name' => $user->name]) }}
                                    </p>
                                </div>
                            </div>

                            <div class="card-body-modern p-4">
                                <div class="form-group-modern">
                                    <label for="gym_id" class="form-label-modern required-field">
                                        {{ __('messages.gym_label') }}
                                    </label>
                                    <div class="input-group-modern">
                                        <select required class="select-modern" name="gym_id" id="gym_id">
                                            <option value="" disabled selected>
                                                {{ __('messages.select_gym_placeholder') }}
                                            </option>
                                            @foreach ($gyms as $gym)
                                                <option value="{{ $gym->id }}">{{ $gym->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer-modern">
                                <div class="d-flex justify-content-between align-items-center w-100">
                                    <a href="{{ route('allUsers.list') }}" class="btn-ghost">
                                        {{ __('messages.cancel') }}
                                    </a>
                                    <button type="submit" class="btn-primary-modern">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                        {{ __('messages.save') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
<style>
    /* Styles are consistent with other modernized pages */
    :root {
        --primary: #6366f1;
        --primary-dark: #4f46e5;
        --primary-bg: #eef2ff;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-300: #d1d5db;
        --gray-400: #9ca3af;
        --gray-500: #6b7280;
        --gray-600: #4b5563;
        --gray-700: #374151;
        --gray-900: #111827;
        --radius-md: 10px;
        --radius-lg: 16px;
        --transition: all 0.2s ease;
    }

    .label-ar { font-family: 'Cairo', sans-serif; font-size: 0.9em; margin-left: 5px; }
    .page-title { font-size: 1.75rem; font-weight: 700; color: var(--gray-900); display: flex; align-items: center; margin: 0; }
    .modern-card { background: white; border-radius: var(--radius-lg); border: 1px solid var(--gray-200); overflow: hidden; }
    .card-header-modern { padding: 1.5rem; border-bottom: 1px solid var(--gray-100); display: flex; align-items: center; gap: 1rem; }
    .header-icon { width: 40px; height: 40px; background: var(--primary-bg); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; color: var(--primary); }
    .header-title { font-size: 1.125rem; font-weight: 700; color: var(--gray-900); margin: 0; }
    .header-subtitle { font-size: 0.875rem; color: var(--gray-500); margin: 0.25rem 0 0 0; }
    .form-group-modern { margin-bottom: 1.5rem; }
    .form-label-modern { display: block; font-size: 0.875rem; font-weight: 600; color: var(--gray-700); margin-bottom: 0.5rem; }
    .select-modern { width: 100%; padding: 0.75rem 1rem; border-radius: var(--radius-md); border: 1px solid var(--gray-300); background-color: white; color: var(--gray-900); transition: var(--transition); appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 1rem center; }
    .select-modern:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 4px var(--primary-bg); }
    .btn-primary-modern { background: var(--primary); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: var(--radius-md); font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem; transition: var(--transition); }
    .btn-primary-modern:hover { background: var(--primary-dark); transform: translateY(-1px); }
    .btn-ghost { color: var(--gray-600); padding: 0.75rem 1.5rem; border-radius: var(--radius-md); font-weight: 600; transition: var(--transition); text-decoration: none; }
    .btn-ghost:hover { background: var(--gray-100); color: var(--gray-900); }
    .alert-modern { padding: 1rem 1.25rem; border-radius: var(--radius-md); display: flex; align-items: center; gap: 0.75rem; border: 1px solid transparent; }
    .alert-success { background: #ecfdf5; border-color: #10b981; color: #065f46; }
    .alert-danger { background: #fef2f2; border-color: #ef4444; color: #991b1b; }
</style>
@push('styles')

