{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0" id="permissionsTable">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <th>{{ ucfirst(__('PkgAutorisation::permission.name')) }}</th>
                <th>{{ ucfirst(__('Core::sysController.singular')) }}</th>
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $permission)
                <tr>
                    <td>{{ $permission->name }}</td>
                    <td>{{ $permission->sysController->name ?? '-' }}</td>
                    <td class="text-center">
                        @can('show-permission')
                            <a href="{{ route('permissions.show', $permission) }}" data-id="{{$permission->id}}" class="btn btn-default btn-sm showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-permission')
                            <a href="{{ route('permissions.edit', $permission) }}" data-id="{{$permission->id}}" class="btn btn-sm btn-default editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-permission')
                            <form action="{{ route('permissions.destroy', $permission) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$permission->id}}"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce permission ?')">
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

