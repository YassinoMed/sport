@extends('layouts.user-layout')
@section('content')
<div class="content-wrapper pb-4">
  <section class="content-header"><div class="container-fluid"><h4><i class="fas fa-plus mr-2"></i>{{ __('messages.new_test') }}</h4></div></section>
  <section class="content">
    <form method="POST" action="{{ route('athlete-tests.manage.store') }}" class="w-75 m-auto">
      @csrf
      <div class="card">
        <div class="card-header"><h3 class="card-title">{{ __('messages.details') }}</h3></div>
        <div class="card-body">
          <div class="form-row">
            <div class="form-group col-md-6"><label>{{ __('messages.name_fr') }}</label><input type="text" name="name_fr" class="form-control" required></div>
            <div class="form-group col-md-6"><label>{{ __('messages.name_ar') }}</label><input type="text" name="name_ar" class="form-control"></div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-4"><label>{{ __('messages.category') }}</label><input type="text" name="category" class="form-control" placeholder="Physique/Technique/..." required></div>
            <div class="form-group col-md-4"><label>{{ __('messages.unit') }}</label><input type="text" name="unit" class="form-control" placeholder="s, kg, cm, %"></div>
            <div class="form-group col-md-4"><label>{{ __('messages.description') }}</label><input type="text" name="description" class="form-control"></div>
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