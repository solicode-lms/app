{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('technology-table')
<div class="card-body p-0 crud-card-body" id="technologies-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-technology') || Auth::user()->can('destroy-technology');
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
                    $isEditable = Auth::user()->can('edit-technology') && Auth::user()->can('update', $technology);
                @endphp
                <tr id="technology-row-{{$technology->id}}" data-id="{{$technology->id}}">
                    <x-checkbox-row :item="$technology" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 41%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$technology->id}}" data-field="nom"  data-toggle="tooltip" title="{{ $technology->nom }}" >
                    <x-field :entity="$technology" field="nom">
                        {{ $technology->nom }}
                    </x-field>
                    </td>
                    <td style="max-width: 41%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$technology->id}}" data-field="category_technology_id"  data-toggle="tooltip" title="{{ $technology->categoryTechnology }}" >
                    <x-field :entity="$technology" field="categoryTechnology">
                       
                         {{  $technology->categoryTechnology }}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-technology')
                        @can('update', $technology)
                            <a href="{{ route('technologies.edit', ['technology' => $technology->id]) }}" data-id="{{$technology->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-technology')
                        @can('view', $technology)
                            <a href="{{ route('technologies.show', ['technology' => $technology->id]) }}" data-id="{{$technology->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-technology')
                        @can('delete', $technology)
                            <form class="context-state" action="{{ route('technologies.destroy',['technology' => $technology->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$technology->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endcan
                        @endcan
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