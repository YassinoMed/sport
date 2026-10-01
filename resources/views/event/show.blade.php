@extends('layouts.user-layout')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $event->name }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">{{ __('messages.home') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('events.index') }}">{{ __('messages.events') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('messages.details') }}</li>
                    </ol>   
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('messages.event_info') }}</h3>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="row">
                    <div class="col-md-8">
                        <p><strong>{{ __('messages.dates') }}:</strong>
                            {{ $event->start_at->format('d/m/Y') }} {{ __('messages.at_time') }} {{ $event->start_at->format('H:i') }}
                            @if($event->end_at) — {{ $event->end_at->format('d/m/Y') }} {{ __('messages.at_time') }} {{ $event->end_at->format('H:i') }} @endif
                        </p>
                        <p><strong>{{ __('messages.location') }}:</strong> {{ $event->location ?? '—' }}</p>
                        <p><strong>{{ __('messages.belt_requirement') }}:</strong> {{ $event->belt_requirement ?? '—' }}</p>
                        @unless(auth()->user()->hasRole('coach'))
                        <p><strong>{{ __('messages.fee') }}:</strong> {{ number_format($event->registration_fee / 100, 2) }} {{ __('messages.amount_tnd') }}</p>
                        @endunless
                        <p><strong>{{ __('messages.capacity') }}:</strong> {{ $event->capacity > 0 ? $event->capacity : __('messages.unlimited') }}</p>
                        <p><strong>{{ __('messages.registrants') }}:</strong> {{ $event->registrations_count }}</p>
                        @if(is_array($event->weight_categories) && count($event->weight_categories) > 0)
                            <div class="mt-3">
                                <strong>{{ __('messages.weight_categories') }}:</strong>
                                <table class="table table-sm mt-2">
                                    <thead>
                                        <tr>
                                            <th>{{ __('messages.name') }}</th>
                                            <th>{{ __('messages.min_kg') }}</th>
                                            <th>{{ __('messages.max_kg') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($event->weight_categories as $wc)
                                            <tr>
                                                <td>{{ $wc['name'] }}</td>
                                                <td>{{ $wc['min'] }}</td>
                                                <td>{{ $wc['max'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>



                <div class="mt-3">
                    <a href="{{ route('events.register', $event->id) }}" class="btn btn-success">
                        <i class="fas fa-ticket-alt"></i> {{ __('messages.register') }}
                    </a>
                    <a href="{{ route('events.live', $event->id) }}" class="btn btn-danger">
                        <i class="fas fa-broadcast-tower"></i> Résultats Live
                    </a>
                    <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#qrCodeModal">
                        <i class="fas fa-qrcode"></i> Partager Live
                    </button>
                    @if(auth()->user()->hasAnyRole(['admin', 'cityManager', 'gymManager', 'coach']))
                        <a href="{{ route('events.export-pdf', $event->id) }}" class="btn btn-primary">
                            <i class="fas fa-file-pdf"></i> {{ __('messages.export_pdf') }}
                        </a>
                    @endif
                    <a href="{{ route('events.index') }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
                </div>


            </div>
        </div>

        @php
            $totalCollected = $event->registrations->sum('paid_amount') / 100;
            $participantCount = $event->registrations->count();
            $capacity = $event->capacity;
            $fillingRate = $capacity > 0 ? ($participantCount / $capacity) * 100 : 0;
            
            $paidCount = $event->registrations->filter(fn($r) => $r->paid_amount >= $event->registration_fee)->count();
            $partialCount = $event->registrations->filter(fn($r) => $r->paid_amount > 0 && $r->paid_amount < $event->registration_fee)->count();
            $unpaidCount = $event->registrations->filter(fn($r) => $r->paid_amount == 0)->count();
        @endphp

        <div class="row">
            <div class="col-md-3">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ number_format($totalCollected, 2) }} <small style="font-size: 15px;">TND</small></h3>
                        <p>Total Collecté</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $participantCount }}<small style="font-size: 15px;"> / {{ $capacity }}</small></h3>
                        <p>Taux de Remplissage ({{ number_format($fillingRate, 1) }}%)</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">État des Paiements</h3>
                    </div>
                    <div class="card-body p-2">
                        <div class="d-flex justify-content-around text-center">
                            <div>
                                <h4 class="text-success mb-0">{{ $paidCount }}</h4>
                                <small>Payés</small>
                            </div>
                            <div>
                                <h4 class="text-warning mb-0">{{ $partialCount }}</h4>
                                <small>Partiels</small>
                            </div>
                            <div>
                                <h4 class="text-danger mb-0">{{ $unpaidCount }}</h4>
                                <small>Non payés</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('messages.participants_and_payment') }}</h3>
                <div class="card-tools d-flex align-items-center">
                    <div class="btn-group mr-3">
                        <label class="btn btn-sm btn-outline-secondary active filter-btn" data-filter="all">
                            <input type="radio" name="options" id="option_all" class="btn-check" autocomplete="off" checked> Tous
                        </label>
                        <label class="btn btn-sm btn-outline-success filter-btn" data-filter="paid">
                            <input type="radio" name="options" id="option_paid" class="btn-check" autocomplete="off"> Payés
                        </label>
                        <label class="btn btn-sm btn-outline-warning filter-btn" data-filter="partial">
                            <input type="radio" name="options" id="option_partial" class="btn-check" autocomplete="off"> Partiels
                        </label>
                        <label class="btn btn-sm btn-outline-danger filter-btn" data-filter="unpaid">
                            <input type="radio" name="options" id="option_unpaid" class="btn-check" autocomplete="off"> Non payés
                        </label>
                    </div>
                    <div class="input-group input-group-sm" style="width: 200px;">
                        <input type="text" id="participantSearch" class="form-control float-right" placeholder="{{ __('messages.search_athlete_placeholder') }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="participantsTable">
                    <thead>
                        <tr>
                            <th>{{ __('messages.athlete') }}</th>
                            @unless(auth()->user()->hasRole('coach'))
                            <th>{{ __('messages.payment') }}</th>
                            @endunless
                            <th>{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($event->registrations as $reg)
                            @php
                                $paidAmount = $reg->paid_amount / 100;
                                $fee = $event->registration_fee / 100;
                                if ($reg->paid_amount >= $event->registration_fee) {
                                    $statusClass = 'btn-success';
                                    $badgeClass = 'badge-success';
                                    $statusText = __('messages.paid');
                                } elseif ($reg->paid_amount > 0) {
                                    $statusClass = 'btn-warning';
                                    $badgeClass = 'badge-warning';
                                    $statusText = __('messages.partial') ?? 'Partiel';
                                } else {
                                    $statusClass = 'btn-danger';
                                    $badgeClass = 'badge-danger';
                                    $statusText = __('messages.non_paid');
                                }
                                $isFullyPaid = ($reg->paid_amount >= $event->registration_fee);
                                 $currentStatus = $reg->paid_amount >= $event->registration_fee ? 'paid' : ($reg->paid_amount > 0 ? 'partial' : 'unpaid');
                             @endphp
                             <tr data-status="{{ $currentStatus }}">
                                 <td>{{ $reg->user->name }}</td>
                                @unless(auth()->user()->hasRole('coach'))
                                <td>
                                    @if(auth()->user()->hasAnyRole(['admin', 'cityManager', 'gymManager']))
                                        <button type="button" 
                                                class="btn btn-sm {{ $statusClass }} edit-payment-btn"
                                                data-reg-id="{{ $reg->id }}"
                                                data-user-name="{{ $reg->user->name }}"
                                                data-gym-id="{{ $reg->user->gym_id }}"
                                                data-paid-amount="{{ number_format($paidAmount, 2, '.', '') }}"
                                                data-status-text="{{ $statusText }}"
                                                data-status-class="{{ $badgeClass }}">
                                            <i class="fas fa-money-bill-wave"></i>
                                            {{ $statusText }}
                                            ({{ number_format($paidAmount, 2) }} {{ __('messages.amount_tnd') }})
                                        </button>
                                    @else
                                        <span class="badge {{ $badgeClass }}">
                                            {{ $statusText }} 
                                            @if($reg->paid_amount > 0)
                                                ({{ number_format($paidAmount, 2) }} {{ __('messages.amount_tnd') }})
                                            @endif
                                        </span>
                                    @endif
                                </td>
                                @endunless
                                <td>
                                    @if(auth()->user()->hasAnyRole(['admin', 'cityManager', 'gymManager']))
                                        @if(!$isFullyPaid)
                                            <button type="button" 
                                                    class="btn btn-sm btn-success edit-payment-btn"
                                                    data-reg-id="{{ $reg->id }}"
                                                    data-user-name="{{ $reg->user->name }}"
                                                    data-gym-id="{{ $reg->user->gym_id }}"
                                                    data-paid-amount="{{ number_format($fee, 2, '.', '') }}"
                                                    data-status-text="{{ $statusText }}"
                                                    data-status-class="{{ $badgeClass }}">
                                                <i class="fas fa-hand-holding-usd"></i> {{ __('messages.pay') }}
                                            </button>
                                        @endif
                                        <button type="button" 
                                                class="btn btn-sm btn-info edit-payment-btn"
                                                data-reg-id="{{ $reg->id }}"
                                                data-user-name="{{ $reg->user->name }}"
                                                data-gym-id="{{ $reg->user->gym_id }}"
                                                data-paid-amount="{{ number_format($paidAmount, 2, '.', '') }}"
                                                data-status-text="{{ $statusText }}"
                                                data-status-class="{{ $badgeClass }}">
                                            <i class="fas fa-edit"></i> {{ __('messages.edit') }}
                                        </button>
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->hasRole('coach') ? 2 : 3 }}">{{ __('messages.no_registrants') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="paymentForm" method="POST" action="">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">
                        {{ __('messages.payment') }} - <span id="athleteName"></span>
                        <span id="paymentStatusBadge" class="badge ml-2"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="payment_status_select" class="form-label">{{ __('messages.status') }}</label>
                        <select class="form-control" id="payment_status_select" name="payment_status">
                            <option value="unpaid">{{ __('messages.non_paid') }}</option>
                            <option value="paid">{{ __('messages.paid') }}</option>
                        </select>
                        <input type="hidden" id="amount" name="amount" value="0">
                        <small class="text-muted">{{ __('messages.fee') }}: {{ number_format($event->registration_fee / 100, 2) }} {{ __('messages.amount_tnd') }}</small>
                    </div>

                    <div class="form-group mb-3" id="cash_register_group" style="display: none;">
                        <label for="cash_register_id" class="form-label">
                            <i class="fas fa-cash-register"></i> {{ __('messages.cash_register') ?? 'Caisse' }}
                        </label>
                        <select class="form-control" id="cash_register_id" name="cash_register_id">
                            <option value="">{{ __('messages.select_cash_register') ?? 'Choisir une caisse...' }}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('messages.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- QR Code Modal -->
<div class="modal fade" id="qrCodeModal" tabindex="-1" aria-labelledby="qrCodeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content text-center">
            <div class="modal-header">
                <h5 class="modal-title" id="qrCodeModalLabel">Accès Live</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="qrcode" class="d-flex justify-content-center mb-3"></div>
                <p class="small text-muted mb-3">Scannez pour accéder au live</p>
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" id="liveUrl" value="{{ route('events.live', $event->id) }}" readonly>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" onclick="copyLiveUrl()">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    window.copyLiveUrl = function() {
        var copyText = document.getElementById("liveUrl");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        document.execCommand("copy");
        
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Lien copié !',
                showConfirmButton: false,
                timer: 1500,
                toast: true,
                position: 'top-end'
            });
        } else {
            alert('Lien copié !');
        }
    };

