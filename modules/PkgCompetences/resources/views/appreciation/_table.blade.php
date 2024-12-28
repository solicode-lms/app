{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0" id="appreciationsTable">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <th>{{ ucfirst(__('PkgCompetences::appreciation.nom')) }}</th>
                <th>{{ ucfirst(__('PkgUtilisateurs::formateur.singular')) }}</th>
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $appreciation)
                <tr>
                    <td>{{ $appreciation->nom }}</td>
                    <td>{{ $appreciation->formateur->nom ?? '-' }}</td>
                    <td class="text-center">
                        @can('show-appreciation')
                            <a href="{{ route('appreciations.show', $appreciation) }}" data-id="{{$appreciation->id}}" class="btn btn-default btn-sm showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-appreciation')
                            <a href="{{ route('appreciations.edit', $appreciation) }}" data-id="{{$appreciation->id}}" class="btn btn-sm btn-default editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-appreciation')
                            <form action="{{ route('appreciations.destroy', $appreciation) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$appreciation->id}}">
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

