{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="niveauxScolaires-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="code" label="{{ ucfirst(__('PkgUtilisateurs::niveauxScolaire.code')) }}" />
                <x-sortable-column field="nom" label="{{ ucfirst(__('PkgUtilisateurs::niveauxScolaire.nom')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($niveauxScolaires_data as $niveauxScolaire)
                <tr>
                    <td>@limit($niveauxScolaire->code, 80)</td>
                    <td>@limit($niveauxScolaire->nom, 80)</td>
                    <td class="text-right">
                        @can('show-niveauxScolaire')
                            <a href="{{ route('niveauxScolaires.show', ['niveauxScolaire' => $niveauxScolaire->id]) }}" data-id="{{$niveauxScolaire->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-niveauxScolaire')
                        @can('update', $niveauxScolaire)
                            <a href="{{ route('niveauxScolaires.edit', ['niveauxScolaire' => $niveauxScolaire->id]) }}" data-id="{{$niveauxScolaire->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-niveauxScolaire')
                        @can('delete', $niveauxScolaire)
                            <form class="context-state" action="{{ route('niveauxScolaires.destroy',['niveauxScolaire' => $niveauxScolaire->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$niveauxScolaire->id}}">
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
    @section('niveauxScolaire-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $niveauxScolaires_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>