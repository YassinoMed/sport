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
                                    <span class="label-fr">Modifier</span>
                                    <span class="label-ar" dir="rtl">تعديل</span>
                                </li>
                            </ol>
                        </nav>
                        <h1 class="page-title">
                            <i class="fas fa-edit"></i>
                            <span class="label-fr">Modifier la Participation</span>
                            <span class="label-ar" dir="rtl">تعديل المشاركة</span>
                        </h1>
                        <p class="page-subtitle">
                            <span class="label-fr">Modifier les détails de l'inscription</span>
                            <span class="label-ar" dir="rtl">تعديل تفاصيل التسجيل</span>
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('participation.list') }}" class="btn-ghost">
                            <i class="fas fa-arrow-left"></i>
                            <span class="label-fr">Retour à la liste</span>
                            <span class="label-ar" dir="rtl">العودة إلى القائمة</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <form action="{{ route('participation.update', $participation->id) }}" method="post">
                    @csrf
                    @method('put')
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="modern-card">
                                <div class="card-header-modern card-header-primary">
                                    <div class="header-icon">
                                        <i class="fas fa-edit"></i>
                                    </div>
                                    <div>
                                        <h3 class="header-title">
                                            <span class="label-fr">Modifier les informations</span>
                                            <span class="label-ar" dir="rtl">تعديل المعلومات</span>
                                        </h3>
                                        <p class="header-subtitle mb-0">ID: #{{ $participation->id }}</p>
                                    </div>
                                </div>
                                <div class="card-body-modern">
                                    @if ($errors->any())
                                        <div class="alert-modern alert-error mb-4">
                                            <i class="fas fa-exclamation-circle mt-1"></i>
                                            <ul class="mb-0 list-unstyled">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <div class="form-group-modern">
                                        <label for="user_id" class="form-label-modern">
                                            <i class="fas fa-user"></i>
                                            <span class="label-fr">Utilisateur *</span>
                                            <span class="label-ar" dir="rtl">المستخدم *</span>
                                        </label>
                                        <select class="form-select-modern select2 @error('user_id') is-invalid @enderror"
                                            name="user_id" id="user_id" required>
                                            <option value="">
                                                <span class="label-fr">Sélectionner un utilisateur</span>
                                                <span class="label-ar" dir="rtl">اختر مستخدماً</span>
                                            </option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}" 
                                                    {{ (old('user_id', $participation->user_id) == $user->id) ? 'selected' : '' }}>
                                                    {{ $user->name }} ({{ $user->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
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
                                                    {{ (old('course_id', $participation->course_id) == $course->id) ? 'selected' : '' }}>
                                                    {{ $course->name }}
                                                    @if($course->description)
                                                        - {{ Str::limit($course->description, 50) }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group-modern">
                                                <label for="status" class="form-label-modern">
                                                    <i class="fas fa-info-circle"></i>
                                                    <span class="label-fr">Statut *</span>
                                                    <span class="label-ar" dir="rtl">الحالة *</span>
                                                </label>
                                                <select class="form-select-modern select2 @error('status') is-invalid @enderror"
                                                    name="status" id="status" required>
                                                    <option value="registered" {{ (old('status', $participation->status) == 'registered') ? 'selected' : '' }}>
                                                        <span class="label-fr">Inscrit</span>
                                                        <span class="label-ar" dir="rtl">مسجل</span>
                                                    </option>
                                                    <option value="completed" {{ (old('status', $participation->status) == 'completed') ? 'selected' : '' }}>
                                                        <span class="label-fr">Terminé</span>
                                                        <span class="label-ar" dir="rtl">مكتمل</span>
                                                    </option>
                                                    <option value="cancelled" {{ (old('status', $participation->status) == 'cancelled') ? 'selected' : '' }}>
                                                        <span class="label-fr">Annulé</span>
                                                        <span class="label-ar" dir="rtl">ملغي</span>
                                                    </option>
                                                    <option value="pending" {{ (old('status', $participation->status) == 'pending') ? 'selected' : '' }}>
                                                        <span class="label-fr">En attente</span>
                                                        <span class="label-ar" dir="rtl">قيد الانتظار</span>
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group-modern">
                                                <label for="participated_at" class="form-label-modern">
                                                    <i class="fas fa-calendar-alt"></i>
                                                    <span class="label-fr">Date de Participation</span>
                                                    <span class="label-ar" dir="rtl">تاريخ المشاركة</span>
                                                </label>
                                                <input type="datetime-local" id="participated_at" name="participated_at" 
                                                    class="form-control-modern @error('participated_at') is-invalid @enderror"
                                                    value="{{ old('participated_at', $participation->participated_at ? \Carbon\Carbon::parse($participation->participated_at)->format('Y-m-d\TH:i') : '') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer-modern bg-light d-flex justify-content-between">
                                    <a href="{{ route('participation.list') }}" class="btn-ghost">
                                        <i class="fas fa-arrow-left"></i>
                                        <span class="label-fr">Annuler</span>
                                        <span class="label-ar" dir="rtl">إلغاء</span>
                                    </a>
                                    <button type="submit" class="btn-primary-modern">
                                        <i class="fas fa-save"></i>
                                        <span class="label-fr">Mettre à jour</span>
                                        <span class="label-ar" dir="rtl">تحديث</span>
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

@push('scripts')
<script>
    $(function () {
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: "Sélectionner...",
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endpush