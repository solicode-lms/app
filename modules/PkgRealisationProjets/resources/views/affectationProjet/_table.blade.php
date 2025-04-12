{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('affectationProjet-table')
<div class="card-body table-responsive p-0 crud-card-body" id="affectationProjets-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                <x-sortable-column width="21.25" field="projet_id" modelname="affectationProjet" label="{{ ucfirst(__('PkgCreationProjet::projet.singular')) }}" />
                <x-sortable-column width="21.25" field="groupe_id" modelname="affectationProjet" label="{{ ucfirst(__('PkgApprenants::groupe.singular')) }}" />
                <x-sortable-column width="21.25"  field="date_debut" modelname="affectationProjet" label="{{ ucfirst(__('PkgRealisationProjets::affectationProjet.date_debut')) }}" />
                <x-sortable-column width="21.25"  field="date_fin" modelname="affectationProjet" label="{{ ucfirst(__('PkgRealisationProjets::affectationProjet.date_fin')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('affectationProjet-table-tbody')
            @foreach ($affectationProjets_data as $affectationProjet)
                <tr id="affectationProjet-row-{{$affectationProjet->id}}">
                    <td style="max-width: 21.25%;" class="text-truncate" data-toggle="tooltip" title="{{ $affectationProjet->projet }}" >
                    <x-field :data="$affectationProjet" field="projet">
                       
                         {{  $affectationProjet->projet }}
                    </x-field>
                    </td>
                    <td style="max-width: 21.25%;" class="text-truncate" data-toggle="tooltip" title="{{ $affectationProjet->groupe }}" >
                    <x-field :data="$affectationProjet" field="groupe">
                       
                         {{  $affectationProjet->groupe }}
                    </x-field>
                    </td>
                    <td style="max-width: 21.25%;" class="text-truncate" data-toggle="tooltip" title="{{ $affectationProjet->date_debut }}" >
                    <x-field :data="$affectationProjet" field="date_debut">
                        {{ $affectationProjet->date_debut }}
                    </x-field>
                    </td>
                    <td style="max-width: 21.25%;" class="text-truncate" data-toggle="tooltip" title="{{ $affectationProjet->date_fin }}" >
                    <x-field :data="$affectationProjet" field="date_fin">
                        {{ $affectationProjet->date_fin }}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-affectationProjet')
                        @can('update', $affectationProjet)
                            <a href="{{ route('affectationProjets.edit', ['affectationProjet' => $affectationProjet->id]) }}" data-id="{{$affectationProjet->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-affectationProjet')
                        @can('view', $affectationProjet)
                            <a href="{{ route('affectationProjets.show', ['affectationProjet' => $affectationProjet->id]) }}" data-id="{{$affectationProjet->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-affectationProjet')
                        @can('delete', $affectationProjet)
                            <form class="context-state" action="{{ route('affectationProjets.destroy',['affectationProjet' => $affectationProjet->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$affectationProjet->id}}">
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
    @section('affectationProjet-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $affectationProjets_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>