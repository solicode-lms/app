{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="affectationProjets-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="projet_id" modelname="affectationProjet" label="{{ ucfirst(__('PkgCreationProjet::projet.singular')) }}" />
                <x-sortable-column field="groupe_id" modelname="affectationProjet" label="{{ ucfirst(__('PkgApprenants::groupe.singular')) }}" />
                <x-sortable-column field="date_debut" modelname="affectationProjet" label="{{ ucfirst(__('PkgRealisationProjets::affectationProjet.date_debut')) }}" />
                <x-sortable-column field="date_fin" modelname="affectationProjet" label="{{ ucfirst(__('PkgRealisationProjets::affectationProjet.date_fin')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('affectationProjet-table-tbody')
            @foreach ($affectationProjets_data as $affectationProjet)
                <tr id="affectationProjet-row-{{$affectationProjet->id}}">
                    <td>@limit($affectationProjet->projet, 50)</td>
                    <td>@limit($affectationProjet->groupe, 50)</td>
                    <td>@limit($affectationProjet->date_debut, 50)</td>
                    <td>@limit($affectationProjet->date_fin, 50)</td>
                    <td class="text-right">

                        @can('show-affectationProjet')
                        @can('view', $affectationProjet)
                            <a href="{{ route('affectationProjets.show', ['affectationProjet' => $affectationProjet->id]) }}" data-id="{{$affectationProjet->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-affectationProjet')
                        @can('update', $affectationProjet)
                            <a href="{{ route('affectationProjets.edit', ['affectationProjet' => $affectationProjet->id]) }}" data-id="{{$affectationProjet->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-affectationProjet')
                        @can('delete', $affectationProjet)
                            <form class="context-state" action="{{ route('affectationProjets.destroy',['affectationProjet' => $affectationProjet->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$affectationProjet->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endcan
                        @endcan
                    </td>
                </tr>
            @endforeach
            @show
        </tbody>
    </table>
</div>

<div class="card-footer">
    @section('affectationProjet-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $affectationProjets_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>