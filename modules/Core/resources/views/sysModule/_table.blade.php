{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="sysModules-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="name" label="{{ ucfirst(__('Core::sysModule.name')) }}" />
                <x-sortable-column field="description" label="{{ ucfirst(__('Core::sysModule.description')) }}" />
                <x-sortable-column field="is_active" label="{{ ucfirst(__('Core::sysModule.is_active')) }}" />
                <x-sortable-column field="color_id" label="{{ ucfirst(__('Core::sysColor.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sysModules_data as $sysModule)
                <tr>
                    <td>@limit($sysModule->name, 80)</td>
                    <td>{!! $sysModule->description !!}</td>
                    <td>@limit($sysModule->is_active, 80)</td>
                    <td>@limit($sysModule->sysColor->name ?? '-', 80)</td>
                    <td class="text-right">
                        @can('show-sysModule')
                            <a href="{{ route('sysModules.show', ['sysModule' => $sysModule->id]) }}" data-id="{{$sysModule->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-sysModule')
                            <a href="{{ route('sysModules.edit', ['sysModule' => $sysModule->id]) }}" data-id="{{$sysModule->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-sysModule')
                            <form class="context-state" action="{{ route('sysModules.destroy',['sysModule' => $sysModule->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$sysModule->id}}">
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
        {{ $sysModules_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>