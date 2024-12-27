{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0" id="formateursTable">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <th>{{ ucfirst(__('PkgUtilisateurs::formateur.nom')) }}</th>
                <th>{{ ucfirst(__('PkgUtilisateurs::formateur.prenom')) }}</th>
                <th>{{ ucfirst(__('PkgUtilisateurs::formateur.adresse')) }}</th>
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $formateur)
                <tr>
                    <td>{{ $formateur->nom }}</td>
                    <td>{{ $formateur->prenom }}</td>
                    <td>{{ $formateur->adresse }}</td>
                    <td class="text-center">
                        @can('show-formateur')
                            <a href="{{ route('formateurs.show', $formateur) }}" data-id="{{$formateur->id}}" class="btn btn-default btn-sm showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-formateur')
                            <a href="{{ route('formateurs.edit', $formateur) }}" data-id="{{$formateur->id}}" class="btn btn-sm btn-default editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-formateur')
                            <form action="{{ route('formateurs.destroy', $formateur) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$formateur->id}}"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce formateur ?')">
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

