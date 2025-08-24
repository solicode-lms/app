{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('eRelationship-table')
<div class="card-body p-0 crud-card-body" id="eRelationships-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $eRelationships_permissions['edit-eRelationship'] || $eRelationships_permissions['destroy-eRelationship'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="20.5"  field="name" modelname="eRelationship" label="{!!ucfirst(__('PkgGapp::eRelationship.name'))!!}" />
                <x-sortable-column :sortable="true" width="20.5"  field="type" modelname="eRelationship" label="{!!ucfirst(__('PkgGapp::eRelationship.type'))!!}" />
                <x-sortable-column :sortable="true" width="20.5" field="source_e_model_id" modelname="eRelationship" label="{!!ucfirst(__('PkgGapp::eModel.singular'))!!}" />
                <x-sortable-column :sortable="true" width="20.5" field="target_e_model_id" modelname="eRelationship" label="{!!ucfirst(__('PkgGapp::eModel.singular'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('eRelationship-table-tbody')
            @foreach ($eRelationships_data as $eRelationship)
                @php
                    $isEditable = $eRelationships_permissions['edit-eRelationship'] && $eRelationships_permissionsByItem['update'][$eRelationship->id];
                @endphp
                <tr id="eRelationship-row-{{$eRelationship->id}}" data-id="{{$eRelationship->id}}">
                    <x-checkbox-row :item="$eRelationship" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 20.5%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$eRelationship->id}}" data-field="name">
                        {{ $eRelationship->name }}

                    </td>
                    <td style="max-width: 20.5%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$eRelationship->id}}" data-field="type">
                        {{ $eRelationship->type }}

                    </td>
                    <td style="max-width: 20.5%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$eRelationship->id}}" data-field="source_e_model_id">
                        {{  $eRelationship->sourceEModel }}

                    </td>
                    <td style="max-width: 20.5%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$eRelationship->id}}" data-field="target_e_model_id">
                        {{  $eRelationship->targetEModel }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($eRelationships_permissions['edit-eRelationship'])
                        <x-action-button :entity="$eRelationship" actionName="edit">
                        @if($eRelationships_permissionsByItem['update'][$eRelationship->id])
                            <a href="{{ route('eRelationships.edit', ['eRelationship' => $eRelationship->id]) }}" data-id="{{$eRelationship->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($eRelationships_permissions['show-eRelationship'])
                        <x-action-button :entity="$eRelationship" actionName="show">
                        @if($eRelationships_permissionsByItem['view'][$eRelationship->id])
                            <a href="{{ route('eRelationships.show', ['eRelationship' => $eRelationship->id]) }}" data-id="{{$eRelationship->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$eRelationship" actionName="delete">
                        @if($eRelationships_permissions['destroy-eRelationship'])
                        @if($eRelationships_permissionsByItem['delete'][$eRelationship->id])
                            <form class="context-state" action="{{ route('eRelationships.destroy',['eRelationship' => $eRelationship->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$eRelationship->id}}">
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
    @section('eRelationship-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $eRelationships_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>