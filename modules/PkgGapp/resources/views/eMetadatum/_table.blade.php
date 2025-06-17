{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('eMetadatum-table')
<div class="card-body p-0 crud-card-body" id="eMetadata-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $eMetadata_permissions['edit-eMetadatum'] || $eMetadata_permissions['destroy-eMetadatum'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="27.333333333333332" field="e_model_id" modelname="eMetadatum" label="{{ucfirst(__('PkgGapp::eModel.singular'))}}" />
                <x-sortable-column :sortable="true" width="27.333333333333332" field="e_data_field_id" modelname="eMetadatum" label="{{ucfirst(__('PkgGapp::eDataField.singular'))}}" />
                <x-sortable-column :sortable="true" width="27.333333333333332" field="e_metadata_definition_id" modelname="eMetadatum" label="{{ucfirst(__('PkgGapp::eMetadataDefinition.singular'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('eMetadatum-table-tbody')
            @foreach ($eMetadata_data as $eMetadatum)
                @php
                    $isEditable = $eMetadata_permissions['edit-eMetadatum'] && $eMetadata_permissionsByItem['update'][$eMetadatum->id];
                @endphp
                <tr id="eMetadatum-row-{{$eMetadatum->id}}" data-id="{{$eMetadatum->id}}">
                    <x-checkbox-row :item="$eMetadatum" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$eMetadatum->id}}" data-field="e_model_id"  data-toggle="tooltip" title="{{ $eMetadatum->eModel }}" >
                        {{  $eMetadatum->eModel }}

                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$eMetadatum->id}}" data-field="e_data_field_id"  data-toggle="tooltip" title="{{ $eMetadatum->eDataField }}" >
                        {{  $eMetadatum->eDataField }}

                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$eMetadatum->id}}" data-field="e_metadata_definition_id"  data-toggle="tooltip" title="{{ $eMetadatum->eMetadataDefinition }}" >
                        @include('PkgGapp::eMetadatum.custom.fields.eMetadataDefinition', ['entity' => $eMetadatum])
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($eMetadata_permissions['edit-eMetadatum'])
                        <x-action-button :entity="$eMetadatum" actionName="edit">
                        @if($eMetadata_permissionsByItem['update'][$eMetadatum->id])
                            <a href="{{ route('eMetadata.edit', ['eMetadatum' => $eMetadatum->id]) }}" data-id="{{$eMetadatum->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($eMetadata_permissions['show-eMetadatum'])
                        <x-action-button :entity="$eMetadatum" actionName="show">
                        @if($eMetadata_permissionsByItem['view'][$eMetadatum->id])
                            <a href="{{ route('eMetadata.show', ['eMetadatum' => $eMetadatum->id]) }}" data-id="{{$eMetadatum->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$eMetadatum" actionName="delete">
                        @if($eMetadata_permissions['destroy-eMetadatum'])
                        @if($eMetadata_permissionsByItem['delete'][$eMetadatum->id])
                            <form class="context-state" action="{{ route('eMetadata.destroy',['eMetadatum' => $eMetadatum->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$eMetadatum->id}}">
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
    @section('eMetadatum-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $eMetadata_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>