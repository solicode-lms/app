{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationMicroCompetence-table')
<div class="card-body p-0 crud-card-body" id="realisationMicroCompetences-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $realisationMicroCompetences_permissions['edit-realisationMicroCompetence'] || $realisationMicroCompetences_permissions['destroy-realisationMicroCompetence'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="32.5" field="micro_competence_id" modelname="realisationMicroCompetence" label="{!!ucfirst(__('PkgCompetences::microCompetence.singular'))!!}" />
                <x-sortable-column :sortable="true" width="32.5"  field="progression_cache" modelname="realisationMicroCompetence" label="{!!ucfirst(__('PkgApprentissage::realisationMicroCompetence.progression_cache'))!!}" />
                <x-sortable-column :sortable="true" width="10"  field="note_cache" modelname="realisationMicroCompetence" label="{!!ucfirst(__('PkgApprentissage::realisationMicroCompetence.note_cache'))!!}" />
                <x-sortable-column :sortable="true" width="7"  field="lien_livrable" modelname="realisationMicroCompetence" label="{!!ucfirst(__('PkgApprentissage::realisationMicroCompetence.lien_livrable'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('realisationMicroCompetence-table-tbody')
            @foreach ($realisationMicroCompetences_data as $realisationMicroCompetence)
                @php
                    $isEditable = $realisationMicroCompetences_permissions['edit-realisationMicroCompetence'] && $realisationMicroCompetences_permissionsByItem['update'][$realisationMicroCompetence->id];
                @endphp
                <tr id="realisationMicroCompetence-row-{{$realisationMicroCompetence->id}}" data-id="{{$realisationMicroCompetence->id}}">
                    <x-checkbox-row :item="$realisationMicroCompetence" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 32.5%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationMicroCompetence->id}}" data-field="micro_competence_id" >
                        @include('PkgApprentissage::realisationMicroCompetence.custom.fields.microCompetence', ['entity' => $realisationMicroCompetence])
                    </td>
                    <td style="max-width: 32.5%;white-space: normal;" class=" text-truncate" data-id="{{$realisationMicroCompetence->id}}" data-field="progression_cache" >
                        @include('PkgApprentissage::realisationMicroCompetence.custom.fields.progression_cache', ['entity' => $realisationMicroCompetence])
                    </td>
                    <td style="max-width: 10%;white-space: normal;" class=" text-truncate" data-id="{{$realisationMicroCompetence->id}}" data-field="note_cache" >
                        @include('PkgApprentissage::realisationMicroCompetence.custom.fields.note_cache', ['entity' => $realisationMicroCompetence])
                    </td>
                    <td style="max-width: 7%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationMicroCompetence->id}}" data-field="lien_livrable">
     @if($realisationMicroCompetence->lien_livrable)
    <a href="{{ $realisationMicroCompetence->lien_livrable }}" target="_blank">
        <i class="fas fa-link"></i>
    </a>
    @else
    —
    @endif


                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($realisationMicroCompetences_permissions['edit-realisationMicroCompetence'])
                        <x-action-button :entity="$realisationMicroCompetence" actionName="edit">
                        @if($realisationMicroCompetences_permissionsByItem['update'][$realisationMicroCompetence->id])
                            <a href="{{ route('realisationMicroCompetences.edit', ['realisationMicroCompetence' => $realisationMicroCompetence->id]) }}" data-id="{{$realisationMicroCompetence->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($realisationMicroCompetences_permissions['show-realisationMicroCompetence'])
                        <x-action-button :entity="$realisationMicroCompetence" actionName="show">
                        @if($realisationMicroCompetences_permissionsByItem['view'][$realisationMicroCompetence->id])
                            <a href="{{ route('realisationMicroCompetences.show', ['realisationMicroCompetence' => $realisationMicroCompetence->id]) }}" data-id="{{$realisationMicroCompetence->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$realisationMicroCompetence" actionName="delete">
                        @if($realisationMicroCompetences_permissions['destroy-realisationMicroCompetence'])
                        @if($realisationMicroCompetences_permissionsByItem['delete'][$realisationMicroCompetence->id])
                            <form class="context-state" action="{{ route('realisationMicroCompetences.destroy',['realisationMicroCompetence' => $realisationMicroCompetence->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$realisationMicroCompetence->id}}">
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
    @section('realisationMicroCompetence-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $realisationMicroCompetences_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>