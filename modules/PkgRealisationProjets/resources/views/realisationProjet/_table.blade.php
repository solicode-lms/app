{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="realisationProjets-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="affectation_projet_id" modelname="realisationProjet" label="{{ ucfirst(__('PkgRealisationProjets::affectationProjet.singular')) }}" />
                <x-sortable-column field="apprenant_id" modelname="realisationProjet" label="{{ ucfirst(__('PkgApprenants::apprenant.singular')) }}" />
                <x-sortable-column field="etats_realisation_projet_id" modelname="realisationProjet" label="{{ ucfirst(__('PkgRealisationProjets::etatsRealisationProjet.singular')) }}" />
                <x-sortable-column field="LivrablesRealisation" modelname="realisationProjet" label="{{ ucfirst(__('PkgRealisationProjets::livrablesRealisation.plural')) }}" />

                <x-sortable-column field="Validation" modelname="realisationProjet" label="{{ ucfirst(__('PkgRealisationProjets::validation.plural')) }}" />

                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($realisationProjets_data as $realisationProjet)
                <tr id="realisationProjet-row-{{$realisationProjet->id}}">
                    <td>@limit($realisationProjet->affectationProjet, 50)</td>
                    <td>@limit($realisationProjet->apprenant, 50)</td>
                    <td>@limit($realisationProjet->etatsRealisationProjet, 50)</td>
                    <td>
                        <ul>
                            @foreach ($realisationProjet->livrablesRealisations as $livrablesRealisation)
                                <li>{{ $livrablesRealisation }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        <ul>
                            @foreach ($realisationProjet->validations as $validation)
                                <li>{{ $validation }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="text-right">

                        @can('show-realisationProjet')
                        @can('view', $realisationProjet)
                            <a href="{{ route('realisationProjets.show', ['realisationProjet' => $realisationProjet->id]) }}" data-id="{{$realisationProjet->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-realisationProjet')
                        @can('update', $realisationProjet)
                            <a href="{{ route('realisationProjets.edit', ['realisationProjet' => $realisationProjet->id]) }}" data-id="{{$realisationProjet->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-realisationProjet')
                        @can('delete', $realisationProjet)
                            <form class="context-state" action="{{ route('realisationProjets.destroy',['realisationProjet' => $realisationProjet->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$realisationProjet->id}}">
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
    @section('realisationProjet-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $realisationProjets_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>