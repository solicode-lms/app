{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="metadata-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="metadata_type_id" label="{{ ucfirst(__('PkgGapp::metadataType.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($metadata_data as $metadatum)
                <tr>
                    <td>@limit($metadatum->metadataType->name ?? '-', 80)</td>
                    <td class="text-right">
                        @can('show-metadatum')
                            <a href="{{ route('metadata.show', ['metadatum' => $metadatum->id]) }}" data-id="{{$metadatum->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-metadatum')
                        @can('update', $metadatum)
                            <a href="{{ route('metadata.edit', ['metadatum' => $metadatum->id]) }}" data-id="{{$metadatum->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-metadatum')
                        @can('delete', $metadatum)
                            <form class="context-state" action="{{ route('metadata.destroy',['metadatum' => $metadatum->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$metadatum->id}}">
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
    @section('metadatum-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $metadata_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>