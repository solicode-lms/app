{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="specialites-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="nom" label="{{ ucfirst(__('PkgFormation::specialite.nom')) }}" />
                <x-sortable-column field="formateurs" label="{{ ucfirst(__('PkgFormation::formateur.plural')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($specialites_data as $specialite)
                <tr id="specialite-row-{{$specialite->id}}">
                    <td>@limit($specialite->nom, 80)</td>
                    <td>
                        <ul>
                            @foreach ($specialite->formateurs as $formateur)
                                <li>{{ $formateur }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="text-right">

                        @can('show-specialite')
                            <a href="{{ route('specialites.show', ['specialite' => $specialite->id]) }}" data-id="{{$specialite->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
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
        </tbody>
    </table>
</div>

<div class="card-footer">
    @section('specialite-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $specialites_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>