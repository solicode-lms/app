{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('chapitre-table')
<div class="card-body table-responsive p-0 crud-card-body" id="chapitres-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="nom" modelname="chapitre" label="{{ ucfirst(__('PkgAutoformation::chapitre.nom')) }}" />
                <x-sortable-column field="lien" modelname="chapitre" label="{{ ucfirst(__('PkgAutoformation::chapitre.lien')) }}" />
                <x-sortable-column field="formation_id" modelname="chapitre" label="{{ ucfirst(__('PkgAutoformation::formation.singular')) }}" />
                <x-sortable-column field="niveau_competence_id" modelname="chapitre" label="{{ ucfirst(__('PkgCompetences::niveauCompetence.singular')) }}" />
                <x-sortable-column field="formateur_id" modelname="chapitre" label="{{ ucfirst(__('PkgFormation::formateur.singular')) }}" />
                <x-sortable-column field="chapitre_officiel_id" modelname="chapitre" label="{{ ucfirst(__('PkgAutoformation::chapitre.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('chapitre-table-tbody')
            @foreach ($chapitres_data as $chapitre)
                <tr id="chapitre-row-{{$chapitre->id}}">
                    <td>
                     <span @if(strlen($chapitre->nom) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $chapitre->nom }}" 
                        @endif>
                        {{ Str::limit($chapitre->nom, 40) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($chapitre->lien) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $chapitre->lien }}" 
                        @endif>
                        {{ Str::limit($chapitre->lien, 40) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($chapitre->formation) > 50) 
                            data-toggle="tooltip" 
                            title="{{ $chapitre->formation }}" 
                        @endif>
                        {{ Str::limit($chapitre->formation, 50) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($chapitre->niveauCompetence) > 50) 
                            data-toggle="tooltip" 
                            title="{{ $chapitre->niveauCompetence }}" 
                        @endif>
                        {{ Str::limit($chapitre->niveauCompetence, 50) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($chapitre->formateur) > 50) 
                            data-toggle="tooltip" 
                            title="{{ $chapitre->formateur }}" 
                        @endif>
                        {{ Str::limit($chapitre->formateur, 50) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($chapitre->chapitreOfficiel) > 50) 
                            data-toggle="tooltip" 
                            title="{{ $chapitre->chapitreOfficiel }}" 
                        @endif>
                        {{ Str::limit($chapitre->chapitreOfficiel, 50) }}
                    </span>
                    </td>
                    <td class="text-right">

                        @can('show-chapitre')
                        @can('view', $chapitre)
                            <a href="{{ route('chapitres.show', ['chapitre' => $chapitre->id]) }}" data-id="{{$chapitre->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-chapitre')
                        @can('update', $chapitre)
                            <a href="{{ route('chapitres.edit', ['chapitre' => $chapitre->id]) }}" data-id="{{$chapitre->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-chapitre')
                        @can('delete', $chapitre)
                            <form class="context-state" action="{{ route('chapitres.destroy',['chapitre' => $chapitre->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$chapitre->id}}">
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
    @section('chapitre-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $chapitres_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>