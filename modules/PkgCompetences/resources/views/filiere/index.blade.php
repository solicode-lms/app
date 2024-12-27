@extends('layouts.admin')
@section('title', curd_index_title('PkgCompetences::filiere'))
@section('script')
<script>

document.addEventListener("DOMContentLoaded", function () {
   
    const filiereCrud = new GenericCrud({
        fetchUrl: '{{ route('filieres.index') }}', // Utilise la méthode `index` pour charger le HTML
        storeUrl: '{{ route('filieres.store') }}', // URL pour ajouter une filière
        deleteUrl: '{{ route('filieres.destroy', ['filiere' => ':id']) }}', // Placeholder :id pour suppression
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        tableSelector: '#filieresTable', // Sélecteur du tableau HTML
        formSelector: '#filiereForm',   // Sélecteur du formulaire
        modalSelector: '#filiereModal'  // Sélecteur du modal
    });

    filiereCrud.init(); // Initialisation des fonctionnalités CRUD

});


</script>
@endsection
@section('content')
    @include('PkgCompetences::filiere._index')
@endsection
