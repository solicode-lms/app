{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="dataFields-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="name" label="{{ ucfirst(__('PkgGapp::dataField.name')) }}" />
                <x-sortable-column field="i_model_id" label="{{ ucfirst(__('PkgGapp::iModel.singular')) }}" />
                <x-sortable-column field="field_type_id" label="{{ ucfirst(__('PkgGapp::fieldType.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dataFields_data as $dataField)
                <tr>
                    <td>@limit($dataField->name, 80)</td>
                    <td>@limit($dataField->iModel->name ?? '-', 80)</td>
                    <td>@limit($dataField->fieldType->name ?? '-', 80)</td>
                    <td class="text-right">
                        @can('show-dataField')
                            <a href="{{ route('dataFields.show', ['dataField' => $dataField->id]) }}" data-id="{{$dataField->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-dataField')
                        @can('update', $dataField)
                            <a href="{{ route('dataFields.edit', ['dataField' => $dataField->id]) }}" data-id="{{$dataField->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-dataField')
                        @can('delete', $dataField)
                            <form class="context-state" action="{{ route('dataFields.destroy',['dataField' => $dataField->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$dataField->id}}">
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
    @section('dataField-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $dataFields_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>