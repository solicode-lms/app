{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationFormation-table')
<div class="card-body p-0 crud-card-body" id="realisationFormations-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-realisationFormation') || Auth::user()->can('destroy-realisationFormation');
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
                    $isEditable = Auth::user()->can('edit-realisationFormation') && Auth::user()->can('update', $realisationFormation);
                @endphp
                <tr id="realisationFormation-row-{{$realisationFormation->id}}" data-id="{{$realisationFormation->id}}">
                    <x-checkbox-row :item="$realisationFormation" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 16.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationFormation->id}}" data-field="date_debut"  data-toggle="tooltip" title="{{ $realisationFormation->date_debut }}" >
                    <x-field :entity="$realisationFormation" field="date_debut">
                        {{ $realisationFormation->date_debut }}
                    </x-field>
                    </td>
                    <td style="max-width: 16.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationFormation->id}}" data-field="date_fin"  data-toggle="tooltip" title="{{ $realisationFormation->date_fin }}" >
                    <x-field :entity="$realisationFormation" field="date_fin">
                        {{ $realisationFormation->date_fin }}
                    </x-field>
                    </td>
                    <td style="max-width: 16.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationFormation->id}}" data-field="formation_id"  data-toggle="tooltip" title="{{ $realisationFormation->formation }}" >
                    <x-field :entity="$realisationFormation" field="formation">
                       
                         {{  $realisationFormation->formation }}
                    </x-field>
                    </td>
                    <td style="max-width: 16.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationFormation->id}}" data-field="apprenant_id"  data-toggle="tooltip" title="{{ $realisationFormation->apprenant }}" >
                    <x-field :entity="$realisationFormation" field="apprenant">
                       
                         {{  $realisationFormation->apprenant }}
                    </x-field>
                    </td>
                    <td style="max-width: 16.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationFormation->id}}" data-field="etat_formation_id"  data-toggle="tooltip" title="{{ $realisationFormation->etatFormation }}" >
                    <x-field :entity="$realisationFormation" field="etatFormation">
                        @if(!empty($realisationFormation->etatFormation))
                        <x-badge 
                        :text="$realisationFormation->etatFormation" 
                        :background="$realisationFormation->etatFormation->sysColor->hex ?? '#6c757d'" 
                        />
                        @endif
                    </x-field>
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @can('edit-realisationFormation')
                        <x-action-button :entity="$realisationFormation" actionName="edit">
                        @can('update', $realisationFormation)
                            <a href="{{ route('realisationFormations.edit', ['realisationFormation' => $realisationFormation->id]) }}" data-id="{{$realisationFormation->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan
                        @can('show-realisationFormation')
                        <x-action-button :entity="$realisationFormation" actionName="show">
                        @can('view', $realisationFormation)
                            <a href="{{ route('realisationFormations.show', ['realisationFormation' => $realisationFormation->id]) }}" data-id="{{$realisationFormation->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan

                        <x-action-button :entity="$realisationFormation" actionName="delete">
                        @can('destroy-realisationFormation')
                        @can('delete', $realisationFormation)
                            <form class="context-state" action="{{ route('realisationFormations.destroy',['realisationFormation' => $realisationFormation->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$realisationFormation->id}}">
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
    @section('realisationFormation-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $realisationFormations_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>