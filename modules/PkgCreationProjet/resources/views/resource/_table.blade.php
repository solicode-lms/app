{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('resource-table')
<div class="card-body table-responsive p-0 crud-card-body" id="resources-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                <x-sortable-column width="42.5"  field="nom" modelname="resource" label="{{ ucfirst(__('PkgCreationProjet::resource.nom')) }}" />
                <x-sortable-column width="42.5"  field="lien" modelname="resource" label="{{ ucfirst(__('PkgCreationProjet::resource.lien')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('resource-table-tbody')
            @foreach ($resources_data as $resource)
                <tr id="resource-row-{{$resource->id}}">
                    <td style="max-width: 42.5%;" class="text-truncate" data-toggle="tooltip" title="{{ $resource->nom }}" >
                    <x-field :data="$resource" field="nom">
                        {{ $resource->nom }}
                    </x-field>
                    </td>
                    <td style="max-width: 42.5%;" class="text-truncate" data-toggle="tooltip" title="{{ $resource->lien }}" >
                    <x-field :data="$resource" field="lien">
                        {{ $resource->lien }}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-resource')
                        @can('update', $resource)
                            <a href="{{ route('resources.edit', ['resource' => $resource->id]) }}" data-id="{{$resource->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-resource')
                        @can('view', $resource)
                            <a href="{{ route('resources.show', ['resource' => $resource->id]) }}" data-id="{{$resource->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
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