{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="formateurs-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="nom" label="{{ ucfirst(__('PkgUtilisateurs::formateur.nom')) }}" />
                <x-sortable-column field="prenom" label="{{ ucfirst(__('PkgUtilisateurs::formateur.prenom')) }}" />
                <x-sortable-column field="adresse" label="{{ ucfirst(__('PkgUtilisateurs::formateur.adresse')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($formateurs_data as $formateur)
                <tr>
                    <td>@limit($formateur->nom, 80)</td>
                    <td>@limit($formateur->prenom, 80)</td>
                    <td>@limit($formateur->adresse, 80)</td>
                    <td class="text-right">
                        @can('show-formateur')
                            <a href="{{ route('formateurs.show', ['formateur' => $formateur->id]) }}" data-id="{{$formateur->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-formateur')
                            <a href="{{ route('formateurs.edit', ['formateur' => $formateur->id]) }}" data-id="{{$formateur->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-formateur')
                            <form class="context-state" action="{{ route('formateurs.destroy',['formateur' => $formateur->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$formateur->id}}">
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
        {{ $formateurs_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>