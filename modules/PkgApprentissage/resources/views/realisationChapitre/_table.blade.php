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
                <x-sortable-column :sortable="true" width="40" field="chapitre_id" modelname="realisationChapitre" label="{!!ucfirst(__('PkgCompetences::chapitre.singular'))!!}" />
                <x-sortable-column :sortable="true" width="10" field="etat_realisation_chapitre_id" modelname="realisationChapitre" label="{!!ucfirst(__('PkgApprentissage::realisationChapitre.etat_realisation_chapitre_id'))!!}" />
                <x-sortable-column :sortable="true" width="32"  field="apprenant" modelname="realisationChapitre" label="{!!ucfirst(__('PkgApprentissage::realisationChapitre.apprenant'))!!}" />
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
                    <td style="max-width: 40%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationChapitre->id}}" data-field="chapitre_id" >
                        @include('PkgApprentissage::realisationChapitre.custom.fields.chapitre', ['entity' => $realisationChapitre])
                    </td>
                    <td style="max-width: 10%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationChapitre->id}}" data-field="etat_realisation_chapitre_id">
                        @if(!empty($realisationChapitre->etatRealisationChapitre))
                        <x-badge 
                        :text="$realisationChapitre->etatRealisationChapitre" 
                        :background="$realisationChapitre->etatRealisationChapitre->sysColor->hex ?? '#6c757d'" 
                        />
                        @endif

                    </td>
                    <td style="max-width: 32%;white-space: normal;" class=" text-truncate" data-id="{{$realisationChapitre->id}}" data-field="apprenant">
                        {{ $realisationChapitre->apprenant }}

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