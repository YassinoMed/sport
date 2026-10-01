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
                                    <span class="label-ar">الرئيسية</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('category-attendance.index') }}" class="breadcrumb-link">
                                    <span class="label-fr">Présence</span>
                                    <span class="label-ar">الحضور</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <span class="label-fr">{{ $category->name }}</span>
                                <span class="label-ar">{{ $category->name }}</span>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="page-title">
                        <i class="fas fa-user-check"></i>
                        <span class="label-fr">Présence par Catégorie</span>
                        <span class="label-ar">الحضور حسب الفئة</span>
                    </h1>
                    <p class="page-subtitle">
                        <span class="label-fr">Marquer les présences - {{ $category->name }}</span>
                        <span class="label-ar">تسجيل الحضور - {{ $category->name }}</span>
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('category-attendance.index') }}" class="btn-ghost">
                        <i class="fas fa-arrow-left"></i>
                        <span class="label-fr">Retour</span>
                        <span class="label-ar">عودة</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="modern-card mb-4">
                    <div class="card-header-modern card-header-primary">
                        <div class="header-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div>
                            <h3 class="header-title">
                                <span class="label-fr">Marquer les présences - {{ $category->name }}</span>
                                <span class="label-ar">تسجيل الحضور - {{ $category->name }}</span>
                            </h3>
                            <p class="header-subtitle mb-0">
                                <span class="label-fr">{{ $users->count() }} athlètes dans cette catégorie</span>
                                <span class="label-ar">{{ $users->count() }} رياضي في هذه الفئة</span>
                            </p>
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

                        <form action="{{ route('category-attendance.store', $category->id) }}" method="POST" id="attendanceForm">
                            @csrf

                            <div class="row mb-4 align-items-end">
                                <div class="col-md-8">
                                    <div class="stat-card stat-primary">
                                        <div class="stat-icon">
                                            <i class="fas fa-tags"></i>
                                        </div>
                                        <div class="stat-content">
                                            <span class="stat-label">
                                                <span class="label-fr">Catégorie</span>
                                                <span class="label-ar">الفئة</span>
                                            </span>
                                            <span class="stat-value" style="font-size: 1.25rem;">{{ $category->name }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group-modern">
                                        <label for="attendance_date" class="form-label-modern">
                                            <i class="fas fa-calendar-alt"></i>
                                            <span class="label-fr">Date de présence</span>
                                            <span class="label-ar">تاريخ الحضور</span>
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" 
                                               class="form-control-modern @error('attendance_date') is-invalid @enderror" 
                                               id="attendance_date" 
                                               name="attendance_date" 
                                               value="{{ old('attendance_date', date('Y-m-d')) }}" 
                                               required>
                                    </div>
                                </div>
                            </div>

                            @if($users->isEmpty())
                                <div class="empty-state">
                                    <i class="fas fa-users fa-4x mb-3 opacity-20"></i>
                                    <h4>
                                        <span class="label-fr">Aucun athlète dans cette catégorie</span>
                                        <span class="label-ar">لا يوجد رياضيين في هذه الفئة</span>
                                    </h4>
                                    <p class="mb-4">
                                        <span class="label-fr">Il n'y a aucun athlète assigné à cette catégorie pour le moment.</span>
                                        <span class="label-ar">لا يوجد رياضيين معينين لهذه الفئة حالياً.</span>
                                    </p>
                                </div>
                            @else
                                <div class="modern-card bg-light border mb-4">
                                    <div class="card-body p-3">
                                        <div class="row align-items-center">
                                            <div class="col-xl-7 col-lg-12 mb-3 mb-xl-0">
                                                <div class="d-flex flex-wrap gap-2">
                                                    <div class="btn-group mr-2">
                                                        <button type="button" class="btn btn-sm btn-success-modern" onclick="markAll('present')">
                                                            <i class="fas fa-check"></i>
                                                            <span class="label-fr">Tous présents</span>
                                                            <span class="label-ar">الكل حاضر</span>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-danger-modern" onclick="markAll('absent')">
                                                            <i class="fas fa-times"></i>
                                                            <span class="label-fr">Tous absents</span>
                                                            <span class="label-ar">الكل غائب</span>
                                                        </button>
                                                    </div>
                                                    <div class="btn-group mr-2">
                                                        <button type="button" class="btn btn-sm btn-ghost" onclick="autoFromLastStatus()">
                                                            <i class="fas fa-history"></i>
                                                            <span class="label-fr">Statut précédent</span>
                                                            <span class="label-ar">الحالة السابقة</span>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-primary-modern" data-toggle="modal" data-target="#qrScannerModal">
                                                            <i class="fas fa-qrcode"></i>
                                                            <span class="label-fr">Scanner QR</span>
                                                            <span class="label-ar">مسح QR</span>
                                                        </button>
                                                    </div>
                                                    <select id="bulkStatus" class="form-select-modern w-auto py-1" style="height: 38px; font-size: 0.875rem;">
                                                        <option value="">
                                                            <span class="label-fr">Statut global...</span>
                                                            <span class="label-ar">الحالة العامة...</span>
                                                        </option>
                                                        <option value="present">
                                                            <span class="label-fr">Présent</span>
                                                            <span class="label-ar">حاضر</span>
                                                        </option>
                                                        <option value="absent">
                                                            <span class="label-fr">Absent</span>
                                                            <span class="label-ar">غائب</span>
                                                        </option>
                                                        <option value="excused">
                                                            <span class="label-fr">Excusé</span>
                                                            <span class="label-ar">معذور</span>
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xl-5 col-lg-12">
                                                <input type="text" id="participantSearch" class="form-control-modern py-1" placeholder="🔍 Chercher... / بحث..." style="height: 38px; font-size: 0.875rem;">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="modern-table">
                                        <thead>
                                            <tr>
                                                <th width="35%">
                                                    <span class="label-fr">Athlète</span>
                                                    <span class="label-ar">الرياضي</span>
                                                </th>
                                                <th width="20%" class="d-none d-md-table-cell">
                                                    <span class="label-fr">Assiduité (5 derniers)</span>
                                                    <span class="label-ar">المواظبة (آخر 5)</span>
                                                </th>
                                                <th width="25%">
                                                    <span class="label-fr">Statut présence</span>
                                                    <span class="label-ar">حالة الحضور</span>
                                                </th>
                                                <th width="20%" class="d-none d-md-table-cell">
                                                    <span class="label-fr">Notes</span>
                                                    <span class="label-ar">ملاحظات</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($users as $index => $u)
                                                <tr data-user-id="{{ $u->id }}" 
                                                    data-last-status="{{ optional($u->categoryAttendances->first())->status ?? '' }}" 
                                                    data-name="{{ $u->name }}" 
                                                    data-email="{{ $u->email }}">
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm avatar-placeholder mr-3">
                                                                {{ strtoupper(substr($u->name, 0, 1)) }}
                                                            </div>
                                                            <div>
                                                                <div class="font-weight-bold text-dark">{{ $u->name }}</div>
                                                                @if(isset($u->payments) && $u->payments->isEmpty())
                                                                    <span class="badge-modern badge-danger" style="font-size: 0.65rem; padding: 1px 5px;">
                                                                        <i class="fas fa-exclamation-triangle"></i>
                                                                        <span class="label-fr">Non payé</span>
                                                                        <span class="label-ar">غير مدفوع</span>
                                                                    </span>
                                                                @elseif(isset($u->payments))
                                                                    <span class="badge-modern badge-success" style="font-size: 0.65rem; padding: 1px 5px;">
                                                                        <i class="fas fa-check-circle"></i>
                                                                        <span class="label-fr">Payé</span>
                                                                        <span class="label-ar">مدفوع</span>
                                                                    </span>
                                                                @endif
                                                                <div class="text-muted small d-none d-lg-block">{{ $u->email }}</div>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="attendances[{{ $index }}][user_id]" value="{{ $u->id }}">
                                                    </td>
                                                    <td class="d-none d-md-table-cell">
                                                        <div class="streak-container" title="Historique des 5 dernières sessions">
                                                            @foreach($u->categoryAttendances as $att)
                                                                <div class="streak-dot {{ $att->status }}" data-toggle="tooltip" title="{{ \Carbon\Carbon::parse($att->attendance_date)->format('d/m/Y') }}: {{ __($att->status) }}"></div>
                                                            @endforeach
                                                            @for($i = count($u->categoryAttendances); $i < 5; $i++)
                                                                <div class="streak-dot" title="N/A"></div>
                                                            @endfor
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <select class="form-select-modern status-select" name="attendances[{{ $index }}][status]" required style="height: 38px; font-size: 0.875rem;">
                                                            <option value="present" selected>
                                                                ✅ <span class="label-fr">Présent</span><span class="label-ar">حاضر</span>
                                                            </option>
                                                            <option value="absent">
                                                                ❌ <span class="label-fr">Absent</span><span class="label-ar">غائب</span>
                                                            </option>
                                                            <option value="excused">
                                                                ⚠️ <span class="label-fr">Excusé</span><span class="label-ar">معذور</span>
                                                            </option>
                                                        </select>
                                                    </td>
                                                    <td class="d-none d-md-table-cell">
                                                        <input type="text" class="form-control-modern" 
                                                               name="attendances[{{ $index }}][notes]" 
                                                               placeholder="Note... / ملاحظة..."
                                                               style="height: 38px; font-size: 0.875rem;">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="modern-card bg-light">
                                            <div class="card-body p-3">
                                                <div class="row text-center">
                                                    <div class="col-4 border-right">
                                                        <div class="text-success font-weight-bold">
                                                            <i class="fas fa-check-circle mr-1"></i>
                                                            <span class="label-fr">Présents:</span>
                                                            <span class="label-ar">الحاضرون:</span>
                                                            <span id="present-count">0</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-4 border-right">
                                                        <div class="text-danger font-weight-bold">
                                                            <i class="fas fa-times-circle mr-1"></i>
                                                            <span class="label-fr">Absents:</span>
                                                            <span class="label-ar">الغائبون:</span>
                                                            <span id="absent-count">0</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="text-warning font-weight-bold">
                                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                                            <span class="label-fr">Excusés:</span>
                                                            <span class="label-ar">المعذورون:</span>
                                                            <span id="excused-count">0</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit" class="btn-primary-modern btn-lg px-5">
                                        <i class="fas fa-save mr-2"></i>
                                        <span class="label-fr">Enregistrer les présences</span>
                                        <span class="label-ar">حفظ الحضور</span>
                                    </button>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- QR Scanner Modal -->
<div class="modal fade" id="qrScannerModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="fas fa-qrcode mr-2"></i>
                    <span class="label-fr">Scanner QR Code</span>
                    <span class="label-ar">مسح رمز QR</span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div id="qr-reader" class="rounded overflow-hidden border"></div>
                <div id="qr-reader-results" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
$(document).ready(function() {
    function updateCounts() {
        let presentCount = $('.status-select').filter(function() { return $(this).val() === 'present'; }).length;
        let absentCount = $('.status-select').filter(function() { return $(this).val() === 'absent'; }).length;
        let excusedCount = $('.status-select').filter(function() { return $(this).val() === 'excused'; }).length;
        
        $('#present-count').text(presentCount);
        $('#absent-count').text(absentCount);
        $('#excused-count').text(excusedCount);
    }

    function updateRowStyle($select) {
        var status = $select.val();
        var $row = $select.closest('tr');
        $row.removeClass('table-success table-danger table-warning');
        if (status === 'present') $row.addClass('table-success');
        else if (status === 'absent') $row.addClass('table-danger');
        else if (status === 'excused') $row.addClass('table-warning');
    }

    $('.status-select').on('change', function() {
        var $select = $(this);
        updateCounts();
        updateRowStyle($select);

        // Auto-save via AJAX
        var userId = $select.closest('tr').find('input[name*="[user_id]"]').val();
        var status = $select.val();
        var attendanceDate = $('input[name="attendance_date"]').val();
        var categoryId = "{{ $category->id }}";

        if (attendanceDate && categoryId && userId && status) {
            $select.addClass('loading-status');
            $.ajax({
                url: "{{ route('category-attendance.ajaxUpdate') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    user_id: userId,
                    status: status,
                    attendance_date: attendanceDate,
                    category_id: categoryId
                },
                success: function(response) {
                    $select.removeClass('loading-status').addClass('save-success');
                    setTimeout(() => $select.removeClass('save-success'), 2000);
                },
                error: function() {
                    $select.removeClass('loading-status').addClass('save-error');
                }
            });
        }
    }).each(function() {
        updateRowStyle($(this));
    });

    window.markAll = function(status) {
        $('.status-select').val(status).trigger('change');
    };

    window.autoFromLastStatus = function() {
        $('tbody tr').each(function() {
            var last = $(this).data('last-status');
            if (last) {
                $(this).find('.status-select').val(String(last)).trigger('change');
            }
        });
    };

    // Filtrage
    $('#participantSearch').on('input', function() {
        var query = $(this).val().toLowerCase();
        $('tbody tr').each(function() {
            var name = (String($(this).data('name')) || '').toLowerCase();
            var email = (String($(this).data('email')) || '').toLowerCase();
            if (name.indexOf(query) !== -1 || email.indexOf(query) !== -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    $('#bulkStatus').on('change', function() {
        var status = $(this).val();
        if (status) markAll(status);
    });

    // QR Scanner
    let html5QrcodeScanner = null;
    $('#qrScannerModal').on('shown.bs.modal', function () {
        const config = { fps: 10, qrbox: { width: 250, height: 250 } };
        if (!html5QrcodeScanner) {
            html5QrcodeScanner = new Html5Qrcode("qr-reader");
        }
        html5QrcodeScanner.start(
            { facingMode: "environment" }, 
            config,
            (decodedText) => {
                handleScannedId(decodedText);
            },
            (errorMessage) => {}
        ).catch((err) => {
            console.error(`Unable to start scanning, error: ${err}`);
        });
    }).on('hidden.bs.modal', function () {
        if (html5QrcodeScanner) {
            html5QrcodeScanner.stop().catch(err => console.error(err));
        }
    });

    function handleScannedId(userId) {
        userId = userId.trim();
        var found = false;
        $('input[name*="[user_id]"]').each(function() {
            if ($(this).val() == userId) {
                var $select = $(this).closest('tr').find('.status-select');
                $select.val('present').trigger('change');
                var name = $(this).closest('tr').data('name');
                found = true;
                
                // Play success sound
                try {
                    var audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');
                    audio.play();
                } catch(e) {}

                Swal.fire({
                    icon: 'success',
                    title: 'Présence marquée',
                    text: name,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });

                setTimeout(() => { $('#qrScannerModal').modal('hide'); }, 1500);
                return false;
            }
        });
        if (!found) {
            Swal.fire({
                icon: 'error',
                title: 'Utilisateur non trouvé',
                text: `L'ID #${userId} n'est pas dans cette liste.`,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        }
    }

    updateCounts();
});
</script>

<style>
.table-success { background-color: rgba(16, 185, 129, 0.1) !important; }
.table-danger { background-color: rgba(239, 68, 68, 0.1) !important; }
.table-warning { background-color: rgba(245, 158, 11, 0.1) !important; }
</style>
@endpush
