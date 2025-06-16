{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationFormation-table')
<div class="card-body p-0 crud-card-body" id="realisationFormations-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $realisationFormations_permissions['edit-realisationFormation'] || $devrealisationFormations_permissions['destroy-realisationFormation'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="16.4"  field="date_debut" modelname="realisationFormation" label="{{ucfirst(__('PkgAutoformation::realisationFormation.date_debut'))}}" />
                <x-sortable-column :sortable="true" width="16.4"  field="date_fin" modelname="realisationFormation" label="{{ucfirst(__('PkgAutoformation::realisationFormation.date_fin'))}}" />
                <x-sortable-column :sortable="true" width="16.4" field="formation_id" modelname="realisationFormation" label="{{ucfirst(__('PkgAutoformation::formation.singular'))}}" />
                <x-sortable-column :sortable="true" width="16.4" field="apprenant_id" modelname="realisationFormation" label="{{ucfirst(__('PkgApprenants::apprenant.singular'))}}" />
                <x-sortable-column :sortable="true" width="16.4" field="etat_formation_id" modelname="realisationFormation" label="{{ucfirst(__('PkgAutoformation::etatFormation.singular'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('realisationFormation-table-tbody')
            @foreach ($realisationFormations_data as $realisationFormation)
                @php
                    $isEditable = $realisationFormations_permissions['edit-realisationFormation'] && $realisationFormations_permissionsByItem['update'][$realisationFormation->id];
                @endphp
                <tr id="realisationFormation-row-{{$realisationFormation->id}}" data-id="{{$realisationFormation->id}}">
                    <x-checkbox-row :item="$realisationFormation" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 16.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationFormation->id}}" data-field="date_debut"  data-toggle="tooltip" title="{{ $realisationFormation->date_debut }}" >
                        <x-deadline-display :value="$realisationFormation->date_debut" />
                    </td>
                    <td style="max-width: 16.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationFormation->id}}" data-field="date_fin"  data-toggle="tooltip" title="{{ $realisationFormation->date_fin }}" >
                        <x-deadline-display :value="$realisationFormation->date_fin" />
                    </td>
                    <td style="max-width: 16.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationFormation->id}}" data-field="formation_id"  data-toggle="tooltip" title="{{ $realisationFormation->formation }}" >
                        {{  $realisationFormation->formation }}

                    </td>
                    <td style="max-width: 16.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationFormation->id}}" data-field="apprenant_id"  data-toggle="tooltip" title="{{ $realisationFormation->apprenant }}" >
                        {{  $realisationFormation->apprenant }}

                    </td>
                    <td style="max-width: 16.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationFormation->id}}" data-field="etat_formation_id"  data-toggle="tooltip" title="{{ $realisationFormation->etatFormation }}" >
                        @if(!empty($realisationFormation->etatFormation))
                        <x-badge 
                        :text="$realisationFormation->etatFormation" 
                        :background="$realisationFormation->etatFormation->sysColor->hex ?? '#6c757d'" 
                        />
                        @endif

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($realisationFormations_permissions['edit-realisationFormation'])
                        <x-action-button :entity="$realisationFormation" actionName="edit">
                        @if($realisationFormations_permissionsByItem['update'][$realisationFormation->id])
                            <a href="{{ route('realisationFormations.edit', ['realisationFormation' => $realisationFormation->id]) }}" data-id="{{$realisationFormation->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($realisationFormations_permissions['show-realisationFormation'])
                        <x-action-button :entity="$realisationFormation" actionName="show">
                        @if($realisationFormations_permissionsByItem['view'][$realisationFormation->id])
                            <a href="{{ route('realisationFormations.show', ['realisationFormation' => $realisationFormation->id]) }}" data-id="{{$realisationFormation->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$realisationFormation" actionName="delete">
                        @if($realisationFormations_permissions['destroy-realisationFormation'])
                        @if($realisationFormations_permissionsByItem['delete'][$realisationFormation->id])
                            <form class="context-state" action="{{ route('realisationFormations.destroy',['realisationFormation' => $realisationFormation->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$realisationFormation->id}}">
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
    @section('realisationFormation-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $realisationFormations_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>