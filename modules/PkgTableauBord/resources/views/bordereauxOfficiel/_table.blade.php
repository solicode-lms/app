{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('bordereauxOfficiel-table')
<div class="card-body p-0 crud-card-body" id="bordereauxOfficiels-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $bordereauxOfficiels_permissions['edit-bordereauxOfficiel'] || $bordereauxOfficiels_permissions['destroy-bordereauxOfficiel'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="16.4" field="module_id" modelname="bordereauxOfficiel" label="{!!ucfirst(__('PkgFormation::module.singular'))!!}" />
                <x-sortable-column :sortable="true" width="16.4" field="groupe_id" modelname="bordereauxOfficiel" label="{!!ucfirst(__('PkgApprenants::groupe.singular'))!!}" />
                <x-sortable-column :sortable="true" width="16.4" field="annee_formation_id" modelname="bordereauxOfficiel" label="{!!ucfirst(__('PkgFormation::anneeFormation.singular'))!!}" />
                <x-sortable-column :sortable="true" width="16.4"  field="statut" modelname="bordereauxOfficiel" label="{!!ucfirst(__('PkgTableauBord::bordereauxOfficiel.statut'))!!}" />
                <x-sortable-column :sortable="true" width="16.4" field="genere_par_user_id" modelname="bordereauxOfficiel" label="{!!ucfirst(__('PkgAutorisation::user.singular'))!!}" />
                <th class="text-center crud-actions-header">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('bordereauxOfficiel-table-tbody')
            @foreach ($bordereauxOfficiels_data as $bordereauxOfficiel)
                @php
                    $isEditable = $bordereauxOfficiels_permissions['edit-bordereauxOfficiel'] && $bordereauxOfficiels_permissionsByItem['update'][$bordereauxOfficiel->id];
                @endphp
                <tr id="bordereauxOfficiel-row-{{$bordereauxOfficiel->id}}" data-id="{{$bordereauxOfficiel->id}}">
                    <x-checkbox-row :item="$bordereauxOfficiel" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 16.4%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$bordereauxOfficiel->id}}" data-field="module_id">
                        {{  $bordereauxOfficiel->module }}

                    </td>
                    <td style="max-width: 16.4%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$bordereauxOfficiel->id}}" data-field="groupe_id">
                        {{  $bordereauxOfficiel->groupe }}

                    </td>
                    <td style="max-width: 16.4%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$bordereauxOfficiel->id}}" data-field="annee_formation_id">
                        {{  $bordereauxOfficiel->anneeFormation }}

                    </td>
                    <td style="max-width: 16.4%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$bordereauxOfficiel->id}}" data-field="statut">
                        {{ $bordereauxOfficiel->statut }}

                    </td>
                    <td style="max-width: 16.4%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$bordereauxOfficiel->id}}" data-field="genere_par_user_id">
                        {{  $bordereauxOfficiel->genereParUser }}

                    </td>
                    <td class="text-right wrappable crud-actions-cell" style="max-width: 15%;">
                        <div class="crud-actions-wrapper">
                        <div class="actions-secondary-group">

                        </div>

                        <div class="actions-main-group">
                        @if($bordereauxOfficiels_permissions['edit-bordereauxOfficiel'])
                        <x-action-button :entity="$bordereauxOfficiel" actionName="edit">
                        @if($bordereauxOfficiels_permissionsByItem['update'][$bordereauxOfficiel->id])
                            <a href="{{ route('bordereauxOfficiels.edit', ['bordereauxOfficiel' => $bordereauxOfficiel->id]) }}" data-id="{{$bordereauxOfficiel->id}}" class="btn btn-sm btn-default context-state editEntity btn-action-main">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($bordereauxOfficiels_permissions['show-bordereauxOfficiel'])
                        <x-action-button :entity="$bordereauxOfficiel" actionName="show">
                        @if($bordereauxOfficiels_permissionsByItem['view'][$bordereauxOfficiel->id])
                            <a href="{{ route('bordereauxOfficiels.show', ['bordereauxOfficiel' => $bordereauxOfficiel->id]) }}" data-id="{{$bordereauxOfficiel->id}}" class="btn btn-default btn-sm context-state showEntity btn-action-main">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$bordereauxOfficiel" actionName="delete">
                        @if($bordereauxOfficiels_permissions['destroy-bordereauxOfficiel'])
                        @if($bordereauxOfficiels_permissionsByItem['delete'][$bordereauxOfficiel->id])
                            <form class="context-state" action="{{ route('bordereauxOfficiels.destroy',['bordereauxOfficiel' => $bordereauxOfficiel->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger d-none d-lg-inline deleteEntity btn-action-delete" data-id="{{$bordereauxOfficiel->id}}">
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
    @section('bordereauxOfficiel-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $bordereauxOfficiels_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>