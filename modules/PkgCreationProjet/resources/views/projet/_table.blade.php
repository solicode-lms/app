{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0" id="projetsTable">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <th>{{ ucfirst(__('PkgCreationProjet::projet.titre')) }}</th>
                <th>{{ ucfirst(__('PkgCreationProjet::projet.date_debut')) }}</th>
                <th>{{ ucfirst(__('PkgCreationProjet::projet.date_fin')) }}</th>
                <th>{{ ucfirst(__('PkgUtilisateurs::formateur.singular')) }}</th>
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $projet)
                <tr>
                    <td>{{ $projet->titre }}</td>
                    <td>{{ $projet->date_debut }}</td>
                    <td>{{ $projet->date_fin }}</td>
                    <td>{{ $projet->formateur->nom ?? '-' }}</td>
                    <td class="text-center">
                        @can('show-projet')
                            <a href="{{ route('projets.show', $projet) }}" data-id="{{$projet->id}}" class="btn btn-default btn-sm showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-projet')
                            <a href="{{ route('projets.edit', $projet) }}" data-id="{{$projet->id}}" class="btn btn-sm btn-default editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-projet')
                            <form action="{{ route('projets.destroy', $projet) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$projet->id}}">
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

