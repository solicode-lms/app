{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('categoryTechnology-table')
<div class="card-body table-responsive p-0 crud-card-body" id="categoryTechnologies-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                <x-sortable-column width="85"  field="nom" modelname="categoryTechnology" label="{{ ucfirst(__('PkgCompetences::categoryTechnology.nom')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('categoryTechnology-table-tbody')
            @foreach ($categoryTechnologies_data as $categoryTechnology)
                <tr id="categoryTechnology-row-{{$categoryTechnology->id}}">
                    <td style="max-width: 85%;" class="text-truncate" data-toggle="tooltip" title="{{ $categoryTechnology->nom }}" >
                    <x-field :data="$categoryTechnology" field="nom">
                        {{ $categoryTechnology->nom }}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-categoryTechnology')
                        @can('update', $categoryTechnology)
                            <a href="{{ route('categoryTechnologies.edit', ['categoryTechnology' => $categoryTechnology->id]) }}" data-id="{{$categoryTechnology->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-categoryTechnology')
                        @can('view', $categoryTechnology)
                            <a href="{{ route('categoryTechnologies.show', ['categoryTechnology' => $categoryTechnology->id]) }}" data-id="{{$categoryTechnology->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-categoryTechnology')
                        @can('delete', $categoryTechnology)
                            <form class="context-state" action="{{ route('categoryTechnologies.destroy',['categoryTechnology' => $categoryTechnology->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$categoryTechnology->id}}">
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
    @section('categoryTechnology-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $categoryTechnologies_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>