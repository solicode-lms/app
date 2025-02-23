{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="eDataFields-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                 <th>
                    Ordre
                </th>
                <x-sortable-column field="name" modelname="eDataField" label="{{ ucfirst(__('PkgGapp::eDataField.name')) }}" />
                <x-sortable-column field="data_type" modelname="eDataField" label="{{ ucfirst(__('PkgGapp::eDataField.data_type')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($eDataFields_data as $eDataField)
                <tr id="eDataField-row-{{$eDataField->id}}">
                    <td>@limit($eDataField->getOrder(), 50)</td>
                    <td>@limit($eDataField->name, 50)</td>
                    <td>@limit($eDataField->data_type, 50)</td>
                    <td class="text-right">

                        @can('show-eDataField')
                        @can('view', $eDataField)
                            <a href="{{ route('eDataFields.show', ['eDataField' => $eDataField->id]) }}" data-id="{{$eDataField->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-eDataField')
                        @can('update', $eDataField)
                            <a href="{{ route('eDataFields.edit', ['eDataField' => $eDataField->id]) }}" data-id="{{$eDataField->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-eDataField')
                        @can('delete', $eDataField)
                            <form class="context-state" action="{{ route('eDataFields.destroy',['eDataField' => $eDataField->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$eDataField->id}}">
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
    @section('eDataField-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $eDataFields_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>