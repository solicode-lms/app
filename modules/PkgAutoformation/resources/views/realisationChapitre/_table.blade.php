{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationChapitre-table')
<div class="card-body table-responsive p-0 crud-card-body" id="realisationChapitres-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                <x-sortable-column width="17"  field="date_debut" modelname="realisationChapitre" label="{{ ucfirst(__('PkgAutoformation::realisationChapitre.date_debut')) }}" />
                <x-sortable-column width="17"  field="date_fin" modelname="realisationChapitre" label="{{ ucfirst(__('PkgAutoformation::realisationChapitre.date_fin')) }}" />
                <x-sortable-column width="17" field="chapitre_id" modelname="realisationChapitre" label="{{ ucfirst(__('PkgAutoformation::chapitre.singular')) }}" />
                <x-sortable-column width="17" field="realisation_formation_id" modelname="realisationChapitre" label="{{ ucfirst(__('PkgAutoformation::realisationFormation.singular')) }}" />
                <x-sortable-column width="17" field="etat_chapitre_id" modelname="realisationChapitre" label="{{ ucfirst(__('PkgAutoformation::etatChapitre.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('realisationChapitre-table-tbody')
            @foreach ($realisationChapitres_data as $realisationChapitre)
                <tr id="realisationChapitre-row-{{$realisationChapitre->id}}">
                    <td style="max-width: 17%;" class="text-truncate" data-toggle="tooltip" title="{{ $realisationChapitre->date_debut }}" >
                    <x-field :entity="$realisationChapitre" field="date_debut">
                        {{ $realisationChapitre->date_debut }}
                    </x-field>
                    </td>
                    <td style="max-width: 17%;" class="text-truncate" data-toggle="tooltip" title="{{ $realisationChapitre->date_fin }}" >
                    <x-field :entity="$realisationChapitre" field="date_fin">
                        {{ $realisationChapitre->date_fin }}
                    </x-field>
                    </td>
                    <td style="max-width: 17%;" class="text-truncate" data-toggle="tooltip" title="{{ $realisationChapitre->chapitre }}" >
                    <x-field :entity="$realisationChapitre" field="chapitre">
                       
                         {{  $realisationChapitre->chapitre }}
                    </x-field>
                    </td>
                    <td style="max-width: 17%;" class="text-truncate" data-toggle="tooltip" title="{{ $realisationChapitre->realisationFormation }}" >
                    <x-field :entity="$realisationChapitre" field="realisationFormation">
                       
                         {{  $realisationChapitre->realisationFormation }}
                    </x-field>
                    </td>
                    <td style="max-width: 17%;" class="text-truncate" data-toggle="tooltip" title="{{ $realisationChapitre->etatChapitre }}" >
                    <x-field :entity="$realisationChapitre" field="etatChapitre">
                        @if(!empty($realisationChapitre->etatChapitre))
                        <x-badge 
                        :text="$realisationChapitre->etatChapitre" 
                        :background="$realisationChapitre->etatChapitre->sysColor->hex ?? '#6c757d'" 
                        />
                        @endif
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-realisationChapitre')
                        @can('update', $realisationChapitre)
                            <a href="{{ route('realisationChapitres.edit', ['realisationChapitre' => $realisationChapitre->id]) }}" data-id="{{$realisationChapitre->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-realisationChapitre')
                        @can('view', $realisationChapitre)
                            <a href="{{ route('realisationChapitres.show', ['realisationChapitre' => $realisationChapitre->id]) }}" data-id="{{$realisationChapitre->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
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