{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('evaluationRealisationTache-table')
<div class="card-body p-0 crud-card-body" id="evaluationRealisationTaches-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $evaluationRealisationTaches_permissions['edit-evaluationRealisationTache'] || $evaluationRealisationTaches_permissions['destroy-evaluationRealisationTache'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="50" field="realisation_tache_id" modelname="evaluationRealisationTache" label="{!!ucfirst(__('PkgEvaluateurs::evaluationRealisationTache.realisation_tache_id'))!!}" />
                <x-sortable-column :sortable="true" width="16"  field="note" modelname="evaluationRealisationTache" label="{!!ucfirst(__('PkgEvaluateurs::evaluationRealisationTache.note'))!!}" />
                <x-sortable-column :sortable="true" width="16"  field="nombre_livrables" modelname="evaluationRealisationTache" label="{!!ucfirst(__('PkgEvaluateurs::evaluationRealisationTache.nombre_livrables'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('evaluationRealisationTache-table-tbody')
            @foreach ($evaluationRealisationTaches_data as $evaluationRealisationTache)
                @php
                    $isEditable = $evaluationRealisationTaches_permissions['edit-evaluationRealisationTache'] && $evaluationRealisationTaches_permissionsByItem['update'][$evaluationRealisationTache->id];
                @endphp
                <tr id="evaluationRealisationTache-row-{{$evaluationRealisationTache->id}}" data-id="{{$evaluationRealisationTache->id}}">
                    <x-checkbox-row :item="$evaluationRealisationTache" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 50%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$evaluationRealisationTache->id}}" data-field="realisation_tache_id" >
                        @include('PkgEvaluateurs::evaluationRealisationTache.custom.fields.realisationTache', ['entity' => $evaluationRealisationTache])
                    </td>
                    <td style="max-width: 16%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$evaluationRealisationTache->id}}" data-field="note" >
                        @include('PkgEvaluateurs::evaluationRealisationTache.custom.fields.note', ['entity' => $evaluationRealisationTache])
                    </td>
                    <td style="max-width: 16%;white-space: normal;" class=" text-truncate" data-id="{{$evaluationRealisationTache->id}}" data-field="nombre_livrables" >
                        @include('PkgEvaluateurs::evaluationRealisationTache.custom.fields.nombre_livrables', ['entity' => $evaluationRealisationTache])
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($evaluationRealisationTaches_permissions['edit-evaluationRealisationTache'])
                        <x-action-button :entity="$evaluationRealisationTache" actionName="edit">
                        @if($evaluationRealisationTaches_permissionsByItem['update'][$evaluationRealisationTache->id])
                            <a href="{{ route('evaluationRealisationTaches.edit', ['evaluationRealisationTache' => $evaluationRealisationTache->id]) }}" data-id="{{$evaluationRealisationTache->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($evaluationRealisationTaches_permissions['show-evaluationRealisationTache'])
                        <x-action-button :entity="$evaluationRealisationTache" actionName="show">
                        @if($evaluationRealisationTaches_permissionsByItem['view'][$evaluationRealisationTache->id])
                            <a href="{{ route('evaluationRealisationTaches.show', ['evaluationRealisationTache' => $evaluationRealisationTache->id]) }}" data-id="{{$evaluationRealisationTache->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$evaluationRealisationTache" actionName="delete">
                        @if($evaluationRealisationTaches_permissions['destroy-evaluationRealisationTache'])
                        @if($evaluationRealisationTaches_permissionsByItem['delete'][$evaluationRealisationTache->id])
                            <form class="context-state" action="{{ route('evaluationRealisationTaches.destroy',['evaluationRealisationTache' => $evaluationRealisationTache->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$evaluationRealisationTache->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                        @endif
                        </x-action-button>
                    </td>
                </tr>
            @endforeach
            @show
        </tbody>
    </table>
</div>
@show

<div class="card-footer">
    @section('evaluationRealisationTache-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $evaluationRealisationTaches_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>