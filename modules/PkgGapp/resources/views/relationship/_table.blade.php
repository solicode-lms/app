{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="relationships-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="source_model_id" label="{{ ucfirst(__('PkgGapp::iModel.singular')) }}" />
                <x-sortable-column field="target_model_id" label="{{ ucfirst(__('PkgGapp::iModel.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($relationships_data as $relationship)
                <tr>
                    <td>@limit($relationship->iModel->name ?? '-', 80)</td>
                    <td>@limit($relationship->iModel->name ?? '-', 80)</td>
                    <td class="text-right">
                        @can('show-relationship')
                            <a href="{{ route('relationships.show', ['relationship' => $relationship->id]) }}" data-id="{{$relationship->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-relationship')
                        @can('update', $relationship)
                            <a href="{{ route('relationships.edit', ['relationship' => $relationship->id]) }}" data-id="{{$relationship->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-relationship')
                        @can('delete', $relationship)
                            <form class="context-state" action="{{ route('relationships.destroy',['relationship' => $relationship->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$relationship->id}}">
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
    @section('relationship-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $relationships_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>