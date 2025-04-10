{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('competence-table')
<div class="card-body table-responsive p-0 crud-card-body" id="competences-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="code" modelname="competence" label="{{ ucfirst(__('PkgCompetences::competence.code')) }}" />
                <x-sortable-column field="mini_code" modelname="competence" label="{{ ucfirst(__('PkgCompetences::competence.mini_code')) }}" />
                <x-sortable-column field="nom" modelname="competence" label="{{ ucfirst(__('PkgCompetences::competence.nom')) }}" />
                <x-sortable-column field="module_id" modelname="competence" label="{{ ucfirst(__('PkgFormation::module.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('competence-table-tbody')
            @foreach ($competences_data as $competence)
                <tr id="competence-row-{{$competence->id}}">
                    <td>
                     <span @if(strlen($competence->code) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $competence->code }}" 
                        @endif>
                        {{ Str::limit($competence->code, 40) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($competence->mini_code) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $competence->mini_code }}" 
                        @endif>
                        {{ Str::limit($competence->mini_code, 40) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($competence->nom) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $competence->nom }}" 
                        @endif>
                        {{ Str::limit($competence->nom, 40) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($competence->module) > 50) 
                            data-toggle="tooltip" 
                            title="{{ $competence->module }}" 
                        @endif>
                        {{ Str::limit($competence->module, 50) }}
                    </span>
                    </td>
                    <td class="text-right">

                        @can('show-competence')
                        @can('view', $competence)
                            <a href="{{ route('competences.show', ['competence' => $competence->id]) }}" data-id="{{$competence->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
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
            @show
        </tbody>
    </table>
</div>
@show

<div class="card-footer">
    @section('competence-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $competences_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>