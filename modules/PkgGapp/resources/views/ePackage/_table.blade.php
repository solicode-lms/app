{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('ePackage-table')
<div class="card-body p-0 crud-card-body" id="ePackages-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $ePackages_permissions['edit-ePackage'] || $ePackages_permissions['destroy-ePackage'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="82"  field="name" modelname="ePackage" label="{!!ucfirst(__('PkgGapp::ePackage.name'))!!}" />
                <th class="text-center crud-actions-header">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('ePackage-table-tbody')
            @foreach ($ePackages_data as $ePackage)
                @php
                    $isEditable = $ePackages_permissions['edit-ePackage'] && $ePackages_permissionsByItem['update'][$ePackage->id];
                @endphp
                <tr id="ePackage-row-{{$ePackage->id}}" data-id="{{$ePackage->id}}">
                    <x-checkbox-row :item="$ePackage" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 82%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$ePackage->id}}" data-field="name">
                        {{ $ePackage->name }}

                    </td>
                    <td class="text-right wrappable crud-actions-cell" style="max-width: 15%;">
                        <div class="crud-actions-wrapper">
                        <div class="actions-secondary-group">

                        </div>

                        <div class="actions-main-group">
                        @if($ePackages_permissions['edit-ePackage'])
                        <x-action-button :entity="$ePackage" actionName="edit">
                        @if($ePackages_permissionsByItem['update'][$ePackage->id])
                            <a href="{{ route('ePackages.edit', ['ePackage' => $ePackage->id]) }}" data-id="{{$ePackage->id}}" class="btn btn-sm btn-default context-state editEntity btn-action-main">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($ePackages_permissions['show-ePackage'])
                        <x-action-button :entity="$ePackage" actionName="show">
                        @if($ePackages_permissionsByItem['view'][$ePackage->id])
                            <a href="{{ route('ePackages.show', ['ePackage' => $ePackage->id]) }}" data-id="{{$ePackage->id}}" class="btn btn-default btn-sm context-state showEntity btn-action-main">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$ePackage" actionName="delete">
                        @if($ePackages_permissions['destroy-ePackage'])
                        @if($ePackages_permissionsByItem['delete'][$ePackage->id])
                            <form class="context-state" action="{{ route('ePackages.destroy',['ePackage' => $ePackage->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger d-none d-lg-inline deleteEntity btn-action-delete" data-id="{{$ePackage->id}}">
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
    @section('ePackage-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $ePackages_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>