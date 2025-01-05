{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="nationalites-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="code" label="{{ ucfirst(__('PkgUtilisateurs::nationalite.code')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($nationalites_data as $nationalite)
                <tr>
                    <td>@limit($nationalite->code, 80)</td>
                    <td class="text-right">
                        @can('show-nationalite')
                            <a href="{{ route('nationalites.show', ['nationalite' => $nationalite->id]) }}" data-id="{{$nationalite->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-nationalite')
                            <a href="{{ route('nationalites.edit', ['nationalite' => $nationalite->id]) }}" data-id="{{$nationalite->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-nationalite')
                            <form class="context-state" action="{{ route('nationalites.destroy',['nationalite' => $nationalite->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$nationalite->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
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
        {{ $nationalites_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>