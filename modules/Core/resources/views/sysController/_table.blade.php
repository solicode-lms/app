{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('sysController-table')
<div class="card-body table-responsive p-0 crud-card-body" id="sysControllers-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="sys_module_id" modelname="sysController" label="{{ ucfirst(__('Core::sysModule.singular')) }}" />
                <x-sortable-column field="name" modelname="sysController" label="{{ ucfirst(__('Core::sysController.name')) }}" />
                <x-sortable-column field="is_active" modelname="sysController" label="{{ ucfirst(__('Core::sysController.is_active')) }}" />
                <x-sortable-column field="Permission" modelname="sysController" label="{{ ucfirst(__('PkgAutorisation::permission.plural')) }}" />

                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('sysController-table-tbody')
            @foreach ($sysControllers_data as $sysController)
                <tr id="sysController-row-{{$sysController->id}}">
                    <td>@limit($sysController->sysModule, 50)</td>
                    <td>@limit($sysController->name, 50)</td>
                    <td>@limit($sysController->is_active, 50)</td>
                    <td>
                        <ul>
                            @foreach ($sysController->controllerIdPermissions as $permission)
                                <li>{{ $permission }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="text-right">

                        @can('show-sysController')
                        @can('view', $sysController)
                            <a href="{{ route('sysControllers.show', ['sysController' => $sysController->id]) }}" data-id="{{$sysController->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
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
            @show
        </tbody>
    </table>
</div>
@show

<div class="card-footer">
    @section('sysController-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $sysControllers_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>