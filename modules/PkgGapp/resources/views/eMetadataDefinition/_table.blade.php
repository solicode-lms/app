{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="eMetadataDefinitions-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="code" label="{{ ucfirst(__('PkgGapp::eMetadataDefinition.code')) }}" />
                <x-sortable-column field="name" label="{{ ucfirst(__('PkgGapp::eMetadataDefinition.name')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($eMetadataDefinitions_data as $eMetadataDefinition)
                <tr>
                    <td>@limit($eMetadataDefinition->code, 80)</td>
                    <td>@limit($eMetadataDefinition->name, 80)</td>
                    <td class="text-right">
                        @can('show-eMetadataDefinition')
                            <a href="{{ route('eMetadataDefinitions.show', ['eMetadataDefinition' => $eMetadataDefinition->id]) }}" data-id="{{$eMetadataDefinition->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-eMetadataDefinition')
                        @can('update', $eMetadataDefinition)
                            <a href="{{ route('eMetadataDefinitions.edit', ['eMetadataDefinition' => $eMetadataDefinition->id]) }}" data-id="{{$eMetadataDefinition->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-eMetadataDefinition')
                        @can('delete', $eMetadataDefinition)
                            <form class="context-state" action="{{ route('eMetadataDefinitions.destroy',['eMetadataDefinition' => $eMetadataDefinition->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$eMetadataDefinition->id}}">
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
    @section('eMetadataDefinition-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $eMetadataDefinitions_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>