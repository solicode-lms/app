{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="sysControllers-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="sys_module_id" label="{{ ucfirst(__('Core::sysModule.singular')) }}" />
                <x-sortable-column field="name" label="{{ ucfirst(__('Core::sysController.name')) }}" />
                <x-sortable-column field="is_active" label="{{ ucfirst(__('Core::sysController.is_active')) }}" />
                <x-sortable-column field="Permission" label="{{ ucfirst(__('PkgAutorisation::permission.plural')) }}" />

                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sysControllers_data as $sysController)
                <tr id="sysController-row-{{$sysController->id}}">
                    <td>@limit($sysController->sysModule->name ?? '-', 80)</td>
                    <td>@limit($sysController->name, 80)</td>
                    <td>@limit($sysController->is_active, 80)</td>
                    <td>
                        <ul>
                            @foreach ($sysController->permissions as $permission)
                                <li>{{ $permission }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="text-right">
                        @can('show-sysController')
                            <a href="{{ route('sysControllers.show', ['sysController' => $sysController->id]) }}" data-id="{{$sysController->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-sysController')
                        @can('update', $sysController)
                            <a href="{{ route('sysControllers.edit', ['sysController' => $sysController->id]) }}" data-id="{{$sysController->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-sysController')
                        @can('delete', $sysController)
                            <form class="context-state" action="{{ route('sysControllers.destroy',['sysController' => $sysController->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$sysController->id}}">
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
    @section('sysController-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $sysControllers_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>