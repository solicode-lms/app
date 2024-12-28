{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0" id="specialitesTable">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <th>{{ ucfirst(__('PkgUtilisateurs::specialite.nom')) }}</th>
                <th>{{ ucfirst(__('PkgUtilisateurs::specialite.description')) }}</th>
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $specialite)
                <tr>
                    <td>{{ $specialite->nom }}</td>
                    <td>{{ $specialite->description }}</td>
                    <td class="text-center">
                        @can('show-specialite')
                            <a href="{{ route('specialites.show', $specialite) }}" data-id="{{$specialite->id}}" class="btn btn-default btn-sm showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-specialite')
                            <a href="{{ route('specialites.edit', $specialite) }}" data-id="{{$specialite->id}}" class="btn btn-sm btn-default editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-specialite')
                            <form action="{{ route('specialites.destroy', $specialite) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$specialite->id}}">
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

