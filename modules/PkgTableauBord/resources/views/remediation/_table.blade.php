{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('remediation-table')
<div class="card-body p-0 crud-card-body" id="remediations-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $remediations_permissions['edit-remediation'] || $remediations_permissions['destroy-remediation'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="41" field="realisation_tache_id" modelname="remediation" label="{!!ucfirst(__('PkgRealisationTache::realisationTache.singular'))!!}" />
                <x-sortable-column :sortable="true" width="41" field="formateur_id" modelname="remediation" label="{!!ucfirst(__('PkgFormation::formateur.singular'))!!}" />
                <th class="text-center crud-actions-header">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('remediation-table-tbody')
            @foreach ($remediations_data as $remediation)
                @php
                    $isEditable = $remediations_permissions['edit-remediation'] && $remediations_permissionsByItem['update'][$remediation->id];
                @endphp
                <tr id="remediation-row-{{$remediation->id}}" data-id="{{$remediation->id}}">
                    <x-checkbox-row :item="$remediation" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 41%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$remediation->id}}" data-field="realisation_tache_id">
                        {{  $remediation->realisationTache }}

                    </td>
                    <td style="max-width: 41%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$remediation->id}}" data-field="formateur_id">
                        {{  $remediation->formateur }}

                    </td>
                    <td class="text-right wrappable crud-actions-cell" style="max-width: 15%;">
                        <div class="crud-actions-wrapper">
                        <div class="actions-secondary-group">

                        </div>

                        <div class="actions-main-group">
                        @if($remediations_permissions['edit-remediation'])
                        <x-action-button :entity="$remediation" actionName="edit">
                        @if($remediations_permissionsByItem['update'][$remediation->id])
                            <a href="{{ route('remediations.edit', ['remediation' => $remediation->id]) }}" data-id="{{$remediation->id}}" class="btn btn-sm btn-default context-state editEntity btn-action-main">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($remediations_permissions['show-remediation'])
                        <x-action-button :entity="$remediation" actionName="show">
                        @if($remediations_permissionsByItem['view'][$remediation->id])
                            <a href="{{ route('remediations.show', ['remediation' => $remediation->id]) }}" data-id="{{$remediation->id}}" class="btn btn-default btn-sm context-state showEntity btn-action-main">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$remediation" actionName="delete">
                        @if($remediations_permissions['destroy-remediation'])
                        @if($remediations_permissionsByItem['delete'][$remediation->id])
                            <form class="context-state" action="{{ route('remediations.destroy',['remediation' => $remediation->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger d-none d-lg-inline deleteEntity btn-action-delete" data-id="{{$remediation->id}}">
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
    @section('remediation-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $remediations_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>