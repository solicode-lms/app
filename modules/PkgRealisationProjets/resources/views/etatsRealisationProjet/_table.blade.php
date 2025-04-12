{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatsRealisationProjet-table')
<div class="card-body table-responsive p-0 crud-card-body" id="etatsRealisationProjets-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                <x-sortable-column width="42.5" field="formateur_id" modelname="etatsRealisationProjet" label="{{ ucfirst(__('PkgFormation::formateur.singular')) }}" />
                <x-sortable-column width="42.5"  field="titre" modelname="etatsRealisationProjet" label="{{ ucfirst(__('PkgRealisationProjets::etatsRealisationProjet.titre')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('etatsRealisationProjet-table-tbody')
            @foreach ($etatsRealisationProjets_data as $etatsRealisationProjet)
                <tr id="etatsRealisationProjet-row-{{$etatsRealisationProjet->id}}">
                    <td style="max-width: 42.5%;" class="text-truncate" data-toggle="tooltip" title="{{ $etatsRealisationProjet->formateur }}" >
                    <x-field :data="$etatsRealisationProjet" field="formateur">
                       
                         {{  $etatsRealisationProjet->formateur }}
                    </x-field>
                    </td>
                    <td style="max-width: 42.5%;" class="text-truncate" data-toggle="tooltip" title="{{ $etatsRealisationProjet->titre }}" >
                    <x-field :data="$etatsRealisationProjet" field="titre">
                        {{ $etatsRealisationProjet->titre }}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-etatsRealisationProjet')
                        @can('update', $etatsRealisationProjet)
                            <a href="{{ route('etatsRealisationProjets.edit', ['etatsRealisationProjet' => $etatsRealisationProjet->id]) }}" data-id="{{$etatsRealisationProjet->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-etatsRealisationProjet')
                        @can('view', $etatsRealisationProjet)
                            <a href="{{ route('etatsRealisationProjets.show', ['etatsRealisationProjet' => $etatsRealisationProjet->id]) }}" data-id="{{$etatsRealisationProjet->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-etatsRealisationProjet')
                        @can('delete', $etatsRealisationProjet)
                            <form class="context-state" action="{{ route('etatsRealisationProjets.destroy',['etatsRealisationProjet' => $etatsRealisationProjet->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$etatsRealisationProjet->id}}">
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
    @section('etatsRealisationProjet-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $etatsRealisationProjets_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>