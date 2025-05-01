{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgNotification::notification'))
@section('content')
    @include('PkgNotification::notification._index')
@endsection
