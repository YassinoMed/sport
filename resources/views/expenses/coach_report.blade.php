@extends('layouts.user-layout')

@section('content')
<style>
  .report-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
  }
  
  .report-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0;
  }
  
  .btn-print {
    background: white;
    color: #667eea;
    border: none;
    padding: 0.6rem 1.5rem;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }
  
  .btn-print:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    color: #764ba2;
  }
  
  .info-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border: none;
    margin-bottom: 1.5rem;
    transition: transform 0.2s;
  }
  
  .info-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
  }
  
  .card-header-custom {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    border: none;
    border-radius: 12px 12px 0 0;
    padding: 1rem 1.5rem;
    margin: -1.5rem -1.5rem 1.5rem -1.5rem;
  }
  
  .card-title-custom {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2d3748;
    margin: 0;
  }
  
  .info-item {
    margin-bottom: 1rem;
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 8px;
    transition: background 0.2s;
  }
  
  .info-item:hover {
    background: #e9ecef;
  }
  
  .info-label {
    font-weight: 600;
    color: #495057;
    display: block;
    margin-bottom: 0.25rem;
  }
  
  .info-value {
    color: #212529;
  }
  
  .stat-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 20px;
    font-weight: 500;
    font-size: 0.95rem;
  }
  
  .table-modern {
    width: 100%;
    margin-bottom: 0;
  }
  
  .table-modern thead th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1rem;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    border: none;
  }
  
  .table-modern tbody tr {
    transition: all 0.2s;
  }
  
  .table-modern tbody tr:hover {
    background: #f8f9fa;
  }
  
  .table-modern tbody td {
    padding: 1rem;
    border-bottom: 1px solid #e9ecef;
  }
  
  .table-modern tfoot th {
    background: #f8f9fa;
    padding: 1rem;
    font-weight: 600;
    border-top: 2px solid #667eea;
  }
  
  .empty-state {
    padding: 3rem 1rem;
    text-align: center;
    color: #6c757d;
  }
  
  .amount-highlight {
    font-weight: 600;
    color: #667eea;
    font-size: 1.05rem;
  }
  
  .total-amount {
    font-size: 1.2rem;
    font-weight: 700;
    color: #764ba2;
  }
  
  @media print {
    .btn-print { display: none; }
    .main-header, .main-footer, .main-sidebar, #sidebar-overlay { display: none !important; }
    .content-wrapper { margin-left: 0 !important; }
    body { margin: 0; font-size: 12px; }
    @page { size: A4; margin: 12mm; }

    .report-header { background: #ffffff !important; color: #000 !important; box-shadow: none !important; border: 1px solid #ddd; }
    .report-title { color: #000 !important; }

    .info-card { box-shadow: none !important; border: 1px solid #ddd; }
    .card-header-custom { background: #efefef !important; color: #000 !important; border: 1px solid #ddd; }
    .card-title-custom { color: #000 !important; }

    .table-modern thead th { background: #efefef !important; color: #000 !important; }
    .table-modern tbody tr:hover { background: transparent !important; }
    .table-modern tfoot th { background: #f7f7f7 !important; color: #000 !important; border-top: 1px solid #ccc; }

    .stat-badge { background: #eeeeee !important; color: #000 !important; border: 1px solid #ccc; }
    .amount-highlight { color: #000 !important; }
    .total-amount { color: #000 !important; }
  }
</style>

<div class="content-wrapper pb-4">
  <section class="content-header">
    <div class="container-fluid">
      <div class="report-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
          <h1 class="report-title">{{ __('messages.coach_report') }} — {{ $coach->name }}</h1>
          <button class="btn-print" onclick="window.print()">🖨️ {{ __('messages.print') }}</button>
        </div>
        <div class="mt-3">
          <span class="stat-badge">📅 {{ $month }}</span>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <!-- Informations principales -->
      <div class="info-card">
        <div class="card-header-custom">
          <h3 class="card-title-custom">ℹ️ {{ __('messages.informations') }}</h3>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="info-item">
              <span class="info-label">👤 {{ __('messages.name') }}:</span>
              <span class="info-value">{{ $coach->name }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">✉️ {{ __('messages.email') }}:</span>
              <span class="info-value">{{ $coach->email }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">📍 {{ __('messages.city') }}:</span>
              <span class="info-value">{{ $coach->city->name ?? '—' }}</span>
            </div>
          </div>
          <div class="col-md-6">
            <div class="info-item">
              <span class="info-label">📅 {{ __('messages.from') }} / {{ __('messages.to') }}:</span>
              <span class="info-value">{{ $start->format('Y-m-d') }} → {{ $end->format('Y-m-d') }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">✅ {{ __('messages.presents') }}:</span>
              <span class="info-value">
                <span class="stat-badge">{{ $presentCount }} {{ __('messages.records') }}</span>
              </span>
            </div>
            <div class="info-item">
              <span class="info-label">❌ {{ __('messages.absents') }}:</span>
              <span class="info-value">
                <span class="stat-badge">{{ $absenceCount }} {{ __('messages.records') }}</span>
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Cours -->
      <div class="info-card">
        <div class="card-header-custom">
          <h3 class="card-title-custom">👨‍🏫 {{ __('messages.courses') }}</h3>
        </div>
        @if(($coachCourses ?? collect())->count() > 0)
          <div class="table-responsive">
            <table class="table-modern">
              <thead>
                <tr>
                  <th>{{ __('messages.name') }}</th>
                  <th>{{ __('messages.gym') }}</th>
                  <th>{{ __('messages.start') }}</th>
                  <th>{{ __('messages.end') }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach(($coachCourses ?? []) as $c)
                  <tr>
                    <td><strong>{{ $c->name }}</strong></td>
                    <td>{{ optional($c->gym)->name ?? '—' }}</td>
                    <td>{{ $c->start_date ? $c->start_date->format('Y-m-d') : '—' }}</td>
                    <td>{{ $c->end_date ? $c->end_date->format('Y-m-d') : '—' }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <div class="empty-state">
            <p>📭 {{ __('messages.no_data') }}</p>
          </div>
        @endif
      </div>

      <!-- Salaire -->
      <div class="info-card">
        <div class="card-header-custom">
          <h3 class="card-title-custom">💰 {{ __('messages.salary_related_expenses') }}</h3>
        </div>
        @if($coachExpenses->count() > 0)
          <div class="table-responsive">
            <table class="table-modern">
              <thead>
                <tr>
                  <th>{{ __('messages.date') }}</th>
                  <th>{{ __('messages.category') }}</th>
                  <th>{{ __('messages.method') }}</th>
                  <th>{{ __('messages.amount') }} (TND)</th>
                  <th>{{ __('messages.notes') }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach($coachExpenses as $e)
                  <tr>
                    <td>{{ $e->expense_date }}</td>
                    <td>{{ $e->category->name ?? '-' }}</td>
                    <td><span class="badge badge-info">{{ $e->method }}</span></td>
                    <td class="amount-highlight">{{ number_format($e->amount_cents/100, 2, ',', ' ') }}</td>
                    <td>{{ $e->notes ?: '—' }}</td>
                  </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="3" class="text-right">{{ __('messages.total') }}</th>
                  <th class="total-amount">{{ number_format($salaryTotalCents/100, 2, ',', ' ') }} TND</th>
                  <th></th>
                </tr>
              </tfoot>
            </table>
          </div>
        @else
          <div class="empty-state">
            <p>🧾 {{ __('messages.no_expenses') }}</p>
          </div>
        @endif
      </div>
    </div>
  </section>
</div>
@endsection
