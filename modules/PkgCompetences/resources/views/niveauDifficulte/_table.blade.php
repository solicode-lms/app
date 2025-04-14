{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('niveauDifficulte-table')
<div class="card-body table-responsive p-0 crud-card-body" id="niveauDifficultes-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                <x-sortable-column width="42.5"  field="nom" modelname="niveauDifficulte" label="{{ ucfirst(__('PkgCompetences::niveauDifficulte.nom')) }}" />
                <x-sortable-column width="42.5" field="formateur_id" modelname="niveauDifficulte" label="{{ ucfirst(__('PkgFormation::formateur.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('niveauDifficulte-table-tbody')
            @foreach ($niveauDifficultes_data as $niveauDifficulte)
                <tr id="niveauDifficulte-row-{{$niveauDifficulte->id}}">
                    <td style="max-width: 42.5%;" class="text-truncate" data-toggle="tooltip" title="{{ $niveauDifficulte->nom }}" >
                    <x-field :entity="$niveauDifficulte" field="nom">
                        {{ $niveauDifficulte->nom }}
                    </x-field>
                    </td>
                    <td style="max-width: 42.5%;" class="text-truncate" data-toggle="tooltip" title="{{ $niveauDifficulte->formateur }}" >
                    <x-field :entity="$niveauDifficulte" field="formateur">
                       
                         {{  $niveauDifficulte->formateur }}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-niveauDifficulte')
                        @can('update', $niveauDifficulte)
                            <a href="{{ route('niveauDifficultes.edit', ['niveauDifficulte' => $niveauDifficulte->id]) }}" data-id="{{$niveauDifficulte->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-niveauDifficulte')
                        @can('view', $niveauDifficulte)
                            <a href="{{ route('niveauDifficultes.show', ['niveauDifficulte' => $niveauDifficulte->id]) }}" data-id="{{$niveauDifficulte->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-niveauDifficulte')
                        @can('delete', $niveauDifficulte)
                            <form class="context-state" action="{{ route('niveauDifficultes.destroy',['niveauDifficulte' => $niveauDifficulte->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$niveauDifficulte->id}}">
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
    @section('niveauDifficulte-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $niveauDifficultes_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>