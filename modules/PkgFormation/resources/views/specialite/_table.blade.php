{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('specialite-table')
<div class="card-body table-responsive p-0 crud-card-body" id="specialites-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="nom" modelname="specialite" label="{{ ucfirst(__('PkgFormation::specialite.nom')) }}" />
                <x-sortable-column field="formateurs" modelname="specialite" label="{{ ucfirst(__('PkgFormation::formateur.plural')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('specialite-table-tbody')
            @foreach ($specialites_data as $specialite)
                <tr id="specialite-row-{{$specialite->id}}">
                    <td>@limit($specialite->nom, 50)</td>
                    <td>
                        <ul>
                            @foreach ($specialite->formateurs as $formateur)
                                <li>{{ $formateur }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="text-right">

                        @can('show-specialite')
                        @can('view', $specialite)
                            <a href="{{ route('specialites.show', ['specialite' => $specialite->id]) }}" data-id="{{$specialite->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-specialite')
                        @can('update', $specialite)
                            <a href="{{ route('specialites.edit', ['specialite' => $specialite->id]) }}" data-id="{{$specialite->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-specialite')
                        @can('delete', $specialite)
                            <form class="context-state" action="{{ route('specialites.destroy',['specialite' => $specialite->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$specialite->id}}">
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
    @section('specialite-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $specialites_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>