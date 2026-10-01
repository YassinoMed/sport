@extends('layouts.user-layout')
@section('content')
<div class="content-wrapper pb-4">
  <section class="content-header"><div class="container-fluid"><h4>{{ __('messages.test_catalog') }}</h4></div></section>
  <section class="content">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-flask mr-1"></i>{{ __('messages.tests_available') }}</span>
        <div>
          <a href="{{ route('athlete-tests.manage.create') }}" class="btn btn-primary btn-sm mr-2"><i class="fas fa-plus"></i> {{ __('messages.new_test') }}</a>
          <form method="POST" action="{{ route('athlete-tests.seed.replace') }}" class="d-inline mr-2" onsubmit="return confirm('{{ __('messages.replace_catalog_confirm') }}')">
            @csrf
          </form>
          <form method="POST" action="{{ route('athlete-tests.seed') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-outline-primary btn-sm"><i class="fas fa-layer-group"></i> {{ __('messages.add_default') }}</button>
          </form>
        </div>
      </div>
      <div class="card-body p-0">
        <table class="table table-striped mb-0">
          <thead><tr><th>{{ __('messages.category') }}</th><th>{{ __('messages.name_fr') }}</th><th>{{ __('messages.name_ar') }}</th><th>{{ __('messages.unit') }}</th><th>{{ __('messages.actions') }}</th></tr></thead>
          <tbody>
            @forelse($tests as $t)
              <tr>
                <td>{{ $t->category }}</td>
                <td>{{ $t->name_fr }}</td>
                <td>{{ $t->name_ar }}</td>
                <td>{{ $t->unit ?? '-' }}</td>
                <td>
                  <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('athlete-tests.manage.edit', $t->id) }}" class="btn btn-outline-warning" title="{{ __('messages.edit') }}"><i class="fas fa-pencil-alt"></i></a>
                    <form method="POST" action="{{ route('athlete-tests.manage.destroy', $t->id) }}" onsubmit="return confirm('{{ __('messages.confirm_delete') }}')" class="d-inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-outline-danger" title="{{ __('messages.delete') }}"><i class="fas fa-trash"></i></button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr><td colspan="5" class="text-center text-muted">{{ __('messages.no_tests') }}</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    <div class="card mt-3">
      <style>
        .belt-dot{width:12px;height:12px;display:inline-block;border-radius:50%;margin-right:6px;border:1px solid rgba(0,0,0,.2);vertical-align:middle}
      </style>
      <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-medal mr-1"></i>{{ __('messages.belts') }}</span>
        <a href="{{ route('belt.list') }}" class="btn btn-outline-info btn-sm"><i class="fas fa-list"></i> {{ __('messages.see_all') }}</a>
      </div>
      <div class="card-body p-0">
        <table class="table table-striped mb-0" id="beltsTableShort">
          <thead>
            <tr>
              <th>{{ __('messages.athlete') }}</th>
              <th>{{ __('messages.belt') }}</th>
              <th>{{ __('messages.date') }}</th>
              @unless(auth()->user()->hasRole('coach'))
              <th>{{ __('messages.price') }}</th>
              @endunless
              <th>{{ __('messages.actions') }}</th>
            </tr>
          </thead>
          <tbody>
            @forelse(($belts ?? []) as $b)
              <tr>
                <td>{{ optional($b->user)->name ?? '-' }}</td>
                <td>
                  @php
                    $n = strtolower($b->belt_name);
                    $c = '#111827';
                    
                    if (str_contains($n, 'jaune')) $c = '#f6c105';
                    elseif (str_contains($n, 'vert')) $c = '#22c55e';
                    elseif (str_contains($n, 'bleu')) $c = '#3b82f6';
                    elseif (str_contains($n, 'violet')) $c = '#8b5cf6';
                    elseif (str_contains($n, 'rouge')) $c = '#ef4444';
                    elseif (str_contains($n, 'noir')) $c = '#111827';
                    elseif (str_contains($n, 'blanc')) $c = '#f5f5f5';
                    elseif (str_contains($n, 'orange')) $c = '#fb923c';
                    elseif (str_contains($n, 'marron') || str_contains($n, 'brun')) $c = '#92400e';
                  @endphp
                  @php
                    $ar = '';
                    $num = null;
                    preg_match('/(\d+)/', $n, $m);
                    if (!empty($m[1])) { $num = $m[1]; }
                    if (str_contains($n, 'blanc')) $ar = 'حزام أبيض';
                    elseif (str_contains($n, 'jaune')) $ar = 'حزام أصفر' . ($num ? " $num" : '');
                    elseif (str_contains($n, 'vert')) $ar = 'حزام أخضر' . ($num ? " $num" : '');
                    elseif (str_contains($n, 'bleu')) $ar = 'حزام أزرق' . ($num ? " $num" : '');
                    elseif (str_contains($n, 'violet')) $ar = 'حزام بنفسجي' . ($num ? " $num" : '');
                    elseif (str_contains($n, 'rouge')) $ar = 'حزام أحمر' . ($num ? " $num" : '');
                    elseif (str_contains($n, 'noir')) $ar = 'حزام أسود' . ($num ? " درجة $num" : '');
                    elseif (str_contains($n, 'orange')) $ar = 'حزام برتقالي' . ($num ? " $num" : '');
                    elseif (str_contains($n, 'marron') || str_contains($n, 'brun')) $ar = 'حزام بني' . ($num ? " $num" : '');
                  @endphp
                  <span class="belt-dot" style="background-color: {{ $c }}; {{ $c === '#f5f5f5' ? 'border: 2px solid #9ca3af;' : '' }}"></span>
                  <span class="badge bg-light text-dark">{{ $b->belt_name }}{{ $ar ? ' / ' . $ar : '' }}</span>
                </td>
                <td>{{ optional($b->achieved_at)->format('d/m/Y') ?? '-' }}</td>
                @unless(auth()->user()->hasRole('coach'))
                <td>{{ $b->price_cents !== null ? number_format($b->price_cents/100, 2, ',', ' ') : '-' }}</td>
                @endunless
                <td>
                  <a href="{{ route('belt.show', $b->id) }}" class="btn btn-outline-info btn-sm" title="{{ __('messages.show') }}"><i class="fas fa-eye"></i></a>
                </td>
              </tr>
            @empty
              <tr><td colspan="{{ auth()->user()->hasRole('coach') ? 4 : 5 }}" class="text-center text-muted">{{ __('messages.no_belts') }}</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </section>
</div>
@endsection