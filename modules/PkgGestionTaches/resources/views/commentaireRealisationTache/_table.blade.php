{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('commentaireRealisationTache-table')
<div class="card-body table-responsive p-0 crud-card-body" id="commentaireRealisationTaches-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                <th style="width: 10px;">
                    <input type="checkbox" class="check-all-rows" />
                </th>
                <x-sortable-column width="20.5"  field="commentaire" modelname="commentaireRealisationTache" label="{{ ucfirst(__('PkgGestionTaches::commentaireRealisationTache.commentaire')) }}" />
                <x-sortable-column width="20.5" field="realisation_tache_id" modelname="commentaireRealisationTache" label="{{ ucfirst(__('PkgGestionTaches::realisationTache.singular')) }}" />
                <x-sortable-column width="20.5" field="formateur_id" modelname="commentaireRealisationTache" label="{{ ucfirst(__('PkgFormation::formateur.singular')) }}" />
                <x-sortable-column width="20.5" field="apprenant_id" modelname="commentaireRealisationTache" label="{{ ucfirst(__('PkgApprenants::apprenant.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('commentaireRealisationTache-table-tbody')
            @foreach ($commentaireRealisationTaches_data as $commentaireRealisationTache)
                <tr id="commentaireRealisationTache-row-{{$commentaireRealisationTache->id}}">
                    <td>
                        <input type="checkbox" class="check-row" value="{{ $commentaireRealisationTache->id }}" data-id="{{ $commentaireRealisationTache->id }}">
                    </td>
                    <td style="max-width: 20.5%;" class="text-truncate" data-toggle="tooltip" title="{{ $commentaireRealisationTache->commentaire }}" >
                    <x-field :entity="$commentaireRealisationTache" field="commentaire">
                        {!! \App\Helpers\TextHelper::formatHtmlWithLineBreaks($commentaireRealisationTache->commentaire, 30) !!}
                    </x-field>
                    </td>
                    <td style="max-width: 20.5%;" class="text-truncate" data-toggle="tooltip" title="{{ $commentaireRealisationTache->realisationTache }}" >
                    <x-field :entity="$commentaireRealisationTache" field="realisationTache">
                       
                         {{  $commentaireRealisationTache->realisationTache }}
                    </x-field>
                    </td>
                    <td style="max-width: 20.5%;" class="text-truncate" data-toggle="tooltip" title="{{ $commentaireRealisationTache->formateur }}" >
                    <x-field :entity="$commentaireRealisationTache" field="formateur">
                       
                         {{  $commentaireRealisationTache->formateur }}
                    </x-field>
                    </td>
                    <td style="max-width: 20.5%;" class="text-truncate" data-toggle="tooltip" title="{{ $commentaireRealisationTache->apprenant }}" >
                    <x-field :entity="$commentaireRealisationTache" field="apprenant">
                       
                         {{  $commentaireRealisationTache->apprenant }}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-commentaireRealisationTache')
                        @can('update', $commentaireRealisationTache)
                            <a href="{{ route('commentaireRealisationTaches.edit', ['commentaireRealisationTache' => $commentaireRealisationTache->id]) }}" data-id="{{$commentaireRealisationTache->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-commentaireRealisationTache')
                        @can('view', $commentaireRealisationTache)
                            <a href="{{ route('commentaireRealisationTaches.show', ['commentaireRealisationTache' => $commentaireRealisationTache->id]) }}" data-id="{{$commentaireRealisationTache->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-commentaireRealisationTache')
                        @can('delete', $commentaireRealisationTache)
                            <form class="context-state" action="{{ route('commentaireRealisationTaches.destroy',['commentaireRealisationTache' => $commentaireRealisationTache->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$commentaireRealisationTache->id}}">
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
    @section('commentaireRealisationTache-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $commentaireRealisationTaches_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>