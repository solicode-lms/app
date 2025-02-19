{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="resources-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="nom" modelname="resource" label="{{ ucfirst(__('PkgCreationProjet::resource.nom')) }}" />
                <x-sortable-column field="lien" modelname="resource" label="{{ ucfirst(__('PkgCreationProjet::resource.lien')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($resources_data as $resource)
                <tr id="resource-row-{{$resource->id}}">
                    <td>@limit($resource->nom, 80)</td>
                    <td>@limit($resource->lien, 80)</td>
                    <td class="text-right">

                        @can('show-resource')
                            <a href="{{ route('resources.show', ['resource' => $resource->id]) }}" data-id="{{$resource->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-resource')
                        @can('update', $resource)
                            <a href="{{ route('resources.edit', ['resource' => $resource->id]) }}" data-id="{{$resource->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-resource')
                        @can('delete', $resource)
                            <form class="context-state" action="{{ route('resources.destroy',['resource' => $resource->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$resource->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endcan
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="card-footer">
    @section('resource-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $resources_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>