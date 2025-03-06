{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgGestionTaches::workflowTache'))
@section('content')
    @include('PkgGestionTaches::workflowTache._index')
@endsection
