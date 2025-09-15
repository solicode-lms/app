{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('critereEvaluation-table')
<div class="card-body p-0 crud-card-body" id="critereEvaluations-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $critereEvaluations_permissions['edit-critereEvaluation'] || $critereEvaluations_permissions['destroy-critereEvaluation'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="4"  field="ordre" modelname="critereEvaluation" label="{!!ucfirst(__('PkgCompetences::critereEvaluation.ordre'))!!}" />
                <x-sortable-column :sortable="false" width="40"  field="intitule" modelname="critereEvaluation" label="{!!ucfirst(__('PkgCompetences::critereEvaluation.intitule'))!!}" />
                <x-sortable-column :sortable="true" width="15" field="phase_evaluation_id" modelname="critereEvaluation" label="{!!ucfirst(__('PkgCompetences::phaseEvaluation.singular'))!!}" />
                <x-sortable-column :sortable="true" width="23" field="unite_apprentissage_id" modelname="critereEvaluation" label="{!!ucfirst(__('PkgCompetences::uniteApprentissage.singular'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('critereEvaluation-table-tbody')
            @foreach ($critereEvaluations_data as $critereEvaluation)
                @php
                    $isEditable = $critereEvaluations_permissions['edit-critereEvaluation'] && $critereEvaluations_permissionsByItem['update'][$critereEvaluation->id];
                @endphp
                <tr id="critereEvaluation-row-{{$critereEvaluation->id}}" data-id="{{$critereEvaluation->id}}">
                    <x-checkbox-row :item="$critereEvaluation" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 4%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$critereEvaluation->id}}" data-field="ordre">
                            <div class="sortable-button d-flex justify-content-left align-items-center" style="height: 100%;  min-height: 26px;">
                            <i class="fas fa-th-list" title="{{ $critereEvaluation->ordre }}"  data-toggle="tooltip" ></i>  
                        </div>

                    </td>
                    <td style="max-width: 40%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$critereEvaluation->id}}" data-field="intitule" >
                        @include('PkgCompetences::critereEvaluation.custom.fields.intitule', ['entity' => $critereEvaluation])
                    </td>
                    <td style="max-width: 15%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$critereEvaluation->id}}" data-field="phase_evaluation_id">
                        {{  $critereEvaluation->phaseEvaluation }}

                    </td>
                    <td style="max-width: 23%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$critereEvaluation->id}}" data-field="unite_apprentissage_id">
                        {{  $critereEvaluation->uniteApprentissage }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($critereEvaluations_permissions['edit-critereEvaluation'])
                        <x-action-button :entity="$critereEvaluation" actionName="edit">
                        @if($critereEvaluations_permissionsByItem['update'][$critereEvaluation->id])
                            <a href="{{ route('critereEvaluations.edit', ['critereEvaluation' => $critereEvaluation->id]) }}" data-id="{{$critereEvaluation->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($critereEvaluations_permissions['show-critereEvaluation'])
                        <x-action-button :entity="$critereEvaluation" actionName="show">
                        @if($critereEvaluations_permissionsByItem['view'][$critereEvaluation->id])
                            <a href="{{ route('critereEvaluations.show', ['critereEvaluation' => $critereEvaluation->id]) }}" data-id="{{$critereEvaluation->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$critereEvaluation" actionName="delete">
                        @if($critereEvaluations_permissions['destroy-critereEvaluation'])
                        @if($critereEvaluations_permissionsByItem['delete'][$critereEvaluation->id])
                            <form class="context-state" action="{{ route('critereEvaluations.destroy',['critereEvaluation' => $critereEvaluation->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$critereEvaluation->id}}">
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
    @section('critereEvaluation-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $critereEvaluations_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>