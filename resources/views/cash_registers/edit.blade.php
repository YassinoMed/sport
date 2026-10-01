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
                                <a href="{{ route('welcome') }}" class="breadcrumb-link">
                                    <i class="fas fa-home"></i>
                                    <span class="label-fr">Accueil</span>
                                    <span class="label-ar">الرئيسية</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('cash-registers.index') }}" class="breadcrumb-link">
                                    <span class="label-fr">Caisses</span>
                                    <span class="label-ar">الصناديق</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <span class="label-fr">Modifier la caisse</span>
                                <span class="label-ar">تعديل الصندوق</span>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="page-title">
                        <i class="fas fa-edit me-2 text-primary"></i>
                        <span class="label-fr">Modifier la caisse</span>
                        <span class="label-ar" dir="rtl">تعديل الصندوق</span>
                    </h1>
                </div>
                <a href="{{ route('cash-registers.index') }}" class="btn-ghost shadow-sm bg-white">
                    <i class="fas fa-arrow-left me-2"></i>
                    <span class="label-fr">Retour à la liste</span>
                    <span class="label-ar">العودة للقائمة</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="modern-card">
                        <div class="card-header-modern">
                            <div class="header-icon header-icon-primary">
                                <i class="fas fa-cash-register"></i>
                            </div>
                            <div>
                                <h3 class="header-title">
                                    <span class="label-fr">Modifier les informations</span>
                                    <span class="label-ar">تعديل المعلومات</span>
                                </h3>
                            </div>
                        </div>

                        <div class="card-body-modern p-4">
                            @if ($errors->any())
                                <div class="alert-modern alert-danger mb-4">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('cash-registers.update', $cashRegister) }}" method="post">
                                @csrf
                                @method('PUT')
                                <div class="row g-4">
                                    <!-- Name Field -->
                                    <div class="col-12">
                                        <div class="form-group-modern">
                                            <label for="name" class="form-label-modern">
                                                <span class="label-fr">Nom de la caisse</span>
                                                <span class="label-ar">اسم الصندوق</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input 
                                                type="text" 
                                                id="name" 
                                                class="form-control-modern @error('name') is-invalid @enderror" 
                                                name="name" 
                                                required 
                                                placeholder="Ex: Caisse Principale"
                                                value="{{ old('name', $cashRegister->name) }}"
                                            >
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Gym Field -->
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label for="gym_id" class="form-label-modern">
                                                <span class="label-fr">Salle</span>
                                                <span class="label-ar">القاعة</span>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select id="gym_id" name="gym_id" class="form-select-modern @error('gym_id') is-invalid @enderror" required>
                                                <option value="" disabled>
                                                    <span class="label-fr">Choisir une salle...</span>
                                                    <span class="label-ar">اختر قاعة...</span>
                                                </option>
                                                @foreach ($gyms as $gym)
                                                    <option value="{{ $gym->id }}" {{ old('gym_id', $cashRegister->gym_id) == $gym->id ? 'selected' : '' }}>
                                                        {{ $gym->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('gym_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Status Field -->
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label for="status" class="form-label-modern">
                                                <span class="label-fr">Statut</span>
                                                <span class="label-ar">الحالة</span>
                                            </label>
                                            <select id="status" name="status" class="form-select-modern @error('status') is-invalid @enderror">
                                                <option value="active" {{ old('status', $cashRegister->status) == 'active' ? 'selected' : '' }}>
                                                    <span class="label-fr">Actif</span>
                                                    <span class="label-ar">نشط</span>
                                                </option>
                                                <option value="inactive" {{ old('status', $cashRegister->status) == 'inactive' ? 'selected' : '' }}>
                                                    <span class="label-fr">Inactif</span>
                                                    <span class="label-ar">غير نشط</span>
                                                </option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 mt-4">
                                        <div class="d-flex justify-content-between">
                                            <a href="{{ route('cash-registers.index') }}" class="btn-ghost">
                                                <span class="label-fr">Annuler</span>
                                                <span class="label-ar">إلغاء</span>
                                            </a>
                                            <button type="submit" class="btn-primary-modern">
                                                <i class="fas fa-save me-2"></i>
                                                <span class="label-fr">Mettre à jour</span>
                                                <span class="label-ar">تحديث</span>
                                            </button>
                                        </div>
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
