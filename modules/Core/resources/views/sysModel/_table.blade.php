{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="sysModels-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="name" modelname="sysModel" label="{{ ucfirst(__('Core::sysModel.name')) }}" />
                <x-sortable-column field="sys_module_id" modelname="sysModel" label="{{ ucfirst(__('Core::sysModel.sys_module_id')) }}" />
                <x-sortable-column field="sys_color_id" modelname="sysModel" label="{{ ucfirst(__('Core::sysModel.sys_color_id')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('sysModel-table-tbody')
            @foreach ($sysModels_data as $sysModel)
                <tr id="sysModel-row-{{$sysModel->id}}">
                    <td>@limit($sysModel->name, 50)</td>
                    <td>@limit($sysModel->sysModule, 50)</td>
                    <td>@limit($sysModel->sysColor, 50)</td>
                    <td class="text-right">

                        @can('show-sysModel')
                        @can('view', $sysModel)
                            <a href="{{ route('sysModels.show', ['sysModel' => $sysModel->id]) }}" data-id="{{$sysModel->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-sysModel')
                        @can('update', $sysModel)
                            <a href="{{ route('sysModels.edit', ['sysModel' => $sysModel->id]) }}" data-id="{{$sysModel->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-sysModel')
                        @can('delete', $sysModel)
                            <form class="context-state" action="{{ route('sysModels.destroy',['sysModel' => $sysModel->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$sysModel->id}}">
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

<div class="card-footer">
    @section('sysModel-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $sysModels_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>