{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('technology-table')
<div class="card-body p-0 crud-card-body" id="technologies-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $technologies_permissions['edit-technology'] || $devtechnologies_permissions['destroy-technology'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="41"  field="nom" modelname="technology" label="{{ucfirst(__('PkgCompetences::technology.nom'))}}" />
                <x-sortable-column :sortable="true" width="41" field="category_technology_id" modelname="technology" label="{{ucfirst(__('PkgCompetences::categoryTechnology.singular'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('technology-table-tbody')
            @foreach ($technologies_data as $technology)
                @php
                    $isEditable = $technologies_permissions['edit-technology'] && $technologies_permissionsByItem['update'][$technology->id];
                @endphp
                <tr id="technology-row-{{$technology->id}}" data-id="{{$technology->id}}">
                    <x-checkbox-row :item="$technology" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 41%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$technology->id}}" data-field="nom"  data-toggle="tooltip" title="{{ $technology->nom }}" >
                        {{ $technology->nom }}

                    </td>
                    <td style="max-width: 41%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$technology->id}}" data-field="category_technology_id"  data-toggle="tooltip" title="{{ $technology->categoryTechnology }}" >
                        {{  $technology->categoryTechnology }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($technologies_permissions['edit-technology'])
                        <x-action-button :entity="$technology" actionName="edit">
                        @if($technologies_permissionsByItem['update'][$technology->id])
                            <a href="{{ route('technologies.edit', ['technology' => $technology->id]) }}" data-id="{{$technology->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($technologies_permissions['show-technology'])
                        <x-action-button :entity="$technology" actionName="show">
                        @if($technologies_permissionsByItem['view'][$technology->id])
                            <a href="{{ route('technologies.show', ['technology' => $technology->id]) }}" data-id="{{$technology->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$technology" actionName="delete">
                        @if($technologies_permissions['destroy-technology'])
                        @if($technologies_permissionsByItem['delete'][$technology->id])
                            <form class="context-state" action="{{ route('technologies.destroy',['technology' => $technology->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$technology->id}}">
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
    @section('technology-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $technologies_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>