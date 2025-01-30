{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgApprenants::apprenantKonosy'))
@section('content')
    @include('PkgApprenants::apprenantKonosy._index')
@endsection
