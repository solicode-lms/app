{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('transfertCompetence-table')
<div class="card-body p-0 crud-card-body" id="transfertCompetences-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-transfertCompetence') || Auth::user()->can('destroy-transfertCompetence');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="27.333333333333332" field="competence_id" modelname="transfertCompetence" label="{{ucfirst(__('PkgCompetences::competence.singular'))}}" />
                <x-sortable-column :sortable="true" width="27.333333333333332" field="niveau_difficulte_id" modelname="transfertCompetence" label="{{ucfirst(__('PkgCompetences::niveauDifficulte.singular'))}}" />
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="note" modelname="transfertCompetence" label="{{ucfirst(__('PkgCreationProjet::transfertCompetence.note'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('transfertCompetence-table-tbody')
            @foreach ($transfertCompetences_data as $transfertCompetence)
                @php
                    $isEditable = Auth::user()->can('edit-transfertCompetence') && Auth::user()->can('update', $transfertCompetence);
                @endphp
                <tr id="transfertCompetence-row-{{$transfertCompetence->id}}" data-id="{{$transfertCompetence->id}}">
                    <x-checkbox-row :item="$transfertCompetence" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$transfertCompetence->id}}" data-field="competence_id"  data-toggle="tooltip" title="{{ $transfertCompetence->competence }}" >
                    <x-field :entity="$transfertCompetence" field="competence">
                       
                         {{  $transfertCompetence->competence }}
                    </x-field>
                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$transfertCompetence->id}}" data-field="niveau_difficulte_id"  data-toggle="tooltip" title="{{ $transfertCompetence->niveauDifficulte }}" >
                    <x-field :entity="$transfertCompetence" field="niveauDifficulte">
                       
                         {{  $transfertCompetence->niveauDifficulte }}
                    </x-field>
                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$transfertCompetence->id}}" data-field="note"  data-toggle="tooltip" title="{{ $transfertCompetence->note }}" >
                    <x-field :entity="$transfertCompetence" field="note">
                        {{ $transfertCompetence->note }}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-transfertCompetence')
                        <x-action-button :entity="$transfertCompetence" actionName="edit">
                        @can('update', $transfertCompetence)
                            <a href="{{ route('transfertCompetences.edit', ['transfertCompetence' => $transfertCompetence->id]) }}" data-id="{{$transfertCompetence->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan
                        @can('show-transfertCompetence')
                        <x-action-button :entity="$transfertCompetence" actionName="show">
                        @can('view', $transfertCompetence)
                            <a href="{{ route('transfertCompetences.show', ['transfertCompetence' => $transfertCompetence->id]) }}" data-id="{{$transfertCompetence->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan

                        <x-action-button :entity="$transfertCompetence" actionName="delete">
                        @can('destroy-transfertCompetence')
                        @can('delete', $transfertCompetence)
                            <form class="context-state" action="{{ route('transfertCompetences.destroy',['transfertCompetence' => $transfertCompetence->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$transfertCompetence->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endcan
                        @endcan
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