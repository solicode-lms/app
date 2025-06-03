{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('evaluationRealisationProjet-table')
<div class="card-body p-0 crud-card-body" id="evaluationRealisationProjets-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-evaluationRealisationProjet') || Auth::user()->can('destroy-evaluationRealisationProjet');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="27.333333333333332" field="realisation_projet_id" modelname="evaluationRealisationProjet" label="{{ucfirst(__('PkgRealisationProjets::realisationProjet.singular'))}}" />
                <x-sortable-column :sortable="true" width="27.333333333333332" field="evaluateur_id" modelname="evaluationRealisationProjet" label="{{ucfirst(__('PkgValidationProjets::evaluateur.singular'))}}" />
                <x-sortable-column :sortable="true" width="27.333333333333332" field="etat_evaluation_projet_id" modelname="evaluationRealisationProjet" label="{{ucfirst(__('PkgValidationProjets::etatEvaluationProjet.singular'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('evaluationRealisationProjet-table-tbody')
            @foreach ($evaluationRealisationProjets_data as $evaluationRealisationProjet)
                @php
                    $isEditable = Auth::user()->can('edit-evaluationRealisationProjet') && Auth::user()->can('update', $evaluationRealisationProjet);
                @endphp
                <tr id="evaluationRealisationProjet-row-{{$evaluationRealisationProjet->id}}" data-id="{{$evaluationRealisationProjet->id}}">
                    <x-checkbox-row :item="$evaluationRealisationProjet" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$evaluationRealisationProjet->id}}" data-field="realisation_projet_id"  data-toggle="tooltip" title="{{ $evaluationRealisationProjet->realisationProjet }}" >
                    <x-field :entity="$evaluationRealisationProjet" field="realisationProjet">
                       
                         {{  $evaluationRealisationProjet->realisationProjet }}
                    </x-field>
                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$evaluationRealisationProjet->id}}" data-field="evaluateur_id"  data-toggle="tooltip" title="{{ $evaluationRealisationProjet->evaluateur }}" >
                    <x-field :entity="$evaluationRealisationProjet" field="evaluateur">
                       
                         {{  $evaluationRealisationProjet->evaluateur }}
                    </x-field>
                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$evaluationRealisationProjet->id}}" data-field="etat_evaluation_projet_id"  data-toggle="tooltip" title="{{ $evaluationRealisationProjet->etatEvaluationProjet }}" >
                    <x-field :entity="$evaluationRealisationProjet" field="etatEvaluationProjet">
                        @if(!empty($evaluationRealisationProjet->etatEvaluationProjet))
                        <x-badge 
                        :text="$evaluationRealisationProjet->etatEvaluationProjet" 
                        :background="$evaluationRealisationProjet->etatEvaluationProjet->sysColor->hex ?? '#6c757d'" 
                        />
                        @endif
                    </x-field>
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @can('edit-evaluationRealisationProjet')
                        <x-action-button :entity="$evaluationRealisationProjet" actionName="edit">
                        @can('update', $evaluationRealisationProjet)
                            <a href="{{ route('evaluationRealisationProjets.edit', ['evaluationRealisationProjet' => $evaluationRealisationProjet->id]) }}" data-id="{{$evaluationRealisationProjet->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan
                        @can('show-evaluationRealisationProjet')
                        <x-action-button :entity="$evaluationRealisationProjet" actionName="show">
                        @can('view', $evaluationRealisationProjet)
                            <a href="{{ route('evaluationRealisationProjets.show', ['evaluationRealisationProjet' => $evaluationRealisationProjet->id]) }}" data-id="{{$evaluationRealisationProjet->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan

                        <x-action-button :entity="$evaluationRealisationProjet" actionName="delete">
                        @can('destroy-evaluationRealisationProjet')
                        @can('delete', $evaluationRealisationProjet)
                            <form class="context-state" action="{{ route('evaluationRealisationProjets.destroy',['evaluationRealisationProjet' => $evaluationRealisationProjet->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$evaluationRealisationProjet->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endcan
                        @endcan
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