{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('commentaireRealisationTache-table')
<div class="card-body p-0 crud-card-body" id="commentaireRealisationTaches-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-commentaireRealisationTache') || Auth::user()->can('destroy-commentaireRealisationTache');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="20.5"  field="commentaire" modelname="commentaireRealisationTache" label="{{ucfirst(__('PkgGestionTaches::commentaireRealisationTache.commentaire'))}}" />
                <x-sortable-column :sortable="true" width="20.5" field="realisation_tache_id" modelname="commentaireRealisationTache" label="{{ucfirst(__('PkgGestionTaches::realisationTache.singular'))}}" />
                <x-sortable-column :sortable="true" width="20.5" field="formateur_id" modelname="commentaireRealisationTache" label="{{ucfirst(__('PkgFormation::formateur.singular'))}}" />
                <x-sortable-column :sortable="true" width="20.5" field="apprenant_id" modelname="commentaireRealisationTache" label="{{ucfirst(__('PkgApprenants::apprenant.singular'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('commentaireRealisationTache-table-tbody')
            @foreach ($commentaireRealisationTaches_data as $commentaireRealisationTache)
                @php
                    $isEditable = Auth::user()->can('edit-commentaireRealisationTache') && Auth::user()->can('update', $commentaireRealisationTache);
                @endphp
                <tr id="commentaireRealisationTache-row-{{$commentaireRealisationTache->id}}" data-id="{{$commentaireRealisationTache->id}}">
                    <x-checkbox-row :item="$commentaireRealisationTache" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$commentaireRealisationTache->id}}" data-field="commentaire"  data-toggle="tooltip" title="{{ $commentaireRealisationTache->commentaire }}" >
                    <x-field :entity="$commentaireRealisationTache" field="commentaire">
                        {!! \App\Helpers\TextHelper::formatHtmlWithLineBreaks($commentaireRealisationTache->commentaire, 30) !!}
                    </x-field>
                    </td>
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$commentaireRealisationTache->id}}" data-field="realisation_tache_id"  data-toggle="tooltip" title="{{ $commentaireRealisationTache->realisationTache }}" >
                    <x-field :entity="$commentaireRealisationTache" field="realisationTache">
                       
                         {{  $commentaireRealisationTache->realisationTache }}
                    </x-field>
                    </td>
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$commentaireRealisationTache->id}}" data-field="formateur_id"  data-toggle="tooltip" title="{{ $commentaireRealisationTache->formateur }}" >
                    <x-field :entity="$commentaireRealisationTache" field="formateur">
                       
                         {{  $commentaireRealisationTache->formateur }}
                    </x-field>
                    </td>
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$commentaireRealisationTache->id}}" data-field="apprenant_id"  data-toggle="tooltip" title="{{ $commentaireRealisationTache->apprenant }}" >
                    <x-field :entity="$commentaireRealisationTache" field="apprenant">
                       
                         {{  $commentaireRealisationTache->apprenant }}
                    </x-field>
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @can('edit-commentaireRealisationTache')
                        <x-action-button :entity="$commentaireRealisationTache" actionName="edit">
                        @can('update', $commentaireRealisationTache)
                            <a href="{{ route('commentaireRealisationTaches.edit', ['commentaireRealisationTache' => $commentaireRealisationTache->id]) }}" data-id="{{$commentaireRealisationTache->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan
                        @can('show-commentaireRealisationTache')
                        <x-action-button :entity="$commentaireRealisationTache" actionName="show">
                        @can('view', $commentaireRealisationTache)
                            <a href="{{ route('commentaireRealisationTaches.show', ['commentaireRealisationTache' => $commentaireRealisationTache->id]) }}" data-id="{{$commentaireRealisationTache->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan

                        <x-action-button :entity="$commentaireRealisationTache" actionName="delete">
                        @can('destroy-commentaireRealisationTache')
                        @can('delete', $commentaireRealisationTache)
                            <form class="context-state" action="{{ route('commentaireRealisationTaches.destroy',['commentaireRealisationTache' => $commentaireRealisationTache->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$commentaireRealisationTache->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endcan
                        @endcan
                        </x-action-button>
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