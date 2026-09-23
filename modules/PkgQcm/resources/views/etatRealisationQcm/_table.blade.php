{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatRealisationQcm-table')
<div class="card-body p-0 crud-card-body" id="etatRealisationQcms-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $etatRealisationQcms_permissions['edit-etatRealisationQcm'] || $etatRealisationQcms_permissions['destroy-etatRealisationQcm'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="41"  field="titre" modelname="etatRealisationQcm" label="{!!ucfirst(__('PkgQcm::etatRealisationQcm.titre'))!!}" />
                <x-sortable-column :sortable="true" width="41" field="sys_color_id" modelname="etatRealisationQcm" label="{!!ucfirst(__('Core::sysColor.singular'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('etatRealisationQcm-table-tbody')
            @foreach ($etatRealisationQcms_data as $etatRealisationQcm)
                @php
                    $isEditable = $etatRealisationQcms_permissions['edit-etatRealisationQcm'] && $etatRealisationQcms_permissionsByItem['update'][$etatRealisationQcm->id];
                @endphp
                <tr id="etatRealisationQcm-row-{{$etatRealisationQcm->id}}" data-id="{{$etatRealisationQcm->id}}">
                    <x-checkbox-row :item="$etatRealisationQcm" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 41%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatRealisationQcm->id}}" data-field="titre">
                        {{ $etatRealisationQcm->titre }}

                    </td>
                    <td style="max-width: 41%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatRealisationQcm->id}}" data-field="sys_color_id">
                        <x-badge 
                        :text="$etatRealisationQcm->sysColor->name ?? ''" 
                        :background="$etatRealisationQcm->sysColor->hex ?? '#6c757d'" 
                        />

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($etatRealisationQcms_permissions['edit-etatRealisationQcm'])
                        <x-action-button :entity="$etatRealisationQcm" actionName="edit">
                        @if($etatRealisationQcms_permissionsByItem['update'][$etatRealisationQcm->id])
                            <a href="{{ route('etatRealisationQcms.edit', ['etatRealisationQcm' => $etatRealisationQcm->id]) }}" data-id="{{$etatRealisationQcm->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($etatRealisationQcms_permissions['show-etatRealisationQcm'])
                        <x-action-button :entity="$etatRealisationQcm" actionName="show">
                        @if($etatRealisationQcms_permissionsByItem['view'][$etatRealisationQcm->id])
                            <a href="{{ route('etatRealisationQcms.show', ['etatRealisationQcm' => $etatRealisationQcm->id]) }}" data-id="{{$etatRealisationQcm->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$etatRealisationQcm" actionName="delete">
                        @if($etatRealisationQcms_permissions['destroy-etatRealisationQcm'])
                        @if($etatRealisationQcms_permissionsByItem['delete'][$etatRealisationQcm->id])
                            <form class="context-state" action="{{ route('etatRealisationQcms.destroy',['etatRealisationQcm' => $etatRealisationQcm->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$etatRealisationQcm->id}}">
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
    @section('etatRealisationQcm-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $etatRealisationQcms_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>