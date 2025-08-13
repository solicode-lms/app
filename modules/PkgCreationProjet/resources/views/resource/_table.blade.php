{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('resource-table')
<div class="card-body p-0 crud-card-body" id="resources-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $resources_permissions['edit-resource'] || $resources_permissions['destroy-resource'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="41"  field="nom" modelname="resource" label="{!!ucfirst(__('PkgCreationProjet::resource.nom'))!!}" />
                <x-sortable-column :sortable="true" width="41"  field="lien" modelname="resource" label="{!!ucfirst(__('PkgCreationProjet::resource.lien'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('resource-table-tbody')
            @foreach ($resources_data as $resource)
                @php
                    $isEditable = $resources_permissions['edit-resource'] && $resources_permissionsByItem['update'][$resource->id];
                @endphp
                <tr id="resource-row-{{$resource->id}}" data-id="{{$resource->id}}">
                    <x-checkbox-row :item="$resource" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 41%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$resource->id}}" data-field="nom"  data-toggle="tooltip" title="{{ $resource->nom }}" >
                        {{ $resource->nom }}

                    </td>
                    <td style="max-width: 41%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$resource->id}}" data-field="lien"  data-toggle="tooltip" title="{{ $resource->lien }}" >
     @if($resource->lien)
    <a href="{{ $resource->lien }}" target="_blank">
        <i class="fas fa-link"></i>
    </a>
    @else
    —
    @endif


                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($resources_permissions['edit-resource'])
                        <x-action-button :entity="$resource" actionName="edit">
                        @if($resources_permissionsByItem['update'][$resource->id])
                            <a href="{{ route('resources.edit', ['resource' => $resource->id]) }}" data-id="{{$resource->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($resources_permissions['show-resource'])
                        <x-action-button :entity="$resource" actionName="show">
                        @if($resources_permissionsByItem['view'][$resource->id])
                            <a href="{{ route('resources.show', ['resource' => $resource->id]) }}" data-id="{{$resource->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$resource" actionName="delete">
                        @if($resources_permissions['destroy-resource'])
                        @if($resources_permissionsByItem['delete'][$resource->id])
                            <form class="context-state" action="{{ route('resources.destroy',['resource' => $resource->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$resource->id}}">
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
    @section('resource-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $resources_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>