{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('categoryTechnology-table')
<div class="card-body p-0 crud-card-body" id="categoryTechnologies-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $categoryTechnologies_permissions['edit-categoryTechnology'] || $devcategoryTechnologies_permissions['destroy-categoryTechnology'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="82"  field="nom" modelname="categoryTechnology" label="{{ucfirst(__('PkgCompetences::categoryTechnology.nom'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('categoryTechnology-table-tbody')
            @foreach ($categoryTechnologies_data as $categoryTechnology)
                @php
                    $isEditable = $categoryTechnologies_permissions['edit-categoryTechnology'] && $categoryTechnologies_permissionsByItem['update'][$categoryTechnology->id];
                @endphp
                <tr id="categoryTechnology-row-{{$categoryTechnology->id}}" data-id="{{$categoryTechnology->id}}">
                    <x-checkbox-row :item="$categoryTechnology" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 82%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$categoryTechnology->id}}" data-field="nom"  data-toggle="tooltip" title="{{ $categoryTechnology->nom }}" >
                        {{ $categoryTechnology->nom }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($categoryTechnologies_permissions['edit-categoryTechnology'])
                        <x-action-button :entity="$categoryTechnology" actionName="edit">
                        @if($categoryTechnologies_permissionsByItem['update'][$categoryTechnology->id])
                            <a href="{{ route('categoryTechnologies.edit', ['categoryTechnology' => $categoryTechnology->id]) }}" data-id="{{$categoryTechnology->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($categoryTechnologies_permissions['show-categoryTechnology'])
                        <x-action-button :entity="$categoryTechnology" actionName="show">
                        @if($categoryTechnologies_permissionsByItem['view'][$categoryTechnology->id])
                            <a href="{{ route('categoryTechnologies.show', ['categoryTechnology' => $categoryTechnology->id]) }}" data-id="{{$categoryTechnology->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$categoryTechnology" actionName="delete">
                        @if($categoryTechnologies_permissions['destroy-categoryTechnology'])
                        @if($categoryTechnologies_permissionsByItem['delete'][$categoryTechnology->id])
                            <form class="context-state" action="{{ route('categoryTechnologies.destroy',['categoryTechnology' => $categoryTechnology->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$categoryTechnology->id}}">
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
    @section('categoryTechnology-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $categoryTechnologies_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>