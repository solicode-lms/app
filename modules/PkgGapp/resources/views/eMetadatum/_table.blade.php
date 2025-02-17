{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="eMetadata-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                 <th>
                    Value
                </th>
                <x-sortable-column field="e_model_id" label="{{ ucfirst(__('PkgGapp::eModel.singular')) }}" />
                <x-sortable-column field="e_data_field_id" label="{{ ucfirst(__('PkgGapp::eDataField.singular')) }}" />
                <x-sortable-column field="e_metadata_definition_id" label="{{ ucfirst(__('PkgGapp::eMetadataDefinition.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($eMetadata_data as $eMetadatum)
                <tr id="eMetadatum-row-{{$eMetadatum->id}}">
                    <td>@limit($eMetadatum->getValue(), 80)</td>
                    <td>@limit($eMetadatum->eModel, 80)</td>
                    <td>@limit($eMetadatum->eDataField, 80)</td>
                    <td>@limit($eMetadatum->eMetadataDefinition, 80)</td>
                    <td class="text-right">

                        @can('show-eMetadatum')
                            <a href="{{ route('eMetadata.show', ['eMetadatum' => $eMetadatum->id]) }}" data-id="{{$eMetadatum->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-eMetadatum')
                        @can('update', $eMetadatum)
                            <a href="{{ route('eMetadata.edit', ['eMetadatum' => $eMetadatum->id]) }}" data-id="{{$eMetadatum->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-eMetadatum')
                        @can('delete', $eMetadatum)
                            <form class="context-state" action="{{ route('eMetadata.destroy',['eMetadatum' => $eMetadatum->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$eMetadatum->id}}">
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
    @section('eMetadatum-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $eMetadata_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>