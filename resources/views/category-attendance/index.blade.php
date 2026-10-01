@extends('layouts.user-layout')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Gestion de la Présence par Cat égories</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Accueil / الرئيسية</a></li>
                        <li class="breadcrumb-item active">Présence Catégories</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Liste

 des Catégories / قائمة الفئات</h3>
            </div>
            <div class="card-body">
                @if($categories->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Catégorie / الفئة</th>
                                    <th>Utilisateurs / المستخدمين</th>
                                    <th>Présents</th>
                                    <th>Absents</th>
                                    <th>Excusés</th>
                                    <th>Dernière Présence</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                    <tr>
                                        <td>
                                            <strong>{{ $category->name }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $category->total_users }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">{{ $category->presents }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-danger">{{ $category->absents }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-warning">{{ $category->excused }}</span>
                                        </td>
                                        <td>
                                            @if($category->last_attendance_date)
                                                {{ \Carbon\Carbon::parse($category->last_attendance_date)->format('d/m/Y') }}
                                            @else
                                                <span class="text-muted">Aucune</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('category-attendance.create', $category->id) }}" 
                                                   class="btn btn-primary btn-sm" title="Marquer Présence">
                                                    <i class="fas fa-check"></i> Présence / الحضور
                                                </a>
                                                <a href="{{ route('category-attendance.statistics', $category->id) }}" 
                                                   class="btn btn-info btn-sm" title="Statistiques">
                                                    <i class="fas fa-chart-bar"></i> Stats
                                                </a>
                                            </div>
                                        </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Aucune catégorie disponible pour le moment.
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>
@push('styles')
<style>
    .table thead th { white-space: nowrap; }
    .btn-group .btn { margin-right: .25rem; }
    .badge { font-size: .85rem; }
    @media (max-width: 768px) {
        .btn-group { flex-wrap: wrap; }
        .btn-group .btn { margin-bottom: .25rem; }
    }
}
</style>
@endpush
@endsection
