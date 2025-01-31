{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_add_label('PkgCreationProjet::projet'))


@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: false,
        page: "create",
        entity_name: 'projet',
        crudSelector: '#projetForm', 
        formSelector: '#projetForm',
        indexUrl: '{{ route('projets.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgCreationProjet::projet.singular") }}',
    });
</script>
@endpush

@section('content')
    <div class="content-header">
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-table"></i>
                                {{ curd_index_add_label('PkgCreationProjet::projet') }}
                            </h3>
                        </div>
                        <!-- Obtenir le formulaire -->
                        @include('PkgCreationProjet::projet._fields')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
