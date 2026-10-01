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
                    <li class="breadcrumb-item">
                        <a href="{{ route('group.show', $group->id) }}" class="breadcrumb-link">
                            {{ $group->name }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active">
                        <span class="label-fr">Modifier</span>
                        <span class="label-ar">تعديل</span>
                    </li>
                </ol>
            </nav>
            <h1 class="page-title">
                <i class="fas fa-edit text-primary me-2"></i>
                <span class="label-fr">Modifier le groupe : {{ $group->name }}</span>
                <span class="label-ar" dir="rtl">تعديل المجموعة : {{ $group->name }}</span>
            </h1>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            @endif

            <div class="modern-card">
                <div class="card-header-modern">
                    <h3 class="card-title">
                        <i class="fas fa-edit me-2"></i>
                        <span class="label-fr">Modifier le groupe</span>
                        <span class="label-ar">تعديل المجموعة</span>
                    </h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('group.update', $group->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">
                                        <span class="label-fr">Nom du groupe</span>
                                        <span class="label-ar">اسم المجموعة</span>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name" class="form-control-modern" value="{{ old('name', $group->name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">
                                        <span class="label-fr">Description</span>
                                        <span class="label-ar">الوصف</span>
                                    </label>
                                    <textarea name="description" class="form-control-modern" rows="3">{{ old('description', $group->description) }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">
                                        <span class="label-fr">Membres du groupe</span>
                                        <span class="label-ar">أعضاء المجموعة</span>
                                    </label>
                                    <p class="text-muted small mb-2">
                                        <i class="fas fa-info-circle me-1"></i>
                                        <span class="label-fr">Un athlète peut appartenir à plusieurs groupes. Cochez/décochez pour associer/dissocier.</span>
                                        <span class="label-ar">يمكن لكل رياضي الانتماء إلى عدة مجموعات. حدد أو أزل التحديد للربط أو الفصل.</span>
                                    </p>
                                    {{-- Search filter --}}
                                    <div class="mb-2">
                                        <input type="text" id="searchMembers" class="form-control form-control-sm" placeholder="🔍 Rechercher un athlète...">
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <small class="text-muted">
                                            <span id="selectedCount">0</span>
                                            <span class="label-fr">sélectionné(s)</span>
                                            <span class="label-ar">محدد</span>
                                        </small>
                                        <div>
                                            <button type="button" class="btn btn-outline-primary btn-xs" id="selectAll">
                                                <span class="label-fr">Tout sélectionner</span>
                                                <span class="label-ar">تحديد الكل</span>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-xs" id="deselectAll">
                                                <span class="label-fr">Tout désélectionner</span>
                                                <span class="label-ar">إلغاء تحديد الكل</span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="members-list-container border rounded p-3" style="max-height: 350px; overflow-y: auto; background: #f8fafc;">
                                        @forelse($users as $u)
                                            <div class="form-check-modern mb-2 member-item">
                                                <input class="form-check-input member-checkbox" type="checkbox" name="user_ids[]" id="user_{{ $u->id }}" value="{{ $u->id }}" {{ in_array($u->id, old('user_ids', $memberIds)) ? 'checked' : '' }}>
                                                <label class="form-check-label ms-2" for="user_{{ $u->id }}">
                                                    {{ $u->name }}
                                                </label>
                                            </div>
                                        @empty
                                            <div class="text-muted text-center py-3">
                                                <span class="label-fr">Aucun athlète disponible</span>
                                                <span class="label-ar">لا يوجد رياضيون متاحون</span>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions-modern mt-4">
                            <button type="submit" class="btn btn-primary-modern">
                                <i class="fas fa-save me-2"></i>
                                <span class="label-fr">Enregistrer les modifications</span>
                                <span class="label-ar">حفظ التعديلات</span>
                            </button>
                            <a href="{{ route('group.show', $group->id) }}" class="btn btn-secondary-modern">
                                <i class="fas fa-times me-2"></i>
                                <span class="label-fr">Annuler</span>
                                <span class="label-ar">إلغاء</span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchMembers');
    const checkboxes = document.querySelectorAll('.member-checkbox');
    const memberItems = document.querySelectorAll('.member-item');
    const selectedCountEl = document.getElementById('selectedCount');

    function updateCount() {
        selectedCountEl.textContent = document.querySelectorAll('.member-checkbox:checked').length;
    }

    searchInput.addEventListener('input', function() {
        const term = this.value.toLowerCase();
        memberItems.forEach(function(item) {
            const label = item.querySelector('label').textContent.toLowerCase();
            item.style.display = label.includes(term) ? '' : 'none';
        });
    });

    checkboxes.forEach(function(cb) {
        cb.addEventListener('change', updateCount);
    });

    document.getElementById('selectAll').addEventListener('click', function() {
        memberItems.forEach(function(item) {
            if (item.style.display !== 'none') {
                item.querySelector('.member-checkbox').checked = true;
            }
        });
        updateCount();
    });

    document.getElementById('deselectAll').addEventListener('click', function() {
        checkboxes.forEach(function(cb) { cb.checked = false; });
        updateCount();
    });

    updateCount();
});
</script>
@endsection
