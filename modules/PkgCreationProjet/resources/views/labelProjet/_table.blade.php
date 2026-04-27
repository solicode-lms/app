{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('labelProjet-table')
<div class="card-body p-0 crud-card-body" id="labelProjets-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $labelProjets_permissions['edit-labelProjet'] || $labelProjets_permissions['destroy-labelProjet'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="nom" modelname="labelProjet" label="{!!ucfirst(__('PkgCreationProjet::labelProjet.nom'))!!}" />
                <x-sortable-column :sortable="true" width="27.333333333333332" field="projet_id" modelname="labelProjet" label="{!!ucfirst(__('PkgCreationProjet::projet.singular'))!!}" />
                <x-sortable-column :sortable="true" width="27.333333333333332" field="sys_color_id" modelname="labelProjet" label="{!!ucfirst(__('Core::sysColor.singular'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('labelProjet-table-tbody')
            @foreach ($labelProjets_data as $labelProjet)
                @php
                    $isEditable = $labelProjets_permissions['edit-labelProjet'] && $labelProjets_permissionsByItem['update'][$labelProjet->id];
                @endphp
                <tr id="labelProjet-row-{{$labelProjet->id}}" data-id="{{$labelProjet->id}}">
                    <x-checkbox-row :item="$labelProjet" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 27.333333333333332%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$labelProjet->id}}" data-field="nom">
                        {{ $labelProjet->nom }}

                    </td>
                    <td style="max-width: 27.333333333333332%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$labelProjet->id}}" data-field="projet_id">
                        {{  $labelProjet->projet }}

                    </td>
                    <td style="max-width: 27.333333333333332%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$labelProjet->id}}" data-field="sys_color_id">
                        <x-badge 
                        :text="$labelProjet->sysColor->name ?? ''" 
                        :background="$labelProjet->sysColor->hex ?? '#6c757d'" 
                        />

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($labelProjets_permissions['edit-labelProjet'])
                        <x-action-button :entity="$labelProjet" actionName="edit">
                        @if($labelProjets_permissionsByItem['update'][$labelProjet->id])
                            <a href="{{ route('labelProjets.edit', ['labelProjet' => $labelProjet->id]) }}" data-id="{{$labelProjet->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($labelProjets_permissions['show-labelProjet'])
                        <x-action-button :entity="$labelProjet" actionName="show">
                        @if($labelProjets_permissionsByItem['view'][$labelProjet->id])
                            <a href="{{ route('labelProjets.show', ['labelProjet' => $labelProjet->id]) }}" data-id="{{$labelProjet->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$labelProjet" actionName="delete">
                        @if($labelProjets_permissions['destroy-labelProjet'])
                        @if($labelProjets_permissionsByItem['delete'][$labelProjet->id])
                            <form class="context-state" action="{{ route('labelProjets.destroy',['labelProjet' => $labelProjet->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$labelProjet->id}}">
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
    @section('labelProjet-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $labelProjets_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>