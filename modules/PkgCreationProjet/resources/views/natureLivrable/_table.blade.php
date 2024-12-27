{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0" id="natureLivrablesTable">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <th>{{ ucfirst(__('PkgCreationProjet::natureLivrable.nom')) }}</th>
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $natureLivrable)
                <tr>
                    <td>{{ $natureLivrable->nom }}</td>
                    <td class="text-center">
                        @can('show-natureLivrable')
                            <a href="{{ route('natureLivrables.show', $natureLivrable) }}" data-id="{{$natureLivrable->id}}" class="btn btn-default btn-sm showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-natureLivrable')
                            <a href="{{ route('natureLivrables.edit', $natureLivrable) }}" data-id="{{$natureLivrable->id}}" class="btn btn-sm btn-default editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-natureLivrable')
                            <form action="{{ route('natureLivrables.destroy', $natureLivrable) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$natureLivrable->id}}"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce naturelivrable ?')">
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

