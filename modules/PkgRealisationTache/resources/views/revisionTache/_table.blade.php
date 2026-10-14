{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('revisionTache-table')
<div class="card-body p-0 crud-card-body" id="revisionTaches-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $revisionTaches_permissions['edit-revisionTache'] || $revisionTaches_permissions['destroy-revisionTache'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="41" field="realisation_tache_id" modelname="revisionTache" label="{!!ucfirst(__('PkgRealisationTache::realisationTache.singular'))!!}" />
                <x-sortable-column :sortable="true" width="41"  field="date_validation" modelname="revisionTache" label="{!!ucfirst(__('PkgRealisationTache::revisionTache.date_validation'))!!}" />
                <th class="text-center crud-actions-header">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('revisionTache-table-tbody')
            @foreach ($revisionTaches_data as $revisionTache)
                @php
                    $isEditable = $revisionTaches_permissions['edit-revisionTache'] && $revisionTaches_permissionsByItem['update'][$revisionTache->id];
                @endphp
                <tr id="revisionTache-row-{{$revisionTache->id}}" data-id="{{$revisionTache->id}}">
                    <x-checkbox-row :item="$revisionTache" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 41%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$revisionTache->id}}" data-field="realisation_tache_id">
                        {{  $revisionTache->realisationTache }}

                    </td>
                    <td style="max-width: 41%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$revisionTache->id}}" data-field="date_validation">
                        <x-deadline-display :value="$revisionTache->date_validation" />
                    </td>
                    <td class="text-right wrappable crud-actions-cell" style="max-width: 15%;">
                        <div class="crud-actions-wrapper">
                        <div class="actions-secondary-group">

                        </div>

                        <div class="actions-main-group">
                        @if($revisionTaches_permissions['edit-revisionTache'])
                        <x-action-button :entity="$revisionTache" actionName="edit">
                        @if($revisionTaches_permissionsByItem['update'][$revisionTache->id])
                            <a href="{{ route('revisionTaches.edit', ['revisionTache' => $revisionTache->id]) }}" data-id="{{$revisionTache->id}}" class="btn btn-sm btn-default context-state editEntity btn-action-main">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($revisionTaches_permissions['show-revisionTache'])
                        <x-action-button :entity="$revisionTache" actionName="show">
                        @if($revisionTaches_permissionsByItem['view'][$revisionTache->id])
                            <a href="{{ route('revisionTaches.show', ['revisionTache' => $revisionTache->id]) }}" data-id="{{$revisionTache->id}}" class="btn btn-default btn-sm context-state showEntity btn-action-main">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$revisionTache" actionName="delete">
                        @if($revisionTaches_permissions['destroy-revisionTache'])
                        @if($revisionTaches_permissionsByItem['delete'][$revisionTache->id])
                            <form class="context-state" action="{{ route('revisionTaches.destroy',['revisionTache' => $revisionTache->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger d-none d-lg-inline deleteEntity btn-action-delete" data-id="{{$revisionTache->id}}">
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
    @section('revisionTache-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $revisionTaches_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>