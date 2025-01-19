{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="apprenants-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="nom" label="{{ ucfirst(__('PkgUtilisateurs::apprenant.nom')) }}" />
                <x-sortable-column field="actif" label="{{ ucfirst(__('PkgUtilisateurs::apprenant.actif')) }}" />
                <x-sortable-column field="groupe_id" label="{{ ucfirst(__('PkgUtilisateurs::groupe.singular')) }}" />
                <x-sortable-column field="niveaux_scolaire_id" label="{{ ucfirst(__('PkgUtilisateurs::niveauxScolaire.singular')) }}" />
                <x-sortable-column field="nationalite_id" label="{{ ucfirst(__('PkgUtilisateurs::nationalite.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($apprenants_data as $apprenant)
                <tr>
                    <td>@limit($apprenant->nom, 80)</td>
                    <td>@limit($apprenant->actif, 80)</td>
                    <td>@limit($apprenant->groupe->code ?? '-', 80)</td>
                    <td>@limit($apprenant->niveauxScolaire->code ?? '-', 80)</td>
                    <td>@limit($apprenant->nationalite->code ?? '-', 80)</td>
                    <td class="text-right">
                        @can('show-apprenant')
                            <a href="{{ route('apprenants.show', ['apprenant' => $apprenant->id]) }}" data-id="{{$apprenant->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-apprenant')
                        @can('update', $apprenant)
                            <a href="{{ route('apprenants.edit', ['apprenant' => $apprenant->id]) }}" data-id="{{$apprenant->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-apprenant')
                        @can('delete', $apprenant)
                            <form class="context-state" action="{{ route('apprenants.destroy',['apprenant' => $apprenant->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$apprenant->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endcan
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="card-footer">
    @section('apprenant-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $apprenants_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>