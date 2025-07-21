{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('eDataField-table')
<div class="card-body p-0 crud-card-body" id="eDataFields-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $eDataFields_permissions['edit-eDataField'] || $eDataFields_permissions['destroy-eDataField'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="5"  field="displayOrder" modelname="eDataField" label="{!!ucfirst(__('PkgGapp::eDataField.displayOrder'))!!}" />
                <x-sortable-column :sortable="true" width="15"  field="name" modelname="eDataField" label="{!!ucfirst(__('PkgGapp::eDataField.name'))!!}" />
                <x-sortable-column :sortable="true" width="15" field="e_model_id" modelname="eDataField" label="{!!ucfirst(__('PkgGapp::eModel.singular'))!!}" />
                <x-sortable-column :sortable="true" width="10"  field="data_type" modelname="eDataField" label="{!!ucfirst(__('PkgGapp::eDataField.data_type'))!!}" />
                <x-sortable-column :sortable="true" width="8"  field="displayInTable" modelname="eDataField" label="{!!ucfirst(__('PkgGapp::eDataField.displayInTable'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('eDataField-table-tbody')
            @foreach ($eDataFields_data as $eDataField)
                @php
                    $isEditable = $eDataFields_permissions['edit-eDataField'] && $eDataFields_permissionsByItem['update'][$eDataField->id];
                @endphp
                <tr id="eDataField-row-{{$eDataField->id}}" data-id="{{$eDataField->id}}">
                    <x-checkbox-row :item="$eDataField" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 5%;" class=" text-truncate" data-id="{{$eDataField->id}}" data-field="displayOrder"  data-toggle="tooltip" title="{{ $eDataField->displayOrder }}" >
                            <div class="sortable-button d-flex justify-content-left align-items-center" style="height: 100%;  min-height: 26px;">
                            <i class="fas fa-th-list" title="{{ $eDataField->displayOrder }}"  data-toggle="tooltip" ></i>  
                        </div>

                    </td>
                    <td style="max-width: 15%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$eDataField->id}}" data-field="name"  data-toggle="tooltip" title="{{ $eDataField->name }}" >
                        @include('PkgGapp::eDataField.custom.fields.name', ['entity' => $eDataField])
                    </td>
                    <td style="max-width: 15%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$eDataField->id}}" data-field="e_model_id"  data-toggle="tooltip" title="{{ $eDataField->eModel }}" >
                        {{  $eDataField->eModel }}

                    </td>
                    <td style="max-width: 10%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$eDataField->id}}" data-field="data_type"  data-toggle="tooltip" title="{{ $eDataField->data_type }}" >
                        {{ $eDataField->data_type }}

                    </td>
                    <td style="max-width: 8%;" class=" text-truncate" data-id="{{$eDataField->id}}" data-field="displayInTable"  data-toggle="tooltip" title="{{ $eDataField->displayInTable }}" >
                        <span class="{{ $eDataField->displayInTable ? 'text-success' : 'text-danger' }}">
                            {{ $eDataField->displayInTable ? 'Oui' : 'Non' }}
                        </span>

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($eDataFields_permissions['edit-eDataField'])
                        <x-action-button :entity="$eDataField" actionName="edit">
                        @if($eDataFields_permissionsByItem['update'][$eDataField->id])
                            <a href="{{ route('eDataFields.edit', ['eDataField' => $eDataField->id]) }}" data-id="{{$eDataField->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($eDataFields_permissions['show-eDataField'])
                        <x-action-button :entity="$eDataField" actionName="show">
                        @if($eDataFields_permissionsByItem['view'][$eDataField->id])
                            <a href="{{ route('eDataFields.show', ['eDataField' => $eDataField->id]) }}" data-id="{{$eDataField->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$eDataField" actionName="delete">
                        @if($eDataFields_permissions['destroy-eDataField'])
                        @if($eDataFields_permissionsByItem['delete'][$eDataField->id])
                            <form class="context-state" action="{{ route('eDataFields.destroy',['eDataField' => $eDataField->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$eDataField->id}}">
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
    @section('eDataField-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $eDataFields_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>