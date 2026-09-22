{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('qcm-table')
<div class="card-body p-0 crud-card-body" id="qcms-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $qcms_permissions['edit-qcm'] || $qcms_permissions['destroy-qcm'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="41"  field="titre" modelname="qcm" label="{!!ucfirst(__('PkgQcm::qcm.titre'))!!}" />
                <x-sortable-column :sortable="true" width="41" field="formateur_id" modelname="qcm" label="{!!ucfirst(__('PkgFormation::formateur.singular'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('qcm-table-tbody')
            @foreach ($qcms_data as $qcm)
                @php
                    $isEditable = $qcms_permissions['edit-qcm'] && $qcms_permissionsByItem['update'][$qcm->id];
                @endphp
                <tr id="qcm-row-{{$qcm->id}}" data-id="{{$qcm->id}}">
                    <x-checkbox-row :item="$qcm" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 41%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$qcm->id}}" data-field="titre">
                        {{ $qcm->titre }}

                    </td>
                    <td style="max-width: 41%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$qcm->id}}" data-field="formateur_id">
                        {{  $qcm->formateur }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($qcms_permissions['edit-qcm'])
                        <x-action-button :entity="$qcm" actionName="edit">
                        @if($qcms_permissionsByItem['update'][$qcm->id])
                            <a href="{{ route('qcms.edit', ['qcm' => $qcm->id]) }}" data-id="{{$qcm->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($qcms_permissions['show-qcm'])
                        <x-action-button :entity="$qcm" actionName="show">
                        @if($qcms_permissionsByItem['view'][$qcm->id])
                            <a href="{{ route('qcms.show', ['qcm' => $qcm->id]) }}" data-id="{{$qcm->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$qcm" actionName="delete">
                        @if($qcms_permissions['destroy-qcm'])
                        @if($qcms_permissionsByItem['delete'][$qcm->id])
                            <form class="context-state" action="{{ route('qcms.destroy',['qcm' => $qcm->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$qcm->id}}">
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
    @section('qcm-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $qcms_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>