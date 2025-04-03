{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('formation-table')
<div class="card-body table-responsive p-0 crud-card-body" id="formations-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="nom" modelname="formation" label="{{ ucfirst(__('PkgAutoformation::formation.nom')) }}" />
                <x-sortable-column field="competence_id" modelname="formation" label="{{ ucfirst(__('PkgCompetences::competence.singular')) }}" />
                <x-sortable-column field="is_officiel" modelname="formation" label="{{ ucfirst(__('PkgAutoformation::formation.is_officiel')) }}" />
                <x-sortable-column field="formateur_id" modelname="formation" label="{{ ucfirst(__('PkgFormation::formateur.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('formation-table-tbody')
            @foreach ($formations_data as $formation)
                <tr id="formation-row-{{$formation->id}}">
                    <td>
                     <span @if(strlen($formation->nom) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $formation->nom }}" 
                        @endif>
                        {{ Str::limit($formation->nom, 40) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($formation->competence) > 50) 
                            data-toggle="tooltip" 
                            title="{{ $formation->competence }}" 
                        @endif>
                        {{ Str::limit($formation->competence, 50) }}
                    </span>
                    </td>
                    <td>
                        <span class="{{ $formation->is_officiel ? 'text-success' : 'text-danger' }}">
                            {{ $formation->is_officiel ? 'Oui' : 'Non' }}
                        </span>
                    </td>
                    <td>
                     <span @if(strlen($formation->formateur) > 50) 
                            data-toggle="tooltip" 
                            title="{{ $formation->formateur }}" 
                        @endif>
                        {{ Str::limit($formation->formateur, 50) }}
                    </span>
                    </td>
                    <td class="text-right">

                        @can('show-formation')
                        @can('view', $formation)
                            <a href="{{ route('formations.show', ['formation' => $formation->id]) }}" data-id="{{$formation->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-formation')
                        @can('update', $formation)
                            <a href="{{ route('formations.edit', ['formation' => $formation->id]) }}" data-id="{{$formation->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-formation')
                        @can('delete', $formation)
                            <form class="context-state" action="{{ route('formations.destroy',['formation' => $formation->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$formation->id}}">
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
    @section('formation-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $formations_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>