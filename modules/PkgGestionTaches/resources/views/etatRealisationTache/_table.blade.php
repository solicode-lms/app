{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatRealisationTache-table')
<div class="card-body table-responsive p-0 crud-card-body" id="etatRealisationTaches-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="nom" modelname="etatRealisationTache" label="{{ ucfirst(__('PkgGestionTaches::etatRealisationTache.nom')) }}" />
                <x-sortable-column field="formateur_id" modelname="etatRealisationTache" label="{{ ucfirst(__('PkgFormation::formateur.singular')) }}" />
                <x-sortable-column field="sys_color_id" modelname="etatRealisationTache" label="{{ ucfirst(__('Core::sysColor.singular')) }}" />
                <x-sortable-column field="workflow_tache_id" modelname="etatRealisationTache" label="{{ ucfirst(__('PkgGestionTaches::workflowTache.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('etatRealisationTache-table-tbody')
            @foreach ($etatRealisationTaches_data as $etatRealisationTache)
                <tr id="etatRealisationTache-row-{{$etatRealisationTache->id}}">
                    <td>
                     <span @if(strlen($etatRealisationTache->nom) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $etatRealisationTache->nom }}" 
                        @endif>
                        {{ Str::limit($etatRealisationTache->nom, 40) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($etatRealisationTache->formateur) > 50) 
                            data-toggle="tooltip" 
                            title="{{ $etatRealisationTache->formateur }}" 
                        @endif>
                        {{ Str::limit($etatRealisationTache->formateur, 50) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($etatRealisationTache->sysColor) > 50) 
                            data-toggle="tooltip" 
                            title="{{ $etatRealisationTache->sysColor }}" 
                        @endif>
                        {{ Str::limit($etatRealisationTache->sysColor, 50) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($etatRealisationTache->workflowTache) > 50) 
                            data-toggle="tooltip" 
                            title="{{ $etatRealisationTache->workflowTache }}" 
                        @endif>
                        {{ Str::limit($etatRealisationTache->workflowTache, 50) }}
                    </span>
                    </td>
                    <td class="text-right">

                        @can('show-etatRealisationTache')
                        @can('view', $etatRealisationTache)
                            <a href="{{ route('etatRealisationTaches.show', ['etatRealisationTache' => $etatRealisationTache->id]) }}" data-id="{{$etatRealisationTache->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-etatRealisationTache')
                        @can('update', $etatRealisationTache)
                            <a href="{{ route('etatRealisationTaches.edit', ['etatRealisationTache' => $etatRealisationTache->id]) }}" data-id="{{$etatRealisationTache->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-etatRealisationTache')
                        @can('delete', $etatRealisationTache)
                            <form class="context-state" action="{{ route('etatRealisationTaches.destroy',['etatRealisationTache' => $etatRealisationTache->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$etatRealisationTache->id}}">
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
    @section('etatRealisationTache-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $etatRealisationTaches_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>