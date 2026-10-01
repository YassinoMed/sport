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
                                <a href="{{ route('belt.list') }}" class="breadcrumb-link">
                                    <span class="label-fr">Ceintures</span>
                                    <span class="label-ar">الأحزمة</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <span class="label-fr">Nouvelle ceinture</span>
                                <span class="label-ar">حزام جديد</span>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="page-title">
                        <span class="label-fr">Nouvelle ceinture</span>
                        <span class="label-ar">إسناد حزام جديد</span>
                    </h1>
                </div>
                <a href="{{ route('belt.list') }}" class="btn-ghost">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    <span class="label-fr">Retour à la liste</span>
                    <span class="label-ar">العودة للقائمة</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
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

            <form action="{{ route('belt.store') }}" method="POST">
                @csrf
                <div class="row g-4 justify-content-center">
                    <div class="col-lg-8">
                        <div class="modern-card">
                            <div class="card-header-modern">
                                <div class="header-icon header-icon-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                                </div>
                                <div>
                                    <h3 class="header-title">
                                        <span class="label-fr">Détails du passage de grade</span>
                                        <span class="label-ar">تفاصيل اختبار الحزام</span>
                                    </h3>
                                </div>
                            </div>
                            
                            <div class="card-body-modern p-4">
                                <div class="alert-modern alert-info mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                    <span class="label-fr">Veuillez remplir les informations pour enregistrer un nouveau grade.</span>
                                    <span class="label-ar">يرجى ملء المعلومات لتسجيل درجة حزام جديدة.</span>
                                </div>

                                <div class="row g-4">
                                    <!-- Athlete -->
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="user_id">
                                                <span class="label-fr">Athlète</span>
                                                <span class="label-ar">اللاعب</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select name="user_id" id="user_id" class="form-control-modern select2 @error('user_id') is-invalid @enderror" required>
                                                <option value="" disabled selected>
                                                    <span class="label-fr">Choisir un athlète...</span>
                                                    <span class="label-ar">اختر لاعباً...</span>
                                                </option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}" data-gym-id="{{ $user->gym_id }}" data-last-belt="{{ optional($user->belts->first())->belt_name }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Belt Name -->
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="belt_name">
                                                <span class="label-fr">Nom de la ceinture</span>
                                                <span class="label-ar">اسم الحزام</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select name="belt_name" id="belt_name" class="form-control-modern select2 @error('belt_name') is-invalid @enderror" required>
                                                <option value="" disabled selected>
                                                    <span class="label-fr">Choisir un حزام...</span>
                                                    <span class="label-ar">اختر حزاماً...</span>
                                                </option>
                                                @foreach(($beltOptions ?? []) as $value => $label)
                                                    <option value="{{ $value }}" {{ old('belt_name') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Achieved At -->
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="achieved_at">
                                                <span class="label-fr">Date d'obtention</span>
                                                <span class="label-ar">تاريخ الحصول عليه</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="date" name="achieved_at" id="achieved_at" 
                                                   class="form-control-modern @error('achieved_at') is-invalid @enderror" 
                                                   value="{{ old('achieved_at') }}" 
                                                   max="{{ date('Y-m-d') }}" 
                                                   required>
                                        </div>
                                    </div>

                                    <!-- Exam At -->
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="exam_at">
                                                <span class="label-fr">Date de l'examen</span>
                                                <span class="label-ar">تاريخ الاختبار</span>
                                            </label>
                                            <input type="date" name="exam_at" id="exam_at"
                                                   class="form-control-modern @error('exam_at') is-invalid @enderror"
                                                   value="{{ old('exam_at') }}"
                                                   max="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>

                                    <!-- Price -->
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="price">
                                                <span class="label-fr">Prix du passage</span>
                                                <span class="label-ar">سعر الاختبار</span>
                                            </label>
                                            <div class="input-group-modern">
                                                <input type="number" step="0.01" min="0" name="price" id="price" 
                                                       class="form-control-modern @error('price') is-invalid @enderror" 
                                                       value="{{ old('price') }}" 
                                                       placeholder="0.00">
                                                <span class="input-group-text-modern">TND</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Cash Register -->
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="cash_register_id">
                                                <span class="label-fr">Caisse</span>
                                                <span class="label-ar">الصندوق</span>
                                            </label>
                                            <select name="cash_register_id" id="cash_register_id" class="form-control-modern select2 @error('cash_register_id') is-invalid @enderror">
                                                <option value="">
                                                    <span class="label-fr">Choisir une caisse...</span>
                                                    <span class="label-ar">اختر صندوقاً...</span>
                                                </option>
                                                @foreach($cashRegisters as $register)
                                                    <option value="{{ $register->id }}" data-gym-id="{{ $register->gym_id }}" {{ old('cash_register_id') == $register->id ? 'selected' : '' }}>
                                                        {{ $register->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer-modern bg-light d-flex justify-content-between p-4">
                                <a href="{{ route('belt.list') }}" class="btn-ghost">
                                    <span class="label-fr">Annuler</span>
                                    <span class="label-ar">إلغاء</span>
                                </a>
                                <button type="submit" class="btn-primary-modern">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                    <span class="label-fr">Enregistrer</span>
                                    <span class="label-ar">حفظ</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection
@section('dataTableScript')
<script>
document.addEventListener('DOMContentLoaded', function(){
  var userSelect = document.getElementById('user_id');
  var beltSelect = document.getElementById('belt_name');
  function applyDefaultBelt(){
    var opt = userSelect.options[userSelect.selectedIndex];
    var last = opt ? opt.getAttribute('data-last-belt') : '';
    if (last && !{{ json_encode((bool)old('belt_name')) }}) {
      beltSelect.value = last;
    }
  }
  userSelect.addEventListener('change', function() {
    applyDefaultBelt();
    filterCashRegisters();
  });

  function filterCashRegisters() {
    var selectedUser = userSelect.options[userSelect.selectedIndex];
    var gymId = selectedUser ? selectedUser.getAttribute('data-gym-id') : '';
    var cashRegisterSelect = document.getElementById('cash_register_id');
    
    if (!cashRegisterSelect) return;

    Array.from(cashRegisterSelect.options).forEach(function(option) {
      if (option.value === "") {
        option.style.display = "block";
        return;
      }
      var optionGymId = option.getAttribute('data-gym-id');
      if (gymId && optionGymId && gymId !== optionGymId) {
        option.style.display = "none";
        if (cashRegisterSelect.value === option.value) {
          cashRegisterSelect.value = "";
        }
      } else {
        option.style.display = "block";
      }
    });
    
    // Re-initialize select2 if used
    if (typeof $(cashRegisterSelect).select2 === 'function') {
      $(cashRegisterSelect).trigger('change.select2');
    }
  }

  applyDefaultBelt();
  filterCashRegisters();
});
</script>
@endsection
