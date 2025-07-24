{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationUa-table')
<div class="card-body p-0 crud-card-body" id="realisationUas-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $realisationUas_permissions['edit-realisationUa'] || $realisationUas_permissions['destroy-realisationUa'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="27.333333333333332" field="realisation_micro_competence_id" modelname="realisationUa" label="{!!ucfirst(__('PkgApprentissage::realisationMicroCompetence.singular'))!!}" />
                <x-sortable-column :sortable="true" width="27.333333333333332" field="unite_apprentissage_id" modelname="realisationUa" label="{!!ucfirst(__('PkgCompetences::uniteApprentissage.singular'))!!}" />
                <x-sortable-column :sortable="true" width="27.333333333333332" field="etat_realisation_ua_id" modelname="realisationUa" label="{!!ucfirst(__('PkgApprentissage::etatRealisationUa.singular'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('realisationUa-table-tbody')
            @foreach ($realisationUas_data as $realisationUa)
                @php
                    $isEditable = $realisationUas_permissions['edit-realisationUa'] && $realisationUas_permissionsByItem['update'][$realisationUa->id];
                @endphp
                <tr id="realisationUa-row-{{$realisationUa->id}}" data-id="{{$realisationUa->id}}">
                    <x-checkbox-row :item="$realisationUa" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationUa->id}}" data-field="realisation_micro_competence_id"  data-toggle="tooltip" title="{{ $realisationUa->realisationMicroCompetence }}" >
                        {{  $realisationUa->realisationMicroCompetence }}

                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationUa->id}}" data-field="unite_apprentissage_id"  data-toggle="tooltip" title="{{ $realisationUa->uniteApprentissage }}" >
                        {{  $realisationUa->uniteApprentissage }}

                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationUa->id}}" data-field="etat_realisation_ua_id"  data-toggle="tooltip" title="{{ $realisationUa->etatRealisationUa }}" >
                        @if(!empty($realisationUa->etatRealisationUa))
                        <x-badge 
                        :text="$realisationUa->etatRealisationUa" 
                        :background="$realisationUa->etatRealisationUa->sysColor->hex ?? '#6c757d'" 
                        />
                        @endif

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($realisationUas_permissions['edit-realisationUa'])
                        <x-action-button :entity="$realisationUa" actionName="edit">
                        @if($realisationUas_permissionsByItem['update'][$realisationUa->id])
                            <a href="{{ route('realisationUas.edit', ['realisationUa' => $realisationUa->id]) }}" data-id="{{$realisationUa->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($realisationUas_permissions['show-realisationUa'])
                        <x-action-button :entity="$realisationUa" actionName="show">
                        @if($realisationUas_permissionsByItem['view'][$realisationUa->id])
                            <a href="{{ route('realisationUas.show', ['realisationUa' => $realisationUa->id]) }}" data-id="{{$realisationUa->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$realisationUa" actionName="delete">
                        @if($realisationUas_permissions['destroy-realisationUa'])
                        @if($realisationUas_permissionsByItem['delete'][$realisationUa->id])
                            <form class="context-state" action="{{ route('realisationUas.destroy',['realisationUa' => $realisationUa->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$realisationUa->id}}">
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
    @section('realisationUa-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $realisationUas_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>