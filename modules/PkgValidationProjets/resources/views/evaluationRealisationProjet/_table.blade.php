{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('evaluationRealisationProjet-table')
<div class="card-body p-0 crud-card-body" id="evaluationRealisationProjets-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $evaluationRealisationProjets_permissions['edit-evaluationRealisationProjet'] || $devevaluationRealisationProjets_permissions['destroy-evaluationRealisationProjet'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="16.4" field="realisation_projet_id" modelname="evaluationRealisationProjet" label="{{ucfirst(__('PkgRealisationProjets::realisationProjet.singular'))}}" />
                <x-sortable-column :sortable="true" width="16.4"  field="NomApprenant" modelname="evaluationRealisationProjet" label="{{ucfirst(__('PkgValidationProjets::evaluationRealisationProjet.NomApprenant'))}}" />
                <x-sortable-column :sortable="true" width="16.4" field="evaluateur_id" modelname="evaluationRealisationProjet" label="{{ucfirst(__('PkgValidationProjets::evaluateur.singular'))}}" />
                <x-sortable-column :sortable="true" width="16.4" field="etat_evaluation_projet_id" modelname="evaluationRealisationProjet" label="{{ucfirst(__('PkgValidationProjets::etatEvaluationProjet.singular'))}}" />
                <x-sortable-column :sortable="true" width="16.4"  field="Note" modelname="evaluationRealisationProjet" label="{{ucfirst(__('PkgValidationProjets::evaluationRealisationProjet.Note'))}}" />
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
                    <td style="max-width: 16.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$evaluationRealisationProjet->id}}" data-field="realisation_projet_id"  data-toggle="tooltip" title="{{ $evaluationRealisationProjet->realisationProjet }}" >
                        {{  $evaluationRealisationProjet->realisationProjet }}

                    </td>
                    <td style="max-width: 16.4%;" class=" text-truncate" data-id="{{$evaluationRealisationProjet->id}}" data-field="NomApprenant"  data-toggle="tooltip" title="{{ $evaluationRealisationProjet->NomApprenant }}" >
                        {{ $evaluationRealisationProjet->NomApprenant }}

                    </td>
                    <td style="max-width: 16.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$evaluationRealisationProjet->id}}" data-field="evaluateur_id"  data-toggle="tooltip" title="{{ $evaluationRealisationProjet->evaluateur }}" >
                        {{  $evaluationRealisationProjet->evaluateur }}

                    </td>
                    <td style="max-width: 16.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$evaluationRealisationProjet->id}}" data-field="etat_evaluation_projet_id"  data-toggle="tooltip" title="{{ $evaluationRealisationProjet->etatEvaluationProjet }}" >
                        @if(!empty($evaluationRealisationProjet->etatEvaluationProjet))
                        <x-badge 
                        :text="$evaluationRealisationProjet->etatEvaluationProjet" 
                        :background="$evaluationRealisationProjet->etatEvaluationProjet->sysColor->hex ?? '#6c757d'" 
                        />
                        @endif

                    </td>
                    <td style="max-width: 16.4%;" class=" text-truncate" data-id="{{$evaluationRealisationProjet->id}}" data-field="Note"  data-toggle="tooltip" title="{{ $evaluationRealisationProjet->Note }}" >
                        @include('PkgValidationProjets::evaluationRealisationProjet.custom.fields.Note', ['entity' => $evaluationRealisationProjet])
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
                                <i class="far fa-eye"></i>
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