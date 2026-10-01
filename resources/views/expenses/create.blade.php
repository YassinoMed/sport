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
                                    <i class="fas fa-home"></i>
                                    <span class="label-fr">Accueil</span>
                                    <span class="label-ar">الرئيسية</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('expenses.index') }}" class="breadcrumb-link">
                                    <span class="label-fr">Dépenses</span>
                                    <span class="label-ar">المصاريف</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <span class="label-fr">Nouvelle dépense</span>
                                <span class="label-ar">مصروف جديد</span>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="page-title">
                        <span class="label-fr">Ajouter une dépense</span>
                        <span class="label-ar">إضافة مصروف جديد</span>
                    </h1>
                    <p class="page-subtitle">
                        <span class="label-fr">Gérez vos sorties d'argent</span>
                        <span class="label-ar">إدارة نفقاتك المالية</span>
                    </p>
                </div>
                <a href="{{ route('expenses.index') }}" class="btn-ghost">
                    <i class="fas fa-arrow-left"></i>
                    <span class="label-fr">Retour à la liste</span>
                    <span class="label-ar">العودة للقائمة</span>
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

            <div class="row g-4">
                <!-- Colonne gauche - Gestion catégories -->
                <div class="col-lg-4">
                    <!-- Catégories -->
                    <div class="modern-card mb-4">
                        <div class="card-header-modern card-header-compact">
                            <div class="header-icon header-icon-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2H2v10l9.29 9.29c.94.94 2.48.94 3.42 0l6.58-6.58c.94-.94.94-2.48 0-3.42L12 2Z"/><path d="M7 7h.01"/></svg>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="header-title">Catégories</h3>
                                <span class="header-subtitle">{{ count($categories) }} catégories</span>
                            </div>
                            <button type="button" class="btn-icon-add" onclick="openCategoryModal()" title="Ajouter une catégorie">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                            </button>
                        </div>
                        <div class="category-list">
                            @forelse($categories as $cat)
                                <div class="category-item">
                                    <div class="category-color" style="background: hsl({{ ($loop->index * 37) % 360 }}, 70%, 60%)"></div>
                                    <div class="category-info">
                                        <span class="category-name">{{ $cat->name }}</span>
                                        <span class="category-count">{{ $cat->subcategories->count() }} sous-cat.</span>
                                    </div>
                                    <a href="{{ route('expense-categories.edit', $cat->id) }}" class="category-edit" title="Modifier">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
                                    </a>
                                </div>
                            @empty
                                <div class="empty-state">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2H2v10l9.29 9.29c.94.94 2.48.94 3.42 0l6.58-6.58c.94-.94.94-2.48 0-3.42L12 2Z"/><path d="M7 7h.01"/></svg>
                                    <p>Aucune catégorie</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="card-footer-modern">
                            <a href="{{ route('expense-categories.index') }}" class="link-modern">
                                Gérer les catégories
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Sous-catégories -->
                    <div class="modern-card">
                        <div class="card-header-modern card-header-compact">
                            <div class="header-icon header-icon-info">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/><rect width="20" height="14" x="2" y="6" rx="2"/></svg>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="header-title">Sous-catégories</h3>
                                <span class="header-subtitle">{{ count($subcategories) }} sous-catégories</span>
                            </div>
                            <button type="button" class="btn-icon-add" onclick="openSubcategoryModal()" title="Ajouter une sous-catégorie">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                            </button>
                        </div>
                        <div class="category-list" id="subcategory-list">
                            @forelse($subcategories->take(5) as $subcat)
                                <div class="category-item" data-category="{{ $subcat->expense_category_id }}">
                                    <div class="category-color secondary"></div>
                                    <div class="category-info">
                                        <span class="category-name">{{ $subcat->name }}</span>
                                        <span class="category-count">{{ $subcat->category->name ?? '-' }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-state">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/><rect width="20" height="14" x="2" y="6" rx="2"/></svg>
                                    <p>Aucune sous-catégorie</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="card-footer-modern">
                            <a href="{{ route('expense-subcategories.index') }}" class="link-modern">
                                Gérer les sous-catégories
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Colonne droite - Formulaire -->
                <div class="col-lg-8">
                    <div class="modern-card">
                        <div class="card-header-modern">
                            <div class="header-icon header-icon-danger">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                            </div>
                            <div>
                                <h3 class="header-title">Nouvelle dépense</h3>
                                <span class="header-subtitle">Remplissez les informations de la dépense</span>
                            </div>
                        </div>

                        <div class="card-body-modern">
                            <form action="{{ route('expenses.store') }}" method="POST" id="expense-form" enctype="multipart/form-data">
                                @csrf

                                <div class="row g-4">
                                    <!-- Date -->
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="expense_date">
                                                <i class="fas fa-calendar-alt"></i>
                                                <span class="label-fr">Date</span>
                                                <span class="label-ar">التاريخ</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="date" id="expense_date" name="expense_date" class="form-control-modern" value="{{ $defaultDate }}" required>
                                        </div>
                                    </div>

                                    <!-- Coach -->
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="coach_id">
                                                <i class="fas fa-user-tie"></i>
                                                <span class="label-fr">Coach</span>
                                                <span class="label-ar">المدرب</span>
                                            </label>
                                            <div class="select-wrapper">
                                                <select id="coach_id" name="coach_id" class="form-select-modern">
                                                    <option value="">— <span class="label-fr">Aucun coach</span><span class="label-ar">بدون مدرب</span> —</option>
                                                    @foreach($coaches as $coach)
                                                        <option value="{{ $coach->id }}">{{ $coach->name }}</option>
                                                    @endforeach
                                                </select>
                                                <i class="fas fa-chevron-down select-chevron"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Catégorie -->
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="expense_category_id">
                                                <i class="fas fa-tags"></i>
                                                <span class="label-fr">Catégorie</span>
                                                <span class="label-ar">الصنف</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-with-action">
                                                <div class="select-wrapper flex-grow-1">
                                                    <select id="expense_category_id" name="expense_category_id" class="form-select-modern" required>
                                                        <option value="">Sélectionner une catégorie...</option>
                                                        @foreach($categories as $c)
                                                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <i class="fas fa-chevron-down select-chevron"></i>
                                                </div>
                                                <button type="button" class="btn-add-inline" onclick="openCategoryModal()" title="Nouvelle catégorie">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Sous-catégorie -->
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="expense_subcategory_id">
                                                <i class="fas fa-layer-group"></i>
                                                <span class="label-fr">Sous-catégorie</span>
                                                <span class="label-ar">الصنف الفرعي</span>
                                            </label>
                                            <div class="input-with-action">
                                                <div class="select-wrapper flex-grow-1">
                                                    <select id="expense_subcategory_id" name="expense_subcategory_id" class="form-select-modern">
                                                        <option value="">— Optionnel —</option>
                                                        @foreach($subcategories as $sc)
                                                            <option value="{{ $sc->id }}" data-category="{{ $sc->expense_category_id }}">{{ $sc->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <i class="fas fa-chevron-down select-chevron"></i>
                                                </div>
                                                <button type="button" class="btn-add-inline" onclick="openSubcategoryModal()" title="Nouvelle sous-catégorie">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <p class="form-hint">
                                                <i class="fas fa-info-circle"></i>
                                                <span class="label-fr">Doit correspondre à la catégorie sélectionnée</span>
                                                <span class="label-ar">يجب أن تتطابق مع الصنف المحدد</span>
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Montant -->
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="amount">
                                                <i class="fas fa-money-bill-wave"></i>
                                                <span class="label-fr">Montant</span>
                                                <span class="label-ar">المبلغ</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-with-suffix">
                                                <input type="number" step="0.01" min="0" id="amount" name="amount" class="form-control-modern" placeholder="0.00" required>
                                                <span class="input-suffix">TND</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Méthode de paiement -->
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="method">
                                                <i class="fas fa-credit-card"></i>
                                                <span class="label-fr">Méthode de paiement</span>
                                                <span class="label-ar">طريقة الدفع</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="payment-methods">
                                                <label class="payment-method">
                                                    <input type="radio" name="method" value="cash" checked>
                                                    <div class="method-content">
                                                        <i class="fas fa-money-bill-alt"></i>
                                                        <span class="label-fr">Cash</span>
                                                        <span class="label-ar">نقدا</span>
                                                    </div>
                                                </label>
                                                <label class="payment-method">
                                                    <input type="radio" name="method" value="card">
                                                    <div class="method-content">
                                                        <i class="fas fa-credit-card"></i>
                                                        <span class="label-fr">Carte</span>
                                                        <span class="label-ar">بطاقة</span>
                                                    </div>
                                                </label>
                                                <label class="payment-method">
                                                    <input type="radio" name="method" value="bank">
                                                    <div class="method-content">
                                                        <i class="fas fa-university"></i>
                                                        <span class="label-fr">Virement</span>
                                                        <span class="label-ar">تحويل بنكي</span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Pièce jointe -->
                                    <div class="col-12">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="attachment">
                                                <i class="fas fa-paperclip"></i>
                                                <span class="label-fr">Pièce jointe (PDF, Image...)</span>
                                                <span class="label-ar">المرفقات (PDF، صور...)</span>
                                            </label>
                                            <input type="file" id="attachment" name="attachment" class="form-control-modern" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                            <p class="form-hint">
                                                <span class="label-fr">Formats acceptés: PDF, JPG, PNG, DOC (Max: 5MB)</span>
                                                <span class="label-ar">الصيغ المقبولة: PDF, JPG, PNG, DOC (الأقصى: 5 ميجابايت)</span>
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Notes -->
                                    <div class="col-12">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="notes">
                                                <i class="fas fa-sticky-note"></i>
                                                <span class="label-fr">Notes (optionnel)</span>
                                                <span class="label-ar">ملاحظات (اختياري)</span>
                                            </label>
                                            <textarea id="notes" name="notes" class="form-textarea-modern" rows="3" placeholder="Ajouter des notes ou remarques..."></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions-modern mt-4">
                                    <button type="submit" class="btn-primary-modern">
                                        <i class="fas fa-check"></i>
                                        <span class="label-fr">Enregistrer la dépense</span>
                                        <span class="label-ar">حفظ المصروف</span>
                                    </button>
                                    <a href="{{ route('expenses.index') }}" class="btn-secondary-modern">
                                        <i class="fas fa-times"></i>
                                        <span class="label-fr">Annuler</span>
                                        <span class="label-ar">إلغاء</span>
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

<!-- Modal Ajouter Catégorie -->
<div class="modal-overlay" id="category-modal">
    <div class="modal-modern">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fas fa-tags"></i>
                <span class="label-fr">Nouvelle catégorie</span>
                <span class="label-ar">صنف جديد</span>
            </h3>
            <button type="button" class="modal-close" onclick="closeCategoryModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="{{ route('expense-categories.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group-modern mb-3">
                    <label class="form-label-modern" for="cat_name">
                        <span class="label-fr">Nom de la catégorie</span>
                        <span class="label-ar">اسم الصنف</span>
                        <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="cat_name" name="name" class="form-control-modern" placeholder="Ex: Salaires, Loyer, Équipement..." required>
                </div>
                <div class="form-group-modern">
                    <label class="form-label-modern" for="cat_description">
                        <span class="label-fr">Description</span>
                        <span class="label-ar">الوصف</span>
                    </label>
                    <input type="text" id="cat_description" name="description" class="form-control-modern" placeholder="Description optionnelle...">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary-modern" onclick="closeCategoryModal()">
                    <span class="label-fr">Annuler</span>
                    <span class="label-ar">إلغاء</span>
                </button>
                <button type="submit" class="btn-primary-modern">
                    <i class="fas fa-check"></i>
                    <span class="label-fr">Créer la catégorie</span>
                    <span class="label-ar">إنشاء الصنف</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Ajouter Sous-catégorie -->
<div class="modal-overlay" id="subcategory-modal">
    <div class="modal-modern">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fas fa-layer-group"></i>
                <span class="label-fr">Nouvelle sous-catégorie</span>
                <span class="label-ar">صنف فرعي جديد</span>
            </h3>
            <button type="button" class="modal-close" onclick="closeSubcategoryModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="{{ route('expense-subcategories.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group-modern mb-3">
                    <label class="form-label-modern" for="subcat_category">
                        <span class="label-fr">Catégorie parente</span>
                        <span class="label-ar">الصنف الأصلي</span>
                        <span class="text-danger">*</span>
                    </label>
                    <select id="subcat_category" name="expense_category_id" class="form-select-modern" required>
                        <option value="">Sélectionner...</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group-modern">
                    <label class="form-label-modern" for="subcat_name">
                        <span class="label-fr">Nom de la sous-catégorie</span>
                        <span class="label-ar">اسم الصنف الفرعي</span>
                        <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="subcat_name" name="name" class="form-control-modern" placeholder="Ex: Salaire coach, Électricité..." required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary-modern" onclick="closeSubcategoryModal()">
                    <span class="label-fr">Annuler</span>
                    <span class="label-ar">إلغاء</span>
                </button>
                <button type="submit" class="btn-primary-modern">
                    <i class="fas fa-check"></i>
                    <span class="label-fr">Créer la sous-catégorie</span>
                    <span class="label-ar">إنشاء الصنف الفرعي</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Filtrer les sous-catégories selon la catégorie sélectionnée
document.getElementById('expense_category_id').addEventListener('change', function() {
    const categoryId = this.value;
    const subcategorySelect = document.getElementById('expense_subcategory_id');
    const options = subcategorySelect.querySelectorAll('option');
    
    options.forEach(option => {
        if (option.value === '') {
            option.style.display = '';
        } else if (option.dataset.category === categoryId) {
            option.style.display = '';
        } else {
            option.style.display = 'none';
        }
    });
    
    subcategorySelect.value = '';
});

// Modals
function openCategoryModal() {
    document.getElementById('category-modal').classList.add('active');
}

function closeCategoryModal() {
    document.getElementById('category-modal').classList.remove('active');
}

function openSubcategoryModal() {
    document.getElementById('subcategory-modal').classList.add('active');
    // Pre-select current category if selected
    const currentCategory = document.getElementById('expense_category_id').value;
    if (currentCategory) {
        document.getElementById('subcat_category').value = currentCategory;
    }
}

function closeSubcategoryModal() {
    document.getElementById('subcategory-modal').classList.remove('active');
}

// Close modal on overlay click
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('active');
        }
    });
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.active').forEach(modal => {
            modal.classList.remove('active');
        });
    }
});
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
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    --transition: all 0.2s ease;
}

