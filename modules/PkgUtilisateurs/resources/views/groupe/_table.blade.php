{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0" id="groupesTable">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <th>{{ ucfirst(__('PkgUtilisateurs::groupe.code')) }}</th>
                <th>{{ ucfirst(__('PkgUtilisateurs::groupe.nom')) }}</th>
                <th>{{ ucfirst(__('PkgCompetences::filiere.singular')) }}</th>
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $groupe)
                <tr>
                    <td>{{ $groupe->code }}</td>
                    <td>{{ $groupe->nom }}</td>
                    <td>{{ $groupe->filiere->code ?? '-' }}</td>
                    <td class="text-center">
                        @can('show-groupe')
                            <a href="{{ route('groupes.show', $groupe) }}" data-id="{{$groupe->id}}" class="btn btn-default btn-sm showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-groupe')
                            <a href="{{ route('groupes.edit', $groupe) }}" data-id="{{$groupe->id}}" class="btn btn-sm btn-default editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-groupe')
                            <form action="{{ route('groupes.destroy', $groupe) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$groupe->id}}"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce groupe ?')">
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

