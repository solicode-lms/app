{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('apprenantKonosy-table')
<div class="card-body p-0 crud-card-body" id="apprenantKonosies-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $apprenantKonosies_permissions['edit-apprenantKonosy'] || $apprenantKonosies_permissions['destroy-apprenantKonosy'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="82"  field="Nom" modelname="apprenantKonosy" label="{!!ucfirst(__('PkgApprenants::apprenantKonosy.Nom'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('apprenantKonosy-table-tbody')
            @foreach ($apprenantKonosies_data as $apprenantKonosy)
                @php
                    $isEditable = $apprenantKonosies_permissions['edit-apprenantKonosy'] && $apprenantKonosies_permissionsByItem['update'][$apprenantKonosy->id];
                @endphp
                <tr id="apprenantKonosy-row-{{$apprenantKonosy->id}}" data-id="{{$apprenantKonosy->id}}">
                    <x-checkbox-row :item="$apprenantKonosy" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 82%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$apprenantKonosy->id}}" data-field="Nom">
                        {{ $apprenantKonosy->Nom }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($apprenantKonosies_permissions['edit-apprenantKonosy'])
                        <x-action-button :entity="$apprenantKonosy" actionName="edit">
                        @if($apprenantKonosies_permissionsByItem['update'][$apprenantKonosy->id])
                            <a href="{{ route('apprenantKonosies.edit', ['apprenantKonosy' => $apprenantKonosy->id]) }}" data-id="{{$apprenantKonosy->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($apprenantKonosies_permissions['show-apprenantKonosy'])
                        <x-action-button :entity="$apprenantKonosy" actionName="show">
                        @if($apprenantKonosies_permissionsByItem['view'][$apprenantKonosy->id])
                            <a href="{{ route('apprenantKonosies.show', ['apprenantKonosy' => $apprenantKonosy->id]) }}" data-id="{{$apprenantKonosy->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$apprenantKonosy" actionName="delete">
                        @if($apprenantKonosies_permissions['destroy-apprenantKonosy'])
                        @if($apprenantKonosies_permissionsByItem['delete'][$apprenantKonosy->id])
                            <form class="context-state" action="{{ route('apprenantKonosies.destroy',['apprenantKonosy' => $apprenantKonosy->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$apprenantKonosy->id}}">
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
    @section('apprenantKonosy-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $apprenantKonosies_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>