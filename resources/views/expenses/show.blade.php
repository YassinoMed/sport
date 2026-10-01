@extends('layouts.user-layout')
@section('content')
<div class="content-wrapper pb-5">
    <section class="content-header py-4">
        <div class="container-fluid">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-2">
                            <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">{{ __('messages.home') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('expenses.index') }}" class="breadcrumb-link">{{ __('messages.expenses') }}</a></li>
                            <li class="breadcrumb-item active">Détails</li>
                        </ol>
                    </nav>
                    <h1 class="page-title">Détails de la dépense #{{ $expense->id }}</h1>
                    <p class="page-subtitle">تفاصيل المصروف</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('expenses.index') }}" class="btn-ghost">
                        <i class="fas fa-arrow-left"></i>
                        <span class="label-fr">Retour à la liste</span>
                        <span class="label-ar">العودة للقائمة</span>
                    </a>
                    <a href="{{ route('expenses.edit', $expense->id) }}" class="btn-primary-modern">
                        <i class="fas fa-pen"></i>
                        <span class="label-fr">Modifier</span>
                        <span class="label-ar">تعديل</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="modern-card">
                        <div class="card-header-modern">
                            <h3 class="card-title-modern">Informations</h3>
                        </div>
                        <div class="card-body p-4">
                            <div class="detail-group mb-3">
                                <label class="text-muted small d-block">Date</label>
                                <span class="fw-bold">{{ \Illuminate\Support\Carbon::parse($expense->expense_date)->format('d/m/Y') }}</span>
                            </div>
                            <div class="detail-group mb-3">
                                <label class="text-muted small d-block">Montant</label>
                                <span class="fw-bold text-danger">{{ number_format($expense->amount_cents / 100, 2, ',', ' ') }} TND</span>
                            </div>
                            <div class="detail-group mb-3">
                                <label class="text-muted small d-block">Méthode</label>
                                <span class="badge-status {{ strtolower($expense->method) == 'cash' ? 'badge-cash' : 'badge-bank' }}">{{ $expense->method }}</span>
                            </div>
                            <div class="detail-group mb-3">
                                <label class="text-muted small d-block">Catégorie</label>
                                <span class="fw-bold">{{ $expense->category->name ?? '—' }}</span>
                                <span class="d-block text-muted small">{{ $expense->subcategory->name ?? '—' }}</span>
                            </div>
                            <div class="detail-group mb-3">
                                <label class="text-muted small d-block">Coach</label>
                                <span>{{ $expense->coach->name ?? '—' }}</span>
                            </div>
                            <div class="detail-group mb-0">
                                <label class="text-muted small d-block">Salle</label>
                                <span>{{ $expense->gym->name ?? '—' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="modern-card">
                        <div class="card-header-modern">
                            <h3 class="card-title-modern">Notes & Pièces jointes</h3>
                        </div>
                        <div class="card-body p-4">
                            <div class="detail-group mb-4">
                                <label class="text-muted small d-block">Notes</label>
                                <div class="p-3 bg-light rounded">
                                    {{ $expense->notes ?: '—' }}
                                </div>
                            </div>

                            <div class="detail-group mb-4">
                                <label class="text-muted small d-block">Pièce jointe</label>
                                @if($expense->attachment_path)
                                    <a href="{{ Storage::url($expense->attachment_path) }}" target="_blank" class="text-primary text-decoration-none d-inline-flex align-items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                                        <span>Ouvrir le fichier</span>
                                    </a>
                                @else
                                    <span>—</span>
                                @endif
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="detail-group">
                                        <label class="text-muted small d-block">Créée le</label>
                                        <span>{{ $expense->created_at ? $expense->created_at->format('d/m/Y H:i') : '—' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="detail-group">
                                        <label class="text-muted small d-block">Modifiée le</label>
                                        <span>{{ $expense->updated_at ? $expense->updated_at->format('d/m/Y H:i') : '—' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
