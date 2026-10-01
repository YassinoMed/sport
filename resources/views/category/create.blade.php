@extends('layouts.user-layout')
@section('content')
<div class="content-wrapper pb-4">
    <section class="content-header">
        <div class="container-fluid">
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
                        <a href="{{ route('category.list') }}" class="breadcrumb-link">
                            <span class="label-fr">Catégories</span>
                            <span class="label-ar">الأصناف</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">
                        <span class="label-fr">Nouvelle catégorie</span>
                        <span class="label-ar">صنف جديد</span>
                    </li>
                </ol>
            </nav>
            <h1 class="page-title">
                <i class="fas fa-layer-group text-primary me-2"></i>
                <span class="label-fr">Nouvelle catégorie</span>
                <span class="label-ar" dir="rtl">صنف جديد</span>
            </h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="modern-card">
                <div class="card-header-modern">
                    <h3 class="card-title">
                        <i class="fas fa-plus-circle me-2"></i>
                        <span class="label-fr">Créer une catégorie</span>
                        <span class="label-ar">إنشاء صنف</span>
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('category.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern" for="name">
                                        <span class="label-fr">Nom de la catégorie</span>
                                        <span class="label-ar">اسم الصنف</span>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="name" name="name" class="form-control-modern" required placeholder="Ex: Karate, Judo...">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern" for="description">
                                        <span class="label-fr">Description</span>
                                        <span class="label-ar">الوصف</span>
                                    </label>
                                    <input type="text" id="description" name="description" class="form-control-modern" placeholder="Description optionnelle...">
                                </div>
                            </div>
                        </div>

                        <div class="form-actions-modern mt-4">
                            <button type="submit" class="btn btn-primary-modern">
                                <i class="fas fa-save me-2"></i>
                                <span class="label-fr">Enregistrer</span>
                                <span class="label-ar">حفظ</span>
                            </button>
                            <a href="{{ route('category.list') }}" class="btn btn-secondary-modern">
                                <i class="fas fa-times me-2"></i>
                                <span class="label-fr">Annuler</span>
                                <span class="label-ar">إلغاء</span>
                            </a>
                        </div>
                    </form>

                    <hr class="my-4">
                    
                    <div class="d-flex justify-content-center">
                        <form method="POST" action="{{ route('category.seed-defaults') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="fas fa-magic me-2"></i>
                                <span class="label-fr">Ajouter les catégories par défaut</span>
                                <span class="label-ar">إضافة الأصناف الافتراضية</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

