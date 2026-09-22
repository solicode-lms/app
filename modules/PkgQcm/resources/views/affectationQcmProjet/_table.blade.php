{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('affectationQcmProjet-table')
<div class="card-body p-0 crud-card-body" id="affectationQcmProjets-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $affectationQcmProjets_permissions['edit-affectationQcmProjet'] || $affectationQcmProjets_permissions['destroy-affectationQcmProjet'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="41" field="affectation_projet_id" modelname="affectationQcmProjet" label="{!!ucfirst(__('PkgRealisationProjets::affectationProjet.singular'))!!}" />
                <x-sortable-column :sortable="true" width="41" field="qcm_id" modelname="affectationQcmProjet" label="{!!ucfirst(__('PkgQcm::qcm.singular'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('affectationQcmProjet-table-tbody')
            @foreach ($affectationQcmProjets_data as $affectationQcmProjet)
                @php
                    $isEditable = $affectationQcmProjets_permissions['edit-affectationQcmProjet'] && $affectationQcmProjets_permissionsByItem['update'][$affectationQcmProjet->id];
                @endphp
                <tr id="affectationQcmProjet-row-{{$affectationQcmProjet->id}}" data-id="{{$affectationQcmProjet->id}}">
                    <x-checkbox-row :item="$affectationQcmProjet" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 41%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$affectationQcmProjet->id}}" data-field="affectation_projet_id">
                        {{  $affectationQcmProjet->affectationProjet }}

                    </td>
                    <td style="max-width: 41%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$affectationQcmProjet->id}}" data-field="qcm_id">
                        {{  $affectationQcmProjet->qcm }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($affectationQcmProjets_permissions['edit-affectationQcmProjet'])
                        <x-action-button :entity="$affectationQcmProjet" actionName="edit">
                        @if($affectationQcmProjets_permissionsByItem['update'][$affectationQcmProjet->id])
                            <a href="{{ route('affectationQcmProjets.edit', ['affectationQcmProjet' => $affectationQcmProjet->id]) }}" data-id="{{$affectationQcmProjet->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($affectationQcmProjets_permissions['show-affectationQcmProjet'])
                        <x-action-button :entity="$affectationQcmProjet" actionName="show">
                        @if($affectationQcmProjets_permissionsByItem['view'][$affectationQcmProjet->id])
                            <a href="{{ route('affectationQcmProjets.show', ['affectationQcmProjet' => $affectationQcmProjet->id]) }}" data-id="{{$affectationQcmProjet->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$affectationQcmProjet" actionName="delete">
                        @if($affectationQcmProjets_permissions['destroy-affectationQcmProjet'])
                        @if($affectationQcmProjets_permissionsByItem['delete'][$affectationQcmProjet->id])
                            <form class="context-state" action="{{ route('affectationQcmProjets.destroy',['affectationQcmProjet' => $affectationQcmProjet->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$affectationQcmProjet->id}}">
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
    @section('affectationQcmProjet-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $affectationQcmProjets_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>