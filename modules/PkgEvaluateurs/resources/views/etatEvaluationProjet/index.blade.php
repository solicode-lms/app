{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgEvaluateurs::etatEvaluationProjet'))
@section('content')
    @include('PkgEvaluateurs::etatEvaluationProjet._index')
@endsection
