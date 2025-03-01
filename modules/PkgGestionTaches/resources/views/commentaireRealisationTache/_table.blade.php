{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="commentaireRealisationTaches-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="commentaire" modelname="commentaireRealisationTache" label="{{ ucfirst(__('PkgGestionTaches::commentaireRealisationTache.commentaire')) }}" />
                <x-sortable-column field="realisation_tache_id" modelname="commentaireRealisationTache" label="{{ ucfirst(__('PkgGestionTaches::commentaireRealisationTache.realisation_tache_id')) }}" />
                <x-sortable-column field="formateur_id" modelname="commentaireRealisationTache" label="{{ ucfirst(__('PkgGestionTaches::commentaireRealisationTache.formateur_id')) }}" />
                <x-sortable-column field="apprenant_id" modelname="commentaireRealisationTache" label="{{ ucfirst(__('PkgGestionTaches::commentaireRealisationTache.apprenant_id')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('commentaireRealisationTache-table-tbody')
            @foreach ($commentaireRealisationTaches_data as $commentaireRealisationTache)
                <tr id="commentaireRealisationTache-row-{{$commentaireRealisationTache->id}}">
                    <td>{!! \App\Helpers\TextHelper::formatHtmlWithLineBreaks($commentaireRealisationTache->commentaire, 50) !!}</td>
                    <td>@limit($commentaireRealisationTache->realisationTache, 50)</td>
                    <td>@limit($commentaireRealisationTache->formateur, 50)</td>
                    <td>@limit($commentaireRealisationTache->apprenant, 50)</td>
                    <td class="text-right">

                        @can('show-commentaireRealisationTache')
                        @can('view', $commentaireRealisationTache)
                            <a href="{{ route('commentaireRealisationTaches.show', ['commentaireRealisationTache' => $commentaireRealisationTache->id]) }}" data-id="{{$commentaireRealisationTache->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-commentaireRealisationTache')
                        @can('update', $commentaireRealisationTache)
                            <a href="{{ route('commentaireRealisationTaches.edit', ['commentaireRealisationTache' => $commentaireRealisationTache->id]) }}" data-id="{{$commentaireRealisationTache->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-commentaireRealisationTache')
                        @can('delete', $commentaireRealisationTache)
                            <form class="context-state" action="{{ route('commentaireRealisationTaches.destroy',['commentaireRealisationTache' => $commentaireRealisationTache->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$commentaireRealisationTache->id}}">
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
    @section('commentaireRealisationTache-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $commentaireRealisationTaches_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>