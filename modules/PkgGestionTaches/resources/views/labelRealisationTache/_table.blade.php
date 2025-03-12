{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('labelRealisationTache-table')
<div class="card-body table-responsive p-0 crud-card-body" id="labelRealisationTaches-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="nom" modelname="labelRealisationTache" label="{{ ucfirst(__('PkgGestionTaches::labelRealisationTache.nom')) }}" />
                <x-sortable-column field="formateur_id" modelname="labelRealisationTache" label="{{ ucfirst(__('PkgFormation::formateur.singular')) }}" />
                <x-sortable-column field="sys_color_id" modelname="labelRealisationTache" label="{{ ucfirst(__('Core::sysColor.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('labelRealisationTache-table-tbody')
            @foreach ($labelRealisationTaches_data as $labelRealisationTache)
                <tr id="labelRealisationTache-row-{{$labelRealisationTache->id}}">
                    <td>
                     <span @if(strlen($labelRealisationTache->nom) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $labelRealisationTache->nom }}" 
                        @endif>
                        {{ Str::limit($labelRealisationTache->nom, 40) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($labelRealisationTache->formateur) > 50) 
                            data-toggle="tooltip" 
                            title="{{ $labelRealisationTache->formateur }}" 
                        @endif>
                        {{ Str::limit($labelRealisationTache->formateur, 50) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($labelRealisationTache->sysColor) > 50) 
                            data-toggle="tooltip" 
                            title="{{ $labelRealisationTache->sysColor }}" 
                        @endif>
                        {{ Str::limit($labelRealisationTache->sysColor, 50) }}
                    </span>
                    </td>
                    <td class="text-right">

                        @can('show-labelRealisationTache')
                        @can('view', $labelRealisationTache)
                            <a href="{{ route('labelRealisationTaches.show', ['labelRealisationTache' => $labelRealisationTache->id]) }}" data-id="{{$labelRealisationTache->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-labelRealisationTache')
                        @can('update', $labelRealisationTache)
                            <a href="{{ route('labelRealisationTaches.edit', ['labelRealisationTache' => $labelRealisationTache->id]) }}" data-id="{{$labelRealisationTache->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-labelRealisationTache')
                        @can('delete', $labelRealisationTache)
                            <form class="context-state" action="{{ route('labelRealisationTaches.destroy',['labelRealisationTache' => $labelRealisationTache->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$labelRealisationTache->id}}">
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
    @section('labelRealisationTache-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $labelRealisationTaches_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>