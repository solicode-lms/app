{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('userModelFilter-table')
<div class="card-body p-0 crud-card-body" id="userModelFilters-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $userModelFilters_permissions['edit-userModelFilter'] || $userModelFilters_permissions['destroy-userModelFilter'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="82" field="user_id" modelname="userModelFilter" label="{!!ucfirst(__('PkgAutorisation::user.singular'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('userModelFilter-table-tbody')
            @foreach ($userModelFilters_data as $userModelFilter)
                @php
                    $isEditable = $userModelFilters_permissions['edit-userModelFilter'] && $userModelFilters_permissionsByItem['update'][$userModelFilter->id];
                @endphp
                <tr id="userModelFilter-row-{{$userModelFilter->id}}" data-id="{{$userModelFilter->id}}">
                    <x-checkbox-row :item="$userModelFilter" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 82%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$userModelFilter->id}}" data-field="user_id">
                        {{  $userModelFilter->user }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($userModelFilters_permissions['edit-userModelFilter'])
                        <x-action-button :entity="$userModelFilter" actionName="edit">
                        @if($userModelFilters_permissionsByItem['update'][$userModelFilter->id])
                            <a href="{{ route('userModelFilters.edit', ['userModelFilter' => $userModelFilter->id]) }}" data-id="{{$userModelFilter->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($userModelFilters_permissions['show-userModelFilter'])
                        <x-action-button :entity="$userModelFilter" actionName="show">
                        @if($userModelFilters_permissionsByItem['view'][$userModelFilter->id])
                            <a href="{{ route('userModelFilters.show', ['userModelFilter' => $userModelFilter->id]) }}" data-id="{{$userModelFilter->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$userModelFilter" actionName="delete">
                        @if($userModelFilters_permissions['destroy-userModelFilter'])
                        @if($userModelFilters_permissionsByItem['delete'][$userModelFilter->id])
                            <form class="context-state" action="{{ route('userModelFilters.destroy',['userModelFilter' => $userModelFilter->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$userModelFilter->id}}">
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
    @section('userModelFilter-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $userModelFilters_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>