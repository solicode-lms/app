{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationTache-table')
<div class="card-body p-0 crud-card-body" id="realisationTaches-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $realisationTaches_permissions['edit-realisationTache'] || $realisationTaches_permissions['destroy-realisationTache'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="40" field="tache_id" modelname="realisationTache" label="{!!ucfirst(__('PkgCreationTache::tache.singular'))!!}" />
                <x-sortable-column :sortable="true" width="20" field="etat_realisation_tache_id" modelname="realisationTache" label="{!!ucfirst(__('PkgRealisationTache::realisationTache.etat_realisation_tache_id'))!!}" />
                <x-sortable-column :sortable="true" width="20"  field="nombre_livrables" modelname="realisationTache" label="{!!ucfirst(__('PkgRealisationTache::realisationTache.nombre_livrables'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('realisationTache-table-tbody')
            @foreach ($realisationTaches_data as $realisationTache)
                @php
                    $isEditable = $realisationTaches_permissions['edit-realisationTache'] && $realisationTaches_permissionsByItem['update'][$realisationTache->id];
                @endphp
                <tr id="realisationTache-row-{{$realisationTache->id}}" data-id="{{$realisationTache->id}}">
                    <x-checkbox-row :item="$realisationTache" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 40%;white-space: normal;" class=" text-truncate" data-id="{{$realisationTache->id}}" data-field="tache_id" >
                        @include('PkgRealisationTache::realisationTache.custom.fields.tache', ['entity' => $realisationTache])
                    </td>
                    <td style="max-width: 20%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationTache->id}}" data-field="etat_realisation_tache_id" >
                        @include('PkgRealisationTache::realisationTache.custom.fields.etatRealisationTache', ['entity' => $realisationTache])
                    </td>
                    <td style="max-width: 20%;white-space: normal;" class=" text-truncate" data-id="{{$realisationTache->id}}" data-field="nombre_livrables" >
                        @include('PkgRealisationTache::realisationTache.custom.fields.nombre_livrables', ['entity' => $realisationTache])
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">
                        @if($realisationTaches_permissions['index-livrablesRealisation'])
                        <x-action-button :entity="$realisationTaches" actionName="livrablesRealisations">
                            <a
                                data-toggle="tooltip"
                                title="Livrables"
                                href="{{ route('livrablesRealisations.index', [
                                        'showIndex' => true,
                                        'contextKey' => 'livrablesRealisation.index',
                                        'scope.livrable.projet_id' => $realisationTache->realisationProjet->affectationProjet->projet_id,
                                        'scope.livrablesRealisation.realisation_projet_id' => $realisationTache->realisation_projet_id,
                                ]) }}"
                                class="btn btn-default btn-sm context-state actionEntity showIndex d-none d-md-inline d-lg-inline "
                                data-id="{{ $realisationTache->id }}">
                                <i class="fas fa-file-alt"></i>
                            </a>
                        </x-action-button>
                        @endif
                        @if($realisationTaches_permissions['show-projet'])
                        <x-action-button :entity="$realisationTaches" actionName="projet">
                            <a
                                data-toggle="tooltip"
                                title="Projet"
                                href="{{ route('projets.show', [
                                        'projet' => $realisationTache->realisationProjet->affectationProjet->projet_id,
                                        'showIndex' => true,
                                        'contextKey' => 'projets.show',
                                ]) }}"
                                class="btn btn-default btn-sm context-state actionEntity showIndex d-none d-md-inline d-lg-inline "
                                data-id="{{ $realisationTache->id }}">
                                <i class="fas fa-laptop"></i>
                            </a>
                        </x-action-button>
                        @endif


                       

                        @if($realisationTaches_permissions['edit-realisationTache'])
                        <x-action-button :entity="$realisationTache" actionName="edit">
                        @if($realisationTaches_permissionsByItem['update'][$realisationTache->id])
                            <a href="{{ route('realisationTaches.edit', ['realisationTache' => $realisationTache->id]) }}" data-id="{{$realisationTache->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($realisationTaches_permissions['show-realisationTache'])
                        <x-action-button :entity="$realisationTache" actionName="show">
                        @if($realisationTaches_permissionsByItem['view'][$realisationTache->id])
                            <a href="{{ route('realisationTaches.show', ['realisationTache' => $realisationTache->id]) }}" data-id="{{$realisationTache->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$realisationTache" actionName="delete">
                        @if($realisationTaches_permissions['destroy-realisationTache'])
                        @if($realisationTaches_permissionsByItem['delete'][$realisationTache->id])
                            <form class="context-state" action="{{ route('realisationTaches.destroy',['realisationTache' => $realisationTache->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$realisationTache->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                        @endif
                        </x-action-button>
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