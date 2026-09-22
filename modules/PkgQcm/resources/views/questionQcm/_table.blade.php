{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('questionQcm-table')
<div class="card-body p-0 crud-card-body" id="questionQcms-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $questionQcms_permissions['edit-questionQcm'] || $questionQcms_permissions['destroy-questionQcm'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="4"  field="ordre" modelname="questionQcm" label="{!!ucfirst(__('PkgQcm::questionQcm.ordre'))!!}" />
                <x-sortable-column :sortable="true" width="39" field="qcm_id" modelname="questionQcm" label="{!!ucfirst(__('PkgQcm::qcm.singular'))!!}" />
                <x-sortable-column :sortable="true" width="39" field="question_lib_id" modelname="questionQcm" label="{!!ucfirst(__('PkgQcm::questionLib.singular'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('questionQcm-table-tbody')
            @foreach ($questionQcms_data as $questionQcm)
                @php
                    $isEditable = $questionQcms_permissions['edit-questionQcm'] && $questionQcms_permissionsByItem['update'][$questionQcm->id];
                @endphp
                <tr id="questionQcm-row-{{$questionQcm->id}}" data-id="{{$questionQcm->id}}">
                    <x-checkbox-row :item="$questionQcm" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 4%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$questionQcm->id}}" data-field="ordre">
                            <div class="sortable-button d-flex justify-content-left align-items-center" style="height: 100%;  min-height: 26px;">
                            <i class="fas fa-th-list" title="{{ $questionQcm->ordre }}"  data-toggle="tooltip" ></i>  
                        </div>

                    </td>
                    <td style="max-width: 39%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$questionQcm->id}}" data-field="qcm_id">
                        {{  $questionQcm->qcm }}

                    </td>
                    <td style="max-width: 39%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$questionQcm->id}}" data-field="question_lib_id">
                        {{  $questionQcm->questionLib }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($questionQcms_permissions['edit-questionQcm'])
                        <x-action-button :entity="$questionQcm" actionName="edit">
                        @if($questionQcms_permissionsByItem['update'][$questionQcm->id])
                            <a href="{{ route('questionQcms.edit', ['questionQcm' => $questionQcm->id]) }}" data-id="{{$questionQcm->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($questionQcms_permissions['show-questionQcm'])
                        <x-action-button :entity="$questionQcm" actionName="show">
                        @if($questionQcms_permissionsByItem['view'][$questionQcm->id])
                            <a href="{{ route('questionQcms.show', ['questionQcm' => $questionQcm->id]) }}" data-id="{{$questionQcm->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$questionQcm" actionName="delete">
                        @if($questionQcms_permissions['destroy-questionQcm'])
                        @if($questionQcms_permissionsByItem['delete'][$questionQcm->id])
                            <form class="context-state" action="{{ route('questionQcms.destroy',['questionQcm' => $questionQcm->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$questionQcm->id}}">
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
    @section('questionQcm-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $questionQcms_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>