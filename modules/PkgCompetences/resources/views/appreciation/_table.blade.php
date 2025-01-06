
<div class="card-body table-responsive p-0 crud-card-body" id="appreciations-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="nom" label="{{ ucfirst(__('PkgCompetences::appreciation.nom')) }}" />
                <x-sortable-column field="formateur_id" label="{{ ucfirst(__('PkgUtilisateurs::formateur.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($appreciations_data as $appreciation)
                <tr>
                    <td>@limit($appreciation->nom, 80)</td>
                    <td>@limit($appreciation->formateur->nom ?? '-', 80)</td>
                    <td class="text-right">
                        @can('show-appreciation')
                            <a href="{{ route('appreciations.show', ['appreciation' => $appreciation->id]) }}" data-id="{{$appreciation->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-appreciation')
                        @can('update', $appreciation)
                            <a href="{{ route('appreciations.edit', ['appreciation' => $appreciation->id]) }}" data-id="{{$appreciation->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-appreciation')
                        @can('delete', $appreciation)
                            <form class="context-state" action="{{ route('appreciations.destroy',['appreciation' => $appreciation->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$appreciation->id}}">
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
    @section('crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $appreciations_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>