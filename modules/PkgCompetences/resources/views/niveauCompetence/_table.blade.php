{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0">
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
                            <a href="{{ route('niveauCompetences.show', $niveauCompetence) }}" class="btn btn-default btn-sm">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-niveauCompetence')
                            <a href="{{ route('niveauCompetences.edit', $niveauCompetence) }}" class="btn btn-sm btn-default">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-niveauCompetence')
                            <form action="{{ route('niveauCompetences.destroy', $niveauCompetence) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce niveaucompetence ?')">
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

<div class="d-md-flex justify-content-between align-items-center p-2">
    <div class="d-flex align-items-center mb-2 ml-2 mt-2">
        @can('import-niveauCompetence')
            <form action="{{ route('niveauCompetences.import') }}" method="post" class="mt-2" enctype="multipart/form-data"
                id="importForm">
                @csrf
                <label for="upload" class="btn btn-default btn-sm font-weight-normal">
                    <i class="fas fa-file-download"></i>
                    {{ __('Core::msg.import') }}
                </label>
                <input type="file" id="upload" name="file" style="display:none;" onchange="submitForm()" />
            </form>
        @endcan
        @can('export-niveauCompetence')
            <form class="">
                <a href="{{ route('niveauCompetences.export') }}" class="btn btn-default btn-sm mt-0 mx-2">
                    <i class="fas fa-file-export"></i>
                    {{ __('Core::msg.export') }}</a>
            </form>
        @endcan
    </div>

    <ul class="pagination m-0 float-right">
        {{ $data->onEachSide(1)->links() }}
    </ul>
</div>

<script>
    function submitForm() {
        document.getElementById("importForm").submit();
    }
</script>
