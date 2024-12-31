
@extends('layouts.admin')
@section('title', curd_index_title('PkgCreationProjet::livrable'))
@section('content')

<p>Scoping : {{ $page['scop_entity'] ?? 'Aucun' }}</p>
<p>ID : {{ $page['scop_id'] ?? 'Aucun' }}</p>

{{-- Générer un lien avec les variables --}}
<a href="{{ route('livrables.create', $page) }}">Créer un Livrable</a>

    @include('PkgCreationProjet::livrable._index')
@endsection
