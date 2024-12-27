{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0" id="categorieTechnologiesTable">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <th>{{ ucfirst(__('PkgCompetences::categorieTechnology.nom')) }}</th>
                <th>{{ ucfirst(__('PkgCompetences::categorieTechnology.description')) }}</th>
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $categorieTechnology)
                <tr>
                    <td>{{ $categorieTechnology->nom }}</td>
                    <td>{{ $categorieTechnology->description }}</td>
                    <td class="text-center">
                        @can('show-categorieTechnology')
                            <a href="{{ route('categorieTechnologies.show', $categorieTechnology) }}" data-id="{{$categorieTechnology->id}}" class="btn btn-default btn-sm showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-categorieTechnology')
                            <a href="{{ route('categorieTechnologies.edit', $categorieTechnology) }}" data-id="{{$categorieTechnology->id}}" class="btn btn-sm btn-default editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-categorieTechnology')
                            <form action="{{ route('categorieTechnologies.destroy', $categorieTechnology) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$categorieTechnology->id}}"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce categorietechnology ?')">
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

