{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="iModels-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="name" label="{{ ucfirst(__('PkgGapp::iModel.name')) }}" />
                <x-sortable-column field="icon" label="{{ ucfirst(__('PkgGapp::iModel.icon')) }}" />
                <x-sortable-column field="i_package_id" label="{{ ucfirst(__('PkgGapp::iPackage.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($iModels_data as $iModel)
                <tr>
                    <td>@limit($iModel->name, 80)</td>
                    <td>@limit($iModel->icon, 80)</td>
                    <td>@limit($iModel->iPackage->name ?? '-', 80)</td>
                    <td class="text-right">
                        @can('show-iModel')
                            <a href="{{ route('iModels.show', ['iModel' => $iModel->id]) }}" data-id="{{$iModel->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-iModel')
                        @can('update', $iModel)
                            <a href="{{ route('iModels.edit', ['iModel' => $iModel->id]) }}" data-id="{{$iModel->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-iModel')
                        @can('delete', $iModel)
                            <form class="context-state" action="{{ route('iModels.destroy',['iModel' => $iModel->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$iModel->id}}">
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
    @section('iModel-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $iModels_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>