$(document).ready(function() {
    // Generate QR Code
    if (document.getElementById("qrcode")) {
        try {
            new QRCode(document.getElementById("qrcode"), {
                text: "{{ route('events.live', $event->id) }}",
                width: 180,
                height: 180,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });
        } catch(e) {
            console.error("QR Code generation failed", e);
        }
    }

    // Define URL Template once
    const paymentUrlTemplate = "{{ route('events.registrations.markPaid', ['id' => $event->id, 'registration' => 'REGID']) }}";

    $(document).on('click', '.edit-payment-btn', function(e) {
        e.preventDefault();
        const btn = $(this);
        const regId = btn.data('reg-id');
        const userName = btn.data('user-name');
        const gymId = btn.data('gym-id');
        const paidAmount = parseFloat(btn.data('paid-amount')) || 0;
        const statusText = btn.data('status-text');
        const statusClass = btn.data('status-class');
        
        // Safely parse registration fee
        const registrationFeeStr = "{{ number_format($event->registration_fee / 100, 2, '.', '') }}";
        const registrationFee = parseFloat(registrationFeeStr) || 0;
        
        console.log('Payment button clicked', { regId, userName, gymId, paidAmount, registrationFee });

        if (!regId) {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Erreur', 'ID de réservation manquant', 'error');
            } else {
                alert('Erreur: ID de réservation manquant');
            }
            return;
        }

        $('#athleteName').text(userName);
        
        // Load cash registers for the gym
        if (gymId) {
            fetch(`/cash-registers/by-gym/${gymId}`)
                .then(response => response.json())
                .then(data => {
                    let html = '<option value="">' + ("{{ __('messages.select_cash_register') }}" || 'Choisir une caisse...') + '</option>';
                    data.forEach(reg => {
                        html += `<option value="${reg.id}">${reg.name}</option>`;
                    });
                    $('#cash_register_id').html(html);
                    if (data.length > 0) {
                        $('#cash_register_group').show();
                    } else {
                        $('#cash_register_group').hide();
                    }
                })
                .catch(err => {
                    console.error('Error fetching cash registers:', err);
                    $('#cash_register_group').hide();
                });
        } else {
            $('#cash_register_group').hide();
        }
        
        // Set select value based on current payment
        if (paidAmount >= registrationFee && registrationFee > 0) {
            $('#payment_status_select').val('paid');
            $('#amount').val(registrationFee);
        } else {
            $('#payment_status_select').val('unpaid');
            $('#amount').val(0);
        }
        
        // Update status badge
        $('#paymentStatusBadge')
            .text(statusText)
            .removeClass('badge-success badge-warning badge-danger')
            .addClass(statusClass);
        
        // Update form action URL
        let url = paymentUrlTemplate.replace('REGID', regId);
        $('#paymentForm').attr('action', url);
        
        // Open modal
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            try {
                var myModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('paymentModal'));
                myModal.show();
            } catch(err) {
                $('#paymentModal').modal('show');
            }
        } else {
            $('#paymentModal').modal('show');
        }
    });

    // Form submission validation
    $('#paymentForm').on('submit', function(e) {
        const action = $(this).attr('action');
        const currentUrl = window.location.href.split('#')[0];
        
        // Check if action is empty or points to current page (which would cause 405 on POST)
        if (!action || action === '' || action === currentUrl) {
            e.preventDefault();
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur Technique',
                    text: 'L\'URL de soumission n\'est pas définie. Veuillez rafraîchir la page et réessayer.'
                });
            } else {
                alert('Erreur: L\'action du formulaire n\'est pas définie. Veuillez rafraîchir la page.');
            }
            return false;
        }
    });

    // Update hidden amount when select changes
    $('#payment_status_select').on('change', function() {
        const registrationFeeStr = "{{ number_format($event->registration_fee / 100, 2, '.', '') }}";
        if ($(this).val() === 'paid') {
            $('#amount').val(registrationFeeStr);
        } else {
            $('#amount').val(0);
        }
    });

    // Instant Search and Status Filter
    function filterParticipants() {
        var searchValue = $('#participantSearch').val().toLowerCase();
        var statusFilter = $('.filter-btn.active').data('filter');

        $("#participantsTable tbody tr").each(function() {
            var rowText = $(this).text().toLowerCase();
            var textMatch = rowText.indexOf(searchValue) > -1;
            var statusMatch = (statusFilter === 'all') || ($(this).data('status') === statusFilter);
            $(this).toggle(textMatch && statusMatch);
        });
    }

    $('#participantSearch').on('keyup', filterParticipants);
    
    $('.filter-btn').on('click', function() {
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');
        filterParticipants();
    });

    // Auto-focus amount field when modal opens
    $('#paymentModal').on('shown.bs.modal', function () {
        $('#amount').focus().select();
    });
});
</script>
@endpush

