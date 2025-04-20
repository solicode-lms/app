{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatsRealisationProjet-table')
<div class="card-body table-responsive p-0 crud-card-body" id="etatsRealisationProjets-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-etatsRealisationProjet') || Auth::user()->can('destroy-etatsRealisationProjet');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="20.5" field="formateur_id" modelname="etatsRealisationProjet" label="{{ucfirst(__('PkgFormation::formateur.singular'))}}" />
                <x-sortable-column :sortable="true" width="20.5"  field="titre" modelname="etatsRealisationProjet" label="{{ucfirst(__('PkgRealisationProjets::etatsRealisationProjet.titre'))}}" />
                <x-sortable-column :sortable="true" width="20.5" field="sys_color_id" modelname="etatsRealisationProjet" label="{{ucfirst(__('Core::sysColor.singular'))}}" />
                <x-sortable-column :sortable="true" width="20.5" field="workflow_projet_id" modelname="etatsRealisationProjet" label="{{ucfirst(__('PkgRealisationProjets::workflowProjet.singular'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('etatsRealisationProjet-table-tbody')
            @foreach ($etatsRealisationProjets_data as $etatsRealisationProjet)
                <tr id="etatsRealisationProjet-row-{{$etatsRealisationProjet->id}}" data-id="{{$etatsRealisationProjet->id}}">
                    <x-checkbox-row :item="$etatsRealisationProjet" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 20.5%;" class="editable-cell text-truncate" data-id="{{$etatsRealisationProjet->id}}" data-field="formateur_id"  data-toggle="tooltip" title="{{ $etatsRealisationProjet->formateur }}" >
                    <x-field :entity="$etatsRealisationProjet" field="formateur">
                       
                         {{  $etatsRealisationProjet->formateur }}
                    </x-field>
                    </td>
                    <td style="max-width: 20.5%;" class="editable-cell text-truncate" data-id="{{$etatsRealisationProjet->id}}" data-field="titre"  data-toggle="tooltip" title="{{ $etatsRealisationProjet->titre }}" >
                    <x-field :entity="$etatsRealisationProjet" field="titre">
                        {{ $etatsRealisationProjet->titre }}
                    </x-field>
                    </td>
                    <td style="max-width: 20.5%;" class="editable-cell text-truncate" data-id="{{$etatsRealisationProjet->id}}" data-field="sys_color_id"  data-toggle="tooltip" title="{{ $etatsRealisationProjet->sysColor }}" >
                    <x-field :entity="$etatsRealisationProjet" field="sysColor">
                        <x-badge 
                        :text="$etatsRealisationProjet->sysColor->name ?? ''" 
                        :background="$etatsRealisationProjet->sysColor->hex ?? '#6c757d'" 
                        />
                    </x-field>
                    </td>
                    <td style="max-width: 20.5%;" class="editable-cell text-truncate" data-id="{{$etatsRealisationProjet->id}}" data-field="workflow_projet_id"  data-toggle="tooltip" title="{{ $etatsRealisationProjet->workflowProjet }}" >
                    <x-field :entity="$etatsRealisationProjet" field="workflowProjet">
                        @if(!empty($etatsRealisationProjet->workflowProjet))
                        <x-badge 
                        :text="$etatsRealisationProjet->workflowProjet" 
                        :background="$etatsRealisationProjet->workflowProjet->sysColor->hex ?? '#6c757d'" 
                        />
                        @endif
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-etatsRealisationProjet')
                        @can('update', $etatsRealisationProjet)
                            <a href="{{ route('etatsRealisationProjets.edit', ['etatsRealisationProjet' => $etatsRealisationProjet->id]) }}" data-id="{{$etatsRealisationProjet->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-etatsRealisationProjet')
                        @can('view', $etatsRealisationProjet)
                            <a href="{{ route('etatsRealisationProjets.show', ['etatsRealisationProjet' => $etatsRealisationProjet->id]) }}" data-id="{{$etatsRealisationProjet->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-etatsRealisationProjet')
                        @can('delete', $etatsRealisationProjet)
                            <form class="context-state" action="{{ route('etatsRealisationProjets.destroy',['etatsRealisationProjet' => $etatsRealisationProjet->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$etatsRealisationProjet->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endcan
                        @endcan
                    </td>
                </tr>
            @endforeach
            @show
        </tbody>
    </table>
</div>
@show

<div class="card-footer">
    @section('etatsRealisationProjet-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $etatsRealisationProjets_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>