{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('sousGroupe-table')
<div class="card-body p-0 crud-card-body" id="sousGroupes-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $sousGroupes_permissions['edit-sousGroupe'] || $sousGroupes_permissions['destroy-sousGroupe'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="41"  field="nom" modelname="sousGroupe" label="{!!ucfirst(__('PkgApprenants::sousGroupe.nom'))!!}" />
                <x-sortable-column :sortable="true" width="41" field="groupe_id" modelname="sousGroupe" label="{!!ucfirst(__('PkgApprenants::groupe.singular'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('sousGroupe-table-tbody')
            @foreach ($sousGroupes_data as $sousGroupe)
                @php
                    $isEditable = $sousGroupes_permissions['edit-sousGroupe'] && $sousGroupes_permissionsByItem['update'][$sousGroupe->id];
                @endphp
                <tr id="sousGroupe-row-{{$sousGroupe->id}}" data-id="{{$sousGroupe->id}}">
                    <x-checkbox-row :item="$sousGroupe" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 41%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$sousGroupe->id}}" data-field="nom">
                        {{ $sousGroupe->nom }}

                    </td>
                    <td style="max-width: 41%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$sousGroupe->id}}" data-field="groupe_id">
                        {{  $sousGroupe->groupe }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($sousGroupes_permissions['edit-sousGroupe'])
                        <x-action-button :entity="$sousGroupe" actionName="edit">
                        @if($sousGroupes_permissionsByItem['update'][$sousGroupe->id])
                            <a href="{{ route('sousGroupes.edit', ['sousGroupe' => $sousGroupe->id]) }}" data-id="{{$sousGroupe->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($sousGroupes_permissions['show-sousGroupe'])
                        <x-action-button :entity="$sousGroupe" actionName="show">
                        @if($sousGroupes_permissionsByItem['view'][$sousGroupe->id])
                            <a href="{{ route('sousGroupes.show', ['sousGroupe' => $sousGroupe->id]) }}" data-id="{{$sousGroupe->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$sousGroupe" actionName="delete">
                        @if($sousGroupes_permissions['destroy-sousGroupe'])
                        @if($sousGroupes_permissionsByItem['delete'][$sousGroupe->id])
                            <form class="context-state" action="{{ route('sousGroupes.destroy',['sousGroupe' => $sousGroupe->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$sousGroupe->id}}">
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
    @section('sousGroupe-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $sousGroupes_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>