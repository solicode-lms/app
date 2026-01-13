{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('phaseProjet-table')
<div class="card-body p-0 crud-card-body" id="phaseProjets-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $phaseProjets_permissions['edit-phaseProjet'] || $phaseProjets_permissions['destroy-phaseProjet'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="4"  field="ordre" modelname="phaseProjet" label="{!!ucfirst(__('PkgCreationTache::phaseProjet.ordre'))!!}" />
                <x-sortable-column :sortable="true" width="78"  field="nom" modelname="phaseProjet" label="{!!ucfirst(__('PkgCreationTache::phaseProjet.nom'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('phaseProjet-table-tbody')
            @foreach ($phaseProjets_data as $phaseProjet)
                @php
                    $isEditable = $phaseProjets_permissions['edit-phaseProjet'] && $phaseProjets_permissionsByItem['update'][$phaseProjet->id];
                @endphp
                <tr id="phaseProjet-row-{{$phaseProjet->id}}" data-id="{{$phaseProjet->id}}">
                    <x-checkbox-row :item="$phaseProjet" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 4%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$phaseProjet->id}}" data-field="ordre">
                            <div class="sortable-button d-flex justify-content-left align-items-center" style="height: 100%;  min-height: 26px;">
                            <i class="fas fa-th-list" title="{{ $phaseProjet->ordre }}"  data-toggle="tooltip" ></i>  
                        </div>

                    </td>
                    <td style="max-width: 78%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$phaseProjet->id}}" data-field="nom">
                        {{ $phaseProjet->nom }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($phaseProjets_permissions['edit-phaseProjet'])
                        <x-action-button :entity="$phaseProjet" actionName="edit">
                        @if($phaseProjets_permissionsByItem['update'][$phaseProjet->id])
                            <a href="{{ route('phaseProjets.edit', ['phaseProjet' => $phaseProjet->id]) }}" data-id="{{$phaseProjet->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($phaseProjets_permissions['show-phaseProjet'])
                        <x-action-button :entity="$phaseProjet" actionName="show">
                        @if($phaseProjets_permissionsByItem['view'][$phaseProjet->id])
                            <a href="{{ route('phaseProjets.show', ['phaseProjet' => $phaseProjet->id]) }}" data-id="{{$phaseProjet->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$phaseProjet" actionName="delete">
                        @if($phaseProjets_permissions['destroy-phaseProjet'])
                        @if($phaseProjets_permissionsByItem['delete'][$phaseProjet->id])
                            <form class="context-state" action="{{ route('phaseProjets.destroy',['phaseProjet' => $phaseProjet->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$phaseProjet->id}}">
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
    @section('phaseProjet-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $phaseProjets_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>