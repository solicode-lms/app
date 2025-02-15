{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="technologies-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="nom" label="{{ ucfirst(__('PkgCompetences::technology.nom')) }}" />
                <x-sortable-column field="category_technology_id" label="{{ ucfirst(__('PkgCompetences::categoryTechnology.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($technologies_data as $technology)
                <tr id="technology-row-{{$technology->id}}">
                    <td>@limit($technology->nom, 80)</td>
                    <td>@limit($technology->categoryTechnology, 80)</td>
                    <td class="text-right">
                        @can('show-technology')
                            <a href="{{ route('technologies.show', ['technology' => $technology->id]) }}" data-id="{{$technology->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-technology')
                        @can('update', $technology)
                            <a href="{{ route('technologies.edit', ['technology' => $technology->id]) }}" data-id="{{$technology->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-technology')
                        @can('delete', $technology)
                            <form class="context-state" action="{{ route('technologies.destroy',['technology' => $technology->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$technology->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endcan
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="card-footer">
    @section('technology-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $technologies_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>