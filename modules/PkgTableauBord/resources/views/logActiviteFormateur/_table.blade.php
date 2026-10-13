{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('logActiviteFormateur-table')
<div class="card-body p-0 crud-card-body" id="logActiviteFormateurs-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $logActiviteFormateurs_permissions['edit-logActiviteFormateur'] || $logActiviteFormateurs_permissions['destroy-logActiviteFormateur'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="82" field="formateur_id" modelname="logActiviteFormateur" label="{!!ucfirst(__('PkgFormation::formateur.singular'))!!}" />
                <th class="text-center crud-actions-header">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('logActiviteFormateur-table-tbody')
            @foreach ($logActiviteFormateurs_data as $logActiviteFormateur)
                @php
                    $isEditable = $logActiviteFormateurs_permissions['edit-logActiviteFormateur'] && $logActiviteFormateurs_permissionsByItem['update'][$logActiviteFormateur->id];
                @endphp
                <tr id="logActiviteFormateur-row-{{$logActiviteFormateur->id}}" data-id="{{$logActiviteFormateur->id}}">
                    <x-checkbox-row :item="$logActiviteFormateur" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 82%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$logActiviteFormateur->id}}" data-field="formateur_id">
                        {{  $logActiviteFormateur->formateur }}

                    </td>
                    <td class="text-right wrappable crud-actions-cell" style="max-width: 15%;">
                        <div class="crud-actions-wrapper">
                        <div class="actions-secondary-group">

                        </div>

                        <div class="actions-main-group">
                        @if($logActiviteFormateurs_permissions['edit-logActiviteFormateur'])
                        <x-action-button :entity="$logActiviteFormateur" actionName="edit">
                        @if($logActiviteFormateurs_permissionsByItem['update'][$logActiviteFormateur->id])
                            <a href="{{ route('logActiviteFormateurs.edit', ['logActiviteFormateur' => $logActiviteFormateur->id]) }}" data-id="{{$logActiviteFormateur->id}}" class="btn btn-sm btn-default context-state editEntity btn-action-main">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($logActiviteFormateurs_permissions['show-logActiviteFormateur'])
                        <x-action-button :entity="$logActiviteFormateur" actionName="show">
                        @if($logActiviteFormateurs_permissionsByItem['view'][$logActiviteFormateur->id])
                            <a href="{{ route('logActiviteFormateurs.show', ['logActiviteFormateur' => $logActiviteFormateur->id]) }}" data-id="{{$logActiviteFormateur->id}}" class="btn btn-default btn-sm context-state showEntity btn-action-main">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$logActiviteFormateur" actionName="delete">
                        @if($logActiviteFormateurs_permissions['destroy-logActiviteFormateur'])
                        @if($logActiviteFormateurs_permissionsByItem['delete'][$logActiviteFormateur->id])
                            <form class="context-state" action="{{ route('logActiviteFormateurs.destroy',['logActiviteFormateur' => $logActiviteFormateur->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger d-none d-lg-inline deleteEntity btn-action-delete" data-id="{{$logActiviteFormateur->id}}">
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
    @section('logActiviteFormateur-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $logActiviteFormateurs_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>