{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('tacheAffectation-table')
<div class="card-body p-0 crud-card-body" id="tacheAffectations-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $tacheAffectations_permissions['edit-tacheAffectation'] || $tacheAffectations_permissions['destroy-tacheAffectation'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="41" field="tache_id" modelname="tacheAffectation" label="{!!ucfirst(__('PkgCreationTache::tache.singular'))!!}" />
                <x-sortable-column :sortable="true" width="41" field="affectation_projet_id" modelname="tacheAffectation" label="{!!ucfirst(__('PkgRealisationProjets::affectationProjet.singular'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('tacheAffectation-table-tbody')
            @foreach ($tacheAffectations_data as $tacheAffectation)
                @php
                    $isEditable = $tacheAffectations_permissions['edit-tacheAffectation'] && $tacheAffectations_permissionsByItem['update'][$tacheAffectation->id];
                @endphp
                <tr id="tacheAffectation-row-{{$tacheAffectation->id}}" data-id="{{$tacheAffectation->id}}">
                    <x-checkbox-row :item="$tacheAffectation" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 41%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$tacheAffectation->id}}" data-field="tache_id"  data-toggle="tooltip" title="{{ $tacheAffectation->tache }}" >
                        {{  $tacheAffectation->tache }}

                    </td>
                    <td style="max-width: 41%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$tacheAffectation->id}}" data-field="affectation_projet_id"  data-toggle="tooltip" title="{{ $tacheAffectation->affectationProjet }}" >
                        {{  $tacheAffectation->affectationProjet }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($tacheAffectations_permissions['edit-tacheAffectation'])
                        <x-action-button :entity="$tacheAffectation" actionName="edit">
                        @if($tacheAffectations_permissionsByItem['update'][$tacheAffectation->id])
                            <a href="{{ route('tacheAffectations.edit', ['tacheAffectation' => $tacheAffectation->id]) }}" data-id="{{$tacheAffectation->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($tacheAffectations_permissions['show-tacheAffectation'])
                        <x-action-button :entity="$tacheAffectation" actionName="show">
                        @if($tacheAffectations_permissionsByItem['view'][$tacheAffectation->id])
                            <a href="{{ route('tacheAffectations.show', ['tacheAffectation' => $tacheAffectation->id]) }}" data-id="{{$tacheAffectation->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$tacheAffectation" actionName="delete">
                        @if($tacheAffectations_permissions['destroy-tacheAffectation'])
                        @if($tacheAffectations_permissionsByItem['delete'][$tacheAffectation->id])
                            <form class="context-state" action="{{ route('tacheAffectations.destroy',['tacheAffectation' => $tacheAffectation->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$tacheAffectation->id}}">
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
    @section('tacheAffectation-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $tacheAffectations_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>