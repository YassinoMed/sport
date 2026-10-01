@extends('layouts.user-layout')
@section('content')
<div class="content-wrapper pb-4">
  <section class="content-header"><div class="container-fluid"><h4><i class="fas fa-pencil-alt mr-2"></i>{{ __('messages.edit') }}</h4></div></section>
  <section class="content">
    <form method="POST" action="{{ route('athlete-tests.manage.update', $test->id) }}" class="w-75 m-auto">
      @csrf
      @method('PUT')
      <div class="card">
        <div class="card-header"><h3 class="card-title">{{ __('messages.details') }}</h3></div>
        <div class="card-body">
          <div class="form-row">
            <div class="form-group col-md-6"><label>{{ __('messages.name_fr') }}</label><input type="text" name="name_fr" class="form-control" value="{{ $test->name_fr }}" required></div>
            <div class="form-group col-md-6"><label>{{ __('messages.name_ar') }}</label><input type="text" name="name_ar" class="form-control" value="{{ $test->name_ar }}"></div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-4"><label>{{ __('messages.category') }}</label><input type="text" name="category" class="form-control" value="{{ $test->category }}" required></div>
            <div class="form-group col-md-4"><label>{{ __('messages.unit') }}</label><input type="text" name="unit" class="form-control" value="{{ $test->unit }}"></div>
            <div class="form-group col-md-4"><label>{{ __('messages.description') }}</label><input type="text" name="description" class="form-control" value="{{ $test->description }}"></div>
          </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('athlete-tests.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
          <button type="submit" class="btn btn-success float-right">{{ __('messages.save') }}</button>
        </div>
      </div>
    </form>
  </section>
</div>
@endsection