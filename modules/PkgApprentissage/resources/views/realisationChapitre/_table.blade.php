{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationChapitre-table')
<div class="card-body p-0 crud-card-body" id="realisationChapitres-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $realisationChapitres_permissions['edit-realisationChapitre'] || $realisationChapitres_permissions['destroy-realisationChapitre'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="13.666666666666666"  field="date_debut" modelname="realisationChapitre" label="{!!ucfirst(__('PkgApprentissage::realisationChapitre.date_debut'))!!}" />
                <x-sortable-column :sortable="true" width="13.666666666666666"  field="date_fin" modelname="realisationChapitre" label="{!!ucfirst(__('PkgApprentissage::realisationChapitre.date_fin'))!!}" />
                <x-sortable-column :sortable="true" width="13.666666666666666" field="realisation_ua_id" modelname="realisationChapitre" label="{!!ucfirst(__('PkgApprentissage::realisationUa.singular'))!!}" />
                <x-sortable-column :sortable="true" width="13.666666666666666" field="realisation_tache_id" modelname="realisationChapitre" label="{!!ucfirst(__('PkgRealisationTache::realisationTache.singular'))!!}" />
                <x-sortable-column :sortable="true" width="13.666666666666666" field="chapitre_id" modelname="realisationChapitre" label="{!!ucfirst(__('PkgCompetences::chapitre.singular'))!!}" />
                <x-sortable-column :sortable="true" width="13.666666666666666" field="etat_realisation_chapitre_id" modelname="realisationChapitre" label="{!!ucfirst(__('PkgApprentissage::etatRealisationChapitre.singular'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('realisationChapitre-table-tbody')
            @foreach ($realisationChapitres_data as $realisationChapitre)
                @php
                    $isEditable = $realisationChapitres_permissions['edit-realisationChapitre'] && $realisationChapitres_permissionsByItem['update'][$realisationChapitre->id];
                @endphp
                <tr id="realisationChapitre-row-{{$realisationChapitre->id}}" data-id="{{$realisationChapitre->id}}">
                    <x-checkbox-row :item="$realisationChapitre" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 13.666666666666666%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationChapitre->id}}" data-field="date_debut"  data-toggle="tooltip" title="{{ $realisationChapitre->date_debut }}" >
                        <x-deadline-display :value="$realisationChapitre->date_debut" />
                    </td>
                    <td style="max-width: 13.666666666666666%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationChapitre->id}}" data-field="date_fin"  data-toggle="tooltip" title="{{ $realisationChapitre->date_fin }}" >
                        <x-deadline-display :value="$realisationChapitre->date_fin" />
                    </td>
                    <td style="max-width: 13.666666666666666%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationChapitre->id}}" data-field="realisation_ua_id"  data-toggle="tooltip" title="{{ $realisationChapitre->realisationUa }}" >
                        {{  $realisationChapitre->realisationUa }}

                    </td>
                    <td style="max-width: 13.666666666666666%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationChapitre->id}}" data-field="realisation_tache_id"  data-toggle="tooltip" title="{{ $realisationChapitre->realisationTache }}" >
                        {{  $realisationChapitre->realisationTache }}

                    </td>
                    <td style="max-width: 13.666666666666666%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationChapitre->id}}" data-field="chapitre_id"  data-toggle="tooltip" title="{{ $realisationChapitre->chapitre }}" >
                        {{  $realisationChapitre->chapitre }}

                    </td>
                    <td style="max-width: 13.666666666666666%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationChapitre->id}}" data-field="etat_realisation_chapitre_id"  data-toggle="tooltip" title="{{ $realisationChapitre->etatRealisationChapitre }}" >
                        @if(!empty($realisationChapitre->etatRealisationChapitre))
                        <x-badge 
                        :text="$realisationChapitre->etatRealisationChapitre" 
                        :background="$realisationChapitre->etatRealisationChapitre->sysColor->hex ?? '#6c757d'" 
                        />
                        @endif

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($realisationChapitres_permissions['edit-realisationChapitre'])
                        <x-action-button :entity="$realisationChapitre" actionName="edit">
                        @if($realisationChapitres_permissionsByItem['update'][$realisationChapitre->id])
                            <a href="{{ route('realisationChapitres.edit', ['realisationChapitre' => $realisationChapitre->id]) }}" data-id="{{$realisationChapitre->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($realisationChapitres_permissions['show-realisationChapitre'])
                        <x-action-button :entity="$realisationChapitre" actionName="show">
                        @if($realisationChapitres_permissionsByItem['view'][$realisationChapitre->id])
                            <a href="{{ route('realisationChapitres.show', ['realisationChapitre' => $realisationChapitre->id]) }}" data-id="{{$realisationChapitre->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$realisationChapitre" actionName="delete">
                        @if($realisationChapitres_permissions['destroy-realisationChapitre'])
                        @if($realisationChapitres_permissionsByItem['delete'][$realisationChapitre->id])
                            <form class="context-state" action="{{ route('realisationChapitres.destroy',['realisationChapitre' => $realisationChapitre->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$realisationChapitre->id}}">
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
    @section('realisationChapitre-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $realisationChapitres_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>