{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationTache-table')
<div class="card-body p-0 crud-card-body" id="realisationTaches-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-realisationTache') || Auth::user()->can('destroy-realisationTache');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="17"  field="projet_title" modelname="realisationTache" label="{{ucfirst(__('PkgGestionTaches::realisationTache.projet_title'))}}" />
                <x-sortable-column :sortable="true" width="20" field="tache_id" modelname="realisationTache" label="{{ucfirst(__('PkgGestionTaches::tache.singular'))}}" />
                <x-sortable-column :sortable="true" width="15"  field="nom_prenom_apprenant" modelname="realisationTache" label="{{ucfirst(__('PkgGestionTaches::realisationTache.nom_prenom_apprenant'))}}" />
                <x-sortable-column :sortable="true" width="13" field="etat_realisation_tache_id" modelname="realisationTache" label="{{ucfirst(__('PkgGestionTaches::etatRealisationTache.singular'))}}" />
                <x-sortable-column :sortable="true" width="17"  field="nombre_livrables" modelname="realisationTache" label="{{ucfirst(__('PkgGestionTaches::realisationTache.nombre_livrables'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('realisationTache-table-tbody')
            @foreach ($realisationTaches_data as $realisationTache)
                @php
                    $isEditable = Auth::user()->can('edit-realisationTache') && Auth::user()->can('update', $realisationTache);
                @endphp
                <tr id="realisationTache-row-{{$realisationTache->id}}" data-id="{{$realisationTache->id}}">
                    <x-checkbox-row :item="$realisationTache" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 17%;" class=" text-truncate" data-id="{{$realisationTache->id}}" data-field="projet_title"  data-toggle="tooltip" title="{{ $realisationTache->projet_title }}" >
                    <x-field :entity="$realisationTache" field="projet_title">
                        {{ $realisationTache->projet_title }}
                    </x-field>
                    </td>
                    <td style="max-width: 20%;" class=" text-truncate" data-id="{{$realisationTache->id}}" data-field="tache_id"  data-toggle="tooltip" title="{{ $realisationTache->tache }}" >
                    <x-field :entity="$realisationTache" field="tache">
                       
                         {{  $realisationTache->tache }}
                    </x-field>
                    </td>
                    <td style="max-width: 15%;" class=" text-truncate" data-id="{{$realisationTache->id}}" data-field="nom_prenom_apprenant"  data-toggle="tooltip" title="{{ $realisationTache->nom_prenom_apprenant }}" >
                    <x-field :entity="$realisationTache" field="nom_prenom_apprenant">
                        {{ $realisationTache->nom_prenom_apprenant }}
                    </x-field>
                    </td>
                    <td style="max-width: 13%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationTache->id}}" data-field="etat_realisation_tache_id"  data-toggle="tooltip" title="{{ $realisationTache->etatRealisationTache }}" >
                    <x-field :entity="$realisationTache" field="etatRealisationTache">
                        @if(!empty($realisationTache->etatRealisationTache))
                        <x-badge 
                        :text="$realisationTache->etatRealisationTache" 
                        :background="$realisationTache->etatRealisationTache->sysColor->hex ?? '#6c757d'" 
                        />
                        @endif
                    </x-field>
                    </td>
                    <td style="max-width: 17%;" class=" text-truncate" data-id="{{$realisationTache->id}}" data-field="nombre_livrables"  data-toggle="tooltip" title="{{ $realisationTache->nombre_livrables }}" >
                    <x-field :entity="$realisationTache" field="nombre_livrables">
                        {{ $realisationTache->nombre_livrables }}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">
                        @can('index-livrablesRealisation')
                            <a
                                data-toggle="tooltip"
                                title="Livrables"
                                href="{{ route('livrablesRealisations.index', [
                                        'showIndex' => true,
                                        'contextKey' => 'livrablesRealisation.index',
                                        'scope.livrable.projet_id' => $realisationTache->realisationProjet->affectationProjet->projet_id,
                                        'scope.livrablesRealisation.realisation_projet_id' => $realisationTache->realisation_projet_id,
                                ]) }}"
                                class="btn btn-default btn-sm context-state actionEntity showIndex"
                                data-id="{{ $realisationTache->id }}">
                                <i class="fas fa-file-alt"></i>
                            </a>
                        @endcan
                        @can('show-projet')
                            <a
                                data-toggle="tooltip"
                                title="Projets"
                                href="{{ route('projets.show', [
                                        'projet' => $realisationTache->realisationProjet->affectationProjet->projet_id,
                                        'showIndex' => true,
                                        'contextKey' => 'projets.show',
                                ]) }}"
                                class="btn btn-default btn-sm context-state actionEntity showIndex"
                                data-id="{{ $realisationTache->id }}">
                                <i class="fas fa-laptop"></i>
                            </a>
                        @endcan


                       

                        @can('edit-realisationTache')
                        @can('update', $realisationTache)
                            <a href="{{ route('realisationTaches.edit', ['realisationTache' => $realisationTache->id]) }}" data-id="{{$realisationTache->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-realisationTache')
                        @can('view', $realisationTache)
                            <a href="{{ route('realisationTaches.show', ['realisationTache' => $realisationTache->id]) }}" data-id="{{$realisationTache->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-realisationTache')
                        @can('delete', $realisationTache)
                            <form class="context-state" action="{{ route('realisationTaches.destroy',['realisationTache' => $realisationTache->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$realisationTache->id}}">
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