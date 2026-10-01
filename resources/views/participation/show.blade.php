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
                                    <span class="label-fr">Détails</span>
                                    <span class="label-ar" dir="rtl">تفاصيل</span>
                                </li>
                            </ol>
                        </nav>
                    <h1 class="page-title">
                        <i class="fas fa-info-circle"></i>
                        <span class="label-fr">Détails de la Participation</span>
                        <span class="label-ar" dir="rtl">تفاصيل المشاركة</span>
                    </h1>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('participation.list') }}" class="btn-ghost">
                        <i class="fas fa-arrow-left"></i>
                        <span class="label-fr">Retour</span>
                        <span class="label-ar" dir="rtl">عودة</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
    
    <section class="content">
        <div class="container-fluid">
            <div class="modern-card">
                <div class="card-header-modern card-header-primary">
                    <div class="header-icon">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <div>
                        <h3 class="header-title">
                            <span class="label-fr">Informations de la Participation</span>
                            <span class="label-ar" dir="rtl">معلومات المشاركة</span>
                        </h3>
                        <p class="header-subtitle mb-0">ID: #{{ $participation->id }}</p>
                    </div>
                </div>
                <div class="card-body-modern">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-section">
                                <div class="section-header">
                                    <div class="section-number">1</div>
                                    <h4 class="section-title">
                                        <span class="label-fr">Utilisateur & Cours</span>
                                        <span class="label-ar" dir="rtl">المستخدم والدورة</span>
                                    </h4>
                                </div>
                                
                                <div class="info-list">
                                    <div class="info-item">
                                        <span class="info-label">
                                            <span class="label-fr">Utilisateur</span>
                                            <span class="label-ar" dir="rtl">المستخدم</span>
                                        </span>
                                        <span class="info-value">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm avatar-placeholder mr-2">
                                                    {{ strtoupper(substr($participation->user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="font-weight-bold">{{ $participation->user->name }}</div>
                                                    <div class="text-muted small">{{ $participation->user->email }}</div>
                                                </div>
                                            </div>
                                        </span>
                                    </div>
                                    
                                    <div class="info-item">
                                        <span class="info-label">
                                            <span class="label-fr">Cours</span>
                                            <span class="label-ar" dir="rtl">الدورة</span>
                                        </span>
                                        <span class="info-value">
                                            <div class="font-weight-bold text-primary">{{ $participation->course->name }}</div>
                                            @if($participation->course->description)
                                                <div class="text-muted small">{{ $participation->course->description }}</div>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-section">
                                <div class="section-header">
                                    <div class="section-number">2</div>
                                    <h4 class="section-title">
                                        <span class="label-fr">Statut & Dates</span>
                                        <span class="label-ar" dir="rtl">الحالة والمواعيد</span>
                                    </h4>
                                </div>
                                
                                <div class="info-list">
                                    <div class="info-item">
                                        <span class="info-label">
                                            <span class="label-fr">Statut</span>
                                            <span class="label-ar" dir="rtl">الحالة</span>
                                        </span>
                                        <span class="info-value">
                                            @switch($participation->status)
                                                @case('registered')
                                                    <span class="badge-modern badge-primary">
                                                        <span class="label-fr">Inscrit</span>
                                                        <span class="label-ar" dir="rtl">مسجل</span>
                                                    </span>
                                                    @break
                                                @case('completed')
                                                    <span class="badge-modern badge-success">
                                                        <span class="label-fr">Terminé</span>
                                                        <span class="label-ar" dir="rtl">مكتمل</span>
                                                    </span>
                                                    @break
                                                @case('cancelled')
                                                    <span class="badge-modern badge-danger">
                                                        <span class="label-fr">Annulé</span>
                                                        <span class="label-ar" dir="rtl">ملغي</span>
                                                    </span>
                                                    @break
                                                @case('pending')
                                                    <span class="badge-modern badge-warning">
                                                        <span class="label-fr">En attente</span>
                                                        <span class="label-ar" dir="rtl">قيد الانتظار</span>
                                                    </span>
                                                    @break
                                                @default
                                                    <span class="badge-modern badge-gray">{{ $participation->status }}</span>
                                            @endswitch
                                        </span>
                                    </div>
                                    
                                    <div class="info-item">
                                        <span class="info-label">
                                            <span class="label-fr">Date de Participation</span>
                                            <span class="label-ar" dir="rtl">تاريخ المشاركة</span>
                                        </span>
                                        <span class="info-value">
                                            <i class="far fa-calendar-check mr-1 text-primary"></i>
                                            {{ $participation->participated_at ? \Carbon\Carbon::parse($participation->participated_at)->format('d/m/Y à H:i') : 'Non définie' }}
                                        </span>
                                    </div>
                                    
                                    <div class="info-item">
                                        <span class="info-label">
                                            <span class="label-fr">Dates système</span>
                                            <span class="label-ar" dir="rtl">تواريخ النظام</span>
                                        </span>
                                        <div class="text-muted small">
                                            <div>
                                                <span class="label-fr">Créé le :</span>
                                                <span class="label-ar" dir="rtl">أنشئ في:</span>
                                                {{ $participation->created_at->format('d/m/Y à H:i') }}
                                            </div>
                                            <div>
                                                <span class="label-fr">Modifié le :</span>
                                                <span class="label-ar" dir="rtl">عدل في:</span>
                                                {{ $participation->updated_at->format('d/m/Y à H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4 pt-4 border-top">
                        <div class="col-12">
                            <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('participation.edit', $participation->id) }}" class="btn-warning-modern">
                                        <i class="fas fa-edit"></i>
                                        <span class="label-fr">Modifier</span>
                                        <span class="label-ar" dir="rtl">تعديل</span>
                                    </a>
                                    <form action="{{ route('participation.destroy', $participation->id) }}" method="POST" class="d-inline" 
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette participation ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger-modern">
                                            <i class="fas fa-trash"></i>
                                            <span class="label-fr">Supprimer</span>
                                            <span class="label-ar" dir="rtl">حذف</span>
                                        </button>
                                    </form>
                                </div>
                                <a href="{{ route('participation.list') }}" class="btn-ghost">
                                    <i class="fas fa-arrow-left"></i>
                                    <span class="label-fr">Retour à la liste</span>
                                    <span class="label-ar" dir="rtl">العودة إلى القائمة</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection