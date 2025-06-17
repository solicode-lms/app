{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('transfertCompetence-table')
<div class="card-body p-0 crud-card-body" id="transfertCompetences-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $transfertCompetences_permissions['edit-transfertCompetence'] || $transfertCompetences_permissions['destroy-transfertCompetence'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="41" field="competence_id" modelname="transfertCompetence" label="{{ucfirst(__('PkgCompetences::competence.singular'))}}" />
                <x-sortable-column :sortable="true" width="41" field="niveau_difficulte_id" modelname="transfertCompetence" label="{{ucfirst(__('PkgCompetences::niveauDifficulte.singular'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('transfertCompetence-table-tbody')
            @foreach ($transfertCompetences_data as $transfertCompetence)
                @php
                    $isEditable = $transfertCompetences_permissions['edit-transfertCompetence'] && $transfertCompetences_permissionsByItem['update'][$transfertCompetence->id];
                @endphp
                <tr id="transfertCompetence-row-{{$transfertCompetence->id}}" data-id="{{$transfertCompetence->id}}">
                    <x-checkbox-row :item="$transfertCompetence" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 41%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$transfertCompetence->id}}" data-field="competence_id"  data-toggle="tooltip" title="{{ $transfertCompetence->competence }}" >
                        {{  $transfertCompetence->competence }}

                    </td>
                    <td style="max-width: 41%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$transfertCompetence->id}}" data-field="niveau_difficulte_id"  data-toggle="tooltip" title="{{ $transfertCompetence->niveauDifficulte }}" >
                        {{  $transfertCompetence->niveauDifficulte }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($transfertCompetences_permissions['edit-transfertCompetence'])
                        <x-action-button :entity="$transfertCompetence" actionName="edit">
                        @if($transfertCompetences_permissionsByItem['update'][$transfertCompetence->id])
                            <a href="{{ route('transfertCompetences.edit', ['transfertCompetence' => $transfertCompetence->id]) }}" data-id="{{$transfertCompetence->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($transfertCompetences_permissions['show-transfertCompetence'])
                        <x-action-button :entity="$transfertCompetence" actionName="show">
                        @if($transfertCompetences_permissionsByItem['view'][$transfertCompetence->id])
                            <a href="{{ route('transfertCompetences.show', ['transfertCompetence' => $transfertCompetence->id]) }}" data-id="{{$transfertCompetence->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$transfertCompetence" actionName="delete">
                        @if($transfertCompetences_permissions['destroy-transfertCompetence'])
                        @if($transfertCompetences_permissionsByItem['delete'][$transfertCompetence->id])
                            <form class="context-state" action="{{ route('transfertCompetences.destroy',['transfertCompetence' => $transfertCompetence->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$transfertCompetence->id}}">
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
    @section('transfertCompetence-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $transfertCompetences_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>