{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgSessions::livrableSession'))
@section('content')
    @include('PkgSessions::livrableSession._index')
@endsection
