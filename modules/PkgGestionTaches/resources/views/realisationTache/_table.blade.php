{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationTache-table')
<div class="card-body table-responsive p-0 crud-card-body" id="realisationTaches-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                 <th>
                    Livrables
                </th>
                <x-sortable-column field="tache_id" modelname="realisationTache" label="{{ ucfirst(__('PkgGestionTaches::tache.singular')) }}" />
                <x-sortable-column field="realisation_projet_id" modelname="realisationTache" label="{{ ucfirst(__('PkgRealisationProjets::realisationProjet.singular')) }}" />
                <x-sortable-column field="etat_realisation_tache_id" modelname="realisationTache" label="{{ ucfirst(__('PkgGestionTaches::etatRealisationTache.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('realisationTache-table-tbody')
            @foreach ($realisationTaches_data as $realisationTache)
                <tr id="realisationTache-row-{{$realisationTache->id}}">
                    <td>
                    <x-field :data="$realisationTache" field="Livrables">
                     ComputableFields :  livrables() 
                    </x-field>
                    </td>
                    <td>
                    <x-field :data="$realisationTache" field="tache">
                     <span @if(strlen($realisationTache->tache) > 30) 
                            data-toggle="tooltip" 
                            title="{{ $realisationTache->tache }}" 
                        @endif>
                        {{ Str::limit($realisationTache->tache, 30) }}
                    </span>
                    </x-field>
                    </td>
                    <td>
                    <x-field :data="$realisationTache" field="realisationProjet">
                     <span @if(strlen($realisationTache->realisationProjet) > 30) 
                            data-toggle="tooltip" 
                            title="{{ $realisationTache->realisationProjet }}" 
                        @endif>
                        {{ Str::limit($realisationTache->realisationProjet, 30) }}
                    </span>
                    </x-field>
                    </td>
                    <td>
                    <x-field :data="$realisationTache" field="etatRealisationTache">
                     <span @if(strlen($realisationTache->etatRealisationTache) > 30) 
                            data-toggle="tooltip" 
                            title="{{ $realisationTache->etatRealisationTache }}" 
                        @endif>
                        {{ Str::limit($realisationTache->etatRealisationTache, 30) }}
                    </span>
                    </x-field>
                    </td>
                    <td class="text-right">
                        @can('index-livrablesRealisation')
                            <a
                                data-toggle="tooltip"
                                title="Livrables"
                                href="{{ route('livrablesRealisations.index', [
                                        'showIndex' => true,
                                        'contextKey' => 'livrablesRealisation-index',
                                        'scope.livrable.projet_id' => $realisationTache->realisationProjet->affectationProjet->projet_id,
                                        'scope.livrablesRealisation.realisation_projet_id' => $realisationTache->realisation_projet_id,
                                ]) }}"
                                class="btn btn-default btn-sm context-state actionEntity showIndex"
                                data-id="{{ $realisationTache->id }}">
                                <i class="fas fa-file-alt"></i>
                            </a>
                        @endcan

                        @can('show-realisationTache')
                        @can('view', $realisationTache)
                            <a href="{{ route('realisationTaches.show', ['realisationTache' => $realisationTache->id]) }}" data-id="{{$realisationTache->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-realisationTache')
                        @can('update', $realisationTache)
                            <a href="{{ route('realisationTaches.edit', ['realisationTache' => $realisationTache->id]) }}" data-id="{{$realisationTache->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-realisationTache')
                        @can('delete', $realisationTache)
                            <form class="context-state" action="{{ route('realisationTaches.destroy',['realisationTache' => $realisationTache->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$realisationTache->id}}">
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
@show

<div class="card-footer">
    @section('realisationTache-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $realisationTaches_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>