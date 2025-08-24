{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('nationalite-table')
<div class="card-body p-0 crud-card-body" id="nationalites-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $nationalites_permissions['edit-nationalite'] || $nationalites_permissions['destroy-nationalite'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="82"  field="code" modelname="nationalite" label="{!!ucfirst(__('PkgApprenants::nationalite.code'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('nationalite-table-tbody')
            @foreach ($nationalites_data as $nationalite)
                @php
                    $isEditable = $nationalites_permissions['edit-nationalite'] && $nationalites_permissionsByItem['update'][$nationalite->id];
                @endphp
                <tr id="nationalite-row-{{$nationalite->id}}" data-id="{{$nationalite->id}}">
                    <x-checkbox-row :item="$nationalite" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 82%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$nationalite->id}}" data-field="code">
                        {{ $nationalite->code }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($nationalites_permissions['edit-nationalite'])
                        <x-action-button :entity="$nationalite" actionName="edit">
                        @if($nationalites_permissionsByItem['update'][$nationalite->id])
                            <a href="{{ route('nationalites.edit', ['nationalite' => $nationalite->id]) }}" data-id="{{$nationalite->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($nationalites_permissions['show-nationalite'])
                        <x-action-button :entity="$nationalite" actionName="show">
                        @if($nationalites_permissionsByItem['view'][$nationalite->id])
                            <a href="{{ route('nationalites.show', ['nationalite' => $nationalite->id]) }}" data-id="{{$nationalite->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$nationalite" actionName="delete">
                        @if($nationalites_permissions['destroy-nationalite'])
                        @if($nationalites_permissionsByItem['delete'][$nationalite->id])
                            <form class="context-state" action="{{ route('nationalites.destroy',['nationalite' => $nationalite->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$nationalite->id}}">
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
    @section('nationalite-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $nationalites_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>