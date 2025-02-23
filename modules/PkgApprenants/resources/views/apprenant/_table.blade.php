{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="apprenants-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="nom" modelname="apprenant" label="{{ ucfirst(__('PkgApprenants::apprenant.nom')) }}" />
                <x-sortable-column field="prenom" modelname="apprenant" label="{{ ucfirst(__('PkgApprenants::apprenant.prenom')) }}" />
                <x-sortable-column field="groupes" modelname="apprenant" label="{{ ucfirst(__('PkgApprenants::groupe.plural')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($apprenants_data as $apprenant)
                <tr id="apprenant-row-{{$apprenant->id}}">
                    <td>@limit($apprenant->nom, 50)</td>
                    <td>@limit($apprenant->prenom, 50)</td>
                    <td>
                        <ul>
                            @foreach ($apprenant->groupes as $groupe)
                                <li>{{ $groupe }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="text-right">
                       @can('initPassword-apprenant')
                        <a 
                        data-toggle="tooltip" 
                        title="Initialiser le mot de passe" 
                        href="{{ route('apprenants.initPassword', ['id' => $apprenant->id]) }}" 
                        data-id="{{$apprenant->id}}" 
                        data-url="{{ route('apprenants.initPassword', ['id' => $apprenant->id]) }}" 
                        data-action-type="confirm"
                        class="btn btn-default btn-sm context-state actionEntity">
                            <i class="fas fa-unlock-alt"></i>
                        </a>
                        @endcan
                        
                        @can('show-apprenant')
                        @can('view', $apprenant)
                            <a href="{{ route('apprenants.show', ['apprenant' => $apprenant->id]) }}" data-id="{{$apprenant->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
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