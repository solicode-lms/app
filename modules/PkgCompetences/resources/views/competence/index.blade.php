{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgCompetences::competence'))
@section('content')
    @include('PkgCompetences::competence._index')
@endsection
