{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('tache-table')
<div class="card-body table-responsive p-0 crud-card-body" id="taches-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="titre" modelname="tache" label="{{ ucfirst(__('PkgGestionTaches::tache.titre')) }}" />
                <x-sortable-column field="projet_id" modelname="tache" label="{{ ucfirst(__('PkgCreationProjet::projet.singular')) }}" />
                <x-sortable-column field="priorite_tache_id" modelname="tache" label="{{ ucfirst(__('PkgGestionTaches::prioriteTache.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('tache-table-tbody')
            @foreach ($taches_data as $tache)
                <tr id="tache-row-{{$tache->id}}">
                    <td>
                     <span @if(strlen($tache->titre) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $tache->titre }}" 
                        @endif>
                        {{ Str::limit($tache->titre, 40) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($tache->projet) > 50) 
                            data-toggle="tooltip" 
                            title="{{ $tache->projet }}" 
                        @endif>
                        {{ Str::limit($tache->projet, 50) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($tache->prioriteTache) > 50) 
                            data-toggle="tooltip" 
                            title="{{ $tache->prioriteTache }}" 
                        @endif>
                        {{ Str::limit($tache->prioriteTache, 50) }}
                    </span>
                    </td>
                    <td class="text-right">

                        @can('show-tache')
                        @can('view', $tache)
                            <a href="{{ route('taches.show', ['tache' => $tache->id]) }}" data-id="{{$tache->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-tache')
                        @can('update', $tache)
                            <a href="{{ route('taches.edit', ['tache' => $tache->id]) }}" data-id="{{$tache->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-tache')
                        @can('delete', $tache)
                            <form class="context-state" action="{{ route('taches.destroy',['tache' => $tache->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$tache->id}}">
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
    @section('tache-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $taches_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>