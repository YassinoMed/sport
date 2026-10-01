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
                                <a href="{{ route('gym.list') }}" class="breadcrumb-link">
                                    <span class="label-fr">Salles</span>
                                    <span class="label-ar">القاعات</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <span class="label-fr">Modifier</span>
                                <span class="label-ar">تعديل</span>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="page-title">
                        <span class="label-fr">Modifier la salle</span>
                        <span class="label-ar">تعديل الصالة</span>
                        <span class="id-badge">#{{ $gym->id }}</span>
                    </h1>
                </div>
                <a href="{{ route('gym.list') }}" class="btn-ghost">
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

            <form action="{{route('gym.update',['gym' => $gym['id']])}}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row g-4 justify-content-center">
                    <div class="col-lg-8">
                        <div class="modern-card">
                            <div class="card-header-modern">
                                <div class="header-icon header-icon-warning">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                </div>
                                <div>
                                    <h3 class="header-title">
                                        <span class="label-fr">Modifier les détails</span>
                                        <span class="label-ar">تعديل التفاصيل</span>
                                    </h3>
                                </div>
                            </div>
                            
                            <div class="card-body-modern p-4">
                                <div class="row g-4">
                                    <!-- Name -->
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="name">
                                                <span class="label-fr">Nom de la salle</span>
                                                <span class="label-ar">اسم الصالة</span>
                                            </label>
                                            <input type="text" id="name" name="name" class="form-control-modern @if(old('name') || $gym->name) has-value @endif" value="{{old('name') ?? $gym->name}}" required>
                                        </div>
                                    </div>

                                    <!-- City -->
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="city">
                                                <span class="label-fr">Ville</span>
                                                <span class="label-ar">المدينة</span>
                                            </label>
                                            <select id="city" name="city_id" class="form-control-modern custom-select" required>
                                                <option value="" disabled>Choisir une ville... / اختر مدينة...</option>
                                                @foreach ($cities as $city)
                                                    <option value="{{ $city->id }}" @if($city->id == $gym->city_id) selected @endif>{{ $city->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Gym Manager -->
                                    <div class="col-md-12">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="user_id">
                                                <span class="label-fr">Directeur de salle</span>
                                                <span class="label-ar">مدير الصالة</span>
                                            </label>
                                            <select id="user_id" name="user_id" class="form-control-modern custom-select">
                                                <option value="" disabled>Choisir un directeur... / اختر مديراً...</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}" @if($user->id == $gym->user_id) selected @endif>{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Cover Image -->
                                    <div class="col-12">
                                        <div class="form-group-modern">
                                            <label class="form-label-modern" for="image">
                                                <span class="label-fr">Image de couverture</span>
                                                <span class="label-ar">صورة الغلاف</span>
                                            </label>
                                            <div class="image-upload-wrapper">
                                                @if($gym->cover_image)
                                                    <div class="current-image mb-2">
                                                        <img src="{{ asset($gym->cover_image) }}" alt="Current" class="rounded border" style="max-height: 100px;">
                                                        <p class="small text-muted mt-1">
                                                            <span class="label-fr">Image actuelle</span>
                                                            <span class="label-ar">الصورة الحالية</span>
                                                        </p>
                                                    </div>
                                                @endif
                                                <input type="file" id="image" name="cover_image" class="form-control-modern" accept="image/*">
                                                <div class="upload-placeholder mt-2">
                                                    <small class="text-muted">
                                                        <span class="label-fr">Format suggéré: JPG, PNG (Max: 2MB). Laissez vide pour conserver l'image actuelle.</span>
                                                        <span class="label-ar">التنسيق المقترح: JPG, PNG (الحد الأقصى: 2 ميجابايت). اتركه فارغاً للاحتفاظ بالصورة الحالية.</span>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer-modern bg-light d-flex justify-content-between p-4">
                                <a href="{{ route('gym.list') }}" class="btn-ghost">
                                    <span class="label-fr">Annuler</span>
                                    <span class="label-ar">إلغاء</span>
                                </a>
                                <button type="submit" class="btn-primary-modern">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                    <span class="label-fr">Mettre à jour</span>
                                    <span class="label-ar">تحديث</span>
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
