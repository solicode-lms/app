{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="metadataTypes-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="name" label="{{ ucfirst(__('PkgGapp::metadataType.name')) }}" />
                <x-sortable-column field="code" label="{{ ucfirst(__('PkgGapp::metadataType.code')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($metadataTypes_data as $metadataType)
                <tr>
                    <td>@limit($metadataType->name, 80)</td>
                    <td>@limit($metadataType->code, 80)</td>
                    <td class="text-right">
                        @can('show-metadataType')
                            <a href="{{ route('metadataTypes.show', ['metadataType' => $metadataType->id]) }}" data-id="{{$metadataType->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-metadataType')
                        @can('update', $metadataType)
                            <a href="{{ route('metadataTypes.edit', ['metadataType' => $metadataType->id]) }}" data-id="{{$metadataType->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-metadataType')
                        @can('delete', $metadataType)
                            <form class="context-state" action="{{ route('metadataTypes.destroy',['metadataType' => $metadataType->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$metadataType->id}}">
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
    @section('metadataType-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $metadataTypes_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>