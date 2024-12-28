{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0" id="competencesTable">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <th>{{ ucfirst(__('PkgCompetences::competence.code')) }}</th>
                <th>{{ ucfirst(__('PkgCompetences::competence.nom')) }}</th>
                <th>{{ ucfirst(__('PkgCompetences::competence.description')) }}</th>
                <th>{{ ucfirst(__('PkgCompetences::module.singular')) }}</th>
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $competence)
                <tr>
                    <td>{{ $competence->code }}</td>
                    <td>{{ $competence->nom }}</td>
                    <td>{{ $competence->description }}</td>
                    <td>{{ $competence->module->nom ?? '-' }}</td>
                    <td class="text-center">
                        @can('show-competence')
                            <a href="{{ route('competences.show', $competence) }}" data-id="{{$competence->id}}" class="btn btn-default btn-sm showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-competence')
                            <a href="{{ route('competences.edit', $competence) }}" data-id="{{$competence->id}}" class="btn btn-sm btn-default editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-competence')
                            <form action="{{ route('competences.destroy', $competence) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$competence->id}}">
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

