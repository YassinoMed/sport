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
                                    <span class="label-fr">Accueil</span>
                                    <span class="label-ar">الرئيسية</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('events.index') }}" class="breadcrumb-link">
                                    <span class="label-fr">Événements</span>
                                    <span class="label-ar">الأحداث</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <span class="label-fr">Nouveau</span>
                                <span class="label-ar">إنشاء</span>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="page-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                        <span class="label-fr">Créer un événement</span>
                        <span class="label-ar">إنشاء حدث جديد</span>
                    </h1>
                </div>
                <a href="{{ route('events.index') }}" class="btn-ghost">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    <span class="label-fr">Retour à la liste</span>
                    <span class="label-ar">العودة للقائمة</span>
                </a>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="modern-card animate-fadeIn">
                        <div class="card-header-modern">
                            <div class="header-icon header-icon-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><path d="m9 16 2 2 4-4"/></svg>
                            </div>
                            <div>
                                <h3 class="header-title">
                                    <span class="label-fr">Informations sur l'événement</span>
                                    <span class="label-ar">معلومات الحدث</span>
                                </h3>
                            </div>
                        </div>

                        <div class="card-body-modern p-4">
                            @if ($errors->any())
                                <div class="alert-modern alert-danger mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('events.store') }}" method="POST">
                                @csrf

                                <div class="row g-4 mb-4">
                                    <div class="col-12">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="name">
                                                <span class="label-fr">Nom</span>
                                                <span class="label-ar">الاسم</span>
                                            </label>
                                            <input type="text" class="form-control-modern @error('name') is-invalid @enderror" id="name" name="name" required placeholder="Ex: Tournoi Annuel">
                                            @error('name')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="description">
                                                <span class="label-fr">Description</span>
                                                <span class="label-ar">الوصف</span>
                                            </label>
                                            <textarea class="form-control-modern @error('description') is-invalid @enderror" id="description" name="description" rows="4" style="height: auto;" placeholder="Description de l'événement..."></textarea>
                                            @error('description')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-4 mb-4">
                                    <div class="col-12">
                                        <h5 class="fw-bold mb-3">
                                            <span class="label-fr">Catégories de poids</span>
                                            <span class="label-ar">فئات الوزن</span>
                                        </h5>
                                        @error('weight_categories')
                                            <div class="alert-modern alert-danger mt-2">{{ $message }}</div>
                                        @enderror
                                        <div id="weightCats">
                                            <div class="row gy-3 align-items-end mb-3 wc-row animate-fadeIn">
                                                <div class="col-md-4">
                                                    <div class="form-group-modern">
                                                        <label class="form-label-modern">
                                                            <span class="label-fr">Nom</span>
                                                            <span class="label-ar">الاسم</span>
                                                        </label>
                                                        <input type="text" class="form-control-modern" name="wc_name[]" placeholder="Ex: Senior -75kg">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group-modern">
                                                        <label class="form-label-modern">
                                                            <span class="label-fr">Min (kg)</span>
                                                            <span class="label-ar">الأدنى (كغ)</span>
                                                        </label>
                                                        <input type="number" step="0.1" min="0" max="300" class="form-control-modern" name="wc_min[]" placeholder="0">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group-modern">
                                                        <label class="form-label-modern">
                                                            <span class="label-fr">Max (kg)</span>
                                                            <span class="label-ar">الأقصى (كغ)</span>
                                                        </label>
                                                        <input type="number" step="0.1" min="0" max="300" class="form-control-modern" name="wc_max[]" placeholder="0">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn-ghost text-danger w-100 btn-remove-wc">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                                        <span class="label-fr">Supprimer</span>
                                                        <span class="label-ar">حذف</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" id="btnAddWc" class="btn-ghost text-primary mt-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                                            <span class="label-fr">Ajouter une catégorie de poids</span>
                                            <span class="label-ar">إضافة فئة وزن</span>
                                        </button>
                                    </div>
                                </div>

                                <div class="row g-4 mb-4">
                                    <div class="col-md-8">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="location">
                                                <span class="label-fr">Lieu</span>
                                                <span class="label-ar">الموقع</span>
                                            </label>
                                            <input type="text" class="form-control-modern @error('location') is-invalid @enderror" id="location" name="location" placeholder="Ex: Salle Couverte">
                                            @error('location')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="capacity">
                                                <span class="label-fr">Capacité</span>
                                                <span class="label-ar">السعة</span>
                                            </label>
                                            <input type="number" class="form-control-modern @error('capacity') is-invalid @enderror" id="capacity" name="capacity" value="0" min="0" required placeholder="0">
                                            <small class="text-muted d-block mt-1">
                                                <span class="label-fr">Laissez 0 pour une capacité illimitée</span>
                                                <span class="label-ar">اترك 0 لسعة غير محدودة</span>
                                            </small>
                                            @error('capacity')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-4 mb-4">
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="belt_requirement">
                                                <span class="label-fr">Exigence de ceinture</span>
                                                <span class="label-ar">متطلبات الحزام</span>
                                            </label>
                                            <input type="text" class="form-control-modern @error('belt_requirement') is-invalid @enderror" id="belt_requirement" name="belt_requirement" placeholder="Ex: Ceinture Noire">
                                            @error('belt_requirement')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="registration_fee">
                                                <span class="label-fr">Frais (TND)</span>
                                                <span class="label-ar">رسوم التسجيل</span>
                                            </label>
                                            <input type="number" step="0.01" class="form-control-modern @error('registration_fee') is-invalid @enderror" id="registration_fee" name="registration_fee" value="0" min="0" required placeholder="0.00">
                                            @error('registration_fee')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="start_at">
                                                <span class="label-fr">Date de début</span>
                                                <span class="label-ar">تاريخ البدء</span>
                                            </label>
                                            <input type="datetime-local" class="form-control-modern @error('start_at') is-invalid @enderror" id="start_at" name="start_at" required>
                                            @error('start_at')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-4 mb-4">
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="end_at">
                                                <span class="label-fr">Date de fin</span>
                                                <span class="label-ar">تاريخ الانتهاء</span>
                                            </label>
                                            <input type="datetime-local" class="form-control-modern @error('end_at') is-invalid @enderror" id="end_at" name="end_at">
                                            @error('end_at')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 d-flex align-items-center">
                                        <div class="modern-checkbox mt-4">
                                            <input type="hidden" name="is_public" value="0">
                                            <input type="checkbox" class="modern-checkbox-input" id="is_public" name="is_public" value="1" checked>
                                            <label class="modern-checkbox-label" for="is_public">
                                                <span class="label-fr">Événement public</span>
                                                <span class="label-ar">حدث عام</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer-modern bg-light d-flex justify-content-between p-4 mt-4">
                                    <a href="{{ route('events.index') }}" class="btn-ghost">
                                        <span class="label-fr">Annuler</span>
                                        <span class="label-ar">إلغاء</span>
                                    </a>
                                    <button type="submit" class="btn-primary-modern">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                        <span class="label-fr">Créer l'événement</span>
                                        <span class="label-ar">إنشاء الحدث</span>
                                    </button>
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

