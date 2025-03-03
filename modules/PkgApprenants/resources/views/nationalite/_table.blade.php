{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('nationalite-table')
<div class="card-body table-responsive p-0 crud-card-body" id="nationalites-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="code" modelname="nationalite" label="{{ ucfirst(__('PkgApprenants::nationalite.code')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('nationalite-table-tbody')
            @foreach ($nationalites_data as $nationalite)
                <tr id="nationalite-row-{{$nationalite->id}}">
                    <td>@limit($nationalite->code, 50)</td>
                    <td class="text-right">

                        @can('show-nationalite')
                        @can('view', $nationalite)
                            <a href="{{ route('nationalites.show', ['nationalite' => $nationalite->id]) }}" data-id="{{$nationalite->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-nationalite')
                        @can('update', $nationalite)
                            <a href="{{ route('nationalites.edit', ['nationalite' => $nationalite->id]) }}" data-id="{{$nationalite->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-nationalite')
                        @can('delete', $nationalite)
                            <form class="context-state" action="{{ route('nationalites.destroy',['nationalite' => $nationalite->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$nationalite->id}}">
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
    @section('nationalite-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $nationalites_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>