{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('featureDomain-table')
<div class="card-body p-0 crud-card-body" id="featureDomains-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $featureDomains_permissions['edit-featureDomain'] || $featureDomains_permissions['destroy-featureDomain'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="41"  field="name" modelname="featureDomain" label="{!!ucfirst(__('Core::featureDomain.name'))!!}" />
                <x-sortable-column :sortable="true" width="41" field="sys_module_id" modelname="featureDomain" label="{!!ucfirst(__('Core::sysModule.singular'))!!}" />
                <th class="text-center crud-actions-header">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('featureDomain-table-tbody')
            @foreach ($featureDomains_data as $featureDomain)
                @php
                    $isEditable = $featureDomains_permissions['edit-featureDomain'] && $featureDomains_permissionsByItem['update'][$featureDomain->id];
                @endphp
                <tr id="featureDomain-row-{{$featureDomain->id}}" data-id="{{$featureDomain->id}}">
                    <x-checkbox-row :item="$featureDomain" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 41%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$featureDomain->id}}" data-field="name">
                        {{ $featureDomain->name }}

                    </td>
                    <td style="max-width: 41%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$featureDomain->id}}" data-field="sys_module_id">
                        {{  $featureDomain->sysModule }}

                    </td>
                    <td class="text-right wrappable crud-actions-cell" style="max-width: 15%;">
                        <div class="crud-actions-wrapper">
                        <div class="actions-secondary-group">

                        </div>

                        <div class="actions-main-group">
                        @if($featureDomains_permissions['edit-featureDomain'])
                        <x-action-button :entity="$featureDomain" actionName="edit">
                        @if($featureDomains_permissionsByItem['update'][$featureDomain->id])
                            <a href="{{ route('featureDomains.edit', ['featureDomain' => $featureDomain->id]) }}" data-id="{{$featureDomain->id}}" class="btn btn-sm btn-default context-state editEntity btn-action-main">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($featureDomains_permissions['show-featureDomain'])
                        <x-action-button :entity="$featureDomain" actionName="show">
                        @if($featureDomains_permissionsByItem['view'][$featureDomain->id])
                            <a href="{{ route('featureDomains.show', ['featureDomain' => $featureDomain->id]) }}" data-id="{{$featureDomain->id}}" class="btn btn-default btn-sm context-state showEntity btn-action-main">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$featureDomain" actionName="delete">
                        @if($featureDomains_permissions['destroy-featureDomain'])
                        @if($featureDomains_permissionsByItem['delete'][$featureDomain->id])
                            <form class="context-state" action="{{ route('featureDomains.destroy',['featureDomain' => $featureDomain->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger d-none d-lg-inline deleteEntity btn-action-delete" data-id="{{$featureDomain->id}}">
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
    @section('featureDomain-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $featureDomains_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>