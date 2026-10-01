<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ __('messages.attendance_sheet') }} - {{ $event->name }}</title>
    <style>
        @page {
            margin: 15px;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 13px;
            color: #2563eb;
            margin: 3px 0;
            font-weight: bold;
        }
        .header h2 {
            font-size: 11px;
            color: #dc2626;
            margin: 3px 0;
            font-weight: bold;
        }
        .info-section {
            margin: 12px 0;
            background-color: #f3f4f6;
            padding: 8px;
            border-radius: 4px;
        }
        .info-row {
            margin: 3px 0;
            padding: 2px 0;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            min-width: 120px;
        }
        .legend {
            margin: 10px 0;
            text-align: center;
            font-size: 9px;
        }
        .legend-item {
            display: inline-block;
            margin: 0 8px;
            padding: 3px 10px;
            border: 1px solid #000;
            border-radius: 3px;
        }
        .legend-item.yellow {
            background-color: #fef3c7;
        }
        .legend-item.green {
            background-color: #d1fae5;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #333;
        }
        th {
            background-color: #e5e7eb;
            padding: 6px 4px;
            font-weight: bold;
            text-align: center;
            font-size: 9px;
        }
        td {
            padding: 6px 4px;
            text-align: center;
            font-size: 9px;
        }
        .footer {
            margin-top: 30px;
            font-size: 9px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        .signature-box {
            display: inline-block;
            width: 45%;
            margin: 10px 2%;
            border: 1px dashed #999;
            padding: 30px 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ __('messages.federation_name') }}</h1>
        <h1>{{ __('messages.federation_name_2') }}</h1>
        <h2>{{ __('messages.attendance_sheet') }} - ورقة الحضور - {{ strtoupper($event->name) }}</h2>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">{{ __('messages.event') }} :</span>
            <span>{{ $event->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">{{ __('messages.location') }} :</span>
            <span>{{ $event->location ?? 'Dar Echabab Oued Echmaouni' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">{{ __('messages.city') }} :</span>
            <span>Sfax</span>
        </div>
        <div class="info-row">
            <span class="info-label">{{ __('messages.phone') }} :</span>
            <span>93 208 950</span>
        </div>
        <div class="info-row">
            <span class="info-label">{{ __('messages.start_date') }} :</span>
            <span>{{ $event->start_at->format('d/m/Y à H:i') }}</span>
        </div>
        @if($event->end_at)
        <div class="info-row">
            <span class="info-label">{{ __('messages.end_date') }} :</span>
            <span>{{ $event->end_at->format('d/m/Y à H:i') }}</span>
        </div>
        @endif
        <div class="info-row">
            <span class="info-label">{{ __('messages.registration_fee') }} :</span>
            <span>{{ number_format($event->registration_fee / 100, 2) }} TND</span>
        </div>
        <div class="info-row">
            <span class="info-label">{{ __('messages.capacity') }} :</span>
            <span>{{ $event->capacity > 0 ? $event->capacity : __('messages.unlimited') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">{{ __('messages.registrants_count') }} :</span>
            <span><strong>{{ $registrations->count() }}</strong></span>
        </div>
        @if($event->belt_requirement)
        <div class="info-row">
            <span class="info-label">{{ __('messages.belt_requirement') }} :</span>
            <span>{{ $event->belt_requirement }}</span>
        </div>
        @endif
    </div>


    <div class="legend">
        <span style="font-weight: bold;">{{ __('messages.disciplines') }} :</span>
        <span class="legend-item yellow">{{ __('messages.techniques_taolu') }}</span>
        <span class="legend-item green">{{ __('messages.combat_sanda') }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 5%;">N°</th>
                <th rowspan="2" style="width: 22%;">NOM ET PRÉNOM</th>
                <th colspan="2" style="width: 10%;">SEXE</th>
                <th rowspan="2" style="width: 13%;">N° LICENCE</th>
                <th rowspan="2" style="width: 15%;">CATÉGORIE</th>
                <th rowspan="2" style="width: 10%;">POIDS (KG)</th>
                <th rowspan="2" style="width: 25%;">SIGNATURE</th>
            </tr>
            <tr>
                <th style="width: 5%;">M</th>
                <th style="width: 5%;">F</th>
            </tr>
        </thead>
        <tbody>
            @forelse($registrations as $index => $registration)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td style="text-align: left; padding-left: 8px;">{{ $registration->user->name }}</td>
                <td>{{ $registration->user->gender === 'male' ? 'X' : '' }}</td>
                <td>{{ $registration->user->gender === 'female' ? 'X' : '' }}</td>
                <td>{{ $registration->user->national_id ?? '—' }}</td>
                <td>{{ $registration->user->category->name ?? 'N/A' }}</td>
                <td>{{ $registration->user->weight_kg ?? '—' }}</td>
                <td style="height: 25px;"></td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="padding: 20px; text-align: center; font-style: italic;">
                    Aucun participant inscrit pour le moment
                </td>
            </tr>
            @endforelse
            
            @if($registrations->count() > 0 && $registrations->count() < 15)
                @for($i = $registrations->count(); $i < 15; $i++)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="height: 25px;"></td>
                </tr>
                @endfor
            @endif
        </tbody>
    </table>

    <div class="footer">
        <div style="text-align: center; margin-bottom: 15px;">
            <strong>{{ __('messages.generated_on') }} {{ date('d/m/Y à H:i') }}</strong>
        </div>
        
        <div style="text-align: center;">
            <div class="signature-box">
                <strong>{{ __('messages.coach_signature') }}</strong>
                <br><br>
                <small>{{ __('messages.name') }} : ___________________</small>
            </div>
            <div class="signature-box">
                <strong>{{ __('messages.club_stamp') }}</strong>
            </div>
        </div>
    </div>
</body>
</html>