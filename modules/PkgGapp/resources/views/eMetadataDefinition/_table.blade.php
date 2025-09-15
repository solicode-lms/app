{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('eMetadataDefinition-table')
<div class="card-body p-0 crud-card-body" id="eMetadataDefinitions-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $eMetadataDefinitions_permissions['edit-eMetadataDefinition'] || $eMetadataDefinitions_permissions['destroy-eMetadataDefinition'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="name" modelname="eMetadataDefinition" label="{!!ucfirst(__('PkgGapp::eMetadataDefinition.name'))!!}" />
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="groupe" modelname="eMetadataDefinition" label="{!!ucfirst(__('PkgGapp::eMetadataDefinition.groupe'))!!}" />
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="description" modelname="eMetadataDefinition" label="{!!ucfirst(__('PkgGapp::eMetadataDefinition.description'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('eMetadataDefinition-table-tbody')
            @foreach ($eMetadataDefinitions_data as $eMetadataDefinition)
                @php
                    $isEditable = $eMetadataDefinitions_permissions['edit-eMetadataDefinition'] && $eMetadataDefinitions_permissionsByItem['update'][$eMetadataDefinition->id];
                @endphp
                <tr id="eMetadataDefinition-row-{{$eMetadataDefinition->id}}" data-id="{{$eMetadataDefinition->id}}">
                    <x-checkbox-row :item="$eMetadataDefinition" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 27.333333333333332%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$eMetadataDefinition->id}}" data-field="name">
                        {{ $eMetadataDefinition->name }}

                    </td>
                    <td style="max-width: 27.333333333333332%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$eMetadataDefinition->id}}" data-field="groupe">
                        {{ $eMetadataDefinition->groupe }}

                    </td>
                    <td style="max-width: 27.333333333333332%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$eMetadataDefinition->id}}" data-field="description">
                  
                        {!! \App\Helpers\TextHelper::formatHtmlWithLineBreaks($eMetadataDefinition->description, 30) !!}
                   

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($eMetadataDefinitions_permissions['edit-eMetadataDefinition'])
                        <x-action-button :entity="$eMetadataDefinition" actionName="edit">
                        @if($eMetadataDefinitions_permissionsByItem['update'][$eMetadataDefinition->id])
                            <a href="{{ route('eMetadataDefinitions.edit', ['eMetadataDefinition' => $eMetadataDefinition->id]) }}" data-id="{{$eMetadataDefinition->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($eMetadataDefinitions_permissions['show-eMetadataDefinition'])
                        <x-action-button :entity="$eMetadataDefinition" actionName="show">
                        @if($eMetadataDefinitions_permissionsByItem['view'][$eMetadataDefinition->id])
                            <a href="{{ route('eMetadataDefinitions.show', ['eMetadataDefinition' => $eMetadataDefinition->id]) }}" data-id="{{$eMetadataDefinition->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$eMetadataDefinition" actionName="delete">
                        @if($eMetadataDefinitions_permissions['destroy-eMetadataDefinition'])
                        @if($eMetadataDefinitions_permissionsByItem['delete'][$eMetadataDefinition->id])
                            <form class="context-state" action="{{ route('eMetadataDefinitions.destroy',['eMetadataDefinition' => $eMetadataDefinition->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$eMetadataDefinition->id}}">
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
    @section('eMetadataDefinition-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $eMetadataDefinitions_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>