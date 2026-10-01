<!doctype html>
<html lang="fr" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>{{ __('messages.detailed_financial_report') }} — {{ $month }}</title>

    <style>
        @font-face {
            font-family: 'Amiri';
            src: url('{{ storage_path("fonts/Amiri-Regular.ttf") }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'DejaVu Sans', 'Amiri', Arial, sans-serif;
            font-size: 11px;
            color: #1f2937;
            line-height: 1.4;
        }

        .rtl {
            direction: rtl;
            unicode-bidi: embed;
            font-family: 'Amiri', serif;
        }

        .container {
            padding: 18px 20px;
        }

        .header-table,
        .meta-table,
        .report-table,
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table {
            margin-bottom: 16px;
            border: 1px solid #d1d5db;
            background: #f8fafc;
        }

        .header-table td {
            padding: 12px 14px;
            vertical-align: middle;
        }

        .logo-cell {
            width: 110px;
            text-align: center;
            background: #ffffff;
            border-right: 1px solid #d1d5db;
        }

        .logo {
            max-width: 80px;
            max-height: 80px;
        }

        .title-cell {
            text-align: center;
        }

        .org-name-fr {
            font-size: 14px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 3px;
        }

        .org-name-ar {
            font-size: 16px;
            font-weight: bold;
            color: #374151;
            margin-bottom: 6px;
        }

        .report-title {
            font-size: 16px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 4px;
        }

        .report-subtitle {
            font-size: 11px;
            color: #4b5563;
            margin-bottom: 6px;
        }

        .meta-table td {
            border: 1px solid #e5e7eb;
            background: #ffffff;
            padding: 5px 7px;
            font-size: 10px;
            color: #4b5563;
        }

        .section-title {
            margin: 18px 0 8px 0;
            padding: 7px 10px;
            font-size: 13px;
            font-weight: bold;
            color: #111827;
            background: #eef2f7;
            border: 1px solid #dbe3ec;
        }

        .summary-table th,
        .summary-table td,
        .report-table th,
        .report-table td {
            border: 1px solid #d1d5db;
            padding: 7px 8px;
            vertical-align: top;
        }

        .summary-table th,
        .report-table thead th {
            background: #f3f4f6;
            color: #111827;
            font-size: 10px;
            font-weight: bold;
            text-align: left;
        }

        .summary-table td,
        .report-table td {
            background: #ffffff;
            color: #1f2937;
        }

        .report-table tbody tr:nth-child(even) td {
            background: #fafafa;
        }

        .report-table tfoot th,
        .report-table tfoot td {
            background: #f3f4f6;
            font-weight: bold;
            border-top: 2px solid #9ca3af;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .mono {
            font-family: 'DejaVu Sans Mono', monospace;
        }

        .positive {
            color: #15803d;
            font-weight: bold;
        }

        .negative {
            color: #b91c1c;
            font-weight: bold;
        }

        .warning {
            color: #b45309;
            font-weight: bold;
        }

        .muted {
            color: #6b7280;
        }

        .empty-row td {
            text-align: center;
            color: #6b7280;
            padding: 12px 8px;
            font-style: italic;
        }

        .footer {
            margin-top: 18px;
            padding-top: 8px;
            border-top: 1px solid #d1d5db;
            font-size: 9px;
            color: #6b7280;
            text-align: center;
        }

        .nowrap {
            white-space: nowrap;
        }

        .small {
            font-size: 9px;
        }
    </style>
</head>

<body>
    @php
    $t = function ($key, $default = null) {
    $value = __($key);
    return $value === $key ? ($default ?? $key) : $value;
    };

    $logoPath = public_path('images/logo.png');

    // Texte organisation
    $orgNameFr = 'Association des sports individuels de Sfax';
    $orgNameAr = 'جمعية الرياضات الفردية بصفاقس';

    // Accès sécurisés
    $summary = $summary ?? [];
    $revenue = $revenue ?? [];
    $expense = $expense ?? [];
    $attendance = $attendance ?? [];
    $payments = $payments ?? [];
    $coachesTop = $coachesTop ?? [];
    $gymsTop = $gymsTop ?? [];
    $gyms = $gyms ?? collect();
    $cities = $cities ?? collect();
    $cashRegisters = $cashRegisters ?? collect();

    $selectedGymName = optional($gyms->firstWhere('id', $selectedGymId ?? null))->name;
    $selectedCityName = optional($cities->firstWhere('id', $selectedCityId ?? null))->name;
    $selectedCashRegisterName = optional($cashRegisters->firstWhere('id', $selectedCashRegisterId ?? null))->name;

    $revenueSources = $revenue['sources'] ?? [];
    $expenseCategories = $expense['by_category'] ?? [];
    @endphp

    <div class="container">

        {{-- HEADER --}}
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    @if(file_exists($logoPath))
                    <img src="{{ $logoPath }}" alt="Logo" class="logo">
                    @endif
                </td>

                <td class="title-cell">
                    <div class="org-name-fr">{{ $orgNameFr }}</div>
                    <div class="org-name-ar rtl">{{ $orgNameAr }}</div>

                    <div class="report-title">
                        {{ $t('messages.detailed_financial_report', 'Rapport financier détaillé') }}
                    </div>

                    <div class="report-subtitle">
                        {{ $t('messages.period', 'Période') }} : {{ $month }}
                    </div>
                </td>
            </tr>
        </table>

        <table class="meta-table">
            <tr>
                <td>
                    <strong>{{ $t('messages.edition_date', 'Date d’édition') }} :</strong>
                    {{ date('d/m/Y H:i') }}
                </td>

                <td>
                    <strong>{{ $t('messages.gym', 'Salle') }} :</strong>
                    {{ $selectedGymName ?? $t('messages.all', 'Tous') }}
                </td>

                <td>
                    <strong>{{ $t('messages.city', 'Ville') }} :</strong>
                    {{ $selectedCityName ?? $t('messages.all', 'Toutes') }}
                </td>

                <td>
                    <strong>{{ $t('messages.cash_register', 'Caisse') }} :</strong>
                    {{ $selectedCashRegisterName ?? $t('messages.all', 'Toutes') }}
                </td>
            </tr>
        </table>

        {{-- SYNTHÈSE GLOBALE --}}
        @if(!empty($show_summary))
        <div class="section-title">{{ $t('messages.global_summary', 'Synthèse globale') }}</div>

        <table class="summary-table">
            <thead>
                <tr>
                    <th>{{ $t('messages.metric', 'Indicateur') }}</th>
                    <th class="text-right">{{ $t('messages.value', 'Valeur') }}</th>
                    <th>{{ $t('messages.period', 'Période') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $t('messages.users', 'Utilisateurs') }}</td>
                    <td class="text-right mono">{{ $summary['users_total'] ?? 0 }}</td>
                    <td>{{ $month }}</td>
                </tr>
                <tr>
                    <td>{{ $t('messages.active_users', 'Utilisateurs actifs') }}</td>
                    <td class="text-right mono">{{ $summary['users_active'] ?? 0 }}</td>
                    <td>{{ $month }}</td>
                </tr>
                <tr>
                    <td>{{ $t('messages.coaches', 'Coachs') }}</td>
                    <td class="text-right mono">{{ $summary['coaches_total'] ?? 0 }}</td>
                    <td>{{ $month }}</td>
                </tr>
                <tr>
                    <td>{{ $t('messages.gyms', 'Salles') }}</td>
                    <td class="text-right mono">{{ $summary['gyms_total'] ?? 0 }}</td>
                    <td>{{ $month }}</td>
                </tr>
                <tr>
                    <td>{{ $t('messages.revenues', 'Revenus') }}</td>
                    <td class="text-right mono positive">
                        {{ number_format(($summary['revenue_total_cents'] ?? 0) / 100, 2, ',', ' ') }} TND
                    </td>
                    <td>{{ $month }}</td>
                </tr>
                <tr>
                    <td>{{ $t('messages.expenses', 'Dépenses') }}</td>
                    <td class="text-right mono negative">
                        {{ number_format(($summary['expense_total_cents'] ?? 0) / 100, 2, ',', ' ') }} TND
                    </td>
                    <td>{{ $month }}</td>
                </tr>
                <tr>
                    <td>{{ $t('messages.net_revenue', 'Net') }}</td>
                    <td
                        class="text-right mono {{ (($summary['net_total_cents'] ?? 0) >= 0) ? 'positive' : 'negative' }}">
                        {{ number_format(($summary['net_total_cents'] ?? 0) / 100, 2, ',', ' ') }} TND
                    </td>
                    <td>{{ $month }}</td>
                </tr>
                <tr>
                    <td>{{ $t('messages.courses', 'Cours') }}</td>
                    <td class="text-right mono">{{ $summary['courses_total'] ?? 0 }}</td>
                    <td>{{ $month }}</td>
                </tr>
            </tbody>
        </table>
        @endif

        {{-- REVENUS PAR SOURCE --}}
        @if(!empty($show_revenue))
        <div class="section-title">{{ $t('messages.total_revenue_source', 'Revenus par source') }}</div>

        <table class="report-table">
            <thead>
                <tr>
                    <th>{{ $t('messages.source', 'Source') }}</th>
                    <th class="text-center">{{ $t('messages.transactions', 'Transactions') }}</th>
                    <th class="text-right">{{ $t('messages.amount_tnd', 'Montant (TND)') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($revenueSources as $src)
                <tr>
                    <td>{{ $src['label'] ?? '-' }}</td>
                    <td class="text-center mono">{{ $src['transactions'] ?? 0 }}</td>
                    <td class="text-right mono">{{ number_format((($src['amount_cents'] ?? 0) / 100), 2, ',', ' ') }}
                    </td>
                </tr>
                @empty
                <tr class="empty-row">
                    <td colspan="3">{{ $t('messages.no_revenue_data', 'Aucune donnée de revenu') }}</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th>{{ $t('messages.total', 'Total') }}</th>
                    <th class="text-center mono">{{ collect($revenueSources)->sum('transactions') }}</th>
                    <th class="text-right mono">{{ number_format((($revenue['total_cents'] ?? 0) / 100), 2, ',', ' ') }}
                        TND</th>
                </tr>
            </tfoot>
        </table>
        @endif

        {{-- DÉPENSES PAR CATÉGORIE --}}
        @if(!empty($show_expenses))
        <div class="section-title">{{ $t('messages.expenses_by_category', 'Dépenses par catégorie') }}</div>

        <table class="report-table">
            <thead>
                <tr>
                    <th>{{ $t('messages.category', 'Catégorie') }}</th>
                    <th class="text-right">{{ $t('messages.amount_tnd', 'Montant (TND)') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenseCategories as $row)
                <tr>
                    <td>{{ $row['label'] ?? '-' }}</td>
                    <td class="text-right mono">{{ number_format((($row['amount_cents'] ?? 0) / 100), 2, ',', ' ') }}
                    </td>
                </tr>
                @empty
                <tr class="empty-row">
                    <td colspan="2">{{ $t('messages.no_expenses', 'Aucune dépense') }}</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th>{{ $t('messages.total', 'Total') }}</th>
                    <th class="text-right mono">{{ number_format((($expense['total_cents'] ?? 0) / 100), 2, ',', ' ') }}
                        TND</th>
                </tr>
                <tr>
                    <th>{{ $t('messages.net_revenue', 'Net') }}</th>
                    <th
                        class="text-right mono {{ (($expense['net_total_cents'] ?? 0) >= 0) ? 'positive' : 'negative' }}">
                        {{ number_format((($expense['net_total_cents'] ?? 0) / 100), 2, ',', ' ') }} TND
                    </th>
                </tr>
            </tfoot>
        </table>
        @endif

        {{-- PRÉSENCE PAR COURS --}}
        @if(!empty($show_attendance))
        <div class="section-title">{{ $t('messages.attendance_by_course', 'Présence par cours') }}</div>

        <table class="report-table">
            <thead>
                <tr>
                    <th>{{ $t('messages.course', 'Cours') }}</th>
                    <th>{{ $t('messages.coach', 'Coach') }}</th>
                    <th>{{ $t('messages.gym', 'Salle') }}</th>
                    <th>{{ $t('messages.dates', 'Dates') }}</th>
                    <th class="text-center">{{ $t('messages.sessions', 'Séances') }}</th>
                    <th class="text-center">{{ $t('messages.presents', 'Présents') }}</th>
                    <th class="text-center">{{ $t('messages.absents', 'Absents') }}</th>
                    <th class="text-center">{{ $t('messages.excused', 'Excusés') }}</th>
                    <th class="text-center">{{ $t('messages.rate_percent', 'Taux %') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendance as $row)
                @php
                $rate = (float)($row['presence_rate'] ?? 0);
                $datesText = '-';

                if (!empty($row['session_dates'])) {
                $dates = explode(', ', $row['session_dates']);
                $formattedDates = array_map(function ($d) {
                try {
                return \Carbon\Carbon::parse($d)->format('d/m');
                } catch (\Exception $e) {
                return $d;
                }
                }, $dates);
                $datesText = implode(', ', $formattedDates);
                }
                @endphp
                <tr>
                    <td class="rtl">{{ $row['course_name'] ?? '-' }}</td>
                    <td class="rtl">{{ $row['coach_names'] ?? '-' }}</td>
                    <td class="rtl">{{ $row['gym_name'] ?? '-' }}</td>
                    <td class="small">{{ $datesText }}</td>
                    <td class="text-center mono">{{ $row['total_sessions'] ?? 0 }}</td>
                    <td class="text-center mono">{{ $row['presents'] ?? 0 }}</td>
                    <td class="text-center mono">{{ $row['absents'] ?? 0 }}</td>
                    <td class="text-center mono">{{ $row['excused'] ?? 0 }}</td>
                    <td
                        class="text-center mono {{ $rate >= 80 ? 'positive' : ($rate >= 50 ? 'warning' : 'negative') }}">
                        {{ number_format($rate, 1) }}%
                    </td>
                </tr>
                @empty
                <tr class="empty-row">
                    <td colspan="9">{{ $t('messages.no_attendance_data', 'Aucune donnée de présence pour les filtres
                        sélectionnés') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @endif

        {{-- PAIEMENTS COACHS --}}
        @if(!empty($show_coach_pay))
        <div class="section-title">{{ $t('messages.coach_payments', 'Paiements coachs') }}</div>

        <table class="report-table">
            <thead>
                <tr>
                    <th>{{ $t('messages.transactions', 'Transactions') }}</th>
                    <th class="text-right">{{ $t('messages.total_amount', 'Montant total') }} (TND)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="mono">{{ $payments['coaches']['transactions'] ?? 0 }}</td>
                    <td class="text-right mono">{{ number_format((($payments['coaches']['total_cents'] ?? 0) / 100), 2,
                        ',', ' ') }}</td>
                </tr>
            </tbody>
        </table>
        @endif

        {{-- PAIEMENTS UTILISATEURS --}}
        @if(!empty($show_user_pay))
        <div class="section-title">{{ $t('messages.user_payments', 'Paiements utilisateurs') }}</div>

        <table class="report-table">
            <thead>
                <tr>
                    <th>{{ $t('messages.total_amount', 'Montant total') }} (TND)</th>
                    <th>{{ $t('messages.paid_tnd', 'Montant payé') }}</th>
                    <th class="text-center">{{ $t('messages.paid_count_label', 'Nb payés') }}</th>
                    <th class="text-center">{{ $t('messages.due_count_label', 'Nb dus') }}</th>
                    <th>{{ $t('messages.cash_register', 'Caisse') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="mono">{{ number_format((($payments['users']['total_cents'] ?? 0) / 100), 2, ',', ' ') }}
                    </td>
                    <td class="mono">{{ number_format((($payments['users']['paid_cents'] ?? 0) / 100), 2, ',', ' ') }}
                    </td>
                    <td class="text-center mono">{{ $payments['users']['paid_count'] ?? 0 }}</td>
                    <td class="text-center mono">{{ $payments['users']['due_count'] ?? 0 }}</td>
                    <td>{{ $selectedCashRegisterName ?? $t('messages.all', 'Tous') }}</td>
                </tr>
            </tbody>
        </table>
        @endif

        {{-- TOP COACHS --}}
        @if(!empty($show_top_coaches))
        <div class="section-title">{{ $t('messages.top_coaches', 'Top coachs') }}</div>

        <table class="report-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ $t('messages.name', 'Nom') }}</th>
                    <th class="text-center">{{ $t('messages.sessions', 'Séances') }}</th>
                    <th class="text-center">{{ $t('messages.attendances', 'Présences') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coachesTop as $index => $c)
                <tr>
                    <td class="mono">{{ $index + 1 }}</td>
                    <td class="rtl">{{ $c->name ?? '-' }}</td>
                    <td class="text-center mono">{{ $c->training_sessions_count ?? 0 }}</td>
                    <td class="text-center mono">{{ $c->attendances_count ?? 0 }}</td>
                </tr>
                @empty
                <tr class="empty-row">
                    <td colspan="4">{{ $t('messages.no_coaches', 'Aucun coach') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @endif

        {{-- TOP SALLES --}}
        @if(!empty($show_top_gyms))
        <div class="section-title">{{ $t('messages.top_gyms', 'Top salles') }}</div>

        <table class="report-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ $t('messages.gym', 'Salle') }}</th>
                    <th class="text-center">{{ $t('messages.users', 'Utilisateurs') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gymsTop as $index => $g)
                <tr>
                    <td class="mono">{{ $index + 1 }}</td>
                    <td class="rtl">{{ $g->name ?? '-' }}</td>
                    <td class="text-center mono">{{ $g->users_count ?? 0 }}</td>
                </tr>
                @empty
                <tr class="empty-row">
                    <td colspan="3">{{ $t('messages.no_gyms', 'Aucune salle') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @endif

        {{-- FOOTER --}}
        <div class="footer">
            {{ $t('messages.report_generated_at', 'Rapport généré automatiquement le') }}
            {{ date('d/m/Y à H:i') }}
            — {{ $t('messages.period', 'Période') }} : {{ $month }}
            @if(!empty($selectedGymName))
            — {{ $t('messages.gym', 'Salle') }} : {{ $selectedGymName }}
            @endif
        </div>

    </div>
</body>

</html>