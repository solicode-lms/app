{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationUaProjet-table')
<div class="card-body p-0 crud-card-body" id="realisationUaProjets-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $realisationUaProjets_permissions['edit-realisationUaProjet'] || $realisationUaProjets_permissions['destroy-realisationUaProjet'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="40" field="realisation_tache_id" modelname="realisationUaProjet" label="{!!ucfirst(__('PkgRealisationTache::realisationTache.singular'))!!}" />
                <x-sortable-column :sortable="true" width="14"  field="note" modelname="realisationUaProjet" label="{!!ucfirst(__('PkgApprentissage::realisationUaProjet.note'))!!}" />
                <x-sortable-column :sortable="true" width="14"  field="date_debut" modelname="realisationUaProjet" label="{!!ucfirst(__('PkgApprentissage::realisationUaProjet.date_debut'))!!}" />
                <x-sortable-column :sortable="true" width="14"  field="date_fin" modelname="realisationUaProjet" label="{!!ucfirst(__('PkgApprentissage::realisationUaProjet.date_fin'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('realisationUaProjet-table-tbody')
            @foreach ($realisationUaProjets_data as $realisationUaProjet)
                @php
                    $isEditable = $realisationUaProjets_permissions['edit-realisationUaProjet'] && $realisationUaProjets_permissionsByItem['update'][$realisationUaProjet->id];
                @endphp
                <tr id="realisationUaProjet-row-{{$realisationUaProjet->id}}" data-id="{{$realisationUaProjet->id}}">
                    <x-checkbox-row :item="$realisationUaProjet" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 40%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationUaProjet->id}}" data-field="realisation_tache_id"  data-toggle="tooltip" title="{{ $realisationUaProjet->realisationTache }}" >
                        @include('PkgApprentissage::realisationUaProjet.custom.fields.realisationTache', ['entity' => $realisationUaProjet])
                    </td>
                    <td style="max-width: 14%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationUaProjet->id}}" data-field="note"  data-toggle="tooltip" title="{{ $realisationUaProjet->note }}" >
                        @include('PkgApprentissage::realisationUaProjet.custom.fields.note', ['entity' => $realisationUaProjet])
                    </td>
                    <td style="max-width: 14%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationUaProjet->id}}" data-field="date_debut"  data-toggle="tooltip" title="{{ $realisationUaProjet->date_debut }}" >
                        <x-deadline-display :value="$realisationUaProjet->date_debut" />
                    </td>
                    <td style="max-width: 14%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationUaProjet->id}}" data-field="date_fin"  data-toggle="tooltip" title="{{ $realisationUaProjet->date_fin }}" >
                        <x-deadline-display :value="$realisationUaProjet->date_fin" />
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($realisationUaProjets_permissions['edit-realisationUaProjet'])
                        <x-action-button :entity="$realisationUaProjet" actionName="edit">
                        @if($realisationUaProjets_permissionsByItem['update'][$realisationUaProjet->id])
                            <a href="{{ route('realisationUaProjets.edit', ['realisationUaProjet' => $realisationUaProjet->id]) }}" data-id="{{$realisationUaProjet->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($realisationUaProjets_permissions['show-realisationUaProjet'])
                        <x-action-button :entity="$realisationUaProjet" actionName="show">
                        @if($realisationUaProjets_permissionsByItem['view'][$realisationUaProjet->id])
                            <a href="{{ route('realisationUaProjets.show', ['realisationUaProjet' => $realisationUaProjet->id]) }}" data-id="{{$realisationUaProjet->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$realisationUaProjet" actionName="delete">
                        @if($realisationUaProjets_permissions['destroy-realisationUaProjet'])
                        @if($realisationUaProjets_permissionsByItem['delete'][$realisationUaProjet->id])
                            <form class="context-state" action="{{ route('realisationUaProjets.destroy',['realisationUaProjet' => $realisationUaProjet->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$realisationUaProjet->id}}">
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
    @section('realisationUaProjet-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $realisationUaProjets_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>