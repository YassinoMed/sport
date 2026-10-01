@extends('layouts.user-layout')
@section('content')
<div class="content-wrapper pb-4">
  <section class="content-header">
    <div class="container-fluid">
      <div class="d-flex justify-content-between align-items-center">
        <h4><i class="fas fa-vial mr-2"></i>{{ __('messages.test_tracking') }} — {{ $user->name }}</h4>
        <a href="{{ route('athlete-tests.create', $user->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> {{ __('messages.new_result') }}</a>
      </div>
    </div>
  </section>
  <section class="content">
    <div class="card mb-3 card-modern">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0"><i class="fas fa-chart-line mr-2"></i>Progression</h3>
        <span class="text-muted small">{{ $progressChart['test_name'] ?? '' }}{{ isset($progressChart['unit']) && $progressChart['unit'] ? ' (' . $progressChart['unit'] . ')' : '' }}</span>
      </div>
      <div class="card-body">
        @php
          $labels = $progressChart['labels'] ?? [];
          $values = $progressChart['values'] ?? [];
        @endphp
        @if(count($labels) >= 2 && count($values) >= 2)
          <canvas id="testProgressChart" height="90"></canvas>
        @else
          <div class="text-center text-muted py-3">{{ __('messages.no_data') }}</div>
        @endif
      </div>
    </div>
    <div class="card mb-3 card-modern">
      <div class="card-header"><h3 class="card-title"><i class="fas fa-chart-line mr-2"></i>{{ __('messages.latest_results_by_test') }}</h3></div>
      <div class="card-body p-0">
        <table class="table table-striped mb-0">
          <thead>
            <tr>
              <th><i class="fas fa-layer-group mr-1"></i>{{ __('messages.category') }}</th>
              <th><i class="fas fa-flask mr-1"></i>{{ __('messages.test') }}</th>
              <th><i class="fas fa-balance-scale mr-1"></i>{{ __('messages.value') }}</th>
              <th><i class="fas fa-ruler mr-1"></i>{{ __('messages.unit') }}</th>
              <th><i class="fas fa-calendar-alt mr-1"></i>{{ __('messages.date') }}</th>
            </tr>
          </thead>
          <tbody>
            @forelse(($latestByTest ?? []) as $r)
              <tr>
                <td><span class="badge badge-info">{{ optional($r->test)->category }}</span></td>
                <td>{{ optional($r->test)->name_fr }}</td>
                <td>{{ $r->value }}</td>
                <td>{{ optional($r->test)->unit ?? '-' }}</td>
                <td>{{ optional($r->measured_at)->format('d/m/Y') }}</td>
              </tr>
            @empty
              <tr><td colspan="5" class="text-center text-muted">{{ __('messages.no_data') }}</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    <div class="card card-modern">
      <div class="card-header"><h3 class="card-title"><i class="fas fa-clipboard-list mr-2"></i>{{ __('messages.history') }}</h3></div>
      <div class="card-body">
        <form method="GET" action="{{ route('athlete-tests.user', $user->id) }}" class="form-inline mb-3">
          <div class="form-group mr-2">
            <label class="mr-2"><i class="fas fa-layer-group mr-1"></i>{{ __('messages.category') }}</label>
            <select name="category" class="form-control">
              <option value="">{{ __('messages.all') }}</option>
              @foreach($tests->pluck('category')->unique() as $cat)
                <option value="{{ $cat }}" {{ ($filters['category'] ?? '') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group mr-2">
            <label class="mr-2"><i class="fas fa-flask mr-1"></i>{{ __('messages.test') }}</label>
            <select name="athlete_test_id" class="form-control">
              <option value="">{{ __('messages.all') }}</option>
              @foreach($tests as $t)
                <option value="{{ $t->id }}" {{ (string)($filters['athlete_test_id'] ?? '') === (string)$t->id ? 'selected' : '' }}>{{ $t->name_fr }}</option>
              @endforeach
            </select>
          </div>
          <button type="submit" class="btn btn-primary"><i class="fas fa-filter mr-1"></i>{{ __('messages.filter') }}</button>
        </form>
        <div class="table-responsive">
        <table class="table table-striped mb-0">
          <thead>
            <tr>
              <th><i class="fas fa-layer-group mr-1"></i>{{ __('messages.category') }}</th>
              <th><i class="fas fa-flask mr-1"></i>{{ __('messages.test') }}</th>
              <th><i class="fas fa-balance-scale mr-1"></i>{{ __('messages.value') }}</th>
              <th><i class="fas fa-ruler mr-1"></i>{{ __('messages.unit') }}</th>
              <th><i class="fas fa-calendar-alt mr-1"></i>{{ __('messages.date') }}</th>
              <th><i class="fas fa-sticky-note mr-1"></i>{{ __('messages.notes') }}</th>
            </tr>
          </thead>
          <tbody>
            @forelse($results as $r)
              <tr>
                <td><span class="badge badge-info">{{ optional($r->test)->category }}</span></td>
                <td>{{ optional($r->test)->name_fr }}</td>
                <td>{{ $r->value }}</td>
                <td>{{ optional($r->test)->unit ?? '-' }}</td>
                <td>{{ optional($r->measured_at)->format('d/m/Y') }}</td>
                <td>{{ $r->notes ?? '-' }}</td>
              </tr>
            @empty
              <tr><td colspan="6" class="text-center text-muted">{{ __('messages.no_data') }}</td></tr>
            @endforelse
          </tbody>
        </table>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection

@push('scripts')
<script>
  (function () {
    const el = document.getElementById('testProgressChart');
    if (!el) return;

    const labels = @json($progressChart['labels'] ?? []);
    const values = @json($progressChart['values'] ?? []);

    const styles = getComputedStyle(document.body);
    const primary = styles.getPropertyValue('--primary').trim() || '#667eea';
    const text = document.body.classList.contains('dark-mode') ? 'rgba(255,255,255,0.85)' : 'rgba(45,55,72,0.85)';
    const grid = document.body.classList.contains('dark-mode') ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.06)';

    new Chart(el, {
      type: 'line',
      data: {
        labels,
        datasets: [{
          label: @json($progressChart['test_name'] ?? 'Progression'),
          data: values,
          borderColor: primary,
          backgroundColor: 'rgba(102, 126, 234, 0.12)',
          fill: true,
          tension: 0.25,
          pointRadius: 3,
          pointHoverRadius: 4
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            labels: { color: text }
          },
          tooltip: {
            callbacks: {
              label: function (ctx) {
                const unit = @json($progressChart['unit'] ?? '');
                const v = ctx.parsed.y;
                return unit ? `${v} ${unit}` : `${v}`;
              }
            }
          }
        },
        scales: {
          x: {
            ticks: { color: text },
            grid: { color: grid }
          },
          y: {
            ticks: { color: text },
            grid: { color: grid }
          }
        }
      }
    });
  })();
</script>
@endpush
