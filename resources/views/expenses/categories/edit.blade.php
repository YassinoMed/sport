@extends('layouts.user-layout')

@section('content')
<div class="content-wrapper pb-5 {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <!-- Header Section -->
    <section class="content-header py-4">
        <div class="container-fluid">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h1 class="page-title mb-1 bilingual-label">
                        <span>{{ __('messages.edit', [], 'fr') }}</span>
                        <span class="separator">/</span>
                        <span class="lang-secondary" dir="rtl">{{ __('messages.edit', [], 'ar') }}</span>
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="/" class="breadcrumb-link">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                    <span class="bilingual-label">
                                        <span>{{ __('messages.home', [], 'fr') }}</span>
                                        <span class="separator">/</span>
                                        <span class="lang-secondary" dir="rtl">{{ __('messages.home', [], 'ar') }}</span>
                                    </span>
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('expense-categories.index') }}" class="breadcrumb-link">
                                    <span class="bilingual-label">
                                        <span>{{ __('messages.expense_categories', [], 'fr') }}</span>
                                        <span class="separator">/</span>
                                        <span class="lang-secondary" dir="rtl">{{ __('messages.expense_categories', [], 'ar') }}</span>
                                    </span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <span class="bilingual-label">
                                    <span>{{ $category->name }}</span>
                                </span>
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="header-actions">
                    <a href="{{ route('expense-categories.index') }}" class="btn-ghost">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                        <span class="bilingual-label">
                            <span>{{ __('messages.back', [], 'fr') }}</span>
                            <span class="separator">/</span>
                            <span class="lang-secondary" dir="rtl">{{ __('messages.back', [], 'ar') }}</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card-modern shadow-sm border-0">
                        <div class="card-body p-4 p-md-5">
                            <form action="{{ route('expense-categories.update', $category->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row g-4">
                                    <!-- Category Name -->
                                    <div class="col-12">
                                        <div class="form-group-modern">
                                            <label for="name" class="form-label-modern bilingual-label">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2H2v10l9.29 9.29c.94.94 2.48.94 3.42 0l6.58-6.58c.94-.94.94-2.48 0-3.42L12 2Z"/><path d="M7 7h.01"/></svg>
                                                <span>{{ __('messages.category_name', [], 'fr') }}</span>
                                                <span class="separator">/</span>
                                                <span class="lang-secondary" dir="rtl">{{ __('messages.category_name', [], 'ar') }}</span>
                                            </label>
                                            <input type="text" id="name" name="name" 
                                                class="form-control-modern @error('name') is-invalid @enderror" 
                                                value="{{ old('name', $category->name) }}" required
                                                placeholder="{{ __('messages.category_name', [], 'fr') }} / {{ __('messages.category_name', [], 'ar') }}">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    <div class="col-12">
                                        <div class="form-group-modern">
                                            <label for="description" class="form-label-modern bilingual-label">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                                <span>{{ __('messages.description', [], 'fr') }}</span>
                                                <span class="separator">/</span>
                                                <span class="lang-secondary" dir="rtl">{{ __('messages.description', [], 'ar') }}</span>
                                            </label>
                                            <textarea id="description" name="description" 
                                                class="form-control-modern" rows="4"
                                                placeholder="{{ __('messages.additional_info', [], 'fr') }} / {{ __('messages.additional_info', [], 'ar') }}">{{ old('description', $category->description) }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="col-12 mt-5">
                                        <div class="d-flex flex-wrap justify-content-end gap-3 p-4 bg-light-modern rounded-3">
                                            <a href="{{ route('expense-categories.index') }}" class="btn-ghost">
                                                <span class="bilingual-label">
                                                    <span>{{ __('messages.cancel', [], 'fr') }}</span>
                                                    <span class="separator">/</span>
                                                    <span class="lang-secondary" dir="rtl">{{ __('messages.cancel', [], 'ar') }}</span>
                                                </span>
                                            </a>
                                            <button type="submit" class="btn-primary-modern shadow-sm px-5">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                                <span class="bilingual-label">
                                                    <span>{{ __('messages.update', [], 'fr') }}</span>
                                                    <span class="separator">/</span>
                                                    <span class="lang-secondary" dir="rtl">{{ __('messages.update', [], 'ar') }}</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.bg-light-modern {
    background-color: var(--bg-light) !important;
}
.form-group-modern {
    margin-bottom: 0.5rem;
}
</style>
@endsection
