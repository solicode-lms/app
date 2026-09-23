{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationQcm-table')
<div class="card-body p-0 crud-card-body" id="realisationQcms-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $realisationQcms_permissions['edit-realisationQcm'] || $realisationQcms_permissions['destroy-realisationQcm'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="10.25" field="affectation_qcm_projet_id" modelname="realisationQcm" label="{!!ucfirst(__('PkgQcm::affectationQcmProjet.singular'))!!}" />
                <x-sortable-column :sortable="true" width="10.25" field="qcm_id" modelname="realisationQcm" label="{!!ucfirst(__('PkgQcm::qcm.singular'))!!}" />
                <x-sortable-column :sortable="true" width="10.25" field="apprenant_id" modelname="realisationQcm" label="{!!ucfirst(__('PkgApprenants::apprenant.singular'))!!}" />
                <x-sortable-column :sortable="true" width="10.25" field="etat_realisation_qcm_id" modelname="realisationQcm" label="{!!ucfirst(__('PkgQcm::etatRealisationQcm.singular'))!!}" />
                <x-sortable-column :sortable="true" width="10.25"  field="date_debut" modelname="realisationQcm" label="{!!ucfirst(__('PkgQcm::realisationQcm.date_debut'))!!}" />
                <x-sortable-column :sortable="true" width="10.25"  field="date_fin" modelname="realisationQcm" label="{!!ucfirst(__('PkgQcm::realisationQcm.date_fin'))!!}" />
                <x-sortable-column :sortable="true" width="10.25"  field="date_validation" modelname="realisationQcm" label="{!!ucfirst(__('PkgQcm::realisationQcm.date_validation'))!!}" />
                <x-sortable-column :sortable="true" width="10.25"  field="statut" modelname="realisationQcm" label="{!!ucfirst(__('PkgQcm::realisationQcm.statut'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('realisationQcm-table-tbody')
            @foreach ($realisationQcms_data as $realisationQcm)
                @php
                    $isEditable = $realisationQcms_permissions['edit-realisationQcm'] && $realisationQcms_permissionsByItem['update'][$realisationQcm->id];
                @endphp
                <tr id="realisationQcm-row-{{$realisationQcm->id}}" data-id="{{$realisationQcm->id}}">
                    <x-checkbox-row :item="$realisationQcm" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 10.25%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationQcm->id}}" data-field="affectation_qcm_projet_id">
                        {{  $realisationQcm->affectationQcmProjet }}

                    </td>
                    <td style="max-width: 10.25%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationQcm->id}}" data-field="qcm_id">
                        {{  $realisationQcm->qcm }}

                    </td>
                    <td style="max-width: 10.25%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationQcm->id}}" data-field="apprenant_id">
                        {{  $realisationQcm->apprenant }}

                    </td>
                    <td style="max-width: 10.25%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationQcm->id}}" data-field="etat_realisation_qcm_id">
                        @if(!empty($realisationQcm->etatRealisationQcm))
                        <x-badge 
                        :text="$realisationQcm->etatRealisationQcm" 
                        :background="$realisationQcm->etatRealisationQcm->sysColor->hex ?? '#6c757d'" 
                        />
                        @endif

                    </td>
                    <td style="max-width: 10.25%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationQcm->id}}" data-field="date_debut">
                        <x-deadline-display :value="$realisationQcm->date_debut" />
                    </td>
                    <td style="max-width: 10.25%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationQcm->id}}" data-field="date_fin">
                        <x-deadline-display :value="$realisationQcm->date_fin" />
                    </td>
                    <td style="max-width: 10.25%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationQcm->id}}" data-field="date_validation">
                        <x-deadline-display :value="$realisationQcm->date_validation" />
                    </td>
                    <td style="max-width: 10.25%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationQcm->id}}" data-field="statut">
                        {{ $realisationQcm->statut }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($realisationQcms_permissions['edit-realisationQcm'])
                        <x-action-button :entity="$realisationQcm" actionName="edit">
                        @if($realisationQcms_permissionsByItem['update'][$realisationQcm->id])
                            <a href="{{ route('realisationQcms.edit', ['realisationQcm' => $realisationQcm->id]) }}" data-id="{{$realisationQcm->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($realisationQcms_permissions['show-realisationQcm'])
                        <x-action-button :entity="$realisationQcm" actionName="show">
                        @if($realisationQcms_permissionsByItem['view'][$realisationQcm->id])
                            <a href="{{ route('realisationQcms.show', ['realisationQcm' => $realisationQcm->id]) }}" data-id="{{$realisationQcm->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$realisationQcm" actionName="delete">
                        @if($realisationQcms_permissions['destroy-realisationQcm'])
                        @if($realisationQcms_permissionsByItem['delete'][$realisationQcm->id])
                            <form class="context-state" action="{{ route('realisationQcms.destroy',['realisationQcm' => $realisationQcm->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$realisationQcm->id}}">
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
    @section('realisationQcm-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $realisationQcms_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>