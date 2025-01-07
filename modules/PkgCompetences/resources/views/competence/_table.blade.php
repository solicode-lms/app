{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="competences-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="code" label="{{ ucfirst(__('PkgCompetences::competence.code')) }}" />
                <x-sortable-column field="nom" label="{{ ucfirst(__('PkgCompetences::competence.nom')) }}" />
                <x-sortable-column field="module_id" label="{{ ucfirst(__('PkgCompetences::module.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($competences_data as $competence)
                <tr>
                    <td>@limit($competence->code, 80)</td>
                    <td>@limit($competence->nom, 80)</td>
                    <td>@limit($competence->module->nom ?? '-', 80)</td>
                    <td class="text-right">
                        @can('show-competence')
                            <a href="{{ route('competences.show', ['competence' => $competence->id]) }}" data-id="{{$competence->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-competence')
                        @can('update', $competence)
                            <a href="{{ route('competences.edit', ['competence' => $competence->id]) }}" data-id="{{$competence->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-competence')
                        @can('delete', $competence)
                            <form class="context-state" action="{{ route('competences.destroy',['competence' => $competence->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$competence->id}}">
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
    @section('competence-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $competences_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>