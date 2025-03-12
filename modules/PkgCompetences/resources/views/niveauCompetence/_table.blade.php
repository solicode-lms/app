{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('niveauCompetence-table')
<div class="card-body table-responsive p-0 crud-card-body" id="niveauCompetences-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="nom" modelname="niveauCompetence" label="{{ ucfirst(__('PkgCompetences::niveauCompetence.nom')) }}" />
                <x-sortable-column field="competence_id" modelname="niveauCompetence" label="{{ ucfirst(__('PkgCompetences::competence.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('niveauCompetence-table-tbody')
            @foreach ($niveauCompetences_data as $niveauCompetence)
                <tr id="niveauCompetence-row-{{$niveauCompetence->id}}">
                    <td>
                     <span @if(strlen($niveauCompetence->nom) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $niveauCompetence->nom }}" 
                        @endif>
                        {{ Str::limit($niveauCompetence->nom, 40) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($niveauCompetence->competence) > 50) 
                            data-toggle="tooltip" 
                            title="{{ $niveauCompetence->competence }}" 
                        @endif>
                        {{ Str::limit($niveauCompetence->competence, 50) }}
                    </span>
                    </td>
                    <td class="text-right">

                        @can('show-niveauCompetence')
                        @can('view', $niveauCompetence)
                            <a href="{{ route('niveauCompetences.show', ['niveauCompetence' => $niveauCompetence->id]) }}" data-id="{{$niveauCompetence->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-niveauCompetence')
                        @can('update', $niveauCompetence)
                            <a href="{{ route('niveauCompetences.edit', ['niveauCompetence' => $niveauCompetence->id]) }}" data-id="{{$niveauCompetence->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-niveauCompetence')
                        @can('delete', $niveauCompetence)
                            <form class="context-state" action="{{ route('niveauCompetences.destroy',['niveauCompetence' => $niveauCompetence->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$niveauCompetence->id}}">
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
    @section('niveauCompetence-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $niveauCompetences_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>