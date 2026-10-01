@extends('layouts.user-layout')
@section('content')
<div class="content-wrapper pb-4">
  <section class="content">
    <form action="{{ route('category.update', $category->id) }}" method="post" class="w-75 m-auto">
      @csrf
      @method('PUT')
      <div class="card card-primary">
        <div class="card-header"><h3 class="card-title">Modifier catégorie / تعديل الصنف</h3></div>
        <div class="card-body">
          <div class="form-group"><label for="name">Nom / الاسم</label><input id="name" name="name" class="form-control" value="{{ $category->name }}" required></div>
          <div class="form-group"><label for="description">Description / الوصف</label><input id="description" name="description" class="form-control" value="{{ $category->description }}"></div>
        </div>
      </div>
      <div class="row"><div class="col-12"><a href="{{ route('category.list') }}" class="btn btn-secondary">Annuler / إلغاء</a><input type="submit" value="Enregistrer / حفظ" class="btn btn-success float-right"></div></div>
    </form>
  </section>
</div>
@endsection

