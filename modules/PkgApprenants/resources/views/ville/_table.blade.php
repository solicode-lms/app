{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('ville-table')
<div class="card-body table-responsive p-0 crud-card-body" id="villes-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="nom" modelname="ville" label="{{ ucfirst(__('PkgApprenants::ville.nom')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('ville-table-tbody')
            @foreach ($villes_data as $ville)
                <tr id="ville-row-{{$ville->id}}">
                    <td>
                     <span @if(strlen($ville->nom) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $ville->nom }}" 
                        @endif>
                        {{ Str::limit($ville->nom, 40) }}
                    </span>
                    </td>
                    <td class="text-right">

                        @can('show-ville')
                        @can('view', $ville)
                            <a href="{{ route('villes.show', ['ville' => $ville->id]) }}" data-id="{{$ville->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
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
            @show
        </tbody>
    </table>
</div>
@show

<div class="card-footer">
    @section('ville-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $villes_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>