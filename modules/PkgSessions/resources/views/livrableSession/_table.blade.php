{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('livrableSession-table')
<div class="card-body p-0 crud-card-body" id="livrableSessions-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $livrableSessions_permissions['edit-livrableSession'] || $livrableSessions_permissions['destroy-livrableSession'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="4"  field="ordre" modelname="livrableSession" label="{!!ucfirst(__('PkgSessions::livrableSession.ordre'))!!}" />
                <x-sortable-column :sortable="true" width="26"  field="titre" modelname="livrableSession" label="{!!ucfirst(__('PkgSessions::livrableSession.titre'))!!}" />
                <x-sortable-column :sortable="true" width="26" field="session_formation_id" modelname="livrableSession" label="{!!ucfirst(__('PkgSessions::sessionFormation.singular'))!!}" />
                <x-sortable-column :sortable="true" width="26" field="nature_livrable_id" modelname="livrableSession" label="{!!ucfirst(__('PkgCreationProjet::natureLivrable.singular'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('livrableSession-table-tbody')
            @foreach ($livrableSessions_data as $livrableSession)
                @php
                    $isEditable = $livrableSessions_permissions['edit-livrableSession'] && $livrableSessions_permissionsByItem['update'][$livrableSession->id];
                @endphp
                <tr id="livrableSession-row-{{$livrableSession->id}}" data-id="{{$livrableSession->id}}">
                    <x-checkbox-row :item="$livrableSession" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 4%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$livrableSession->id}}" data-field="ordre">
                            <div class="sortable-button d-flex justify-content-left align-items-center" style="height: 100%;  min-height: 26px;">
                            <i class="fas fa-th-list" title="{{ $livrableSession->ordre }}"  data-toggle="tooltip" ></i>  
                        </div>

                    </td>
                    <td style="max-width: 26%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$livrableSession->id}}" data-field="titre">
                        {{ $livrableSession->titre }}

                    </td>
                    <td style="max-width: 26%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$livrableSession->id}}" data-field="session_formation_id">
                        {{  $livrableSession->sessionFormation }}

                    </td>
                    <td style="max-width: 26%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$livrableSession->id}}" data-field="nature_livrable_id">
                        {{  $livrableSession->natureLivrable }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($livrableSessions_permissions['edit-livrableSession'])
                        <x-action-button :entity="$livrableSession" actionName="edit">
                        @if($livrableSessions_permissionsByItem['update'][$livrableSession->id])
                            <a href="{{ route('livrableSessions.edit', ['livrableSession' => $livrableSession->id]) }}" data-id="{{$livrableSession->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($livrableSessions_permissions['show-livrableSession'])
                        <x-action-button :entity="$livrableSession" actionName="show">
                        @if($livrableSessions_permissionsByItem['view'][$livrableSession->id])
                            <a href="{{ route('livrableSessions.show', ['livrableSession' => $livrableSession->id]) }}" data-id="{{$livrableSession->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$livrableSession" actionName="delete">
                        @if($livrableSessions_permissions['destroy-livrableSession'])
                        @if($livrableSessions_permissionsByItem['delete'][$livrableSession->id])
                            <form class="context-state" action="{{ route('livrableSessions.destroy',['livrableSession' => $livrableSession->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$livrableSession->id}}">
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
    @section('livrableSession-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $livrableSessions_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>