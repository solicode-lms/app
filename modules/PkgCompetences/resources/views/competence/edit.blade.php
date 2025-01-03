{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgCompetences::competence.singular'))

@section('script')
@parent
<script>
    window.contextState = @json($contextState);
 </script>
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: true,
        entity_name: 'competence',
        crudSelector: '#card-tab-competence',
        indexUrl: '{{ route('competences.index') }}', 
        createUrl: '{{ route('competences.create') }}',
        editUrl: '{{ route('competences.edit', ['competence' => ':id']) }}',
        showUrl: '{{ route('competences.show', ['competence' => ':id']) }}',
        storeUrl: '{{ route('competences.store') }}', 
        deleteUrl: '{{ route('competences.destroy', ['competence' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::competence.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::competence.singular") }}',
    });
</script>
@endsection


@section('content')
    <div class="content-header">
    <!-- debug
    @foreach ($contextState as $key => $value)
    Key: {{ $key }}, Value: {{ $value }}<br>
    @endforeach
     -->

    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-competence" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-competence-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-brain"></i>
                                {{ __('Core::msg.edit') }}
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="competence-hasmany-tabs-home-tab" data-toggle="pill" href="#competence-hasmany-tabs-home" role="tab" aria-controls="competence-hasmany-tabs-home" aria-selected="true">{{__('PkgCompetences::competence.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="competence-hasmany-tabs-niveauCompetence-tab" data-toggle="pill" href="#competence-hasmany-tabs-niveauCompetence" role="tab" aria-controls="competence-hasmany-tabs-niveauCompetence" aria-selected="false">{{__('PkgCompetences::niveauCompetence.plural')}}</a>
                        </li>

                       
                        </ul>
                         <button type="button" class="btn btn-info btn-sm btn-card-header">
                            <i class="fa fa-check"></i>
                                Enregistrer
                         </button>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-competence-tabContent">
                            <div class="tab-pane fade show active" id="competence-hasmany-tabs-home" role="tabpanel" aria-labelledby="competence-hasmany-tabs-home-tab">
                                @include('PkgCompetences::competence._fields')
                            </div>

                            <div class="tab-pane fade" id="competence-hasmany-tabs-niveauCompetence" role="tabpanel" aria-labelledby="competence-hasmany-tabs-niveauCompetence-tab">
                                @include('PkgCompetences::niveauCompetence._index')
                            </div>

                           
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                </div>
            </div>
        </div>
    </section>
@endsection
