{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('historiqueRealisationTache-table')
<div class="card-body p-0 crud-card-body" id="historiqueRealisationTaches-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $historiqueRealisationTaches_permissions['edit-historiqueRealisationTache'] || $historiqueRealisationTaches_permissions['destroy-historiqueRealisationTache'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="50"  field="changement" modelname="historiqueRealisationTache" label="{!!ucfirst(__('PkgRealisationTache::historiqueRealisationTache.changement'))!!}" />
                <x-sortable-column :sortable="true" width="15"  field="dateModification" modelname="historiqueRealisationTache" label="{!!ucfirst(__('PkgRealisationTache::historiqueRealisationTache.dateModification'))!!}" />
                <x-sortable-column :sortable="true" width="17" field="user_id" modelname="historiqueRealisationTache" label="{!!ucfirst(__('PkgAutorisation::user.singular'))!!}" />
                <th class="text-center crud-actions-header">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('historiqueRealisationTache-table-tbody')
            @foreach ($historiqueRealisationTaches_data as $historiqueRealisationTache)
                @php
                    $isEditable = $historiqueRealisationTaches_permissions['edit-historiqueRealisationTache'] && $historiqueRealisationTaches_permissionsByItem['update'][$historiqueRealisationTache->id];
                @endphp
                <tr id="historiqueRealisationTache-row-{{$historiqueRealisationTache->id}}" data-id="{{$historiqueRealisationTache->id}}">
                    <x-checkbox-row :item="$historiqueRealisationTache" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 50%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$historiqueRealisationTache->id}}" data-field="changement">
                  
                        {!! $historiqueRealisationTache->changement !!}
                 

                    </td>
                    <td style="max-width: 15%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$historiqueRealisationTache->id}}" data-field="dateModification" >
                        @include('PkgRealisationTache::historiqueRealisationTache.custom.fields.dateModification', ['entity' => $historiqueRealisationTache])
                    </td>
                    <td style="max-width: 17%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$historiqueRealisationTache->id}}" data-field="user_id">
                        {{  $historiqueRealisationTache->user }}

                    </td>
                    <td class="text-right wrappable crud-actions-cell" style="max-width: 15%;">
                        <div class="crud-actions-wrapper">
                        <div class="actions-secondary-group">

                        </div>

                        <div class="actions-main-group">
                        @if($historiqueRealisationTaches_permissions['edit-historiqueRealisationTache'])
                        <x-action-button :entity="$historiqueRealisationTache" actionName="edit">
                        @if($historiqueRealisationTaches_permissionsByItem['update'][$historiqueRealisationTache->id])
                            <a href="{{ route('historiqueRealisationTaches.edit', ['historiqueRealisationTache' => $historiqueRealisationTache->id]) }}" data-id="{{$historiqueRealisationTache->id}}" class="btn btn-sm btn-default context-state editEntity btn-action-main">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($historiqueRealisationTaches_permissions['show-historiqueRealisationTache'])
                        <x-action-button :entity="$historiqueRealisationTache" actionName="show">
                        @if($historiqueRealisationTaches_permissionsByItem['view'][$historiqueRealisationTache->id])
                            <a href="{{ route('historiqueRealisationTaches.show', ['historiqueRealisationTache' => $historiqueRealisationTache->id]) }}" data-id="{{$historiqueRealisationTache->id}}" class="btn btn-default btn-sm context-state showEntity btn-action-main">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$historiqueRealisationTache" actionName="delete">
                        @if($historiqueRealisationTaches_permissions['destroy-historiqueRealisationTache'])
                        @if($historiqueRealisationTaches_permissionsByItem['delete'][$historiqueRealisationTache->id])
                            <form class="context-state" action="{{ route('historiqueRealisationTaches.destroy',['historiqueRealisationTache' => $historiqueRealisationTache->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger d-none d-lg-inline deleteEntity btn-action-delete" data-id="{{$historiqueRealisationTache->id}}">
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
    @section('historiqueRealisationTache-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $historiqueRealisationTaches_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>