{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('equipeProjet-table')
<div class="card-body p-0 crud-card-body" id="equipeProjets-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $equipeProjets_permissions['edit-equipeProjet'] || $equipeProjets_permissions['destroy-equipeProjet'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="41" field="projet_id" modelname="equipeProjet" label="{!!ucfirst(__('PkgCreationProjet::projet.singular'))!!}" />
                <x-sortable-column :sortable="true" width="41"  field="nom" modelname="equipeProjet" label="{!!ucfirst(__('PkgCreationProjet::equipeProjet.nom'))!!}" />
                <th class="text-center crud-actions-header">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('equipeProjet-table-tbody')
            @foreach ($equipeProjets_data as $equipeProjet)
                @php
                    $isEditable = $equipeProjets_permissions['edit-equipeProjet'] && $equipeProjets_permissionsByItem['update'][$equipeProjet->id];
                @endphp
                <tr id="equipeProjet-row-{{$equipeProjet->id}}" data-id="{{$equipeProjet->id}}">
                    <x-checkbox-row :item="$equipeProjet" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 41%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$equipeProjet->id}}" data-field="projet_id">
                        {{  $equipeProjet->projet }}

                    </td>
                    <td style="max-width: 41%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$equipeProjet->id}}" data-field="nom">
                        {{ $equipeProjet->nom }}

                    </td>
                    <td class="text-right wrappable crud-actions-cell" style="max-width: 15%;">
                        <div class="crud-actions-wrapper">
                        <div class="actions-secondary-group">

                        </div>

                        <div class="actions-main-group">
                        @if($equipeProjets_permissions['edit-equipeProjet'])
                        <x-action-button :entity="$equipeProjet" actionName="edit">
                        @if($equipeProjets_permissionsByItem['update'][$equipeProjet->id])
                            <a href="{{ route('equipeProjets.edit', ['equipeProjet' => $equipeProjet->id]) }}" data-id="{{$equipeProjet->id}}" class="btn btn-sm btn-default context-state editEntity btn-action-main">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($equipeProjets_permissions['show-equipeProjet'])
                        <x-action-button :entity="$equipeProjet" actionName="show">
                        @if($equipeProjets_permissionsByItem['view'][$equipeProjet->id])
                            <a href="{{ route('equipeProjets.show', ['equipeProjet' => $equipeProjet->id]) }}" data-id="{{$equipeProjet->id}}" class="btn btn-default btn-sm context-state showEntity btn-action-main">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$equipeProjet" actionName="delete">
                        @if($equipeProjets_permissions['destroy-equipeProjet'])
                        @if($equipeProjets_permissionsByItem['delete'][$equipeProjet->id])
                            <form class="context-state" action="{{ route('equipeProjets.destroy',['equipeProjet' => $equipeProjet->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger d-none d-lg-inline deleteEntity btn-action-delete" data-id="{{$equipeProjet->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                        @endif
                        </x-action-button>
                        </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            @show
        </tbody>
    </table>
</div>
@show

<div class="card-footer">
    @section('equipeProjet-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $equipeProjets_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>