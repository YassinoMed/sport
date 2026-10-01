@extends('layouts.user-layout')

@section('content')
    <div class="content-wrapper pb-5">
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
                                        <span class="label-ar" dir="rtl">الرئيسية</span>
                                    </a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('participation.list') }}" class="breadcrumb-link">
                                        <span class="label-fr">Participations</span>
                                        <span class="label-ar" dir="rtl">المشاركات</span>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active">
                                    <span class="label-fr">Nouveau</span>
                                    <span class="label-ar" dir="rtl">جديد</span>
                                </li>
                            </ol>
                        </nav>
                        <h1 class="page-title">
                            <i class="fas fa-user-plus"></i>
                            <span class="label-fr">Inscrire un membre</span>
                            <span class="label-ar">تسجيل عضو</span>
                        </h1>
                        <p class="page-subtitle">
                            <span class="label-fr">Inscrire un membre à un cours</span>
                            <span class="label-ar">تسجيل عضو في دورة</span>
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('participation.list') }}" class="btn-ghost">
                            <i class="fas fa-list"></i>
                            <span class="label-fr">Liste</span>
                            <span class="label-ar" dir="rtl">القائمة</span>
                        </a>
                        <a href="{{ route('course.list') }}" class="btn-ghost">
                            <i class="fas fa-arrow-left"></i>
                            <span class="label-fr">Retour aux cours</span>
                            <span class="label-ar" dir="rtl">العودة إلى الدورات</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <!-- Filtres -->
                <div class="modern-card mb-4">
                    <div class="card-header-modern">
                        <div class="header-icon header-icon-primary">
                            <i class="fas fa-filter"></i>
                        </div>
                        <div>
                            <h3 class="header-title">
                                <span class="label-fr">Filtres de recherche</span>
                                <span class="label-ar" dir="rtl">فلاتر البحث</span>
                            </h3>
                            <p class="header-subtitle mb-0">
                                <span class="label-fr">Affiner la liste des utilisateurs</span>
                                <span class="label-ar" dir="rtl">تصفية قائمة المستخدمين</span>
                            </p>
                        </div>
                        <button type="button" class="collapse-btn" data-toggle="collapse" data-target="#filterCollapse">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    <div id="filterCollapse" class="collapse show">
                        <div class="card-body-modern">
                            <form method="GET" action="{{ route('participation.create') }}">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <span class="label-fr">Recherche</span>
                                                <span class="label-ar">بحث</span>
                                            </label>
                                            <input type="text" name="q" class="form-control-modern" value="{{ $filters['q'] ?? '' }}" placeholder="Nom, Email, CIN...">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <span class="label-fr">Ville</span>
                                                <span class="label-ar">المدينة</span>
                                            </label>
                                            <select name="city_id" class="form-select-modern select2">
                                                <option value="">
                                                    <span class="label-fr">Toutes</span>
                                                    <span class="label-ar">الكل</span>
                                                </option>
                                                @foreach(($cities ?? []) as $city)
                                                    <option value="{{ $city->id }}" {{ ($filters['city_id'] ?? '') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <span class="label-fr">Salle</span>
                                                <span class="label-ar">القاعة</span>
                                            </label>
                                            <select name="gym_id" class="form-select-modern select2">
                                                <option value="">
                                                    <span class="label-fr">Toutes</span>
                                                    <span class="label-ar">الكل</span>
                                                </option>
                                                @foreach(($gyms ?? []) as $gym)
                                                    <option value="{{ $gym->id }}" {{ ($filters['gym_id'] ?? '') == $gym->id ? 'selected' : '' }}>{{ $gym->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <span class="label-fr">Ceinture</span>
                                                <span class="label-ar">الحزام</span>
                                            </label>
                                            <select name="belt_name" class="form-select-modern select2">
                                                <option value="">
                                                    <span class="label-fr">Toutes</span>
                                                    <span class="label-ar">الكل</span>
                                                </option>
                                                @foreach(($beltNames ?? []) as $bn)
                                                    <option value="{{ $bn }}" {{ ($filters['belt_name'] ?? '') == $bn ? 'selected' : '' }}>{{ $bn }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern">
                                                <span class="label-fr">Catégorie</span>
                                                <span class="label-ar">الفئة</span>
                                            </label>
                                            <select name="category_id" class="form-select-modern select2">
                                                <option value="">
                                                    <span class="label-fr">Toutes</span>
                                                    <span class="label-ar">الكل</span>
                                                </option>
                                                @foreach(($categories ?? []) as $cat)
                                                    <option value="{{ $cat->id }}" {{ ($filters['category_id'] ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex gap-2 mt-3">
                                    <button class="btn-primary-modern" type="submit">
                                        <i class="fas fa-search"></i>
                                        <span class="label-fr">Appliquer</span>
                                        <span class="label-ar">تطبيق</span>
                                    </button>
                                    <a href="{{ route('participation.create') }}" class="btn-ghost">
                                        <i class="fas fa-undo"></i>
                                        <span class="label-fr">Réinitialiser</span>
                                        <span class="label-ar">إعادة ضبط</span>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Formulaire Principal -->
                <form action="{{ route('participation.store') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="modern-card mb-4">
                                <div class="card-header-modern card-header-primary">
                                    <div class="header-icon">
                                        <i class="fas fa-user-plus"></i>
                                    </div>
                                    <div>
                                        <h3 class="header-title">
                                            <span class="label-fr">Détails de l'inscription</span>
                                            <span class="label-ar" dir="rtl">تفاصيل التسجيل</span>
                                        </h3>
                                        <p class="header-subtitle mb-0">
                                            <span class="label-fr">Sélectionnez les utilisateurs et le cours</span>
                                            <span class="label-ar" dir="rtl">اختر المستخدمين والدورة</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="card-body-modern">
                                    @if(session('error_with_waitlist'))
                                        <div class="alert-modern alert-warning mb-4">
                                            <div class="d-flex align-items-center w-100">
                                                <i class="fas fa-exclamation-triangle mr-3 fa-lg"></i>
                                                <div class="flex-grow-1">
                                                    {{ session('error_with_waitlist')['message'] }}
                                                </div>
                                                <form action="{{ route('participation.waitlist') }}" method="POST" class="ml-3">
                                                    @csrf
                                                    <input type="hidden" name="course_id" value="{{ session('error_with_waitlist')['course_id'] }}">
                                                    <input type="hidden" name="user_id" value="{{ session('error_with_waitlist')['user_id'] }}">
                                                    <button type="submit" class="btn-warning-modern btn-sm">
                                                        <i class="fas fa-list-ol mr-1"></i>
                                                        <span class="label-fr">Rejoindre la liste</span>
                                                        <span class="label-ar">الانضمام للقائمة</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endif

                                    @if($errors->has('error'))
                                        <div class="alert-modern alert-error mb-4">
                                            <i class="fas fa-times-circle mt-1"></i>
                                            <div>{{ $errors->first('error') }}</div>
                                        </div>
                                    @endif

                                    <div class="alert-modern alert-info mb-4">
                                        <i class="fas fa-info-circle mt-1"></i>
                                        <div>
                                            <span class="label-fr">Vous pouvez sélectionner plusieurs utilisateurs.</span>
                                            <span class="label-ar" dir="rtl">يمكنك اختيار عدة مستخدمين.</span>
                                        </div>
                                    </div>

                                    <div class="form-group-modern">
                                        <label for="user_ids" class="form-label-modern">
                                            <i class="fas fa-users"></i>
                                            <span class="label-fr">Athlètes *</span>
                                            <span class="label-ar" dir="rtl">الرياضيون *</span>
                                        </label>
                                        <select class="form-control-modern select2" name="user_ids[]" id="user_ids" multiple required>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                            @endforeach
                                        </select>
                                        <small class="form-hint">
                                            <i class="fas fa-search"></i>
                                            {{ count($users) }}
                                            <span class="label-fr">utilisateurs correspondent à vos filtres.</span>
                                            <span class="label-ar" dir="rtl">مستخدمين يطابقون الفلاتر الخاصة بك.</span>
                                        </small>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group-modern">
                                                <label for="course_id" class="form-label-modern">
                                                    <i class="fas fa-graduation-cap"></i>
                                                    <span class="label-fr">Cours *</span>
                                                    <span class="label-ar" dir="rtl">الدورة *</span>
                                                </label>
                                                <select class="form-select-modern select2 @error('course_id') is-invalid @enderror"
                                                    name="course_id" id="course_id" required>
                                                    <option value="">
                                                        <span class="label-fr">Sélectionner un cours</span>
                                                        <span class="label-ar" dir="rtl">اختر دورة</span>
                                                    </option>
                                                    @foreach ($courses as $course)
                                                        <option value="{{ $course->id }}" 
                                                            {{ (old('course_id') ?? ($filters['course_id'] ?? '')) == $course->id ? 'selected' : '' }}
                                                            class="{{ $course->capacity && $course->participations_count >= $course->capacity ? 'text-danger font-weight-bold' : '' }}">
                                                            {{ $course->name }} 
                                                            @if($course->capacity)
                                                                [{{ $course->participations_count }}/{{ $course->capacity }}]
                                                                @if($course->participations_count >= $course->capacity)
                                                                    - ({{ app()->getLocale() == 'ar' ? 'ممتلئ' : 'COMPLET' }})
                                                                @endif
                                                            @else
                                                                - ({{ app()->getLocale() == 'ar' ? 'غير محدود' : 'Illimité' }})
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('course_id')
                                                    <span class="text-danger small mt-1">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group-modern">
                                                <label for="status" class="form-label-modern">
                                                    <i class="fas fa-info-circle"></i>
                                                    <span class="label-fr">Statut *</span>
                                                    <span class="label-ar">الحالة *</span>
                                                </label>
                                                <select class="form-select-modern select2 @error('status') is-invalid @enderror"
                                                    name="status" id="status" required>
                                                    <option value="registered" {{ old('status') == 'registered' ? 'selected' : '' }}>
                                                        <span class="label-fr">Inscrit</span>
                                                        <span class="label-ar">مسجل</span>
                                                    </option>
                                                    <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>
                                                        <span class="label-fr">En attente</span>
                                                        <span class="label-ar">قيد الانتظار</span>
                                                    </option>
                                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>
                                                        <span class="label-fr">Terminé</span>
                                                        <span class="label-ar">مكتمل</span>
                                                    </option>
                                                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>
                                                        <span class="label-fr">Annulé</span>
                                                        <span class="label-ar">ملغي</span>
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group-modern">
                                        <label for="participated_at" class="form-label-modern">
                                            <i class="fas fa-calendar-alt"></i>
                                            <span class="label-fr">Date de Participation</span>
                                            <span class="label-ar" dir="rtl">تاريخ المشاركة</span>
                                        </label>
                                        <input type="datetime-local" id="participated_at" name="participated_at" 
                                            class="form-control-modern @error('participated_at') is-invalid @enderror"
                                            value="{{ old('participated_at', now()->format('Y-m-d\TH:i')) }}">
                                    </div>
                                </div>
                                <div class="card-footer-modern bg-light d-flex justify-content-between">
                                    <a href="{{ route('participation.list') }}" class="btn-ghost">
                                        <i class="fas fa-arrow-left"></i>
                                        <span class="label-fr">Retour</span>
                                        <span class="label-ar">عودة</span>
                                    </a>
                                    <button type="submit" class="btn-success-modern">
                                        <i class="fas fa-save"></i>
                                        <span class="label-fr">Enregistrer</span>
                                        <span class="label-ar">حفظ</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="modern-card mb-4">
                                <div class="card-header-modern">
                                    <div class="header-icon header-icon-warning">
                                        <i class="fas fa-lightbulb"></i>
                                    </div>
                                    <div>
                                        <h3 class="header-title">
                                            <span class="label-fr">Aide</span>
                                            <span class="label-ar">مساعدة</span>
                                        </h3>
                                    </div>
                                </div>
                                <div class="card-body-modern">
                                    <p class="text-muted small">
                                        <strong>
                                            <span class="label-fr">Capacité :</span>
                                            <span class="label-ar">السعة:</span>
                                        </strong>
                                        <span class="label-fr">Si un cours est complet, vous serez invité à ajouter l'athlète à la liste d'attente.</span>
                                        <span class="label-ar">إذا كانت الدورة مكتملة، فسيُطلب منك إضافة الرياضي إلى قائمة الانتظار.</span>
                                    </p>
                                    <p class="text-muted small">
                                        <strong>
                                            <span class="label-fr">Multi-sélection :</span>
                                            <span class="label-ar">اختيار متعدد:</span>
                                        </strong>
                                        <span class="label-fr">Vous pouvez sélectionner plusieurs athlètes à la fois.</span>
                                        <span class="label-ar">يمكنك اختيار عدة رياضيين في وقت واحد.</span>
                                    </p>
                                    <p class="text-muted small">
                                        <strong>
                                            <span class="label-fr">Filtres :</span>
                                            <span class="label-ar">الفلاتر:</span>
                                        </strong>
                                        <span class="label-fr">Utilisez les filtres ci-dessus pour restreindre la liste des athlètes.</span>
                                        <span class="label-ar">استخدم الفلاتر أعلاه لتقييد قائمة الرياضيين.</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
<script>
    $(function () {
        const isAr = $('html').attr('dir') === 'rtl' || $('body').hasClass('arabic');
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: isAr ? "اختر..." : "Sélectionner...",
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endpush

@push('styles')
<style>
    .gap-2 {
        gap: 0.5rem;
    }
    
    .collapse-btn {
        background: none;
        border: none;
        color: var(--gray-400);
        cursor: pointer;
        padding: 0.5rem;
        transition: var(--transition);
    }
    
    .collapse-btn:hover {
        color: var(--primary);
    }
    
    .collapse-btn[aria-expanded="true"] i {
        transform: rotate(180deg);
    }
    
    #user_ids {
        border-radius: var(--radius-md);
        padding: 0.5rem;
    }
    
    #user_ids option {
        padding: 0.5rem;
        border-radius: var(--radius-sm);
        margin-bottom: 2px;
    }
    
    #user_ids option:checked {
        background: var(--primary-bg);
        color: var(--primary);
    }
</style>
@endpush