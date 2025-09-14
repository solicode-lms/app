{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('phaseEvaluation-table')
<div class="card-body p-0 crud-card-body" id="phaseEvaluations-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $phaseEvaluations_permissions['edit-phaseEvaluation'] || $phaseEvaluations_permissions['destroy-phaseEvaluation'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="4"  field="ordre" modelname="phaseEvaluation" label="{!!ucfirst(__('PkgCompetences::phaseEvaluation.ordre'))!!}" />
                <x-sortable-column :sortable="true" width="39"  field="code" modelname="phaseEvaluation" label="{!!ucfirst(__('PkgCompetences::phaseEvaluation.code'))!!}" />
                <x-sortable-column :sortable="true" width="39"  field="libelle" modelname="phaseEvaluation" label="{!!ucfirst(__('PkgCompetences::phaseEvaluation.libelle'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('phaseEvaluation-table-tbody')
            @foreach ($phaseEvaluations_data as $phaseEvaluation)
                @php
                    $isEditable = $phaseEvaluations_permissions['edit-phaseEvaluation'] && $phaseEvaluations_permissionsByItem['update'][$phaseEvaluation->id];
                @endphp
                <tr id="phaseEvaluation-row-{{$phaseEvaluation->id}}" data-id="{{$phaseEvaluation->id}}">
                    <x-checkbox-row :item="$phaseEvaluation" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 4%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$phaseEvaluation->id}}" data-field="ordre">
                            <div class="sortable-button d-flex justify-content-left align-items-center" style="height: 100%;  min-height: 26px;">
                            <i class="fas fa-th-list" title="{{ $phaseEvaluation->ordre }}"  data-toggle="tooltip" ></i>  
                        </div>

                    </td>
                    <td style="max-width: 39%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$phaseEvaluation->id}}" data-field="code">
                        {{ $phaseEvaluation->code }}

                    </td>
                    <td style="max-width: 39%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$phaseEvaluation->id}}" data-field="libelle">
                        {{ $phaseEvaluation->libelle }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($phaseEvaluations_permissions['edit-phaseEvaluation'])
                        <x-action-button :entity="$phaseEvaluation" actionName="edit">
                        @if($phaseEvaluations_permissionsByItem['update'][$phaseEvaluation->id])
                            <a href="{{ route('phaseEvaluations.edit', ['phaseEvaluation' => $phaseEvaluation->id]) }}" data-id="{{$phaseEvaluation->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($phaseEvaluations_permissions['show-phaseEvaluation'])
                        <x-action-button :entity="$phaseEvaluation" actionName="show">
                        @if($phaseEvaluations_permissionsByItem['view'][$phaseEvaluation->id])
                            <a href="{{ route('phaseEvaluations.show', ['phaseEvaluation' => $phaseEvaluation->id]) }}" data-id="{{$phaseEvaluation->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$phaseEvaluation" actionName="delete">
                        @if($phaseEvaluations_permissions['destroy-phaseEvaluation'])
                        @if($phaseEvaluations_permissionsByItem['delete'][$phaseEvaluation->id])
                            <form class="context-state" action="{{ route('phaseEvaluations.destroy',['phaseEvaluation' => $phaseEvaluation->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$phaseEvaluation->id}}">
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
    @section('phaseEvaluation-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $phaseEvaluations_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>