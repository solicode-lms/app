{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('reponseQcm-table')
<div class="card-body p-0 crud-card-body" id="reponseQcms-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $reponseQcms_permissions['edit-reponseQcm'] || $reponseQcms_permissions['destroy-reponseQcm'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="41" field="realisation_qcm_id" modelname="reponseQcm" label="{!!ucfirst(__('PkgQcm::realisationQcm.singular'))!!}" />
                <x-sortable-column :sortable="true" width="41" field="question_qcm_id" modelname="reponseQcm" label="{!!ucfirst(__('PkgQcm::questionQcm.singular'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('reponseQcm-table-tbody')
            @foreach ($reponseQcms_data as $reponseQcm)
                @php
                    $isEditable = $reponseQcms_permissions['edit-reponseQcm'] && $reponseQcms_permissionsByItem['update'][$reponseQcm->id];
                @endphp
                <tr id="reponseQcm-row-{{$reponseQcm->id}}" data-id="{{$reponseQcm->id}}">
                    <x-checkbox-row :item="$reponseQcm" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 41%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$reponseQcm->id}}" data-field="realisation_qcm_id">
                        {{  $reponseQcm->realisationQcm }}

                    </td>
                    <td style="max-width: 41%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$reponseQcm->id}}" data-field="question_qcm_id">
                        {{  $reponseQcm->questionQcm }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($reponseQcms_permissions['edit-reponseQcm'])
                        <x-action-button :entity="$reponseQcm" actionName="edit">
                        @if($reponseQcms_permissionsByItem['update'][$reponseQcm->id])
                            <a href="{{ route('reponseQcms.edit', ['reponseQcm' => $reponseQcm->id]) }}" data-id="{{$reponseQcm->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($reponseQcms_permissions['show-reponseQcm'])
                        <x-action-button :entity="$reponseQcm" actionName="show">
                        @if($reponseQcms_permissionsByItem['view'][$reponseQcm->id])
                            <a href="{{ route('reponseQcms.show', ['reponseQcm' => $reponseQcm->id]) }}" data-id="{{$reponseQcm->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$reponseQcm" actionName="delete">
                        @if($reponseQcms_permissions['destroy-reponseQcm'])
                        @if($reponseQcms_permissionsByItem['delete'][$reponseQcm->id])
                            <form class="context-state" action="{{ route('reponseQcms.destroy',['reponseQcm' => $reponseQcm->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$reponseQcm->id}}">
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
    @section('reponseQcm-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $reponseQcms_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>