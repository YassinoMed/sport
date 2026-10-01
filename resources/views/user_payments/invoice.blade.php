<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('messages.invoice') }} {{ $payment->invoice_number }}</title>
    <style>
        @page { margin: 0; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; margin: 0; padding: 0; line-height: 1.5; }
        @if(app()->getLocale() == 'ar')
        body { font-family: 'DejaVu Sans', sans-serif; text-align: right; }
        @endif
        .invoice-box { padding: 40px; }
        .header { background: #1e1b4b; color: white; padding: 40px; display: table; width: 100%; box-sizing: border-box; }
        .header-content { display: table-cell; vertical-align: middle; }
        .header-logo { font-size: 28px; font-weight: bold; letter-spacing: 1px; }
        .header-meta { text-align: right; display: table-cell; vertical-align: middle; }
        @if(app()->getLocale() == 'ar')
        .header-meta { text-align: left; }
        @endif
        .meta-item { margin-bottom: 5px; font-size: 14px; opacity: 0.9; }
        
        .section { margin-top: 40px; }
        .section-title { font-size: 12px; font-weight: bold; color: #6366f1; text-transform: uppercase; margin-bottom: 10px; letter-spacing: 1px; }
        
        .billing-info { display: table; width: 100%; }
        .info-col { display: table-cell; width: 50%; vertical-align: top; }
        .info-label { font-size: 11px; color: #999; text-transform: uppercase; }
        .info-value { font-size: 15px; font-weight: bold; margin-bottom: 5px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 30px; }
        th { background: #f8fafc; color: #64748b; font-size: 12px; text-transform: uppercase; padding: 12px 15px; text-align: left; border-bottom: 2px solid #e2e8f0; }
        @if(app()->getLocale() == 'ar')
        th { text-align: right; }
        @endif
        td { padding: 15px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
        .text-right { text-align: right; }
        @if(app()->getLocale() == 'ar')
        .text-right { text-align: left; }
        @endif
        
        .totals { margin-top: 30px; margin-left: auto; width: 300px; }
        @if(app()->getLocale() == 'ar')
        .totals { margin-right: auto; margin-left: 0; }
        @endif
        .total-row { display: table; width: 100%; padding: 10px 0; border-bottom: 1px solid #f1f5f9; }
        .total-label { display: table-cell; font-size: 14px; color: #64748b; }
        .total-value { display: table-cell; text-align: right; font-weight: bold; font-size: 16px; }
        @if(app()->getLocale() == 'ar')
        .total-value { text-align: left; }
        @endif
        .grand-total { background: #f8fafc; padding: 15px; margin-top: 10px; border-radius: 8px; }
        .grand-total .total-label { color: #1e1b4b; font-weight: bold; font-size: 18px; }
        .grand-total .total-value { color: #6366f1; font-weight: 900; font-size: 22px; }
        
        .footer { margin-top: 60px; padding-top: 20px; border-top: 1px solid #f1f5f9; text-align: center; color: #94a3b8; font-size: 12px; }
        .badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: bold; text-transform: uppercase; }
        .badge-success { background: #dcfce7; color: #166534; }
        
        .arabic-text { font-family: 'DejaVu Sans', sans-serif; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <div class="header-logo">{{ __('messages.gym_system') }}</div>
            <div class="meta-item">{{ __('messages.professional_sport_club') }}</div>
        </div>
        <div class="header-meta">
            <div class="meta-item">{{ __('messages.payment_receipt') }}</div>
            <div class="meta-item" style="font-size: 20px; font-weight: bold; margin-top: 10px;">{{ $payment->invoice_number }}</div>
            <div class="meta-item">{{ __('messages.date') }}: {{ $payment->paid_at->format('d/m/Y') }}</div>
        </div>
    </div>

    <div class="invoice-box">
        <div class="billing-info">
            <div class="info-col">
                <div class="section-title">{{ __('messages.recipient_athlete') }}</div>
                <div class="info-value">{{ $payment->user->name }}</div>
                <div class="info-label">{{ __('messages.email') }}: {{ $payment->user->email ?? 'N/A' }}</div>
                <div class="info-label">{{ __('messages.phone') }}: {{ $payment->user->phone ?? 'N/A' }}</div>
            </div>
            <div class="info-col">
                <div class="section-title">{{ __('messages.subscription_details') }}</div>
                <div class="info-label">{{ __('messages.coverage_period') }}</div>
                <div class="info-value">
                    {{ $payment->start_date ? $payment->start_date->format('d/m/Y') : '-' }} 
                    {{ __('messages.to_date') }} 
                    {{ $payment->end_date ? $payment->end_date->format('d/m/Y') : '-' }}
                </div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>{{ __('messages.description') }}</th>
                    <th>{{ __('messages.period') }}</th>
                    <th>{{ __('messages.mode') }}</th>
                    <th class="text-right">{{ __('messages.amount') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>{{ __('messages.monthly_subscription') }}</strong><br>
                        <small style="color: #64748b;">{{ $payment->trainingPackage->name ?? __('messages.training_fees') }}</small>
                    </td>
                    <td>{{ $payment->billing_month->translatedFormat('F Y') }}</td>
                    <td>{{ __('messages.' . strtolower($payment->method)) }}</td>
                    <td class="text-right">{{ number_format($payment->amount_cents/100, 2, ',', ' ') }} TND</td>
                </tr>
            </tbody>
        </table>

        <div class="totals">
            <div class="total-row grand-total">
                <div class="total-label">{{ __('messages.total_paid') }}</div>
                <div class="total-value">{{ number_format($payment->amount_cents/100, 2, ',', ' ') }} TND</div>
            </div>
            <div style="text-align: right; margin-top: 10px; @if(app()->getLocale() == 'ar') text-align: left; @endif">
                <span class="badge badge-success">{{ __('messages.paid_status') }}</span>
            </div>
        </div>

        <div class="section">
            <div class="section-title">{{ __('messages.notes') }}</div>
            <p style="font-size: 13px; color: #64748b;">{{ $payment->notes ?? __('messages.no_particular_notes') }}</p>
        </div>

        <div class="footer">
            <p>{{ __('messages.thanks_for_trust') }}</p>
            <p>© {{ date('Y') }} {{ __('messages.gym_system') }}. {{ __('messages.all_rights_reserved') }}</p>
        </div>
    </div>
</body>
</html>
