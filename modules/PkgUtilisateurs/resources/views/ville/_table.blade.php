{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0" id="villesTable">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <th>{{ ucfirst(__('PkgUtilisateurs::ville.nom')) }}</th>
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $ville)
                <tr>
                    <td>{{ $ville->nom }}</td>
                    <td class="text-center">
                        @can('show-ville')
                            <a href="{{ route('villes.show', $ville) }}" data-id="{{$ville->id}}" class="btn btn-default btn-sm showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-ville')
                            <a href="{{ route('villes.edit', $ville) }}" data-id="{{$ville->id}}" class="btn btn-sm btn-default editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-ville')
                            <form action="{{ route('villes.destroy', $ville) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$ville->id}}"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce ville ?')">
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

