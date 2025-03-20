{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationChapitre-table')
<div class="card-body table-responsive p-0 crud-card-body" id="realisationChapitres-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="date_debut" modelname="realisationChapitre" label="{{ ucfirst(__('PkgAutoformation::realisationChapitre.date_debut')) }}" />
                <x-sortable-column field="date_fin" modelname="realisationChapitre" label="{{ ucfirst(__('PkgAutoformation::realisationChapitre.date_fin')) }}" />
                <x-sortable-column field="chapitre_id" modelname="realisationChapitre" label="{{ ucfirst(__('PkgAutoformation::chapitre.singular')) }}" />
                <x-sortable-column field="realisation_formation_id" modelname="realisationChapitre" label="{{ ucfirst(__('PkgAutoformation::realisationFormation.singular')) }}" />
                <x-sortable-column field="etat_chapitre_id" modelname="realisationChapitre" label="{{ ucfirst(__('PkgAutoformation::etatChapitre.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('realisationChapitre-table-tbody')
            @foreach ($realisationChapitres_data as $realisationChapitre)
                <tr id="realisationChapitre-row-{{$realisationChapitre->id}}">
                    <td>
                     <span @if(strlen($realisationChapitre->date_debut) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $realisationChapitre->date_debut }}" 
                        @endif>
                        {{ Str::limit($realisationChapitre->date_debut, 40) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($realisationChapitre->date_fin) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $realisationChapitre->date_fin }}" 
                        @endif>
                        {{ Str::limit($realisationChapitre->date_fin, 40) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($realisationChapitre->chapitre) > 50) 
                            data-toggle="tooltip" 
                            title="{{ $realisationChapitre->chapitre }}" 
                        @endif>
                        {{ Str::limit($realisationChapitre->chapitre, 50) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($realisationChapitre->realisationFormation) > 50) 
                            data-toggle="tooltip" 
                            title="{{ $realisationChapitre->realisationFormation }}" 
                        @endif>
                        {{ Str::limit($realisationChapitre->realisationFormation, 50) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($realisationChapitre->etatChapitre) > 50) 
                            data-toggle="tooltip" 
                            title="{{ $realisationChapitre->etatChapitre }}" 
                        @endif>
                        {{ Str::limit($realisationChapitre->etatChapitre, 50) }}
                    </span>
                    </td>
                    <td class="text-right">

                        @can('show-realisationChapitre')
                        @can('view', $realisationChapitre)
                            <a href="{{ route('realisationChapitres.show', ['realisationChapitre' => $realisationChapitre->id]) }}" data-id="{{$realisationChapitre->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-realisationChapitre')
                        @can('update', $realisationChapitre)
                            <a href="{{ route('realisationChapitres.edit', ['realisationChapitre' => $realisationChapitre->id]) }}" data-id="{{$realisationChapitre->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-realisationChapitre')
                        @can('delete', $realisationChapitre)
                            <form class="context-state" action="{{ route('realisationChapitres.destroy',['realisationChapitre' => $realisationChapitre->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$realisationChapitre->id}}">
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
    @section('realisationChapitre-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $realisationChapitres_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>