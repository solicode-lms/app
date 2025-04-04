{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('module-table')
<div class="card-body table-responsive p-0 crud-card-body" id="modules-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="code" modelname="module" label="{{ ucfirst(__('PkgFormation::module.code')) }}" />
                <x-sortable-column field="nom" modelname="module" label="{{ ucfirst(__('PkgFormation::module.nom')) }}" />
                <x-sortable-column field="masse_horaire" modelname="module" label="{{ ucfirst(__('PkgFormation::module.masse_horaire')) }}" />
                <x-sortable-column field="filiere_id" modelname="module" label="{{ ucfirst(__('PkgFormation::filiere.singular')) }}" />
                <x-sortable-column field="Competence" modelname="module" label="{{ ucfirst(__('PkgCompetences::competence.plural')) }}" />

                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('module-table-tbody')
            @foreach ($modules_data as $module)
                <tr id="module-row-{{$module->id}}">
                    <td>
                     <span @if(strlen($module->code) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $module->code }}" 
                        @endif>
                        {{ Str::limit($module->code, 40) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($module->nom) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $module->nom }}" 
                        @endif>
                        {{ Str::limit($module->nom, 40) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($module->masse_horaire) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $module->masse_horaire }}" 
                        @endif>
                        {{ Str::limit($module->masse_horaire, 40) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($module->filiere) > 50) 
                            data-toggle="tooltip" 
                            title="{{ $module->filiere }}" 
                        @endif>
                        {{ Str::limit($module->filiere, 50) }}
                    </span>
                    </td>
                    <td>
                        <ul>
                            @foreach ($module->competences as $competence)
                                <li @if(strlen($competence) > 40) data-toggle="tooltip" title="{{$competence}}" @endif>@limit($competence, 40)</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="text-right">

                        @can('show-module')
                        @can('view', $module)
                            <a href="{{ route('modules.show', ['module' => $module->id]) }}" data-id="{{$module->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-module')
                        @can('update', $module)
                            <a href="{{ route('modules.edit', ['module' => $module->id]) }}" data-id="{{$module->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-module')
                        @can('delete', $module)
                            <form class="context-state" action="{{ route('modules.destroy',['module' => $module->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$module->id}}">
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
    @section('module-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $modules_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>