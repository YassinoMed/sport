<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <title>Rapport - {{ $user->name }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 13px; color: #333; margin: 0; padding: 0; }
        .header { border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 22px; color: #000; }
        .header p { margin: 5px 0 0 0; font-size: 14px; color: #555; }
        .section-title { font-size: 15px; font-weight: bold; background-color: #f4f4f4; padding: 6px 10px; border-left: 4px solid #4f46e5; margin-top: 25px; margin-bottom: 10px; }
        .report-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 12px; }
        .report-table th, .report-table td { border: 1px solid #ccc; padding: 6px; text-align: left; vertical-align: middle; }
        .report-table th { background-color: #eee; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .mono { font-family: monospace; }
        .badge { padding: 3px 6px; border-radius: 3px; font-size: 10px; font-weight: bold; display: inline-block; }
        .bg-success { background-color: #d1fae5; color: #065f46; border: 1px solid #10b981; }
        .bg-danger { background-color: #fee2e2; color: #991b1b; border: 1px solid #ef4444; }
        .page-break { page-break-after: always; }
        @page { margin: 40px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rapport Individuel</h1>
        <p><strong>{{ $user->name }} (ID: #{{ $user->id }})</strong></p>
        <p>Période: {{ $month }} | Généré le: {{ date('d/m/Y H:i') }}</p>
    </div>

    <div class="section-title">Synthèse globale</div>
    <table class="report-table">
        <thead>
            <tr>
                <th>Indicateur</th>
                <th class="text-right">Valeur</th>
                <th>Période</th>
            </tr>
        </thead>
        <tbody>
            @hasanyrole('admin|cityManager|gymManager')
            <tr>
                <td>Revenu Total</td>
                <td class="text-right mono">{{ number_format(($summary['total_cents'] ?? 0)/100, 2, ',', ' ') }} TND</td>
                <td>{{ $month }}</td>
            </tr>
            @endhasanyrole
            <tr>
                <td>Taux de présence</td>
                <td class="text-right mono">{{ $userPresenceRate ?? 0 }}%</td>
                <td>{{ $month }}</td>
            </tr>
            @hasanyrole('admin|cityManager|gymManager')
            <tr>
                <td>Montant dû total</td>
                <td class="text-right mono">{{ number_format(($unpaidTotalCents ?? 0)/100, 2, ',', ' ') }} TND</td>
                <td>Tous les mois</td>
            </tr>
            @endhasanyrole
            <tr>
                <td>Ceintures obtenues</td>
                <td class="text-right mono">{{ count($beltTimeline ?? []) }}</td>
                <td>Historique</td>
            </tr>
            @hasanyrole('admin|cityManager|gymManager')
            <tr>
                <td>Événements payés</td>
                <td class="text-right mono">{{ number_format(($eventSummary['paid_cents'] ?? 0)/100, 2, ',', ' ') }} TND</td>
                <td>{{ $month }}</td>
            </tr>
            @endhasanyrole
        </tbody>
    </table>

    @hasanyrole('admin|cityManager|gymManager')
    <div class="section-title">Évolution des paiements (6 derniers mois)</div>
    <table class="report-table">
        <thead>
            <tr>
                <th>Période</th>
                <th class="text-right">Payé (TND)</th>
                <th class="text-right">Dû (TND)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($paymentHistory ?? [] as $history)
            <tr>
                <td>{{ $history['label'] }}</td>
                <td class="text-right mono">{{ number_format(($history['paid_cents'] ?? 0)/100, 2, ',', ' ') }} TND</td>
                <td class="text-right mono">{{ number_format(($history['due_cents'] ?? 0)/100, 2, ',', ' ') }} TND</td>
            </tr>
            @empty
            <tr><td colspan="3" class="text-center">Aucune donnée</td></tr>
            @endforelse
        </tbody>
    </table>
    @endhasanyrole

    <div class="section-title">Évolution des présences (6 derniers mois)</div>
    <table class="report-table">
        <thead>
            <tr>
                <th>Période</th>
                <th class="text-right">Taux de présence</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendanceHistory ?? [] as $history)
            <tr>
                <td>{{ $history['label'] }}</td>
                <td class="text-right mono">{{ $history['rate'] }}%</td>
            </tr>
            @empty
            <tr><td colspan="2" class="text-center">Aucune donnée</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">Progression des ceintures</div>
    <table class="report-table">
        <thead>
            <tr>
                <th>Ceinture</th>
                <th>Date</th>
                @hasanyrole('admin|cityManager|gymManager')
                <th class="text-right">Prix (TND)</th>
                @endhasanyrole
            </tr>
        </thead>
        <tbody>
            @forelse($beltTimeline ?? [] as $belt)
            <tr>
                <td>{{ $belt['name'] }}</td>
                <td>{{ $belt['date'] ?? '---' }}</td>
                @hasanyrole('admin|cityManager|gymManager')
                <td class="text-right mono">{{ $belt['price_cents'] !== null ? number_format($belt['price_cents']/100, 2, ',', ' ') . ' TND' : '---' }}</td>
                @endhasanyrole
            </tr>
            @empty
            <tr><td colspan="3" class="text-center">Aucune ceinture obtenue</td></tr>
            @endforelse
        </tbody>
    </table>

    @hasanyrole('admin|cityManager|gymManager')
    <div class="section-title">Détail des paiements ({{ $month }})</div>
    <table class="report-table">
        <thead>
            <tr>
                <th>Mois de facturation</th>
                <th class="text-right">Montant</th>
                <th>Statut</th>
                <th>Méthode</th>
                <th>Date paiement</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments ?? [] as $payment)
            <tr>
                <td>{{ \Carbon\Carbon::parse($payment->billing_month)->format('M Y') }}</td>
                <td class="text-right mono">{{ number_format(($payment->amount_cents ?? 0)/100, 2, ',', ' ') }} TND</td>
                <td>
                    @if($payment->status === 'paid')
                        <span class="badge bg-success">Payé</span>
                    @else
                        <span class="badge bg-danger">Dû</span>
                    @endif
                </td>
                <td>{{ $payment->method ?? '---' }}</td>
                <td>{{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('d/m/Y H:i') : '---' }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center">Aucun paiement</td></tr>
            @endforelse
        </tbody>
    </table>
    @endhasanyrole

    <div class="section-title">Statistiques des Cours Inscrits</div>
    <table class="report-table">
        <thead>
            <tr>
                <th>Cours</th>
                <th class="text-center">Sessions</th>
                <th class="text-center">Présences</th>
                <th class="text-center">Absences</th>
                <th class="text-center">Excusés</th>
                <th class="text-right">Taux</th>
            </tr>
        </thead>
        <tbody>
            @forelse($enrolledCoursesWithPresence ?? [] as $course)
            @php 
                $total = $course['total_sessions'] ?? 0;
                $present = $course['presents'] ?? 0;
                $rate = $total > 0 ? round(($present / $total) * 100, 1) : 0;
            @endphp
            <tr>
                <td>{{ $course['course_name'] }}</td>
                <td class="text-center mono">{{ $total }}</td>
                <td class="text-center mono">{{ $present }}</td>
                <td class="text-center mono">{{ $course['absents'] ?? 0 }}</td>
                <td class="text-center mono">{{ $course['excused'] ?? 0 }}</td>
                <td class="text-right mono">{{ $rate }}%</td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center">Aucun cours inscrit</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">Présences par jour ({{ $month }})</div>
    <table class="report-table">
        <thead>
            <tr>
                <th>Jour</th>
                <th class="text-center">Présents</th>
                <th class="text-center">Absents</th>
                <th class="text-center">Excusés</th>
            </tr>
        </thead>
        <tbody>
            @forelse($courseSessions ?? [] as $session)
            <tr>
                <td>{{ \Carbon\Carbon::parse($session->day)->format('d/m/Y') }}</td>
                <td class="text-center mono">{{ $session->presents ?? 0 }}</td>
                <td class="text-center mono">{{ $session->absents ?? 0 }}</td>
                <td class="text-center mono">{{ $session->excused ?? 0 }}</td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center">Aucune présence</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">Événements ({{ $month }})</div>
    <table class="report-table">
        <thead>
            <tr>
                <th>Événement</th>
                <th>Date</th>
                <th class="text-right">Paiement</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($eventRegistrations ?? [] as $reg)
            @php
                $evt = $reg->event;
                $fee = $evt->registration_fee ?? 0;
                $paid = $reg->paid_amount ?? 0;
                $isPaid = $fee > 0 && $paid >= $fee;
            @endphp
            <tr>
                <td>{{ $evt ? $evt->name : 'Supprimé' }}</td>
                <td>{{ $evt && $evt->start_at ? $evt->start_at->format('d/m/Y H:i') : '---' }}</td>
                <td class="text-right mono">{{ number_format($paid/100, 2, ',', ' ') }} / {{ number_format($fee/100, 2, ',', ' ') }} TND</td>
                <td>
                    @if($isPaid)
                        <span class="badge bg-success">Payé</span>
                    @else
                        <span class="badge bg-danger">{{ $paid > 0 ? 'Partiel' : 'Dû' }}</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center">Aucun événement</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
