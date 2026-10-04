{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatEvaluationProjet-table')
<div class="card-body p-0 crud-card-body" id="etatEvaluationProjets-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $etatEvaluationProjets_permissions['edit-etatEvaluationProjet'] || $etatEvaluationProjets_permissions['destroy-etatEvaluationProjet'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="4"  field="ordre" modelname="etatEvaluationProjet" label="{!!ucfirst(__('PkgEvaluateurs::etatEvaluationProjet.ordre'))!!}" />
                <x-sortable-column :sortable="true" width="26"  field="code" modelname="etatEvaluationProjet" label="{!!ucfirst(__('PkgEvaluateurs::etatEvaluationProjet.code'))!!}" />
                <x-sortable-column :sortable="true" width="26"  field="titre" modelname="etatEvaluationProjet" label="{!!ucfirst(__('PkgEvaluateurs::etatEvaluationProjet.titre'))!!}" />
                <x-sortable-column :sortable="true" width="26" field="sys_color_id" modelname="etatEvaluationProjet" label="{!!ucfirst(__('Core::sysColor.singular'))!!}" />
                <th class="text-center crud-actions-header">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('etatEvaluationProjet-table-tbody')
            @foreach ($etatEvaluationProjets_data as $etatEvaluationProjet)
                @php
                    $isEditable = $etatEvaluationProjets_permissions['edit-etatEvaluationProjet'] && $etatEvaluationProjets_permissionsByItem['update'][$etatEvaluationProjet->id];
                @endphp
                <tr id="etatEvaluationProjet-row-{{$etatEvaluationProjet->id}}" data-id="{{$etatEvaluationProjet->id}}">
                    <x-checkbox-row :item="$etatEvaluationProjet" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 4%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatEvaluationProjet->id}}" data-field="ordre">
                            <div class="sortable-button d-flex justify-content-left align-items-center" style="height: 100%;  min-height: 26px;">
                            <i class="fas fa-th-list" title="{{ $etatEvaluationProjet->ordre }}"  data-toggle="tooltip" ></i>  
                        </div>

                    </td>
                    <td style="max-width: 26%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatEvaluationProjet->id}}" data-field="code">
                        {{ $etatEvaluationProjet->code }}

                    </td>
                    <td style="max-width: 26%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatEvaluationProjet->id}}" data-field="titre">
                        {{ $etatEvaluationProjet->titre }}

                    </td>
                    <td style="max-width: 26%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatEvaluationProjet->id}}" data-field="sys_color_id">
                        <x-badge 
                        :text="$etatEvaluationProjet->sysColor->name ?? ''" 
                        :background="$etatEvaluationProjet->sysColor->hex ?? '#6c757d'" 
                        />

                    </td>
                    <td class="text-right wrappable crud-actions-cell" style="max-width: 15%;">
                        <div class="crud-actions-wrapper">
                        <div class="actions-secondary-group">

                        </div>

                        <div class="actions-main-group">
                        @if($etatEvaluationProjets_permissions['edit-etatEvaluationProjet'])
                        <x-action-button :entity="$etatEvaluationProjet" actionName="edit">
                        @if($etatEvaluationProjets_permissionsByItem['update'][$etatEvaluationProjet->id])
                            <a href="{{ route('etatEvaluationProjets.edit', ['etatEvaluationProjet' => $etatEvaluationProjet->id]) }}" data-id="{{$etatEvaluationProjet->id}}" class="btn btn-sm btn-default context-state editEntity btn-action-main">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($etatEvaluationProjets_permissions['show-etatEvaluationProjet'])
                        <x-action-button :entity="$etatEvaluationProjet" actionName="show">
                        @if($etatEvaluationProjets_permissionsByItem['view'][$etatEvaluationProjet->id])
                            <a href="{{ route('etatEvaluationProjets.show', ['etatEvaluationProjet' => $etatEvaluationProjet->id]) }}" data-id="{{$etatEvaluationProjet->id}}" class="btn btn-default btn-sm context-state showEntity btn-action-main">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$etatEvaluationProjet" actionName="delete">
                        @if($etatEvaluationProjets_permissions['destroy-etatEvaluationProjet'])
                        @if($etatEvaluationProjets_permissionsByItem['delete'][$etatEvaluationProjet->id])
                            <form class="context-state" action="{{ route('etatEvaluationProjets.destroy',['etatEvaluationProjet' => $etatEvaluationProjet->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger d-none d-lg-inline deleteEntity btn-action-delete" data-id="{{$etatEvaluationProjet->id}}">
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
    @section('etatEvaluationProjet-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $etatEvaluationProjets_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>