{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('affectationProjet-table')
<div class="card-body p-0 crud-card-body" id="affectationProjets-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $affectationProjets_permissions['edit-affectationProjet'] || $affectationProjets_permissions['destroy-affectationProjet'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="13.666666666666666" field="projet_id" modelname="affectationProjet" label="{!!ucfirst(__('PkgCreationProjet::projet.singular'))!!}" />
                <x-sortable-column :sortable="true" width="13.666666666666666" field="groupe_id" modelname="affectationProjet" label="{!!ucfirst(__('PkgApprenants::groupe.singular'))!!}" />
                <x-sortable-column :sortable="true" width="13.666666666666666" field="sous_groupe_id" modelname="affectationProjet" label="{!!ucfirst(__('PkgApprenants::sousGroupe.singular'))!!}" />
                <x-sortable-column :sortable="true" width="13.666666666666666"  field="date_debut" modelname="affectationProjet" label="{!!ucfirst(__('PkgRealisationProjets::affectationProjet.date_debut'))!!}" />
                <x-sortable-column :sortable="true" width="13.666666666666666"  field="date_fin" modelname="affectationProjet" label="{!!ucfirst(__('PkgRealisationProjets::affectationProjet.date_fin'))!!}" />
                <x-sortable-column :sortable="false" width="13.666666666666666"  field="evaluateurs" modelname="affectationProjet" label="{!!ucfirst(__('PkgEvaluateurs::evaluateur.plural'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('affectationProjet-table-tbody')
            @foreach ($affectationProjets_data as $affectationProjet)
                @php
                    $isEditable = $affectationProjets_permissions['edit-affectationProjet'] && $affectationProjets_permissionsByItem['update'][$affectationProjet->id];
                @endphp
                <tr id="affectationProjet-row-{{$affectationProjet->id}}" data-id="{{$affectationProjet->id}}">
                    <x-checkbox-row :item="$affectationProjet" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 13.666666666666666%;" class=" text-truncate" data-id="{{$affectationProjet->id}}" data-field="projet_id"  data-toggle="tooltip" title="{{ $affectationProjet->projet }}" >
                        {{  $affectationProjet->projet }}

                    </td>
                    <td style="max-width: 13.666666666666666%;" class=" text-truncate" data-id="{{$affectationProjet->id}}" data-field="groupe_id"  data-toggle="tooltip" title="{{ $affectationProjet->groupe }}" >
                        {{  $affectationProjet->groupe }}

                    </td>
                    <td style="max-width: 13.666666666666666%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$affectationProjet->id}}" data-field="sous_groupe_id"  data-toggle="tooltip" title="{{ $affectationProjet->sousGroupe }}" >
                        {{  $affectationProjet->sousGroupe }}

                    </td>
                    <td style="max-width: 13.666666666666666%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$affectationProjet->id}}" data-field="date_debut"  data-toggle="tooltip" title="{{ $affectationProjet->date_debut }}" >
                        <x-deadline-display :value="$affectationProjet->date_debut" />
                    </td>
                    <td style="max-width: 13.666666666666666%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$affectationProjet->id}}" data-field="date_fin"  data-toggle="tooltip" title="{{ $affectationProjet->date_fin }}" >
                        <x-deadline-display :value="$affectationProjet->date_fin" />
                    </td>
                    <td style="max-width: 13.666666666666666%;" class=" text-truncate" data-id="{{$affectationProjet->id}}" data-field="evaluateurs"  data-toggle="tooltip" title="{{ $affectationProjet->evaluateurs }}" >
                        <ul>
                            @foreach ($affectationProjet->evaluateurs as $evaluateur)
                                <li @if(strlen($evaluateur) > 30) data-toggle="tooltip" title="{{$evaluateur}}"  @endif>@limit($evaluateur, 30)</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">
                        @if($affectationProjets_permissions['exportPV-affectationProjet'])
                        <x-action-button :entity="$affectationProjet" actionName="exportPV">
                            <a 
                            data-toggle="tooltip" 
                            title="Exporter PV en Excel" 
                            href="{{ route('affectationProjets.exportPV', ['id' => $affectationProjet->id]) }}" 
                            data-id="{{$affectationProjet->id}}" 
                            data-url="{{ route('affectationProjets.exportPV', ['id' => $affectationProjet->id]) }}" 
                            data-action-type="downloadMode"
                            class="btn btn-default btn-sm context-state actionEntity">
                                <i class="fas fa-file-excel"></i>
                            </a>
                        </x-action-button>
                        @endif
                        

                       

                        @if($affectationProjets_permissions['edit-affectationProjet'])
                        <x-action-button :entity="$affectationProjet" actionName="edit">
                        @if($affectationProjets_permissionsByItem['update'][$affectationProjet->id])
                            <a href="{{ route('affectationProjets.edit', ['affectationProjet' => $affectationProjet->id]) }}" data-id="{{$affectationProjet->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($affectationProjets_permissions['show-affectationProjet'])
                        <x-action-button :entity="$affectationProjet" actionName="show">
                        @if($affectationProjets_permissionsByItem['view'][$affectationProjet->id])
                            <a href="{{ route('affectationProjets.show', ['affectationProjet' => $affectationProjet->id]) }}" data-id="{{$affectationProjet->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$affectationProjet" actionName="delete">
                        @if($affectationProjets_permissions['destroy-affectationProjet'])
                        @if($affectationProjets_permissionsByItem['delete'][$affectationProjet->id])
                            <form class="context-state" action="{{ route('affectationProjets.destroy',['affectationProjet' => $affectationProjet->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$affectationProjet->id}}">
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
    @section('affectationProjet-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $affectationProjets_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>