{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="historiqueRealisationTaches-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="realisation_tache_id" modelname="historiqueRealisationTache" label="{{ ucfirst(__('PkgGestionTaches::historiqueRealisationTache.realisation_tache_id')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('historiqueRealisationTache-table-tbody')
            @foreach ($historiqueRealisationTaches_data as $historiqueRealisationTache)
                <tr id="historiqueRealisationTache-row-{{$historiqueRealisationTache->id}}">
                    <td>@limit($historiqueRealisationTache->realisationTache, 50)</td>
                    <td class="text-right">

                        @can('show-historiqueRealisationTache')
                        @can('view', $historiqueRealisationTache)
                            <a href="{{ route('historiqueRealisationTaches.show', ['historiqueRealisationTache' => $historiqueRealisationTache->id]) }}" data-id="{{$historiqueRealisationTache->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-historiqueRealisationTache')
                        @can('update', $historiqueRealisationTache)
                            <a href="{{ route('historiqueRealisationTaches.edit', ['historiqueRealisationTache' => $historiqueRealisationTache->id]) }}" data-id="{{$historiqueRealisationTache->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-historiqueRealisationTache')
                        @can('delete', $historiqueRealisationTache)
                            <form class="context-state" action="{{ route('historiqueRealisationTaches.destroy',['historiqueRealisationTache' => $historiqueRealisationTache->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$historiqueRealisationTache->id}}">
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
    @section('historiqueRealisationTache-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $historiqueRealisationTaches_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>