{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('livrablesRealisation-table')
<div class="card-body table-responsive p-0 crud-card-body" id="livrablesRealisations-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="livrable_id" modelname="livrablesRealisation" label="{{ ucfirst(__('PkgCreationProjet::livrable.singular')) }}" />
                <x-sortable-column field="lien" modelname="livrablesRealisation" label="{{ ucfirst(__('PkgRealisationProjets::livrablesRealisation.lien')) }}" />
                <x-sortable-column field="titre" modelname="livrablesRealisation" label="{{ ucfirst(__('PkgRealisationProjets::livrablesRealisation.titre')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('livrablesRealisation-table-tbody')
            @foreach ($livrablesRealisations_data as $livrablesRealisation)
                <tr id="livrablesRealisation-row-{{$livrablesRealisation->id}}">
                    <td>@limit($livrablesRealisation->livrable, 50)</td>
                    <td>@limit($livrablesRealisation->lien, 50)</td>
                    <td>@limit($livrablesRealisation->titre, 50)</td>
                    <td class="text-right">

                        @can('show-livrablesRealisation')
                        @can('view', $livrablesRealisation)
                            <a href="{{ route('livrablesRealisations.show', ['livrablesRealisation' => $livrablesRealisation->id]) }}" data-id="{{$livrablesRealisation->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-livrablesRealisation')
                        @can('update', $livrablesRealisation)
                            <a href="{{ route('livrablesRealisations.edit', ['livrablesRealisation' => $livrablesRealisation->id]) }}" data-id="{{$livrablesRealisation->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-livrablesRealisation')
                        @can('delete', $livrablesRealisation)
                            <form class="context-state" action="{{ route('livrablesRealisations.destroy',['livrablesRealisation' => $livrablesRealisation->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$livrablesRealisation->id}}">
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
    @section('livrablesRealisation-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $livrablesRealisations_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>