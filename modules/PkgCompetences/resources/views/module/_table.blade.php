{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="modules-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="nom" label="{{ ucfirst(__('PkgCompetences::module.nom')) }}" />
                <x-sortable-column field="description" label="{{ ucfirst(__('PkgCompetences::module.description')) }}" />
                <x-sortable-column field="filiere_id" label="{{ ucfirst(__('PkgCompetences::filiere.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($modules_data as $module)
                <tr>
                    <td>@limit($module->nom, 80)</td>
                    <td>{!! $module->description !!}</td>
                    <td>@limit($module->filiere->code ?? '-', 80)</td>
                    <td class="text-right">
                        @can('show-module')
                            <a href="{{ route('modules.show', ['module' => $module->id]) }}" data-id="{{$module->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-module')
                            <a href="{{ route('modules.edit', ['module' => $module->id]) }}" data-id="{{$module->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-module')
                            <form class="context-state" action="{{ route('modules.destroy',['module' => $module->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$module->id}}">
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
    @section('module-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $modules_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>