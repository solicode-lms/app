{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('commentaireRealisationTache-table')
<div class="card-body p-0 crud-card-body" id="commentaireRealisationTaches-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $commentaireRealisationTaches_permissions['edit-commentaireRealisationTache'] || $commentaireRealisationTaches_permissions['destroy-commentaireRealisationTache'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="20.5"  field="commentaire" modelname="commentaireRealisationTache" label="{{ucfirst(__('PkgRealisationTache::commentaireRealisationTache.commentaire'))}}" />
                <x-sortable-column :sortable="true" width="20.5" field="realisation_tache_id" modelname="commentaireRealisationTache" label="{{ucfirst(__('PkgRealisationTache::realisationTache.singular'))}}" />
                <x-sortable-column :sortable="true" width="20.5" field="formateur_id" modelname="commentaireRealisationTache" label="{{ucfirst(__('PkgFormation::formateur.singular'))}}" />
                <x-sortable-column :sortable="true" width="20.5" field="apprenant_id" modelname="commentaireRealisationTache" label="{{ucfirst(__('PkgApprenants::apprenant.singular'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('commentaireRealisationTache-table-tbody')
            @foreach ($commentaireRealisationTaches_data as $commentaireRealisationTache)
                @php
                    $isEditable = $commentaireRealisationTaches_permissions['edit-commentaireRealisationTache'] && $commentaireRealisationTaches_permissionsByItem['update'][$commentaireRealisationTache->id];
                @endphp
                <tr id="commentaireRealisationTache-row-{{$commentaireRealisationTache->id}}" data-id="{{$commentaireRealisationTache->id}}">
                    <x-checkbox-row :item="$commentaireRealisationTache" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$commentaireRealisationTache->id}}" data-field="commentaire"  data-toggle="tooltip" title="{{ $commentaireRealisationTache->commentaire }}" >
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$commentaireRealisationTache->id}}" data-field="commentaire"  data-toggle="tooltip" title="{{ $commentaireRealisationTache->commentaire }}" >
                        {!! \App\Helpers\TextHelper::formatHtmlWithLineBreaks($commentaireRealisationTache->commentaire, 30) !!}
                    </td>

                    </td>
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$commentaireRealisationTache->id}}" data-field="realisation_tache_id"  data-toggle="tooltip" title="{{ $commentaireRealisationTache->realisationTache }}" >
                        {{  $commentaireRealisationTache->realisationTache }}

                    </td>
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$commentaireRealisationTache->id}}" data-field="formateur_id"  data-toggle="tooltip" title="{{ $commentaireRealisationTache->formateur }}" >
                        {{  $commentaireRealisationTache->formateur }}

                    </td>
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$commentaireRealisationTache->id}}" data-field="apprenant_id"  data-toggle="tooltip" title="{{ $commentaireRealisationTache->apprenant }}" >
                        {{  $commentaireRealisationTache->apprenant }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($commentaireRealisationTaches_permissions['edit-commentaireRealisationTache'])
                        <x-action-button :entity="$commentaireRealisationTache" actionName="edit">
                        @if($commentaireRealisationTaches_permissionsByItem['update'][$commentaireRealisationTache->id])
                            <a href="{{ route('commentaireRealisationTaches.edit', ['commentaireRealisationTache' => $commentaireRealisationTache->id]) }}" data-id="{{$commentaireRealisationTache->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($commentaireRealisationTaches_permissions['show-commentaireRealisationTache'])
                        <x-action-button :entity="$commentaireRealisationTache" actionName="show">
                        @if($commentaireRealisationTaches_permissionsByItem['view'][$commentaireRealisationTache->id])
                            <a href="{{ route('commentaireRealisationTaches.show', ['commentaireRealisationTache' => $commentaireRealisationTache->id]) }}" data-id="{{$commentaireRealisationTache->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$commentaireRealisationTache" actionName="delete">
                        @if($commentaireRealisationTaches_permissions['destroy-commentaireRealisationTache'])
                        @if($commentaireRealisationTaches_permissionsByItem['delete'][$commentaireRealisationTache->id])
                            <form class="context-state" action="{{ route('commentaireRealisationTaches.destroy',['commentaireRealisationTache' => $commentaireRealisationTache->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$commentaireRealisationTache->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                        @endif
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