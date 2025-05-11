{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('resource-table')
<div class="card-body p-0 crud-card-body" id="resources-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-resource') || Auth::user()->can('destroy-resource');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="41"  field="nom" modelname="resource" label="{{ucfirst(__('PkgCreationProjet::resource.nom'))}}" />
                <x-sortable-column :sortable="true" width="41"  field="lien" modelname="resource" label="{{ucfirst(__('PkgCreationProjet::resource.lien'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('resource-table-tbody')
            @foreach ($resources_data as $resource)
                @php
                    $isEditable = Auth::user()->can('edit-resource') && Auth::user()->can('update', $resource);
                @endphp
                <tr id="resource-row-{{$resource->id}}" data-id="{{$resource->id}}">
                    <x-checkbox-row :item="$resource" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 41%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$resource->id}}" data-field="nom"  data-toggle="tooltip" title="{{ $resource->nom }}" >
                    <x-field :entity="$resource" field="nom">
                        {{ $resource->nom }}
                    </x-field>
                    </td>
                    <td style="max-width: 41%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$resource->id}}" data-field="lien"  data-toggle="tooltip" title="{{ $resource->lien }}" >
                    <x-field :entity="$resource" field="lien">
                        {{ $resource->lien }}
                    </x-field>
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @can('edit-resource')
                        <x-action-button :entity="$resource" actionName="edit">
                        @can('update', $resource)
                            <a href="{{ route('resources.edit', ['resource' => $resource->id]) }}" data-id="{{$resource->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan
                        @can('show-resource')
                        <x-action-button :entity="$resource" actionName="show">
                        @can('view', $resource)
                            <a href="{{ route('resources.show', ['resource' => $resource->id]) }}" data-id="{{$resource->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan

                        <x-action-button :entity="$resource" actionName="delete">
                        @can('destroy-resource')
                        @can('delete', $resource)
                            <form class="context-state" action="{{ route('resources.destroy',['resource' => $resource->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$resource->id}}">
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
    @section('resource-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $resources_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>