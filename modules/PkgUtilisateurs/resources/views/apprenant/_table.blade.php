{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <th>{{ ucfirst(__('PkgUtilisateurs::apprenant.nom')) }}</th>
                <th>{{ ucfirst(__('PkgUtilisateurs::apprenant.actif')) }}</th>
                <th>{{ ucfirst(__('PkgUtilisateurs::apprenant.adresse')) }}</th>
                <th>{{ ucfirst(__('PkgUtilisateurs::groupe.singular')) }}</th>
                <th>{{ ucfirst(__('PkgUtilisateurs::niveauxScolaire.singular')) }}</th>
                <th>{{ ucfirst(__('PkgUtilisateurs::nationalite.singular')) }}</th>
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $apprenant)
                <tr>
                    <td>{{ $apprenant->nom }}</td>
                    <td>{{ $apprenant->actif }}</td>
                    <td>{{ $apprenant->adresse }}</td>
                    <td>{{ $apprenant->groupe->code ?? '-' }}</td>
                    <td>{{ $apprenant->niveauxScolaire->nom ?? '-' }}</td>
                    <td>{{ $apprenant->nationalite->code ?? '-' }}</td>
                    <td class="text-center">
                        @can('show-apprenant')
                            <a href="{{ route('apprenants.show', $apprenant) }}" class="btn btn-default btn-sm">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-apprenant')
                            <a href="{{ route('apprenants.edit', $apprenant) }}" class="btn btn-sm btn-default">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-apprenant')
                            <form action="{{ route('apprenants.destroy', $apprenant) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce apprenant ?')">
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
        @can('import-apprenant')
            <form action="{{ route('apprenants.import') }}" method="post" class="mt-2" enctype="multipart/form-data"
                id="importForm">
                @csrf
                <label for="upload" class="btn btn-default btn-sm font-weight-normal">
                    <i class="fas fa-file-download"></i>
                    {{ __('Core::msg.import') }}
                </label>
                <input type="file" id="upload" name="file" style="display:none;" onchange="submitForm()" />
            </form>
        @endcan
        @can('export-apprenant')
            <form class="">
                <a href="{{ route('apprenants.export') }}" class="btn btn-default btn-sm mt-0 mx-2">
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
