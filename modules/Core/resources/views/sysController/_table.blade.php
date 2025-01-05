{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="sysControllers-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="module_id" label="{{ ucfirst(__('Core::sysModule.singular')) }}" />
                <x-sortable-column field="name" label="{{ ucfirst(__('Core::sysController.name')) }}" />
                <x-sortable-column field="description" label="{{ ucfirst(__('Core::sysController.description')) }}" />
                <x-sortable-column field="is_active" label="{{ ucfirst(__('Core::sysController.is_active')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sysControllers_data as $sysController)
                <tr>
                    <td>@limit($sysController->sysModule->name ?? '-', 80)</td>
                    <td>@limit($sysController->name, 80)</td>
                    <td>{!! $sysController->description !!}</td>
                    <td>@limit($sysController->is_active, 80)</td>
                    <td class="text-right">
                        @can('show-sysController')
                            <a href="{{ route('sysControllers.show', ['sysController' => $sysController->id]) }}" data-id="{{$sysController->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-sysController')
                            <a href="{{ route('sysControllers.edit', ['sysController' => $sysController->id]) }}" data-id="{{$sysController->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-sysController')
                            <form class="context-state" action="{{ route('sysControllers.destroy',['sysController' => $sysController->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$sysController->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="card-footer">
    @section('crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $sysControllers_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>