@extends('layouts.user-layout')
@section('content')
<div class="content-wrapper pb-5">
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
                                    Accueil
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('expenses.index') }}" class="breadcrumb-link">Dépenses</a>
                            </li>
                            <li class="breadcrumb-item active">Modifier</li>
                        </ol>
                    </nav>
                    <h1 class="page-title">Modifier la Dépense</h1>
                    <p class="page-subtitle">تعديل المصروف</p>
                </div>
                <a href="{{ route('expenses.index') }}" class="btn-ghost">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    Retour à la liste
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
                        <strong>Erreur!</strong>
                        <ul class="mb-0 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="modern-card">
                        <div class="card-header-modern">
                            <h3 class="card-title-modern">Informations de la Dépense</h3>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('expenses.update', $expense->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label for="expense_date" class="form-label-modern">Date / التاريخ</label>
                                        <input type="date" id="expense_date" name="expense_date" class="form-input-modern" value="{{ $expense->expense_date ? $expense->expense_date->format('Y-m-d') : '' }}" required>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="coach_id" class="form-label-modern">Coach / المدرب</label>
                                        <select id="coach_id" name="coach_id" class="form-select-modern">
                                            <option value="">— Aucun coach —</option>
                                            @foreach($coaches as $coach)
                                                <option value="{{ $coach->id }}" {{ (string)$expense->coach_id === (string)$coach->id ? 'selected' : '' }}>{{ $coach->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="expense_category_id" class="form-label-modern">Catégorie / الصنف</label>
                                        <select id="expense_category_id" name="expense_category_id" class="form-select-modern" required>
                                            @foreach($categories as $c)
                                                <option value="{{ $c->id }}" {{ (string)$expense->expense_category_id === (string)$c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="expense_subcategory_id" class="form-label-modern">Sous-catégorie / تصنيف فرعي</label>
                                        <select id="expense_subcategory_id" name="expense_subcategory_id" class="form-select-modern">
                                            <option value="">— Aucune sous-catégorie —</option>
                                            @foreach($subcategories as $sc)
                                                <option value="{{ $sc->id }}" {{ (string)$expense->expense_subcategory_id === (string)$sc->id ? 'selected' : '' }}>{{ $sc->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="amount" class="form-label-modern">Montant (TND) / المبلغ</label>
                                        <div class="input-group-modern">
                                            <input type="number" step="0.01" min="0" id="amount" name="amount" class="form-input-modern" value="{{ number_format($expense->amount_cents/100, 2, '.', '') }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="method" class="form-label-modern">Méthode de Paiement / وسيلة الدفع</label>
                                        <select id="method" name="method" class="form-select-modern" required>
                                            <option value="cash" {{ $expense->method === 'cash' ? 'selected' : '' }}>Cash</option>
                                            <option value="card" {{ $expense->method === 'card' ? 'selected' : '' }}>Carte</option>
                                            <option value="bank" {{ $expense->method === 'bank' ? 'selected' : '' }}>Virement</option>
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <label for="attachment" class="form-label-modern">Pièce jointe / مرفق</label>
                                        @if($expense->attachment_path)
                                            <div class="mb-2">
                                                <a href="{{ Storage::url($expense->attachment_path) }}" target="_blank" class="text-primary text-decoration-none d-flex align-items-center gap-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                                                    Voir le fichier actuel
                                                </a>
                                            </div>
                                        @endif
                                        <input type="file" id="attachment" name="attachment" class="form-input-modern" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                        <p class="form-text text-muted small mt-1">Formats: PDF, JPG, PNG, DOC (Max: 5MB)</p>
                                    </div>

                                    <div class="col-12">
                                        <label for="notes" class="form-label-modern">Notes / ملاحظات</label>
                                        <textarea id="notes" name="notes" class="form-input-modern" rows="3" placeholder="Remarque (optionnel)">{{ $expense->notes }}</textarea>
                                    </div>

                                    <div class="col-12 d-flex justify-content-end gap-3 mt-4">
                                        <a href="{{ route('expenses.index') }}" class="btn-ghost">
                                            Annuler
                                        </a>
                                        <button type="submit" class="btn-primary-modern">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                            Enregistrer les modifications
                                        </button>
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
@endsection
