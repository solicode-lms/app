{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0" id="modulesTable">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <th>{{ ucfirst(__('PkgCompetences::module.nom')) }}</th>
                <th>{{ ucfirst(__('PkgCompetences::module.description')) }}</th>
                <th>{{ ucfirst(__('PkgCompetences::filiere.singular')) }}</th>
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $module)
                <tr>
                    <td>{{ $module->nom }}</td>
                    <td>{{ $module->description }}</td>
                    <td>{{ $module->filiere->code ?? '-' }}</td>
                    <td class="text-center">
                        @can('show-module')
                            <a href="{{ route('modules.show', $module) }}" data-id="{{$module->id}}" class="btn btn-default btn-sm showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-module')
                            <a href="{{ route('modules.edit', $module) }}" data-id="{{$module->id}}" class="btn btn-sm btn-default editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-module')
                            <form action="{{ route('modules.destroy', $module) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$module->id}}"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce module ?')">
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

