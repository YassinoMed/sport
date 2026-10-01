@extends('layouts.user-layout')
@section('content')
<div class="content-wrapper pb-5">
    <!-- En-tête -->
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
                                <a href="{{ route('PaymentPackage.stripe') }}" class="breadcrumb-link">
                                    <span class="label-fr">Paiements</span>
                                    <span class="label-ar">المدفوعات</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <span class="label-fr">Nouveau paiement</span>
                                <span class="label-ar">دفع جديد</span>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="page-title">
                        <span class="label-fr">Enregistrer un paiement</span>
                        <span class="label-ar">تسجيل دفع</span>
                    </h1>
                    <p class="page-subtitle">{{ $user->name }}</p>
                </div>
                <a href="{{ route('user-payments.index', $user->id) }}" class="btn-ghost">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    <span class="label-fr">Retour à la liste</span>
                    <span class="label-ar">العودة للقائمة</span>
                </a>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row g-4">
                <!-- Sidebar - Info Athlète -->
                <div class="col-lg-4">
                    <div class="summary-card">
                        <div class="summary-header">
                            <div class="summary-avatar-main shadow">
                                @if($user->profile_image)
                                    <img src="{{ asset($user->profile_image) }}" alt="{{ $user->name }}">
                                @else
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                @endif
                            </div>
                            <h2 class="summary-title">{{ $user->name }}</h2>
                            <p class="summary-subtitle">
                                <span class="label-fr">Athlète</span>
                                <span class="label-ar">رياضي</span>
                                #{{ $user->id }}
                            </p>
                        </div>
                        <div class="summary-body">
                            <div class="info-list">
                                <div class="info-item">
                                    <span class="info-label">
                                        <span class="label-fr">Téléphone</span>
                                        <span class="label-ar">الهاتف</span>
                                    </span>
                                    <span class="info-value">{{ $user->phone ?? '—' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">
                                        <span class="label-fr">Email</span>
                                        <span class="label-ar">البريد الإلكتروني</span>
                                    </span>
                                    <span class="info-value text-truncate">{{ $user->email ?? '—' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">
                                        <span class="label-fr">Club</span>
                                        <span class="label-ar">النادي</span>
                                    </span>
                                    <span class="info-value">{{ optional($user->gym)->name ?? '—' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">
                                        <span class="label-fr">Ville</span>
                                        <span class="label-ar">المدينة</span>
                                    </span>
                                    <span class="info-value">{{ optional($user->city)->name ?? '—' }}</span>
                                </div>
                            </div>
                            <div class="summary-divider"></div>
                            <div class="recent-payments-mini">
                                <span class="mini-title">
                                    <span class="label-fr">Dernier paiement</span>
                                    <span class="label-ar">آخر دفع</span>
                                </span>
                                @php $lastPayment = $user->payments()->orderByDesc('paid_at')->first(); @endphp
                                @if($lastPayment)
                                    <div class="payment-mini-status">
                                        <span class="amount">{{ number_format($lastPayment->amount_cents / 100, 2) }} TND</span>
                                        <span class="date">{{ $lastPayment->paid_at ? $lastPayment->paid_at->format('d/m/Y') : '—' }}</span>
                                    </div>
                                @else
                                    <p class="text-muted small">
                                        <span class="label-fr">Aucun paiement enregistré</span>
                                        <span class="label-ar">لا يوجد دفع مسجل</span>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulaire Principal -->
                <div class="col-lg-8">
                    @if ($errors->any())
                        <div class="alert-modern alert-error mb-4 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                            <div>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <div class="modern-card">
                        <div class="card-header-modern">
                            <div class="header-icon-box">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                            </div>
                            <div>
                                <h3 class="header-title">
                                    <span class="label-fr">Détails du paiement</span>
                                    <span class="label-ar">تفاصيل الدفع</span>
                                </h3>
                                <span class="header-subtitle">
                                    <span class="label-fr">Veuillez remplir les informations de facturation</span>
                                    <span class="label-ar">يرجى ملء معلومات الفوترة</span>
                                </span>
                            </div>
                        </div>

                        <form action="{{ route('user-payments.store', $user->id) }}" method="post" class="card-body-modern p-4">
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern" for="billing_month">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                            <span class="label-fr">Mois de facturation</span>
                                            <span class="label-ar">شهر الفوترة</span>
                                        </label>
                                        <input type="month" id="billing_month" name="billing_month" class="form-control-modern @error('billing_month') is-invalid @enderror" value="{{ old('billing_month', $defaultMonth) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern" for="package_id">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                                            <span class="label-fr">Forfait d'entraînement</span>
                                            <span class="label-ar">باقة التدريب</span>
                                        </label>
                                        <div class="select-wrapper-modern">
                                            <select id="package_id" name="package_id" class="form-select-modern" onchange="updateAmount(this)">
                                                <option value="">
                                                    <span class="label-fr">Choisir un forfait...</span>
                                                    <span class="label-ar">اختر باقة...</span>
                                                </option>
                                                @foreach($packages as $package)
                                                    <option value="{{ $package->id }}" data-price="{{ $package->price }}" {{ old('package_id') == $package->id ? 'selected' : '' }}>
                                                        {{ $package->name }} ({{ number_format($package->price, 2) }} TND)
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern" for="amount">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                            <span class="label-fr">Montant à payer (TND)</span>
                                            <span class="label-ar">المبلغ المطلوب (د.ت)</span>
                                        </label>
                                        <input type="number" step="0.01" min="0" id="amount" name="amount" class="form-control-modern @error('amount') is-invalid @enderror" value="{{ old('amount') }}" placeholder="0.00" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern" for="start_date">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                            <span class="label-fr">Début de l'adhésion</span>
                                            <span class="label-ar">بداية الاشتراك</span>
                                        </label>
                                        <input type="date" id="start_date" name="start_date" class="form-control-modern @error('start_date') is-invalid @enderror" value="{{ old('start_date', date('Y-m-01')) }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern" for="end_date">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                            <span class="label-fr">Fin de l'adhésion</span>
                                            <span class="label-ar">نهاية الاشتراك</span>
                                        </label>
                                        <input type="date" id="end_date" name="end_date" class="form-control-modern @error('end_date') is-invalid @enderror" value="{{ old('end_date', date('Y-m-t')) }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern" for="cash_register_id">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M7 15h0M2 9.5h20"/><path d="M12 15h0M17 15h0"/></svg>
                                            <span class="label-fr">Caisse</span>
                                            <span class="label-ar">الصندوق</span>
                                        </label>
                                        <div class="select-wrapper-modern">
                                            <select id="cash_register_id" name="cash_register_id" class="form-select-modern">
                                                <option value="">
                                                    <span class="label-fr">Choisir une caisse...</span>
                                                    <span class="label-ar">اختر صندوق...</span>
                                                </option>
                                                @foreach($cashRegisters as $register)
                                                    <option value="{{ $register->id }}" {{ old('cash_register_id') == $register->id ? 'selected' : '' }}>
                                                        {{ $register->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern" for="method">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                                            <span class="label-fr">Mode de paiement</span>
                                            <span class="label-ar">طريقة الدفع</span>
                                        </label>
                                        <div class="select-wrapper-modern">
                                            <select id="method" name="method" class="form-select-modern" required>
                                                <option value="cash" {{ old('method') == 'cash' ? 'selected' : '' }}>
                                                    💵 
                                                    <span class="label-fr">Espèces</span>
                                                    <span class="label-ar">نقداً</span>
                                                </option>
                                                <option value="card" {{ old('method') == 'card' ? 'selected' : '' }}>
                                                    💳 
                                                    <span class="label-fr">Carte</span>
                                                    <span class="label-ar">بطاقة</span>
                                                </option>
                                                <option value="transfer" {{ old('method') == 'transfer' ? 'selected' : '' }}>
                                                    🏦 
                                                    <span class="label-fr">Virement</span>
                                                    <span class="label-ar">تحويل</span>
                                                </option>
                                                <option value="check" {{ old('method') == 'check' ? 'selected' : '' }}>
                                                    📝 
                                                    <span class="label-fr">Chèque</span>
                                                    <span class="label-ar">شيك</span>
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern" for="status">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                            <span class="label-fr">Statut du paiement</span>
                                            <span class="label-ar">حالة الدفع</span>
                                        </label>
                                        <div class="select-wrapper-modern">
                                            <select id="status" name="status" class="form-select-modern" required>
                                                <option value="paid" {{ old('status', 'paid') == 'paid' ? 'selected' : '' }}>
                                                    ✅ 
                                                    <span class="label-fr">Payé</span>
                                                    <span class="label-ar">مدفوع</span>
                                                </option>
                                                <option value="due" {{ old('status') == 'due' ? 'selected' : '' }}>
                                                    ⏳ 
                                                    <span class="label-fr">En attente</span>
                                                    <span class="label-ar">قيد الانتظار</span>
                                                </option>
                                                <option value="refund" {{ old('status') == 'refund' ? 'selected' : '' }}>
                                                    ↩️ 
                                                    <span class="label-fr">Remboursé</span>
                                                    <span class="label-ar">مسترجع</span>
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern" for="paid_at">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                            <span class="label-fr">Date et heure du paiement</span>
                                            <span class="label-ar">تاريخ ووقت الدفع</span>
                                        </label>
                                        <input type="datetime-local" id="paid_at" name="paid_at" class="form-control-modern" value="{{ old('paid_at', now()->format('Y-m-d\TH:i')) }}">
                                        <small class="form-text-hint">
                                            <span class="label-fr">Par défaut : date et heure actuelles</span>
                                            <span class="label-ar">افتراضياً: التاريخ والوقت الحالي</span>
                                        </small>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern" for="notes">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                            <span class="label-fr">Notes / Remarques</span>
                                            <span class="label-ar">ملاحظات</span>
                                        </label>
                                        <textarea id="notes" name="notes" class="form-control-modern" rows="3" placeholder="..."></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions-modern mt-5 border-top pt-4">
                                <button type="submit" class="btn-primary-modern w-100 py-3 shadow-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                    <span class="label-fr">Enregistrer le paiement</span>
                                    <span class="label-ar">حفظ الدفع</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
function updateAmount(select) {
    const selectedOption = select.options[select.selectedIndex];
    const price = selectedOption.getAttribute('data-price');
    if (price) {
        document.getElementById('amount').value = price;
    }
}
</script>

@push('styles')
<style>
/* Sidebar - Info Athlète specific styles */
.summary-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid var(--border);
    overflow: hidden;
}

.summary-header {
    background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
    padding: 2rem 1.5rem;
    text-align: center;
    color: white;
}

.summary-avatar-main {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    border: 3px solid rgba(255,255,255,0.3);
    margin: 0 auto 1rem;
    overflow: hidden;
    position: relative;
    background: rgba(255,255,255,0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 700;
}

.summary-avatar-main img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.summary-title {
    font-size: 1.25rem;
    font-weight: 700;
    margin: 0 0 0.25rem;
}

.summary-subtitle {
    opacity: 0.8;
    font-size: 0.875rem;
}

.summary-body {
    padding: 1.5rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.75rem;
    font-size: 0.9rem;
}

.info-label {
    color: var(--secondary);
}

.info-value {
    font-weight: 600;
    color: #1e293b;
}

.summary-divider {
    height: 1px;
    background: var(--border);
    margin: 1.5rem 0;
}

.mini-title {
    display: block;
    font-size: 0.75rem;
    text-transform: uppercase;
    color: var(--gray-500);
    font-weight: 700;
    margin-bottom: 0.75rem;
}

.payment-mini-status {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 8px;
    padding: 0.75rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.payment-mini-status .amount {
    font-weight: 700;
    color: #15803d;
}

.payment-mini-status .date {
    font-size: 0.875rem;
    color: #15803d;
}
</style>
@endpush

@endsection
