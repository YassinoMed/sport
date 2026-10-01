@extends('layouts.user-layout')

@section('content')
<div class="content-wrapper pb-5 {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <!-- Header Section -->
    <section class="content-header py-4">
        <div class="container-fluid">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h1 class="page-title mb-1 bilingual-label">
                        <span>{{ __('messages.expense_categories', [], 'fr') }}</span>
                        <span class="separator">/</span>
                        <span class="lang-secondary" dir="rtl">{{ __('messages.expense_categories', [], 'ar') }}</span>
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
                            <li class="breadcrumb-item active">
                                <span class="bilingual-label">
                                    <span>{{ __('messages.expense_categories', [], 'fr') }}</span>
                                    <span class="separator">/</span>
                                    <span class="lang-secondary" dir="rtl">{{ __('messages.expense_categories', [], 'ar') }}</span>
                                </span>
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="header-actions">
                    <a href="{{ route('expense-categories.create') }}" class="btn-primary-modern shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                        <span class="bilingual-label">
                            <span>{{ __('messages.new_category', [], 'fr') }}</span>
                            <span class="separator">/</span>
                            <span class="lang-secondary" dir="rtl">{{ __('messages.new_category', [], 'ar') }}</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Categories Table Card -->
            <div class="card-modern shadow-sm border-0 overflow-hidden">
                <div class="table-responsive">
                    <table class="table-modern w-100">
                        <thead>
                            <tr>
                                <th>
                                    <span class="bilingual-label">
                                        <span>{{ __('messages.category_name', [], 'fr') }}</span>
                                        <span class="separator">/</span>
                                        <span class="lang-secondary" dir="rtl">{{ __('messages.category_name', [], 'ar') }}</span>
                                    </span>
                                </th>
                                <th>
                                    <span class="bilingual-label">
                                        <span>{{ __('messages.subcategories', [], 'fr') }}</span>
                                        <span class="separator">/</span>
                                        <span class="lang-secondary" dir="rtl">{{ __('messages.subcategories', [], 'ar') }}</span>
                                    </span>
                                </th>
                                <th class="text-end">
                                    <span class="bilingual-label justify-content-end">
                                        <span>{{ __('messages.actions', [], 'fr') }}</span>
                                        <span class="separator">/</span>
                                        <span class="lang-secondary" dir="rtl">{{ __('messages.actions', [], 'ar') }}</span>
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $cat)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-simple bg-light-primary text-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2H2v10l9.29 9.29c.94.94 2.48.94 3.42 0l6.58-6.58c.94-.94.94-2.48 0-3.42L12 2Z"/><path d="M7 7h.01"/></svg>
                                            </div>
                                            <span class="fw-semibold text-dark-modern">{{ $cat->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-soft-info px-3">
                                            {{ $cat->subcategories_count }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="actions-cell justify-content-end">
                                            <a class="action-btn action-edit" href="{{ route('expense-categories.edit', $cat->id) }}" 
                                                data-tooltip="{{ __('messages.edit', [], 'fr') }} / {{ __('messages.edit', [], 'ar') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                            </a>
                                            <form action="{{ route('expense-categories.destroy', $cat->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn action-delete" 
                                                    onclick="return confirm('{{ __('messages.confirm_delete', [], 'fr') }}')"
                                                    data-tooltip="{{ __('messages.delete', [], 'fr') }} / {{ __('messages.delete', [], 'ar') }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">
                                        <div class="empty-state py-5">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2H2v10l9.29 9.29c.94.94 2.48.94 3.42 0l6.58-6.58c.94-.94.94-2.48 0-3.42L12 2Z"/><path d="M7 7h.01"/></svg>
                                            <h4 class="mt-3">
                                                {{ __('messages.no_data', [], 'fr') }} / {{ __('messages.no_data', [], 'ar') }}
                                            </h4>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($categories->hasPages())
                    <div class="card-footer bg-white py-3">
                        {{ $categories->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>
@endsection
