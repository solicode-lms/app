{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationUaPrototype-table')
<div class="card-body p-0 crud-card-body" id="realisationUaPrototypes-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $realisationUaPrototypes_permissions['edit-realisationUaPrototype'] || $realisationUaPrototypes_permissions['destroy-realisationUaPrototype'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="40" field="realisation_tache_id" modelname="realisationUaPrototype" label="{!!ucfirst(__('PkgRealisationTache::realisationTache.singular'))!!}" />
                <x-sortable-column :sortable="true" width="10"  field="note" modelname="realisationUaPrototype" label="{!!ucfirst(__('PkgApprentissage::realisationUaPrototype.note'))!!}" />
                <x-sortable-column :sortable="false" width="32"  field="criteres_evaluation" modelname="realisationUaPrototype" label="{!!ucfirst(__('PkgApprentissage::realisationUaPrototype.criteres_evaluation'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('realisationUaPrototype-table-tbody')
            @foreach ($realisationUaPrototypes_data as $realisationUaPrototype)
                @php
                    $isEditable = $realisationUaPrototypes_permissions['edit-realisationUaPrototype'] && $realisationUaPrototypes_permissionsByItem['update'][$realisationUaPrototype->id];
                @endphp
                <tr id="realisationUaPrototype-row-{{$realisationUaPrototype->id}}" data-id="{{$realisationUaPrototype->id}}">
                    <x-checkbox-row :item="$realisationUaPrototype" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 40%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationUaPrototype->id}}" data-field="realisation_tache_id"  data-toggle="tooltip" title="{{ $realisationUaPrototype->realisationTache }}" >
                        @include('PkgApprentissage::realisationUaPrototype.custom.fields.realisationTache', ['entity' => $realisationUaPrototype])
                    </td>
                    <td style="max-width: 10%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationUaPrototype->id}}" data-field="note"  data-toggle="tooltip" title="{{ $realisationUaPrototype->note }}" >
                        @include('PkgApprentissage::realisationUaPrototype.custom.fields.note', ['entity' => $realisationUaPrototype])
                    </td>
                    <td style="max-width: 32%;" class=" text-truncate" data-id="{{$realisationUaPrototype->id}}" data-field="criteres_evaluation"  data-toggle="tooltip" title="{{ $realisationUaPrototype->criteres_evaluation }}" >
                        @include('PkgApprentissage::realisationUaPrototype.custom.fields.criteres_evaluation', ['entity' => $realisationUaPrototype])
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($realisationUaPrototypes_permissions['edit-realisationUaPrototype'])
                        <x-action-button :entity="$realisationUaPrototype" actionName="edit">
                        @if($realisationUaPrototypes_permissionsByItem['update'][$realisationUaPrototype->id])
                            <a href="{{ route('realisationUaPrototypes.edit', ['realisationUaPrototype' => $realisationUaPrototype->id]) }}" data-id="{{$realisationUaPrototype->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($realisationUaPrototypes_permissions['show-realisationUaPrototype'])
                        <x-action-button :entity="$realisationUaPrototype" actionName="show">
                        @if($realisationUaPrototypes_permissionsByItem['view'][$realisationUaPrototype->id])
                            <a href="{{ route('realisationUaPrototypes.show', ['realisationUaPrototype' => $realisationUaPrototype->id]) }}" data-id="{{$realisationUaPrototype->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$realisationUaPrototype" actionName="delete">
                        @if($realisationUaPrototypes_permissions['destroy-realisationUaPrototype'])
                        @if($realisationUaPrototypes_permissionsByItem['delete'][$realisationUaPrototype->id])
                            <form class="context-state" action="{{ route('realisationUaPrototypes.destroy',['realisationUaPrototype' => $realisationUaPrototype->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$realisationUaPrototype->id}}">
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
    @section('realisationUaPrototype-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $realisationUaPrototypes_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>