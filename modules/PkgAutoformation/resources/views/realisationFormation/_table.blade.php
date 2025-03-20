{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationFormation-table')
<div class="card-body table-responsive p-0 crud-card-body" id="realisationFormations-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="date_debut" modelname="realisationFormation" label="{{ ucfirst(__('PkgAutoformation::realisationFormation.date_debut')) }}" />
                <x-sortable-column field="date_fin" modelname="realisationFormation" label="{{ ucfirst(__('PkgAutoformation::realisationFormation.date_fin')) }}" />
                <x-sortable-column field="formation_id" modelname="realisationFormation" label="{{ ucfirst(__('PkgAutoformation::formation.singular')) }}" />
                <x-sortable-column field="apprenant_id" modelname="realisationFormation" label="{{ ucfirst(__('PkgApprenants::apprenant.singular')) }}" />
                <x-sortable-column field="etat_formation_id" modelname="realisationFormation" label="{{ ucfirst(__('PkgAutoformation::etatFormation.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('realisationFormation-table-tbody')
            @foreach ($realisationFormations_data as $realisationFormation)
                <tr id="realisationFormation-row-{{$realisationFormation->id}}">
                    <td>
                     <span @if(strlen($realisationFormation->date_debut) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $realisationFormation->date_debut }}" 
                        @endif>
                        {{ Str::limit($realisationFormation->date_debut, 40) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($realisationFormation->date_fin) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $realisationFormation->date_fin }}" 
                        @endif>
                        {{ Str::limit($realisationFormation->date_fin, 40) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($realisationFormation->formation) > 50) 
                            data-toggle="tooltip" 
                            title="{{ $realisationFormation->formation }}" 
                        @endif>
                        {{ Str::limit($realisationFormation->formation, 50) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($realisationFormation->apprenant) > 50) 
                            data-toggle="tooltip" 
                            title="{{ $realisationFormation->apprenant }}" 
                        @endif>
                        {{ Str::limit($realisationFormation->apprenant, 50) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($realisationFormation->etatFormation) > 50) 
                            data-toggle="tooltip" 
                            title="{{ $realisationFormation->etatFormation }}" 
                        @endif>
                        {{ Str::limit($realisationFormation->etatFormation, 50) }}
                    </span>
                    </td>
                    <td class="text-right">

                        @can('show-realisationFormation')
                        @can('view', $realisationFormation)
                            <a href="{{ route('realisationFormations.show', ['realisationFormation' => $realisationFormation->id]) }}" data-id="{{$realisationFormation->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-realisationFormation')
                        @can('update', $realisationFormation)
                            <a href="{{ route('realisationFormations.edit', ['realisationFormation' => $realisationFormation->id]) }}" data-id="{{$realisationFormation->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-realisationFormation')
                        @can('delete', $realisationFormation)
                            <form class="context-state" action="{{ route('realisationFormations.destroy',['realisationFormation' => $realisationFormation->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$realisationFormation->id}}">
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
    @section('realisationFormation-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $realisationFormations_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>