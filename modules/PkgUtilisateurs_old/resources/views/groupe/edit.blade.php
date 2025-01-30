{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgUtilisateurs::groupe.singular'))

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: true,
        page: "edit",
        entity_name: 'groupe',
        filterFormSelector: '#groupe-crud-filter-form',
        crudSelector: '#card-tab-groupe', 
        formSelector: '#groupeForm',
        editUrl: '{{ route('groupes.edit',  ['groupe' => ':id']) }}',
        indexUrl: '{{ route('groupes.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::groupe.singular") }}',
    });
</script>
@endpush


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
                <div id="card-tab-groupe" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-groupe-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-cubes"></i>
                                {{ __('Core::msg.edit') }}
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="groupe-hasmany-tabs-home-tab" data-toggle="pill" href="#groupe-hasmany-tabs-home" role="tab" aria-controls="groupe-hasmany-tabs-home" aria-selected="true">{{__('PkgUtilisateurs::groupe.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="groupe-hasmany-tabs-apprenant-tab" data-toggle="pill" href="#groupe-hasmany-tabs-apprenant" role="tab" aria-controls="groupe-hasmany-tabs-apprenant" aria-selected="false">{{__('PkgUtilisateurs::apprenant.plural')}}</a>
                        </li>

                       
                        </ul>
                         <button type="button" class="btn btn-info btn-sm btn-card-header">
                            <i class="fa fa-check"></i>
                                Enregistrer
                         </button>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-groupe-tabContent">
                            <div class="tab-pane fade show active" id="groupe-hasmany-tabs-home" role="tabpanel" aria-labelledby="groupe-hasmany-tabs-home-tab">
                                @include('PkgUtilisateurs::groupe._fields')
                            </div>

                            <div class="tab-pane fade" id="groupe-hasmany-tabs-apprenant" role="tabpanel" aria-labelledby="groupe-hasmany-tabs-apprenant-tab">
                                @include('PkgUtilisateurs::apprenant._index',['isMany' => true])
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
