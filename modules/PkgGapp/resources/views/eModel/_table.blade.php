{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="eModels-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="code" label="{{ ucfirst(__('PkgGapp::eModel.code')) }}" />
                <x-sortable-column field="name" label="{{ ucfirst(__('PkgGapp::eModel.name')) }}" />
                <x-sortable-column field="icon" label="{{ ucfirst(__('PkgGapp::eModel.icon')) }}" />
                <x-sortable-column field="e_package_id" label="{{ ucfirst(__('PkgGapp::ePackage.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($eModels_data as $eModel)
                <tr>
                    <td>@limit($eModel->code, 80)</td>
                    <td>@limit($eModel->name, 80)</td>
                    <td>@limit($eModel->icon, 80)</td>
                    <td>@limit($eModel->ePackage->code ?? '-', 80)</td>
                    <td class="text-right">
                        @can('show-eModel')
                            <a href="{{ route('eModels.show', ['eModel' => $eModel->id]) }}" data-id="{{$eModel->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-eModel')
                        @can('update', $eModel)
                            <a href="{{ route('eModels.edit', ['eModel' => $eModel->id]) }}" data-id="{{$eModel->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-eModel')
                        @can('delete', $eModel)
                            <form class="context-state" action="{{ route('eModels.destroy',['eModel' => $eModel->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$eModel->id}}">
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
    @section('eModel-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $eModels_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>