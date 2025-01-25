{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('Core::sysColor.singular'))

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: true,
        page: "edit",
        entity_name: 'sysColor',
        filterFormSelector: '#sysColor-crud-filter-form',
        crudSelector: '#card-tab-sysColor', 
        formSelector: '#sysColorForm',
        editUrl: '{{ route('sysColors.edit',  ['sysColor' => ':id']) }}',
        indexUrl: '{{ route('sysColors.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.add") . " : " . __("Core::sysColor.singular") }}',
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
                <div id="card-tab-sysColor" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-sysColor-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-table"></i>
                                {{ __('Core::msg.edit') }}
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="sysColor-hasmany-tabs-home-tab" data-toggle="pill" href="#sysColor-hasmany-tabs-home" role="tab" aria-controls="sysColor-hasmany-tabs-home" aria-selected="true">{{__('Core::sysColor.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="sysColor-hasmany-tabs-sysModel-tab" data-toggle="pill" href="#sysColor-hasmany-tabs-sysModel" role="tab" aria-controls="sysColor-hasmany-tabs-sysModel" aria-selected="false">{{__('Core::sysModel.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="sysColor-hasmany-tabs-sysModule-tab" data-toggle="pill" href="#sysColor-hasmany-tabs-sysModule" role="tab" aria-controls="sysColor-hasmany-tabs-sysModule" aria-selected="false">{{__('Core::sysModule.plural')}}</a>
                        </li>

                       
                        </ul>
                         <button type="button" class="btn btn-info btn-sm btn-card-header">
                            <i class="fa fa-check"></i>
                                Enregistrer
                         </button>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-sysColor-tabContent">
                            <div class="tab-pane fade show active" id="sysColor-hasmany-tabs-home" role="tabpanel" aria-labelledby="sysColor-hasmany-tabs-home-tab">
                                @include('Core::sysColor._fields')
                            </div>

                            <div class="tab-pane fade" id="sysColor-hasmany-tabs-sysModel" role="tabpanel" aria-labelledby="sysColor-hasmany-tabs-sysModel-tab">
                                @include('Core::sysModel._index')
                            </div>
                            <div class="tab-pane fade" id="sysColor-hasmany-tabs-sysModule" role="tabpanel" aria-labelledby="sysColor-hasmany-tabs-sysModule-tab">
                                @include('Core::sysModule._index')
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
