{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('evaluationRealisationProjet-table')
<div class="card-body p-0 crud-card-body" id="evaluationRealisationProjets-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $evaluationRealisationProjets_permissions['edit-evaluationRealisationProjet'] || $evaluationRealisationProjets_permissions['destroy-evaluationRealisationProjet'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="16.4" field="realisation_projet_id" modelname="evaluationRealisationProjet" label="{!!ucfirst(__('PkgRealisationProjets::realisationProjet.singular'))!!}" />
                <x-sortable-column :sortable="true" width="16.4"  field="nomApprenant" modelname="evaluationRealisationProjet" label="{!!ucfirst(__('PkgEvaluateurs::evaluationRealisationProjet.nomApprenant'))!!}" />
                <x-sortable-column :sortable="true" width="16.4" field="evaluateur_id" modelname="evaluationRealisationProjet" label="{!!ucfirst(__('PkgEvaluateurs::evaluateur.singular'))!!}" />
                <x-sortable-column :sortable="true" width="16.4" field="etat_evaluation_projet_id" modelname="evaluationRealisationProjet" label="{!!ucfirst(__('PkgEvaluateurs::etatEvaluationProjet.singular'))!!}" />
                <x-sortable-column :sortable="true" width="16.4"  field="note" modelname="evaluationRealisationProjet" label="{!!ucfirst(__('PkgEvaluateurs::evaluationRealisationProjet.note'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('evaluationRealisationProjet-table-tbody')
            @foreach ($evaluationRealisationProjets_data as $evaluationRealisationProjet)
                @php
                    $isEditable = $evaluationRealisationProjets_permissions['edit-evaluationRealisationProjet'] && $evaluationRealisationProjets_permissionsByItem['update'][$evaluationRealisationProjet->id];
                @endphp
                <tr id="evaluationRealisationProjet-row-{{$evaluationRealisationProjet->id}}" data-id="{{$evaluationRealisationProjet->id}}">
                    <x-checkbox-row :item="$evaluationRealisationProjet" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 16.4%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$evaluationRealisationProjet->id}}" data-field="realisation_projet_id">
                        {{  $evaluationRealisationProjet->realisationProjet }}

                    </td>
                    <td style="max-width: 16.4%;white-space: normal;" class=" text-truncate" data-id="{{$evaluationRealisationProjet->id}}" data-field="nomApprenant">
                        {{ $evaluationRealisationProjet->nomApprenant }}

                    </td>
                    <td style="max-width: 16.4%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$evaluationRealisationProjet->id}}" data-field="evaluateur_id">
                        {{  $evaluationRealisationProjet->evaluateur }}

                    </td>
                    <td style="max-width: 16.4%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$evaluationRealisationProjet->id}}" data-field="etat_evaluation_projet_id">
                        @if(!empty($evaluationRealisationProjet->etatEvaluationProjet))
                        <x-badge 
                        :text="$evaluationRealisationProjet->etatEvaluationProjet" 
                        :background="$evaluationRealisationProjet->etatEvaluationProjet->sysColor->hex ?? '#6c757d'" 
                        />
                        @endif

                    </td>
                    <td style="max-width: 16.4%;white-space: normal;" class=" text-truncate" data-id="{{$evaluationRealisationProjet->id}}" data-field="note" >
                        @include('PkgEvaluateurs::evaluationRealisationProjet.custom.fields.note', ['entity' => $evaluationRealisationProjet])
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($evaluationRealisationProjets_permissions['edit-evaluationRealisationProjet'])
                        <x-action-button :entity="$evaluationRealisationProjet" actionName="edit">
                        @if($evaluationRealisationProjets_permissionsByItem['update'][$evaluationRealisationProjet->id])
                            <a href="{{ route('evaluationRealisationProjets.edit', ['evaluationRealisationProjet' => $evaluationRealisationProjet->id]) }}" data-id="{{$evaluationRealisationProjet->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($evaluationRealisationProjets_permissions['show-evaluationRealisationProjet'])
                        <x-action-button :entity="$evaluationRealisationProjet" actionName="show">
                        @if($evaluationRealisationProjets_permissionsByItem['view'][$evaluationRealisationProjet->id])
                            <a href="{{ route('evaluationRealisationProjets.show', ['evaluationRealisationProjet' => $evaluationRealisationProjet->id]) }}" data-id="{{$evaluationRealisationProjet->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$evaluationRealisationProjet" actionName="delete">
                        @if($evaluationRealisationProjets_permissions['destroy-evaluationRealisationProjet'])
                        @if($evaluationRealisationProjets_permissionsByItem['delete'][$evaluationRealisationProjet->id])
                            <form class="context-state" action="{{ route('evaluationRealisationProjets.destroy',['evaluationRealisationProjet' => $evaluationRealisationProjet->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$evaluationRealisationProjet->id}}">
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
    @section('evaluationRealisationProjet-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $evaluationRealisationProjets_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>