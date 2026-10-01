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
                            <li class="breadcrumb-item active">
                                <span class="label-fr">Présence Sessions</span>
                                <span class="label-ar">حضور الحصص</span>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="page-title">
                        <i class="fas fa-user-check"></i>
                        <span class="label-fr">Présence Sessions d'entraînement</span>
                        <span class="label-ar">حضور حصص التدريب</span>
                    </h1>
                    <p class="page-subtitle">
                        <span class="label-fr">Marquer les présences par session</span>
                        <span class="label-ar">تسجيل الحضور حسب الحصة</span>
                    </p>
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
                                <span class="label-fr">Marquer les présences</span>
                                <span class="label-ar">تسجيل الحضور</span>
                            </h3>
                            <p class="header-subtitle mb-0">
                                <span class="label-fr">Sélectionnez une session et une date</span>
                                <span class="label-ar">اختر حصة وتاريخاً</span>
                            </p>
                        </div>
                    </div>

                    <div class="card-body-modern">
                        @if(session('success'))
                            <div class="alert-modern alert-success mb-4">
                                <i class="fas fa-check-circle mt-1"></i>
                                {{ session('success') }}
                            </div>
                        @endif

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

                        <form method="POST" action="{{ route('attendance.store') }}" id="attendanceForm">
                            @csrf
                            <div class="row mb-4 align-items-end">
                                <div class="col-md-4">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            <i class="fas fa-calendar-alt"></i> 
                                            <span class="label-fr">Date</span>
                                            <span class="label-ar">التاريخ</span>
                                            *
                                        </label>
                                        <input type="date" name="attendance_at" class="form-control-modern" value="{{ old('attendance_at', $defaultDate) }}" required />
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            <i class="fas fa-clock"></i> 
                                            <span class="label-fr">Cours</span>
                                            <span class="label-ar">الدورات</span>
                                            *
                                        </label>
                                        <select name="training_session_id" class="form-select-modern" required>
                                            <option value="">
                                                <span class="label-fr">Sélectionner…</span>
                                                <span class="label-ar">اختر…</span>
                                            </option>
                                            @foreach($sessions as $s)
                                                <option value="{{ $s->id }}" {{ old('training_session_id') == $s->id ? 'selected' : '' }}>
                                                    {{ $s->name }} — {{ $s->day }} {{ $s->starts_at }}-{{ $s->finishes_at }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

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
                                                    <button type="button" class="btn btn-sm btn-primary-modern" data-toggle="modal" data-target="#qrScannerModal">
                                                        <i class="fas fa-qrcode"></i> 
                                                        <span class="label-fr">Scanner QR</span>
                                                        <span class="label-ar">مسح QR</span>
                                                    </button>
                                                </div>
                                                <select id="bulkStatus" class="form-select-modern w-auto py-1" style="height: 38px; font-size: 0.875rem;">
                                                    <option value="">
                                                        <span class="label-fr">Statut global…</span>
                                                        <span class="label-ar">الحالة العامة…</span>
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
                                            <input type="text" id="participantSearch" class="form-control-modern py-1" placeholder="🔍 Chercher un athlète... / ابحث عن رياضي..." style="height: 38px; font-size: 0.875rem;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="modern-table">
                                    <thead>
                                        <tr>
                                            <th width="35%">
                                                <span class="label-fr">Utilisateur</span>
                                                <span class="label-ar">المستخدم</span>
                                            </th>
                                            <th width="20%" class="d-none d-md-table-cell">
                                                <span class="label-fr">Assiduité (5 derniers)</span>
                                                <span class="label-ar">المواظبة (آخر 5)</span>
                                            </th>
                                            <th width="25%" class="d-none d-md-table-cell">
                                                <span class="label-fr">Email</span>
                                                <span class="label-ar">البريد الإلكتروني</span>
                                            </th>
                                            <th width="20%">
                                                <span class="label-fr">Statut</span>
                                                <span class="label-ar">الحالة</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($users as $idx => $u)
                                        <tr data-name="{{ $u->name }}" data-email="{{ $u->email }}">
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
                                                        <input type="hidden" name="attendances[{{ $idx }}][user_id]" value="{{ $u->id }}" />
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="d-none d-md-table-cell">
                                                <div class="streak-container" title="Historique des 5 dernières sessions">
                                                    @foreach($u->attendances as $att)
                                                        <div class="streak-dot {{ $att->status }}" data-toggle="tooltip" title="{{ \Carbon\Carbon::parse($att->attendance_at)->format('d/m/Y') }}: {{ __($att->status) }}"></div>
                                                    @endforeach
                                                    @for($i = count($u->attendances); $i < 5; $i++)
                                                        <div class="streak-dot" title="N/A"></div>
                                                    @endfor
                                                </div>
                                            </td>
                                            <td class="d-none d-md-table-cell text-muted small">{{ $u->email }}</td>
                                            <td>
                                                <select name="attendances[{{ $idx }}][status]" class="form-select-modern status-select" style="height: 38px; font-size: 0.875rem;">
                                                    <option value="present">
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
                                                <div class="col-4">
                                                    <div class="text-success font-weight-bold">
                                                        <i class="fas fa-check-circle mr-1"></i> 
                                                        <span class="label-fr">Présents</span><span class="label-ar">الحاضرون</span>: 
                                                        <span id="present-count">0</span>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="text-danger font-weight-bold">
                                                        <i class="fas fa-times-circle mr-1"></i> 
                                                        <span class="label-fr">Absents</span><span class="label-ar">الغائبون</span>: 
                                                        <span id="absent-count">0</span>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="text-warning font-weight-bold">
                                                        <i class="fas fa-exclamation-circle mr-1"></i> 
                                                        <span class="label-fr">Excusés</span><span class="label-ar">المعذورون</span>: 
                                                        <span id="excused-count">0</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn-primary-modern btn-lg">
                                    <i class="fas fa-save"></i> 
                                    <span class="label-fr">Enregistrer</span>
                                    <span class="label-ar">حفظ</span>
                                </button>
                            </div>
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
                <h5 class="modal-title">
                    <i class="fas fa-qrcode mr-2"></i> 
                    <span class="label-fr">Scanner QR Code</span>
                    <span class="label-ar">مسح رمز QR</span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="qr-reader" style="width: 100%" class="rounded overflow-hidden shadow-sm"></div>
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

    // Changement de couleur de ligne selon le statut
    function updateRowStyle($select) {
        var status = $select.val();
        var $row = $select.closest('tr');
        $row.removeClass('table-success table-danger table-warning');
        if (status === 'present') $row.addClass('table-success');
        else if (status === 'absent') $row.addClass('table-danger');
        else if (status === 'excused') $row.addClass('table-warning');
    }

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

    // Actions rapides
    window.markAll = function(status) {
        $('.status-select').val(status).trigger('change');
    };

    $('#bulkStatus').on('change', function() {
        var status = $(this).val();
        if (status) {
            markAll(status);
        }
    });

    $('.status-select').each(function() {
        updateRowStyle($(this));
    }).on('change', function() {
        var $select = $(this);
        updateRowStyle($select);
        updateCounts();
        
        // Auto-save via AJAX
        var userId = $select.closest('tr').find('input[name*="[user_id]"]').val();
        var status = $select.val();
        var attendanceAt = $('input[name="attendance_at"]').val();
        var trainingSessionId = $('select[name="training_session_id"]').val();

        if (attendanceAt && trainingSessionId && userId && status) {
            $select.addClass('loading-status');
            $.ajax({
                url: "{{ route('attendance.ajaxUpdate') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    user_id: userId,
                    status: status,
                    attendance_at: attendanceAt,
                    training_session_id: trainingSessionId
                },
                success: function(response) {
                    $select.removeClass('loading-status').addClass('save-success');
                    setTimeout(() => $select.removeClass('save-success'), 2000);
                },
                error: function() {
                    $select.removeClass('loading-status').addClass('save-error');
                    // alert('Erreur lors de la sauvegarde automatique.');
                }
            });
        }
    });

    // QR Scanner logic
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

                const isFr = $('.label-fr').first().is(':visible');
                Swal.fire({
                    icon: 'success',
                    title: isFr ? 'Présence marquée' : 'تم تسجيل الحضور',
                    text: name,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });

                setTimeout(() => {
                    $('#qrScannerModal').modal('hide');
                }, 1500);
                
                return false;
            }
        });
        
        if (!found) {
            const isFr = $('.label-fr').first().is(':visible');
            Swal.fire({
                icon: 'error',
                title: isFr ? 'Utilisateur non trouvé' : 'المستخدم غير موجود',
                text: isFr ? `L'ID #${userId} n'est pas dans cette liste.` : `المعرف #${userId} ليس في هذه القائمة.`,
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

