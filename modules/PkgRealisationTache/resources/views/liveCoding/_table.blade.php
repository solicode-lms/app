{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('liveCoding-table')
<div class="card-body p-0 crud-card-body" id="liveCodings-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $liveCodings_permissions['edit-liveCoding'] || $liveCodings_permissions['destroy-liveCoding'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="41" field="realisation_tache_id" modelname="liveCoding" label="{!!ucfirst(__('PkgRealisationTache::realisationTache.singular'))!!}" />
                <x-sortable-column :sortable="false" width="41"  field="commentaire" modelname="liveCoding" label="{!!ucfirst(__('PkgRealisationTache::liveCoding.commentaire'))!!}" />
                <th class="text-center crud-actions-header">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('liveCoding-table-tbody')
            @foreach ($liveCodings_data as $liveCoding)
                @php
                    $isEditable = $liveCodings_permissions['edit-liveCoding'] && $liveCodings_permissionsByItem['update'][$liveCoding->id];
                @endphp
                <tr id="liveCoding-row-{{$liveCoding->id}}" data-id="{{$liveCoding->id}}">
                    <x-checkbox-row :item="$liveCoding" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 41%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$liveCoding->id}}" data-field="realisation_tache_id">
                        {{  $liveCoding->realisationTache }}

                    </td>
                    <td style="max-width: 41%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$liveCoding->id}}" data-field="commentaire">
                  
                        {!! \App\Helpers\TextHelper::formatHtmlWithLineBreaks($liveCoding->commentaire, 30) !!}
                   

                    </td>
                    <td class="text-right wrappable crud-actions-cell" style="max-width: 15%;">
                        <div class="crud-actions-wrapper">
                        <div class="actions-secondary-group">

                        </div>

                        <div class="actions-main-group">
                        @if($liveCodings_permissions['edit-liveCoding'])
                        <x-action-button :entity="$liveCoding" actionName="edit">
                        @if($liveCodings_permissionsByItem['update'][$liveCoding->id])
                            <a href="{{ route('liveCodings.edit', ['liveCoding' => $liveCoding->id]) }}" data-id="{{$liveCoding->id}}" class="btn btn-sm btn-default context-state editEntity btn-action-main">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($liveCodings_permissions['show-liveCoding'])
                        <x-action-button :entity="$liveCoding" actionName="show">
                        @if($liveCodings_permissionsByItem['view'][$liveCoding->id])
                            <a href="{{ route('liveCodings.show', ['liveCoding' => $liveCoding->id]) }}" data-id="{{$liveCoding->id}}" class="btn btn-default btn-sm context-state showEntity btn-action-main">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$liveCoding" actionName="delete">
                        @if($liveCodings_permissions['destroy-liveCoding'])
                        @if($liveCodings_permissionsByItem['delete'][$liveCoding->id])
                            <form class="context-state" action="{{ route('liveCodings.destroy',['liveCoding' => $liveCoding->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger d-none d-lg-inline deleteEntity btn-action-delete" data-id="{{$liveCoding->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                        @endif
                        </x-action-button>
                        </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            @show
        </tbody>
    </table>
</div>
@show

<div class="card-footer">
    @section('liveCoding-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $liveCodings_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>