{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0" id="niveauCompetencesTable">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <th>{{ ucfirst(__('PkgCompetences::niveauCompetence.nom')) }}</th>
                <th>{{ ucfirst(__('PkgCompetences::niveauCompetence.description')) }}</th>
                <th>{{ ucfirst(__('PkgCompetences::competence.singular')) }}</th>
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $niveauCompetence)
                <tr>
                    <td>{{ $niveauCompetence->nom }}</td>
                    <td>{{ $niveauCompetence->description }}</td>
                    <td>{{ $niveauCompetence->competence->code ?? '-' }}</td>
                    <td class="text-center">
                        @can('show-niveauCompetence')
                            <a href="{{ route('niveauCompetences.show', $niveauCompetence) }}" data-id="{{$niveauCompetence->id}}" class="btn btn-default btn-sm showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-niveauCompetence')
                            <a href="{{ route('niveauCompetences.edit', $niveauCompetence) }}" data-id="{{$niveauCompetence->id}}" class="btn btn-sm btn-default editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-niveauCompetence')
                            <form action="{{ route('niveauCompetences.destroy', $niveauCompetence) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$niveauCompetence->id}}">
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