/* Header */
.page-title { font-size: 1.75rem; font-weight: 700; color: var(--gray-900); margin: 0; }
.page-subtitle { font-size: 1rem; color: var(--primary); font-weight: 600; margin: 0.25rem 0 0; }
.breadcrumb { padding: 0; margin: 0; background: transparent; font-size: 0.875rem; }
.breadcrumb-item + .breadcrumb-item::before { content: "›"; color: var(--gray-400); }
.breadcrumb-link { color: var(--gray-500); text-decoration: none; display: inline-flex; align-items: center; gap: 0.375rem; }
.breadcrumb-link:hover { color: var(--primary); }

/* Alerts */
.alert-modern { display: flex; align-items: flex-start; gap: 0.75rem; padding: 1rem 1.25rem; border-radius: var(--radius-md); font-weight: 500; }
.alert-error { background: var(--danger-light); color: #991b1b; }

/* Buttons */
.btn-ghost { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1.25rem; background: transparent; color: var(--gray-600); font-weight: 500; font-size: 0.875rem; border: 1px solid var(--gray-300); border-radius: var(--radius-md); cursor: pointer; transition: var(--transition); text-decoration: none; }
.btn-ghost:hover { background: var(--gray-100); color: var(--gray-700); }

.btn-submit { display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: linear-gradient(135deg, var(--success) 0%, var(--success-dark) 100%); color: white; font-weight: 600; font-size: 0.9375rem; border: none; border-radius: var(--radius-md); cursor: pointer; transition: var(--transition); box-shadow: 0 4px 14px 0 rgba(16, 185, 129, 0.4); }
.btn-submit:hover { transform: translateY(-2px); box-shadow: 0 6px 20px 0 rgba(16, 185, 129, 0.5); }

.btn-primary-modern { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1.25rem; background: var(--primary); color: white; font-weight: 600; font-size: 0.875rem; border: none; border-radius: var(--radius-md); cursor: pointer; transition: var(--transition); }
.btn-primary-modern:hover { background: var(--primary-dark); }

.btn-icon-add { width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; background: var(--primary-bg); color: var(--primary); border: none; border-radius: var(--radius-sm); cursor: pointer; transition: var(--transition); }
.btn-icon-add:hover { background: var(--primary); color: white; }

.btn-add-inline { width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: var(--gray-100); color: var(--gray-600); border: 2px solid var(--gray-200); border-radius: var(--radius-md); cursor: pointer; transition: var(--transition); flex-shrink: 0; }
.btn-add-inline:hover { background: var(--primary-bg); color: var(--primary); border-color: var(--primary-light); }

/* Cards */
.modern-card { background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); overflow: hidden; }
.card-header-modern { display: flex; align-items: center; gap: 1rem; padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--gray-100); }
.card-header-compact { padding: 1rem 1.25rem; }
.header-icon { width: 44px; height: 44px; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.header-icon-primary { background: var(--primary-bg); color: var(--primary); }
.header-icon-info { background: var(--info-light); color: var(--info); }
.header-icon-danger { background: var(--danger-light); color: var(--danger); }
.header-title { font-size: 1rem; font-weight: 700; color: var(--gray-900); margin: 0; }
.header-subtitle { font-size: 0.75rem; color: var(--gray-500); }
.card-body-modern { padding: 1.5rem; }
.card-footer-modern { padding: 0.75rem 1.25rem; background: var(--gray-50); border-top: 1px solid var(--gray-100); }

/* Category List */
.category-list { max-height: 200px; overflow-y: auto; }
.category-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1.25rem; border-bottom: 1px solid var(--gray-100); transition: var(--transition); }
.category-item:last-child { border-bottom: none; }
.category-item:hover { background: var(--gray-50); }
.category-color { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.category-color.secondary { background: var(--info); }
.category-info { flex-grow: 1; min-width: 0; }
.category-name { display: block; font-weight: 600; font-size: 0.875rem; color: var(--gray-900); }
.category-count { font-size: 0.75rem; color: var(--gray-500); }
.category-edit { color: var(--gray-400); transition: var(--transition); }
.category-edit:hover { color: var(--primary); }

.empty-state { padding: 2rem; text-align: center; color: var(--gray-400); }
.empty-state svg { margin-bottom: 0.5rem; }
.empty-state p { margin: 0; font-size: 0.875rem; }

.link-modern { display: flex; align-items: center; gap: 0.375rem; font-size: 0.8125rem; font-weight: 600; color: var(--primary); text-decoration: none; }
.link-modern:hover { text-decoration: underline; }

/* Form */
.form-group-modern { margin-bottom: 0; }
.form-label-modern { display: flex; align-items: center; gap: 0.5rem; font-size: 0.8125rem; font-weight: 600; color: var(--gray-700); margin-bottom: 0.5rem; }
.form-label-modern svg { color: var(--gray-400); }

.form-control-modern, .form-select-modern, .form-textarea-modern { width: 100%; padding: 0.625rem 0.875rem; font-size: 0.9375rem; color: var(--gray-900); background: white; border: 2px solid var(--gray-200); border-radius: var(--radius-md); transition: var(--transition); }
.form-control-modern:hover, .form-select-modern:hover, .form-textarea-modern:hover { border-color: var(--gray-300); }
.form-control-modern:focus, .form-select-modern:focus, .form-textarea-modern:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15); }
.form-textarea-modern { resize: vertical; min-height: 80px; }

