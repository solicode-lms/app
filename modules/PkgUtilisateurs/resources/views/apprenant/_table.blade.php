{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0" id="apprenantsTable">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <th>{{ ucfirst(__('PkgUtilisateurs::apprenant.nom')) }}</th>
                <th>{{ ucfirst(__('PkgUtilisateurs::apprenant.prenom')) }}</th>
                <th>{{ ucfirst(__('PkgUtilisateurs::groupe.singular')) }}</th>
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $apprenant)
                <tr>
                    <td>{{ $apprenant->nom }}</td>
                    <td>{{ $apprenant->prenom }}</td>
                    <td>{{ $apprenant->groupe->code ?? '-' }}</td>
                    <td class="text-center">
                        @can('show-apprenant')
                            <a href="{{ route('apprenants.show', $apprenant) }}" data-id="{{$apprenant->id}}" class="btn btn-default btn-sm showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-apprenant')
                            <a href="{{ route('apprenants.edit', $apprenant) }}" data-id="{{$apprenant->id}}" class="btn btn-sm btn-default editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-apprenant')
                            <form action="{{ route('apprenants.destroy', $apprenant) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$apprenant->id}}"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce apprenant ?')">
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

