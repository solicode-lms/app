{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="villes-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="nom" modelname="ville" label="{{ ucfirst(__('PkgApprenants::ville.nom')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($villes_data as $ville)
                <tr id="ville-row-{{$ville->id}}">
                    <td>@limit($ville->nom, 50)</td>
                    <td class="text-right">

                        @can('show-ville')
                            <a href="{{ route('villes.show', ['ville' => $ville->id]) }}" data-id="{{$ville->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-ville')
                        @can('update', $ville)
                            <a href="{{ route('villes.edit', ['ville' => $ville->id]) }}" data-id="{{$ville->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-ville')
                        @can('delete', $ville)
                            <form class="context-state" action="{{ route('villes.destroy',['ville' => $ville->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$ville->id}}">
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
    @section('ville-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $villes_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>