.select-wrapper { position: relative; }
.form-select-modern { appearance: none; padding-right: 2.5rem; cursor: pointer; }
.select-chevron { position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: var(--gray-400); pointer-events: none; }

.form-hint { display: flex; align-items: center; gap: 0.375rem; margin-top: 0.5rem; font-size: 0.8125rem; color: var(--gray-500); }

.input-with-action { display: flex; gap: 0.5rem; }
.input-with-suffix { position: relative; }
.input-with-suffix input { padding-right: 3.5rem; }
.input-suffix { position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: var(--gray-500); font-weight: 600; font-size: 0.875rem; }

/* Payment Methods */
.payment-methods { display: flex; gap: 0.75rem; flex-wrap: wrap; }
.payment-method { flex: 1; min-width: 100px; }
.payment-method input { display: none; }
.method-content { display: flex; flex-direction: column; align-items: center; gap: 0.5rem; padding: 1rem; background: var(--gray-50); border: 2px solid var(--gray-200); border-radius: var(--radius-md); cursor: pointer; transition: var(--transition); }
.method-content span { font-size: 0.8125rem; font-weight: 600; color: var(--gray-700); }
.method-content svg { color: var(--gray-500); }
.payment-method input:checked + .method-content { background: var(--primary-bg); border-color: var(--primary); }
.payment-method input:checked + .method-content svg { color: var(--primary); }
.payment-method input:checked + .method-content span { color: var(--primary-dark); }
.payment-method:hover .method-content { border-color: var(--gray-300); }

