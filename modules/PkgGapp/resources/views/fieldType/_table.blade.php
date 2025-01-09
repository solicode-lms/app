{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="fieldTypes-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="name" label="{{ ucfirst(__('PkgGapp::fieldType.name')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($fieldTypes_data as $fieldType)
                <tr>
                    <td>@limit($fieldType->name, 80)</td>
                    <td class="text-right">
                        @can('show-fieldType')
                            <a href="{{ route('fieldTypes.show', ['fieldType' => $fieldType->id]) }}" data-id="{{$fieldType->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-fieldType')
                        @can('update', $fieldType)
                            <a href="{{ route('fieldTypes.edit', ['fieldType' => $fieldType->id]) }}" data-id="{{$fieldType->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-fieldType')
                        @can('delete', $fieldType)
                            <form class="context-state" action="{{ route('fieldTypes.destroy',['fieldType' => $fieldType->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$fieldType->id}}">
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
    @section('fieldType-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $fieldTypes_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>