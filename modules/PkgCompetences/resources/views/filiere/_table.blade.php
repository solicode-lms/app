<div class="card-body table-responsive p-0" id="filieresTable">
    <table class="table table-striped text-nowrap"  >
        <thead>
            <tr>
                <th>{{ ucfirst(__('PkgCompetences::filiere.code')) }}</th>
                <th>{{ ucfirst(__('PkgCompetences::filiere.nom')) }}</th>
                <th>{{ ucfirst(__('PkgCompetences::filiere.description')) }}</th>
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $filiere)
                <tr>
                    <td>{{ $filiere->code }}</td>
                    <td>{{ $filiere->nom }}</td>
                    <td>{{ $filiere->description }}</td>
                    <td class="text-center">
                        @can('show-filiere')
                            <a href="{{ route('filieres.show', $filiere) }}" class="btn btn-default btn-sm">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-filiere')
                            <a href="{{ route('filieres.edit', $filiere) }}" class="btn btn-sm btn-default">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-filiere')
                            <form action="{{ route('filieres.destroy', $filiere) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button 
                                type="submit" 
                                data-id="{{$filiere->id}}" 
                                class="btn btn-sm btn-danger deleteEntity"
                                data-message= "Êtes-vous sûr de vouloir supprimer ce filiere ?">
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
