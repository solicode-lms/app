{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('eMetadatum-table')
<div class="card-body p-0 crud-card-body" id="eMetadata-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-eMetadatum') || Auth::user()->can('destroy-eMetadatum');
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
                    $isEditable = Auth::user()->can('edit-eMetadatum') && Auth::user()->can('update', $eMetadatum);
                @endphp
                <tr id="eMetadatum-row-{{$eMetadatum->id}}" data-id="{{$eMetadatum->id}}">
                    <x-checkbox-row :item="$eMetadatum" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$eMetadatum->id}}" data-field="e_model_id"  data-toggle="tooltip" title="{{ $eMetadatum->eModel }}" >
                    <x-field :entity="$eMetadatum" field="eModel">
                       
                         {{  $eMetadatum->eModel }}
                    </x-field>
                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$eMetadatum->id}}" data-field="e_data_field_id"  data-toggle="tooltip" title="{{ $eMetadatum->eDataField }}" >
                    <x-field :entity="$eMetadatum" field="eDataField">
                       
                         {{  $eMetadatum->eDataField }}
                    </x-field>
                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$eMetadatum->id}}" data-field="e_metadata_definition_id"  data-toggle="tooltip" title="{{ $eMetadatum->eMetadataDefinition }}" >
                    <x-field :entity="$eMetadatum" field="eMetadataDefinition">
                       
                         {{  $eMetadatum->eMetadataDefinition }}
                    </x-field>
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @can('edit-eMetadatum')
                        <x-action-button :entity="$eMetadatum" actionName="edit">
                        @can('update', $eMetadatum)
                            <a href="{{ route('eMetadata.edit', ['eMetadatum' => $eMetadatum->id]) }}" data-id="{{$eMetadatum->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan
                        @can('show-eMetadatum')
                        <x-action-button :entity="$eMetadatum" actionName="show">
                        @can('view', $eMetadatum)
                            <a href="{{ route('eMetadata.show', ['eMetadatum' => $eMetadatum->id]) }}" data-id="{{$eMetadatum->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan

                        <x-action-button :entity="$eMetadatum" actionName="delete">
                        @can('destroy-eMetadatum')
                        @can('delete', $eMetadatum)
                            <form class="context-state" action="{{ route('eMetadata.destroy',['eMetadatum' => $eMetadatum->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$eMetadatum->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endcan
                        @endcan
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