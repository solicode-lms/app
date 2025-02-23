{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="groupes-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="code" modelname="groupe" label="{{ ucfirst(__('PkgApprenants::groupe.code')) }}" />
                <x-sortable-column field="filiere_id" modelname="groupe" label="{{ ucfirst(__('PkgFormation::filiere.singular')) }}" />
                <x-sortable-column field="formateurs" modelname="groupe" label="{{ ucfirst(__('PkgFormation::formateur.plural')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($groupes_data as $groupe)
                <tr id="groupe-row-{{$groupe->id}}">
                    <td>@limit($groupe->code, 50)</td>
                    <td>@limit($groupe->filiere, 50)</td>
                    <td>
                        <ul>
                            @foreach ($groupe->formateurs as $formateur)
                                <li>{{ $formateur }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="text-right">

                        @can('show-groupe')
                        @can('view', $groupe)
                            <a href="{{ route('groupes.show', ['groupe' => $groupe->id]) }}" data-id="{{$groupe->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-groupe')
                        @can('update', $groupe)
                            <a href="{{ route('groupes.edit', ['groupe' => $groupe->id]) }}" data-id="{{$groupe->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-groupe')
                        @can('delete', $groupe)
                            <form class="context-state" action="{{ route('groupes.destroy',['groupe' => $groupe->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$groupe->id}}">
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
    @section('groupe-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $groupes_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>