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
                    <li class="breadcrumb-item active">
                        <span class="label-fr">Groupes</span>
                        <span class="label-ar">المجموعات</span>
                    </li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h1 class="page-title">
                    <i class="fas fa-layer-group text-primary me-2"></i>
                    <span class="label-fr">Gestion des groupes</span>
                    <span class="label-ar" dir="rtl">إدارة المجموعات</span>
                </h1>
                <div class="d-flex gap-2 flex-wrap">
                    <form action="{{ route('group.assignAllToA') }}" method="POST" class="d-inline" onsubmit="return confirm('Associer tous les athlètes au Groupe A ?')">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-users-cog me-1"></i>
                            <span class="label-fr">Associer tous à Groupe A</span>
                            <span class="label-ar">ربط الجميع بالمجموعة أ</span>
                        </button>
                    </form>
                    <a href="{{ route('group.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>
                        <span class="label-fr">Créer un groupe</span>
                        <span class="label-ar">إنشاء مجموعة</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            @endif
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            @endif

            <div class="modern-card">
                <div class="card-header-modern">
                    <h3 class="card-title">
                        <i class="fas fa-list me-2"></i>
                        <span class="label-fr">Liste des groupes</span>
                        <span class="label-ar">قائمة المجموعات</span>
                    </h3>
                    <span class="badge badge-primary">{{ $groups->total() }} <span class="label-fr">groupe(s)</span><span class="label-ar">مجموعة</span></span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 40%;">
                                        <span class="label-fr">Nom du groupe</span>
                                        <span class="label-ar">اسم المجموعة</span>
                                    </th>
                                    <th style="width: 15%;">
                                        <span class="label-fr">Description</span>
                                        <span class="label-ar">الوصف</span>
                                    </th>
                                    <th style="width: 15%;" class="text-center">
                                        <span class="label-fr">Membres</span>
                                        <span class="label-ar">الأعضاء</span>
                                    </th>
                                    <th style="width: 30%;" class="text-center">
                                        <span class="label-fr">Actions</span>
                                        <span class="label-ar">الإجراءات</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($groups as $g)
                                    <tr>
                                        <td>
                                            <a href="{{ route('group.show', $g->id) }}" class="font-weight-bold text-primary">
                                                <i class="fas fa-users me-1"></i>{{ $g->name }}
                                            </a>
                                        </td>
                                        <td class="text-muted small">{{ Str::limit($g->description, 50) ?: '—' }}</td>
                                        <td class="text-center">
                                            <span class="badge badge-info" style="font-size: 0.85em; padding: 5px 12px;">
                                                <i class="fas fa-user me-1"></i>{{ $g->users_count }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('group.show', $g->id) }}" class="btn btn-sm btn-outline-info mr-1" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('group.edit', $g->id) }}" class="btn btn-sm btn-outline-primary mr-1" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('group.destroy', $g->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce groupe ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">
                                            <i class="fas fa-inbox fa-2x d-block mb-2"></i>
                                            <span class="label-fr">Aucun groupe créé</span>
                                            <span class="label-ar">لا توجد مجموعات</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($groups->hasPages())
                    <div class="card-footer d-flex justify-content-center">
                        {{ $groups->links() }}
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>
@endsection
