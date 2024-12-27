{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0" id="livrablesTable">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <th>{{ ucfirst(__('PkgCreationProjet::livrable.titre')) }}</th>
                <th>{{ ucfirst(__('PkgCreationProjet::projet.singular')) }}</th>
                <th>{{ ucfirst(__('PkgCreationProjet::natureLivrable.singular')) }}</th>
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $livrable)
                <tr>
                    <td>{{ $livrable->titre }}</td>
                    <td>{{ $livrable->projet->titre ?? '-' }}</td>
                    <td>{{ $livrable->natureLivrable->nom ?? '-' }}</td>
                    <td class="text-center">
                        @can('show-livrable')
                            <a href="{{ route('livrables.show', $livrable) }}" data-id="{{$livrable->id}}" class="btn btn-default btn-sm showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-livrable')
                            <a href="{{ route('livrables.edit', $livrable) }}" data-id="{{$livrable->id}}" class="btn btn-sm btn-default editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-livrable')
                            <form action="{{ route('livrables.destroy', $livrable) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$livrable->id}}"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce livrable ?')">
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

