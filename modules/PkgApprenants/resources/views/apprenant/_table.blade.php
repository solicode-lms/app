{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="apprenants-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="nom" label="{{ ucfirst(__('PkgApprenants::apprenant.nom')) }}" />
                <x-sortable-column field="prenom" label="{{ ucfirst(__('PkgApprenants::apprenant.prenom')) }}" />
                <x-sortable-column field="groupe_id" label="{{ ucfirst(__('PkgApprenants::groupe.singular')) }}" />
                <x-sortable-column field="user_id" label="{{ ucfirst(__('PkgAutorisation::user.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($apprenants_data as $apprenant)
                <tr id="apprenant-row-{{$apprenant->id}}">
                    <td>@limit($apprenant->nom, 80)</td>
                    <td>@limit($apprenant->prenom, 80)</td>
                    <td>@limit($apprenant->groupe->code ?? '-', 80)</td>
                    <td>@limit($apprenant->user->name ?? '-', 80)</td>
                    <td class="text-right">
                        @can('show-apprenant')
                            <a href="{{ route('apprenants.show', ['apprenant' => $apprenant->id]) }}" data-id="{{$apprenant->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-apprenant')
                        @can('update', $apprenant)
                            <a href="{{ route('apprenants.edit', ['apprenant' => $apprenant->id]) }}" data-id="{{$apprenant->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-apprenant')
                        @can('delete', $apprenant)
                            <form class="context-state" action="{{ route('apprenants.destroy',['apprenant' => $apprenant->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$apprenant->id}}">
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
    @section('apprenant-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $apprenants_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>