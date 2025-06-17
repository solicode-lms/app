{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('dependanceTache-table')
<div class="card-body p-0 crud-card-body" id="dependanceTaches-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $dependanceTaches_permissions['edit-dependanceTache'] || $dependanceTaches_permissions['destroy-dependanceTache'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="27.333333333333332" field="tache_id" modelname="dependanceTache" label="{{ucfirst(__('PkgGestionTaches::tache.singular'))}}" />
                <x-sortable-column :sortable="true" width="27.333333333333332" field="type_dependance_tache_id" modelname="dependanceTache" label="{{ucfirst(__('PkgGestionTaches::typeDependanceTache.singular'))}}" />
                <x-sortable-column :sortable="true" width="27.333333333333332" field="tache_cible_id" modelname="dependanceTache" label="{{ucfirst(__('PkgGestionTaches::tache.singular'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('dependanceTache-table-tbody')
            @foreach ($dependanceTaches_data as $dependanceTache)
                @php
                    $isEditable = $dependanceTaches_permissions['edit-dependanceTache'] && $dependanceTaches_permissionsByItem['update'][$dependanceTache->id];
                @endphp
                <tr id="dependanceTache-row-{{$dependanceTache->id}}" data-id="{{$dependanceTache->id}}">
                    <x-checkbox-row :item="$dependanceTache" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$dependanceTache->id}}" data-field="tache_id"  data-toggle="tooltip" title="{{ $dependanceTache->tache }}" >
                        {{  $dependanceTache->tache }}

                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$dependanceTache->id}}" data-field="type_dependance_tache_id"  data-toggle="tooltip" title="{{ $dependanceTache->typeDependanceTache }}" >
                        {{  $dependanceTache->typeDependanceTache }}

                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$dependanceTache->id}}" data-field="tache_cible_id"  data-toggle="tooltip" title="{{ $dependanceTache->tacheCible }}" >
                        {{  $dependanceTache->tacheCible }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($dependanceTaches_permissions['edit-dependanceTache'])
                        <x-action-button :entity="$dependanceTache" actionName="edit">
                        @if($dependanceTaches_permissionsByItem['update'][$dependanceTache->id])
                            <a href="{{ route('dependanceTaches.edit', ['dependanceTache' => $dependanceTache->id]) }}" data-id="{{$dependanceTache->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($dependanceTaches_permissions['show-dependanceTache'])
                        <x-action-button :entity="$dependanceTache" actionName="show">
                        @if($dependanceTaches_permissionsByItem['view'][$dependanceTache->id])
                            <a href="{{ route('dependanceTaches.show', ['dependanceTache' => $dependanceTache->id]) }}" data-id="{{$dependanceTache->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$dependanceTache" actionName="delete">
                        @if($dependanceTaches_permissions['destroy-dependanceTache'])
                        @if($dependanceTaches_permissionsByItem['delete'][$dependanceTache->id])
                            <form class="context-state" action="{{ route('dependanceTaches.destroy',['dependanceTache' => $dependanceTache->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$dependanceTache->id}}">
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
    @section('dependanceTache-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $dependanceTaches_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>