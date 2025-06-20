{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('niveauDifficulte-table')
<div class="card-body p-0 crud-card-body" id="niveauDifficultes-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $niveauDifficultes_permissions['edit-niveauDifficulte'] || $niveauDifficultes_permissions['destroy-niveauDifficulte'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="41"  field="nom" modelname="niveauDifficulte" label="{{ucfirst(__('PkgCompetences::niveauDifficulte.nom'))}}" />
                <x-sortable-column :sortable="true" width="41" field="formateur_id" modelname="niveauDifficulte" label="{{ucfirst(__('PkgFormation::formateur.singular'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('niveauDifficulte-table-tbody')
            @foreach ($niveauDifficultes_data as $niveauDifficulte)
                @php
                    $isEditable = $niveauDifficultes_permissions['edit-niveauDifficulte'] && $niveauDifficultes_permissionsByItem['update'][$niveauDifficulte->id];
                @endphp
                <tr id="niveauDifficulte-row-{{$niveauDifficulte->id}}" data-id="{{$niveauDifficulte->id}}">
                    <x-checkbox-row :item="$niveauDifficulte" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 41%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$niveauDifficulte->id}}" data-field="nom"  data-toggle="tooltip" title="{{ $niveauDifficulte->nom }}" >
                        {{ $niveauDifficulte->nom }}

                    </td>
                    <td style="max-width: 41%;" class=" text-truncate" data-id="{{$niveauDifficulte->id}}" data-field="formateur_id"  data-toggle="tooltip" title="{{ $niveauDifficulte->formateur }}" >
                        {{  $niveauDifficulte->formateur }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($niveauDifficultes_permissions['edit-niveauDifficulte'])
                        <x-action-button :entity="$niveauDifficulte" actionName="edit">
                        @if($niveauDifficultes_permissionsByItem['update'][$niveauDifficulte->id])
                            <a href="{{ route('niveauDifficultes.edit', ['niveauDifficulte' => $niveauDifficulte->id]) }}" data-id="{{$niveauDifficulte->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($niveauDifficultes_permissions['show-niveauDifficulte'])
                        <x-action-button :entity="$niveauDifficulte" actionName="show">
                        @if($niveauDifficultes_permissionsByItem['view'][$niveauDifficulte->id])
                            <a href="{{ route('niveauDifficultes.show', ['niveauDifficulte' => $niveauDifficulte->id]) }}" data-id="{{$niveauDifficulte->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$niveauDifficulte" actionName="delete">
                        @if($niveauDifficultes_permissions['destroy-niveauDifficulte'])
                        @if($niveauDifficultes_permissionsByItem['delete'][$niveauDifficulte->id])
                            <form class="context-state" action="{{ route('niveauDifficultes.destroy',['niveauDifficulte' => $niveauDifficulte->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$niveauDifficulte->id}}">
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
    @section('niveauDifficulte-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $niveauDifficultes_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>