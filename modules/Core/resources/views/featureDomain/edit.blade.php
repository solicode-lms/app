{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('Core::featureDomain.singular'))

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: true,
        page: "edit",
        entity_name: 'featureDomain',
        filterFormSelector: '#featureDomain-crud-filter-form',
        crudSelector: '#card-tab-featureDomain', 
        formSelector: '#featureDomainForm',
        editUrl: '{{ route('featureDomains.edit',  ['featureDomain' => ':id']) }}',
        indexUrl: '{{ route('featureDomains.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.add") . " : " . __("Core::featureDomain.singular") }}',
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
                <div id="card-tab-featureDomain" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-featureDomain-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-table"></i>
                                {{ __('Core::msg.edit') }}
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="featureDomain-hasmany-tabs-home-tab" data-toggle="pill" href="#featureDomain-hasmany-tabs-home" role="tab" aria-controls="featureDomain-hasmany-tabs-home" aria-selected="true">{{__('Core::featureDomain.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="featureDomain-hasmany-tabs-feature-tab" data-toggle="pill" href="#featureDomain-hasmany-tabs-feature" role="tab" aria-controls="featureDomain-hasmany-tabs-feature" aria-selected="false">{{__('Core::feature.plural')}}</a>
                        </li>

                       
                        </ul>
                         <button type="button" class="btn btn-info btn-sm btn-card-header">
                            <i class="fa fa-check"></i>
                                Enregistrer
                         </button>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-featureDomain-tabContent">
                            <div class="tab-pane fade show active" id="featureDomain-hasmany-tabs-home" role="tabpanel" aria-labelledby="featureDomain-hasmany-tabs-home-tab">
                                @include('Core::featureDomain._fields')
                            </div>

                            <div class="tab-pane fade" id="featureDomain-hasmany-tabs-feature" role="tabpanel" aria-labelledby="featureDomain-hasmany-tabs-feature-tab">
                                @include('Core::feature._index',['isMany' => true, "edit_has_many" => false])
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
