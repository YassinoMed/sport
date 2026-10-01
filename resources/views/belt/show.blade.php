@extends('layouts.user-layout')
@section('content')
<div class="content-wrapper pb-4">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6"><h4>Ceinture #{{ $belt->id }}</h4></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('belt.list') }}">Ceintures</a></li>
            <li class="breadcrumb-item active">Détails</li>
          </ol>
        </div>
      </div>
    </div>
  </section>
  <section class="content">
    <div class="card">
      <div class="card-header"><h3 class="card-title">Informations</h3></div>
      <div class="card-body">
        <dl class="row">
          <dt class="col-sm-3">Athlète</dt>
          <dd class="col-sm-9">{{ optional($belt->user)->name ?? '-' }}</dd>
          <dt class="col-sm-3">Ceinture</dt>
          <dd class="col-sm-9"><span class="badge bg-warning text-dark">{{ $belt->belt_name }}</span></dd>
          <dt class="col-sm-3">Date d'obtention</dt>
          <dd class="col-sm-9">{{ optional($belt->achieved_at)->format('d/m/Y') ?? '-' }}</dd>
          @unless(auth()->user()->hasRole('coach'))
          <dt class="col-sm-3">Prix</dt>
          <dd class="col-sm-9">{{ $belt->price_cents !== null ? number_format($belt->price_cents/100, 2, ',', ' ') . ' TND' : '-' }}</dd>
          @endunless
        </dl>
      </div>
      <div class="card-footer">
        <a href="{{ route('belt.list') }}" class="btn btn-secondary">Retour</a>
        @role('admin')
        <a href="{{ route('belt.edit', $belt->id) }}" class="btn btn-primary">Modifier</a>
        @endrole
      </div>
    </div>

    <div class="card mt-3">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Notes / ملاحظات</h3>
        <a href="{{ route('belt.notes.index', ['belt_name' => $belt->belt_name]) }}" class="btn btn-sm btn-outline-primary">Gérer / إدارة</a>
      </div>
      <div class="card-body">
        @if(($categories ?? collect())->count() === 0)
          <div class="text-muted">Aucune catégorie pour cette ceinture</div>
        @else
          @foreach($categories as $category)
            <div class="mb-3">
              <div class="fw-semibold">{{ $category->name }}</div>

              @if(($category->notes ?? collect())->count() > 0)
                <ul class="mb-2">
                  @foreach($category->notes as $note)
                    <li>{{ $note->content }}</li>
                  @endforeach
                </ul>
              @endif

              @if(($category->subcategories ?? collect())->count() > 0)
                <div class="ms-3">
                  @php
                    $renderSubcategory = function (\App\Models\BeltNoteSubcategory $subcategory, int $level = 0) use (&$renderSubcategory) {
                      $marginClass = $level > 0 ? 'ms-3' : '';
                      echo '<div class="mb-2 ' . $marginClass . '">';
                      echo '<div class="fw-semibold">' . e($subcategory->name) . '</div>';

                      $notes = $subcategory->notes ?? collect();
                      if ($notes->count() > 0) {
                        echo '<ul class="mb-0">';
                        foreach ($notes as $note) {
                          echo '<li>' . e($note->content) . '</li>';
                        }
                        echo '</ul>';
                      }

                      $children = $subcategory->childrenRecursive ?? collect();
                      if ($children->count() > 0) {
                        foreach ($children as $child) {
                          $renderSubcategory($child, $level + 1);
                        }
                      }

                      echo '</div>';
                    };
                  @endphp

                  @foreach($category->subcategories as $subcategory)
                    @php $renderSubcategory($subcategory); @endphp
                  @endforeach
                </div>
              @endif
            </div>
          @endforeach
        @endif
      </div>
    </div>
  </section>
</div>
@endsection