/* Form Actions */
.form-actions { display: flex; gap: 1rem; flex-wrap: wrap; }

/* Modal */
.modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 9999; opacity: 0; visibility: hidden; transition: var(--transition); }
.modal-overlay.active { opacity: 1; visibility: visible; }
.modal-modern { background: white; border-radius: var(--radius-lg); box-shadow: 0 25px 50px -12px rgb(0 0 0 / 0.25); width: 100%; max-width: 480px; margin: 1rem; transform: scale(0.95); transition: var(--transition); }
.modal-overlay.active .modal-modern { transform: scale(1); }
.modal-header { display: flex; align-items: center; justify-content: space-between; padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--gray-100); }
.modal-title { display: flex; align-items: center; gap: 0.75rem; font-size: 1.125rem; font-weight: 700; color: var(--gray-900); margin: 0; }
.modal-title svg { color: var(--primary); }
.modal-close { background: none; border: none; color: var(--gray-400); cursor: pointer; padding: 0.25rem; transition: var(--transition); }
.modal-close:hover { color: var(--gray-600); }
.modal-body { padding: 1.5rem; }
.modal-footer { display: flex; justify-content: flex-end; gap: 0.75rem; padding: 1rem 1.5rem; background: var(--gray-50); border-top: 1px solid var(--gray-100); }

/* Responsive */
@media (max-width: 768px) {
    .page-title { font-size: 1.375rem; }
    .form-actions { flex-direction: column; }
    .form-actions .btn-submit, .form-actions .btn-ghost { width: 100%; justify-content: center; }
    .payment-methods { flex-direction: column; }
    .payment-method { min-width: auto; }
}
</style>
@endpush
@endsection
