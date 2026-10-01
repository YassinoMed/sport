@extends('layouts.user-layout')
@section('content')
<div class="content-wrapper pb-4">
  <section class="content-header"><div class="container-fluid"><h4>{{ __('messages.add_result') }} — {{ $user->name }}</h4></div></section>
  <section class="content">
    <form method="POST" action="{{ route('athlete-tests.store', $user->id) }}" class="w-75 m-auto">
      @csrf
      <div class="card">
        <div class="card-header"><h3 class="card-title">{{ __('messages.test_result') }}</h3></div>
        <div class="card-body">
          <div class="form-group">
            <label>{{ __('messages.test') }}</label>
            <select name="athlete_test_id" class="form-control" required>
              @foreach($tests as $t)
                <option value="{{ $t->id }}">[{{ $t->category }}] {{ $t->name_fr }} {{ $t->name_ar ? ' / '.$t->name_ar : '' }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label>{{ __('messages.value') }}</label>
              <input type="text" name="value" class="form-control" required>
            </div>
            <div class="form-group col-md-6">
              <label>{{ __('messages.date') }}</label>
              <input type="date" name="measured_at" class="form-control" required>
            </div>
          </div>
          <div class="form-group">
            <label>{{ __('messages.notes') }}</label>
            <textarea name="notes" class="form-control" rows="3"></textarea>
          </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('athlete-tests.user', $user->id) }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
          <button type="submit" class="btn btn-success float-right">{{ __('messages.save') }}</button>
        </div>
      </div>
    </form>
  </section>
</div>
@endsection