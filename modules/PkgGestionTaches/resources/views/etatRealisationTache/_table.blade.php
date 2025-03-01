{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="etatRealisationTaches-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="nom" modelname="etatRealisationTache" label="{{ ucfirst(__('PkgGestionTaches::etatRealisationTache.nom')) }}" />
                <x-sortable-column field="formateur_id" modelname="etatRealisationTache" label="{{ ucfirst(__('PkgGestionTaches::etatRealisationTache.formateur_id')) }}" />
                <x-sortable-column field="sys_color_id" modelname="etatRealisationTache" label="{{ ucfirst(__('PkgGestionTaches::etatRealisationTache.sys_color_id')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('etatRealisationTache-table-tbody')
            @foreach ($etatRealisationTaches_data as $etatRealisationTache)
                <tr id="etatRealisationTache-row-{{$etatRealisationTache->id}}">
                    <td>@limit($etatRealisationTache->nom, 50)</td>
                    <td>@limit($etatRealisationTache->formateur, 50)</td>
                    <td>@limit($etatRealisationTache->sysColor, 50)</td>
                    <td class="text-right">

                        @can('show-etatRealisationTache')
                        @can('view', $etatRealisationTache)
                            <a href="{{ route('etatRealisationTaches.show', ['etatRealisationTache' => $etatRealisationTache->id]) }}" data-id="{{$etatRealisationTache->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-etatRealisationTache')
                        @can('update', $etatRealisationTache)
                            <a href="{{ route('etatRealisationTaches.edit', ['etatRealisationTache' => $etatRealisationTache->id]) }}" data-id="{{$etatRealisationTache->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-etatRealisationTache')
                        @can('delete', $etatRealisationTache)
                            <form class="context-state" action="{{ route('etatRealisationTaches.destroy',['etatRealisationTache' => $etatRealisationTache->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$etatRealisationTache->id}}">
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
    @section('etatRealisationTache-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $etatRealisationTaches_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>