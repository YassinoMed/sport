@extends('layouts.user-layout')
@section('content')
<div class="content-wrapper pb-4">
  <section class="content">
    <form action="{{ route('expense-subcategories.update', $subcategory->id) }}" method="post" class="w-75 m-auto">
      @csrf
      @method('PUT')
      <div class="card card-primary">
        <div class="card-header"><h3 class="card-title">Modifier sous-catégorie</h3></div>
        <div class="card-body">
          <div class="form-group"><label for="expense_category_id">Catégorie</label>
            <select id="expense_category_id" name="expense_category_id" class="form-control" required>
              @foreach($categories as $c)
                <option value="{{ $c->id }}" {{ (string)$subcategory->expense_category_id === (string)$c->id ? 'selected' : '' }}>{{ $c->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group"><label for="name">Nom</label><input id="name" name="name" class="form-control" value="{{ $subcategory->name }}" required></div>
          <div class="form-group"><label for="description">Description</label><input id="description" name="description" class="form-control" value="{{ $subcategory->description }}"></div>
        </div>
      </div>
      <div class="row"><div class="col-12"><a href="{{ route('expense-subcategories.index') }}" class="btn btn-secondary">Annuler</a><input type="submit" value="Enregistrer" class="btn btn-success float-right"></div></div>
    </form>
  </section>
</div>
@endsection

