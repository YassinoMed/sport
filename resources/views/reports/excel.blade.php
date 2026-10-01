<table>
    <tr>
        <td colspan="4"><strong>{{ __('messages.revenue_attendance_report') }} ({{ $month }})</strong></td>
    </tr>
    <tr>
        <td colspan="4">{{ __('messages.gym') }}: {{ optional($gyms->firstWhere('id', $selectedGymId))->name ?? __('messages.all') }}</td>
    </tr>
    <tr>
        <td colspan="4">{{ __('messages.edition_date') }}: {{ date('d/m/Y H:i') }}</td>
    </tr>

    {{-- ── SUMMARY ──────────────────────────────────────────── --}}
    @if(($show_summary ?? true) && !empty($summary))
    <tr><td colspan="4"></td></tr>
    <tr>
        <td colspan="4"><strong>{{ __('messages.global_summary') }}</strong></td>
    </tr>
    <tr>
        <td>{{ __('messages.users') }}</td>
        <td>{{ __('messages.active_users') }}</td>
        <td>{{ __('messages.coaches') }}</td>
        <td>{{ __('messages.gyms') }}</td>
    </tr>
    <tr>
        <td>{{ $summary['users_total'] ?? 0 }}</td>
        <td>{{ $summary['users_active'] ?? 0 }}</td>
        <td>{{ $summary['coaches_total'] ?? 0 }}</td>
        <td>{{ $summary['gyms_total'] ?? 0 }}</td>
    </tr>
    <tr>
        <td>{{ __('messages.revenues') }} (TND)</td>
        <td>{{ __('messages.expenses') }} (TND)</td>
        <td>{{ __('messages.net_revenue') }} (TND)</td>
        <td></td>
    </tr>
    <tr>
        <td>{{ number_format(($summary['revenue_total_cents'] ?? 0)/100, 2, ',', ' ') }}</td>
        <td>{{ number_format(($summary['expense_total_cents'] ?? 0)/100, 2, ',', ' ') }}</td>
        <td>{{ number_format(($summary['net_total_cents'] ?? 0)/100, 2, ',', ' ') }}</td>
        <td></td>
    </tr>
    @endif

    {{-- ── REVENUE BY SOURCE ────────────────────────────────── --}}
    @if($show_revenue ?? true)
    <tr><td colspan="4"></td></tr>
    <tr>
        <td colspan="4"><strong>{{ __('messages.total_revenue_source') }}</strong></td>
    </tr>
    <tr>
        <th>{{ __('messages.source') }}</th>
        <th>{{ __('messages.transactions') }}</th>
        <th>{{ __('messages.amount_tnd') }}</th>
        <th>{{ __('messages.cash_register') ?? 'Caisse' }}</th>
    </tr>
    @foreach($revenue['sources'] as $src)
    <tr>
        <td>{{ $src['label'] }}</td>
        <td>{{ $src['transactions'] }}</td>
        <td>{{ number_format($src['amount_cents']/100, 2, ',', ' ') }}</td>
        <td>{{ optional($cashRegisters->firstWhere('id', $selectedCashRegisterId))->name ?? '-' }}</td>
    </tr>
    @endforeach
    <tr>
        <td><strong>{{ __('messages.total') }}</strong></td>
        <td><strong>{{ collect($revenue['sources'])->sum('transactions') }}</strong></td>
        <td><strong>{{ number_format($revenue['total_cents']/100, 2, ',', ' ') }}</strong></td>
        <td></td>
    </tr>
    @endif

    {{-- ── EXPENSES BY CATEGORY ─────────────────────────────── --}}
    @if($show_expenses ?? true)
    <tr><td colspan="4"></td></tr>
    <tr>
        <td colspan="4"><strong>{{ __('messages.expenses_by_category') }}</strong></td>
    </tr>
    <tr>
        <th>{{ __('messages.category') }}</th>
        <th>{{ __('messages.amount_tnd') }}</th>
        <th></th>
        <th></th>
    </tr>
    @forelse(($expense['by_category'] ?? []) as $row)
    <tr>
        <td>{{ $row['label'] }}</td>
        <td>{{ number_format(($row['amount_cents'] ?? 0)/100, 2, ',', ' ') }}</td>
        <td></td>
        <td></td>
    </tr>
    @empty
    <tr>
        <td colspan="4">{{ __('messages.no_expenses') }}</td>
    </tr>
    @endforelse
    <tr>
        <td><strong>{{ __('messages.total') }}</strong></td>
        <td><strong>{{ number_format(($expense['total_cents'] ?? 0)/100, 2, ',', ' ') }}</strong></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td><strong>{{ __('messages.net_revenue') }}</strong></td>
        <td><strong>{{ number_format(($expense['net_total_cents'] ?? 0)/100, 2, ',', ' ') }}</strong></td>
        <td></td>
        <td></td>
    </tr>
    @endif

    {{-- ── ATTENDANCE BY COURSE ─────────────────────────────── --}}
    @if($show_attendance ?? true)
    <tr><td colspan="8"></td></tr>
    <tr>
        <td colspan="8"><strong>{{ __('messages.attendance_by_course') }}</strong></td>
    </tr>
    <tr>
        <th>{{ __('messages.course') }}</th>
        <th>Coach</th>
        <th>{{ __('messages.gym') }}</th>
        <th>{{ __('messages.sessions') }}</th>
        <th>{{ __('messages.presents') }}</th>
        <th>{{ __('messages.absents') }}</th>
        <th>{{ __('messages.excused') }}</th>
        <th>{{ __('messages.rate_percent') }}</th>
    </tr>
    @forelse($attendance as $row)
    <tr>
        <td>{{ $row['course_name'] }}</td>
        <td>{{ $row['coach_names'] ?? '-' }}</td>
        <td>{{ $row['gym_name'] ?? '-' }}</td>
        <td>{{ $row['total_sessions'] }}</td>
        <td>{{ $row['presents'] }}</td>
        <td>{{ $row['absents'] }}</td>
        <td>{{ $row['excused'] }}</td>
        <td>{{ number_format($row['presence_rate'], 2, ',', ' ') }}%</td>
    </tr>
    @empty
    <tr>
        <td colspan="8">{{ __('messages.no_data') ?? 'Aucune donnée' }}</td>
    </tr>
    @endforelse
    @endif

    {{-- ── COACH PAYMENTS ───────────────────────────────────── --}}
    @if($show_coach_pay ?? true)
    <tr><td colspan="4"></td></tr>
    <tr>
        <td colspan="4"><strong>{{ __('messages.coach_payments') }}</strong></td>
    </tr>
    <tr>
        <td>{{ __('messages.transactions') }}</td>
        <td>{{ __('messages.total_amount') }} (TND)</td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>{{ $payments['coaches']['transactions'] ?? 0 }}</td>
        <td>{{ number_format(($payments['coaches']['total_cents'] ?? 0)/100, 2, ',', ' ') }}</td>
        <td></td>
        <td></td>
    </tr>
    @endif

    {{-- ── USER PAYMENTS ────────────────────────────────────── --}}
    @if($show_user_pay ?? true)
    <tr><td colspan="5"></td></tr>
    <tr>
        <td colspan="5"><strong>{{ __('messages.user_payments') }}</strong></td>
    </tr>
    <tr>
        <td>{{ __('messages.total_amount') }} (TND)</td>
        <td>{{ __('messages.paid_tnd') }}</td>
        <td>{{ __('messages.paid_count_label') }}</td>
        <td>{{ __('messages.due_count_label') }}</td>
        <td>{{ __('messages.cash_register') ?? 'Caisse' }}</td>
    </tr>
    <tr>
        <td>{{ number_format(($payments['users']['total_cents'] ?? 0)/100, 2, ',', ' ') }}</td>
        <td>{{ number_format(($payments['users']['paid_cents'] ?? 0)/100, 2, ',', ' ') }}</td>
        <td>{{ $payments['users']['paid_count'] ?? 0 }}</td>
        <td>{{ $payments['users']['due_count'] ?? 0 }}</td>
        <td>{{ optional($cashRegisters->firstWhere('id', $selectedCashRegisterId))->name ?? __('messages.all') }}</td>
    </tr>
    @endif

    {{-- ── TOP COACHES ──────────────────────────────────────── --}}
    @if($show_top_coaches ?? true)
    <tr><td colspan="4"></td></tr>
    <tr>
        <td colspan="4"><strong>{{ __('messages.top_coaches') }}</strong></td>
    </tr>
    <tr>
        <th>#</th>
        <th>{{ __('messages.name') }}</th>
        <th>{{ __('messages.sessions') }}</th>
        <th>{{ __('messages.attendances') }}</th>
    </tr>
    @forelse(($coachesTop ?? []) as $index => $c)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $c->name }}</td>
        <td>{{ $c->training_sessions_count ?? 0 }}</td>
        <td>{{ $c->attendances_count ?? 0 }}</td>
    </tr>
    @empty
    <tr>
        <td colspan="4">{{ __('messages.no_coaches') ?? 'Aucun coach' }}</td>
    </tr>
    @endforelse
    @endif

    {{-- ── TOP GYMS ─────────────────────────────────────────── --}}
    @if($show_top_gyms ?? true)
    <tr><td colspan="3"></td></tr>
    <tr>
        <td colspan="3"><strong>{{ __('messages.top_gyms') ?? 'Top Salles' }}</strong></td>
    </tr>
    <tr>
        <th>#</th>
        <th>{{ __('messages.gym') ?? 'Salle' }}</th>
        <th>{{ __('messages.users') ?? 'Utilisateurs' }}</th>
    </tr>
    @forelse(($gymsTop ?? []) as $index => $g)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $g->name }}</td>
        <td>{{ $g->users_count ?? 0 }}</td>
    </tr>
    @empty
    <tr>
        <td colspan="3">{{ __('messages.no_gyms') ?? 'Aucune salle' }}</td>
    </tr>
    @endforelse
    @endif
</table>