@push('styles')
<style>
    /* Complementary styles if not in modern-ui.css */
    .modern-checkbox {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .modern-checkbox-input {
        width: 1.25rem;
        height: 1.25rem;
        border-radius: 4px;
        border: 2px solid var(--border-color);
        cursor: pointer;
    }
    .modern-checkbox-label {
        cursor: pointer;
        font-weight: 500;
        color: var(--text-primary);
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  const container = document.getElementById('weightCats');
  const btnAdd = document.getElementById('btnAddWc');
  
  function newRow(){
    const div = document.createElement('div');
    div.className = 'row gy-3 align-items-end mb-3 wc-row animate-fadeIn';
    div.innerHTML = `
      <div class="col-md-4">
        <div class="form-group-modern">
          <label class="form-label-modern">
            <span class="label-fr">Nom</span>
            <span class="label-ar">الاسم</span>
          </label>
          <input type="text" class="form-control-modern" name="wc_name[]" placeholder="Ex: Senior -75kg">
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group-modern">
          <label class="form-label-modern">
            <span class="label-fr">Min (kg)</span>
            <span class="label-ar">الأدنى (كغ)</span>
          </label>
          <input type="number" step="0.1" min="0" max="300" class="form-control-modern" name="wc_min[]" placeholder="0">
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group-modern">
          <label class="form-label-modern">
            <span class="label-fr">Max (kg)</span>
            <span class="label-ar">الأقصى (كغ)</span>
          </label>
          <input type="number" step="0.1" min="0" max="300" class="form-control-modern" name="wc_max[]" placeholder="0">
        </div>
      </div>
      <div class="col-md-2">
        <button type="button" class="btn-ghost text-danger w-100 btn-remove-wc">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
          <span class="label-fr">Supprimer</span>
          <span class="label-ar">حذف</span>
        </button>
      </div>`;
    return div;
  }
  
  btnAdd.addEventListener('click', function(){ 
    container.appendChild(newRow()); 
  });
  
  container.addEventListener('click', function(e){
    const removeBtn = e.target.closest('.btn-remove-wc');
    if (removeBtn) {
      const row = removeBtn.closest('.wc-row');
      if (row) {
        row.classList.add('animate-scaleOut');
        setTimeout(() => row.remove(), 200);
      }
    }
  });
});
</script>
@endpush
