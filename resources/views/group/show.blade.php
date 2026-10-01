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
                        <a href="{{ route('group.index') }}" class="breadcrumb-link">
                            <span class="label-fr">Groupes</span>
                            <span class="label-ar">المجموعات</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">
                        {{ $group->name }}
                    </li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h1 class="page-title">
                    <i class="fas fa-users text-primary me-2"></i>
                    {{ $group->name }}
                </h1>
                <div class="d-flex gap-2">
                    <a href="{{ route('group.edit', $group->id) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-edit me-1"></i>
                        <span class="label-fr">Modifier</span>
                        <span class="label-ar">تعديل</span>
                    </a>
                    <a href="{{ route('group.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>
                        <span class="label-fr">Retour</span>
                        <span class="label-ar">رجوع</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            {{-- Flash messages --}}
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
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-times-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            @endif

            <div class="row">
                {{-- Group Info Card --}}
                <div class="col-md-4">
                    <div class="modern-card mb-3">
                        <div class="card-header-modern">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle me-2"></i>
                                <span class="label-fr">Informations</span>
                                <span class="label-ar">معلومات</span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>
                                    <span class="label-fr">Nom :</span>
                                    <span class="label-ar">الاسم :</span>
                                </strong>
                                <p class="mb-1">{{ $group->name }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>
                                    <span class="label-fr">Description :</span>
                                    <span class="label-ar">الوصف :</span>
                                </strong>
                                <p class="mb-1 text-muted">{{ $group->description ?: '—' }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>
                                    <span class="label-fr">Nombre de membres :</span>
                                    <span class="label-ar">عدد الأعضاء :</span>
                                </strong>
                                <p class="mb-0">
                                    <span class="badge badge-info" style="font-size: 1em; padding: 6px 14px;">
                                        <i class="fas fa-user me-1"></i>{{ $group->users->count() }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <strong>
                                    <span class="label-fr">Créé le :</span>
                                    <span class="label-ar">تاريخ الإنشاء :</span>
                                </strong>
                                <p class="mb-0 text-muted">{{ $group->created_at ? $group->created_at->format('d/m/Y H:i') : '—' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Add Member Card --}}
                    @if($availableUsers->count() > 0)
                    <div class="modern-card">
                        <div class="card-header-modern">
                            <h3 class="card-title">
                                <i class="fas fa-user-plus me-2"></i>
                                <span class="label-fr">Ajouter un membre</span>
                                <span class="label-ar">إضافة عضو</span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('group.addMember', $group->id) }}">
                                @csrf
                                <div class="form-group-modern mb-3">
                                    <label class="form-label-modern">
                                        <span class="label-fr">Sélectionner un athlète</span>
                                        <span class="label-ar">اختر رياضي</span>
                                    </label>
                                    <select name="user_id" class="form-control-modern" required id="addMemberSelect">
                                        <option value="">
                                            -- Choisir --
                                        </option>
                                        @foreach($availableUsers as $u)
                                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary-modern btn-sm btn-block">
                                    <i class="fas fa-plus me-1"></i>
                                    <span class="label-fr">Ajouter au groupe</span>
                                    <span class="label-ar">إضافة إلى المجموعة</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Members List Card --}}
                <div class="col-md-8">
                    <div class="modern-card">
                        <div class="card-header-modern">
                            <h3 class="card-title">
                                <i class="fas fa-users me-2"></i>
                                <span class="label-fr">Membres du groupe</span>
                                <span class="label-ar">أعضاء المجموعة</span>
                            </h3>
                            <span class="badge badge-primary">{{ $group->users->count() }}</span>
                        </div>
                        <div class="card-body p-0">
                            {{-- Search --}}
                            <div class="p-3 pb-0">
                                <input type="text" id="searchGroupMembers" class="form-control form-control-sm" placeholder="🔍 Rechercher un membre...">
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-sm mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="width: 5%;">#</th>
                                            <th style="width: 45%;">
                                                <span class="label-fr">Nom de l'athlète</span>
                                                <span class="label-ar">اسم الرياضي</span>
                                            </th>
                                            <th style="width: 30%;">
                                                <span class="label-fr">Autres groupes</span>
                                                <span class="label-ar">مجموعات أخرى</span>
                                            </th>
                                            <th style="width: 20%;" class="text-center">
                                                <span class="label-fr">Actions</span>
                                                <span class="label-ar">الإجراءات</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="membersTableBody">
                                        @forelse($group->users as $index => $member)
                                            <tr class="member-row">
                                                <td class="text-muted">{{ $index + 1 }}</td>
                                                <td>
                                                    <a href="{{ route('allUsers.show', $member->id) }}" class="font-weight-bold">
                                                        <i class="fas fa-user-circle me-1 text-primary"></i>
                                                        {{ $member->name }}
                                                    </a>
                                                </td>
                                                <td>
                                                    @php
                                                        $otherGroups = $member->groups->where('id', '!=', $group->id);
                                                    @endphp
                                                    @if($otherGroups->count() > 0)
                                                        @foreach($otherGroups as $og)
                                                            <span class="badge badge-secondary mr-1">{{ $og->name }}</span>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted small">—</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <form action="{{ route('group.removeMember', [$group->id, $member->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Retirer {{ addslashes($member->name) }} de ce groupe ?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Retirer du groupe">
                                                            <i class="fas fa-user-minus me-1"></i>
                                                            <span class="label-fr">Retirer</span>
                                                            <span class="label-ar">إزالة</span>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4 text-muted">
                                                    <i class="fas fa-user-slash fa-2x d-block mb-2"></i>
                                                    <span class="label-fr">Aucun membre dans ce groupe</span>
                                                    <span class="label-ar">لا يوجد أعضاء في هذه المجموعة</span>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchGroupMembers');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const term = this.value.toLowerCase();
            document.querySelectorAll('.member-row').forEach(function(row) {
                const name = row.textContent.toLowerCase();
                row.style.display = name.includes(term) ? '' : 'none';
            });
        });
    }
});
</script>
@endsection
