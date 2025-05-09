{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('eRelationship-table')
<div class="card-body p-0 crud-card-body" id="eRelationships-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-eRelationship') || Auth::user()->can('destroy-eRelationship');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="20.5"  field="name" modelname="eRelationship" label="{{ucfirst(__('PkgGapp::eRelationship.name'))}}" />
                <x-sortable-column :sortable="true" width="20.5"  field="type" modelname="eRelationship" label="{{ucfirst(__('PkgGapp::eRelationship.type'))}}" />
                <x-sortable-column :sortable="true" width="20.5" field="source_e_model_id" modelname="eRelationship" label="{{ucfirst(__('PkgGapp::eModel.singular'))}}" />
                <x-sortable-column :sortable="true" width="20.5" field="target_e_model_id" modelname="eRelationship" label="{{ucfirst(__('PkgGapp::eModel.singular'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('eRelationship-table-tbody')
            @foreach ($eRelationships_data as $eRelationship)
                @php
                    $isEditable = Auth::user()->can('edit-eRelationship') && Auth::user()->can('update', $eRelationship);
                @endphp
                <tr id="eRelationship-row-{{$eRelationship->id}}" data-id="{{$eRelationship->id}}">
                    <x-checkbox-row :item="$eRelationship" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$eRelationship->id}}" data-field="name"  data-toggle="tooltip" title="{{ $eRelationship->name }}" >
                    <x-field :entity="$eRelationship" field="name">
                        {{ $eRelationship->name }}
                    </x-field>
                    </td>
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$eRelationship->id}}" data-field="type"  data-toggle="tooltip" title="{{ $eRelationship->type }}" >
                    <x-field :entity="$eRelationship" field="type">
                        {{ $eRelationship->type }}
                    </x-field>
                    </td>
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$eRelationship->id}}" data-field="source_e_model_id"  data-toggle="tooltip" title="{{ $eRelationship->sourceEModel }}" >
                    <x-field :entity="$eRelationship" field="sourceEModel">
                       
                         {{  $eRelationship->sourceEModel }}
                    </x-field>
                    </td>
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$eRelationship->id}}" data-field="target_e_model_id"  data-toggle="tooltip" title="{{ $eRelationship->targetEModel }}" >
                    <x-field :entity="$eRelationship" field="targetEModel">
                       
                         {{  $eRelationship->targetEModel }}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-eRelationship')
                        <x-action-button :entity="$eRelationship" actionName="edit">
                        @can('update', $eRelationship)
                            <a href="{{ route('eRelationships.edit', ['eRelationship' => $eRelationship->id]) }}" data-id="{{$eRelationship->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan
                        @can('show-eRelationship')
                        <x-action-button :entity="$eRelationship" actionName="show">
                        @can('view', $eRelationship)
                            <a href="{{ route('eRelationships.show', ['eRelationship' => $eRelationship->id]) }}" data-id="{{$eRelationship->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan

                        <x-action-button :entity="$eRelationship" actionName="delete">
                        @can('destroy-eRelationship')
                        @can('delete', $eRelationship)
                            <form class="context-state" action="{{ route('eRelationships.destroy',['eRelationship' => $eRelationship->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$eRelationship->id}}">
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
    @section('eRelationship-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $eRelationships_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>