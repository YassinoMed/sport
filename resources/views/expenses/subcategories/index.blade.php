@extends('layouts.user-layout')
@section('content')
<div class="content-wrapper">
  <section class="content-header"><div class="container-fluid"><h4>Sous-catégories</h4></div></section>
  <section class="content">
    <div class="mb-3"><a href="{{ route('expense-subcategories.create') }}" class="btn btn-primary">Nouvelle sous-catégorie</a></div>
    <div class="card"><div class="card-body p-0">
      <table class="table table-striped">
        <thead><tr><th>Nom</th><th>Catégorie</th><th></th></tr></thead>
        <tbody>
          @foreach($subcategories as $sc)
            <tr>
              <td>{{ $sc->name }}</td>
              <td>{{ $sc->category->name ?? '-' }}</td>
              <td class="text-right">
                <a href="{{ route('expense-subcategories.edit', $sc->id) }}" class="btn btn-sm btn-warning">Modifier</a>
                <form action="{{ route('expense-subcategories.destroy', $sc->id) }}" method="POST" style="display:inline-block">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette sous-catégorie ?')">Supprimer</button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="card-footer">{{ $subcategories->links() }}</div></div>
  </section>
</div>
@endsection

