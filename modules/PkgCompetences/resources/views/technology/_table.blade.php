{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0" id="technologiesTable">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <th>{{ ucfirst(__('PkgCompetences::technology.nom')) }}</th>
                <th>{{ ucfirst(__('PkgCompetences::technology.description')) }}</th>
                <th>{{ ucfirst(__('PkgCompetences::categorieTechnology.singular')) }}</th>
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $technology)
                <tr>
                    <td>{{ $technology->nom }}</td>
                    <td>{{ $technology->description }}</td>
                    <td>{{ $technology->categorieTechnology->nom ?? '-' }}</td>
                    <td class="text-center">
                        @can('show-technology')
                            <a href="{{ route('technologies.show', $technology) }}" data-id="{{$technology->id}}" class="btn btn-default btn-sm showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-technology')
                            <a href="{{ route('technologies.edit', $technology) }}" data-id="{{$technology->id}}" class="btn btn-sm btn-default editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-technology')
                            <form action="{{ route('technologies.destroy', $technology) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$technology->id}}"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce technology ?')">
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

