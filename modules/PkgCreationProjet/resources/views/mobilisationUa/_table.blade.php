{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('mobilisationUa-table')
<div class="card-body p-0 crud-card-body" id="mobilisationUas-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $mobilisationUas_permissions['edit-mobilisationUa'] || $mobilisationUas_permissions['destroy-mobilisationUa'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="30" field="unite_apprentissage_id" modelname="mobilisationUa" label="{!!ucfirst(__('PkgCompetences::uniteApprentissage.singular'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('mobilisationUa-table-tbody')
            @foreach ($mobilisationUas_data as $mobilisationUa)
                @php
                    $isEditable = $mobilisationUas_permissions['edit-mobilisationUa'] && $mobilisationUas_permissionsByItem['update'][$mobilisationUa->id];
                @endphp
                <tr id="mobilisationUa-row-{{$mobilisationUa->id}}" data-id="{{$mobilisationUa->id}}">
                    <x-checkbox-row :item="$mobilisationUa" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 30%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$mobilisationUa->id}}" data-field="unite_apprentissage_id">
                        {{  $mobilisationUa->uniteApprentissage }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($mobilisationUas_permissions['edit-mobilisationUa'])
                        <x-action-button :entity="$mobilisationUa" actionName="edit">
                        @if($mobilisationUas_permissionsByItem['update'][$mobilisationUa->id])
                            <a href="{{ route('mobilisationUas.edit', ['mobilisationUa' => $mobilisationUa->id]) }}" data-id="{{$mobilisationUa->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($mobilisationUas_permissions['show-mobilisationUa'])
                        <x-action-button :entity="$mobilisationUa" actionName="show">
                        @if($mobilisationUas_permissionsByItem['view'][$mobilisationUa->id])
                            <a href="{{ route('mobilisationUas.show', ['mobilisationUa' => $mobilisationUa->id]) }}" data-id="{{$mobilisationUa->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$mobilisationUa" actionName="delete">
                        @if($mobilisationUas_permissions['destroy-mobilisationUa'])
                        @if($mobilisationUas_permissionsByItem['delete'][$mobilisationUa->id])
                            <form class="context-state" action="{{ route('mobilisationUas.destroy',['mobilisationUa' => $mobilisationUa->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$mobilisationUa->id}}">
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
    @section('mobilisationUa-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $mobilisationUas_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>