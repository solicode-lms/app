{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('alignementUa-table')
<div class="card-body p-0 crud-card-body" id="alignementUas-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $alignementUas_permissions['edit-alignementUa'] || $alignementUas_permissions['destroy-alignementUa'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="4"  field="ordre" modelname="alignementUa" label="{!!ucfirst(__('PkgSessions::alignementUa.ordre'))!!}" />
                <x-sortable-column :sortable="true" width="39" field="unite_apprentissage_id" modelname="alignementUa" label="{!!ucfirst(__('PkgCompetences::uniteApprentissage.singular'))!!}" />
                <x-sortable-column :sortable="true" width="39" field="session_formation_id" modelname="alignementUa" label="{!!ucfirst(__('PkgSessions::sessionFormation.singular'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('alignementUa-table-tbody')
            @foreach ($alignementUas_data as $alignementUa)
                @php
                    $isEditable = $alignementUas_permissions['edit-alignementUa'] && $alignementUas_permissionsByItem['update'][$alignementUa->id];
                @endphp
                <tr id="alignementUa-row-{{$alignementUa->id}}" data-id="{{$alignementUa->id}}">
                    <x-checkbox-row :item="$alignementUa" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 4%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$alignementUa->id}}" data-field="ordre">
                            <div class="sortable-button d-flex justify-content-left align-items-center" style="height: 100%;  min-height: 26px;">
                            <i class="fas fa-th-list" title="{{ $alignementUa->ordre }}"  data-toggle="tooltip" ></i>  
                        </div>

                    </td>
                    <td style="max-width: 39%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$alignementUa->id}}" data-field="unite_apprentissage_id">
                        {{  $alignementUa->uniteApprentissage }}

                    </td>
                    <td style="max-width: 39%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$alignementUa->id}}" data-field="session_formation_id">
                        {{  $alignementUa->sessionFormation }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($alignementUas_permissions['edit-alignementUa'])
                        <x-action-button :entity="$alignementUa" actionName="edit">
                        @if($alignementUas_permissionsByItem['update'][$alignementUa->id])
                            <a href="{{ route('alignementUas.edit', ['alignementUa' => $alignementUa->id]) }}" data-id="{{$alignementUa->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($alignementUas_permissions['show-alignementUa'])
                        <x-action-button :entity="$alignementUa" actionName="show">
                        @if($alignementUas_permissionsByItem['view'][$alignementUa->id])
                            <a href="{{ route('alignementUas.show', ['alignementUa' => $alignementUa->id]) }}" data-id="{{$alignementUa->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$alignementUa" actionName="delete">
                        @if($alignementUas_permissions['destroy-alignementUa'])
                        @if($alignementUas_permissionsByItem['delete'][$alignementUa->id])
                            <form class="context-state" action="{{ route('alignementUas.destroy',['alignementUa' => $alignementUa->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$alignementUa->id}}">
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
    @section('alignementUa-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $alignementUas_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>