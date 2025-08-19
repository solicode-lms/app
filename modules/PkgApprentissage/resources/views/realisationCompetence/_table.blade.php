{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationCompetence-table')
<div class="card-body p-0 crud-card-body" id="realisationCompetences-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $realisationCompetences_permissions['edit-realisationCompetence'] || $realisationCompetences_permissions['destroy-realisationCompetence'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="10.4" field="competence_id" modelname="realisationCompetence" label="{!!ucfirst(__('PkgCompetences::competence.singular'))!!}" />
                <x-sortable-column :sortable="true" width="30" field="realisation_module_id" modelname="realisationCompetence" label="{!!ucfirst(__('PkgApprentissage::realisationCompetence.realisation_module_id'))!!}" />
                <x-sortable-column :sortable="true" width="10.4" field="apprenant_id" modelname="realisationCompetence" label="{!!ucfirst(__('PkgApprenants::apprenant.singular'))!!}" />
                <x-sortable-column :sortable="true" width="10.4"  field="progression_cache" modelname="realisationCompetence" label="{!!ucfirst(__('PkgApprentissage::realisationCompetence.progression_cache'))!!}" />
                <x-sortable-column :sortable="true" width="10.4"  field="note_cache" modelname="realisationCompetence" label="{!!ucfirst(__('PkgApprentissage::realisationCompetence.note_cache'))!!}" />
                <x-sortable-column :sortable="true" width="10.4" field="etat_realisation_competence_id" modelname="realisationCompetence" label="{!!ucfirst(__('PkgApprentissage::etatRealisationCompetence.singular'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('realisationCompetence-table-tbody')
            @foreach ($realisationCompetences_data as $realisationCompetence)
                @php
                    $isEditable = $realisationCompetences_permissions['edit-realisationCompetence'] && $realisationCompetences_permissionsByItem['update'][$realisationCompetence->id];
                @endphp
                <tr id="realisationCompetence-row-{{$realisationCompetence->id}}" data-id="{{$realisationCompetence->id}}">
                    <x-checkbox-row :item="$realisationCompetence" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 10.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationCompetence->id}}" data-field="competence_id"  data-toggle="tooltip" title="{{ $realisationCompetence->competence }}" >
                        {{  $realisationCompetence->competence }}

                    </td>
                    <td style="max-width: 30%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationCompetence->id}}" data-field="realisation_module_id"  data-toggle="tooltip" title="{{ $realisationCompetence->realisationModule }}" >
                        {{  $realisationCompetence->realisationModule }}

                    </td>
                    <td style="max-width: 10.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationCompetence->id}}" data-field="apprenant_id"  data-toggle="tooltip" title="{{ $realisationCompetence->apprenant }}" >
                        {{  $realisationCompetence->apprenant }}

                    </td>
                    <td style="max-width: 10.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationCompetence->id}}" data-field="progression_cache"  data-toggle="tooltip" title="{{ $realisationCompetence->progression_cache }}" >
                        @include('PkgApprentissage::realisationCompetence.custom.fields.progression_cache', ['entity' => $realisationCompetence])
                    </td>
                    <td style="max-width: 10.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationCompetence->id}}" data-field="note_cache"  data-toggle="tooltip" title="{{ $realisationCompetence->note_cache }}" >
                        @include('PkgApprentissage::realisationCompetence.custom.fields.note_cache', ['entity' => $realisationCompetence])
                    </td>
                    <td style="max-width: 10.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationCompetence->id}}" data-field="etat_realisation_competence_id"  data-toggle="tooltip" title="{{ $realisationCompetence->etatRealisationCompetence }}" >
                        @if(!empty($realisationCompetence->etatRealisationCompetence))
                        <x-badge 
                        :text="$realisationCompetence->etatRealisationCompetence" 
                        :background="$realisationCompetence->etatRealisationCompetence->sysColor->hex ?? '#6c757d'" 
                        />
                        @endif

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($realisationCompetences_permissions['edit-realisationCompetence'])
                        <x-action-button :entity="$realisationCompetence" actionName="edit">
                        @if($realisationCompetences_permissionsByItem['update'][$realisationCompetence->id])
                            <a href="{{ route('realisationCompetences.edit', ['realisationCompetence' => $realisationCompetence->id]) }}" data-id="{{$realisationCompetence->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($realisationCompetences_permissions['show-realisationCompetence'])
                        <x-action-button :entity="$realisationCompetence" actionName="show">
                        @if($realisationCompetences_permissionsByItem['view'][$realisationCompetence->id])
                            <a href="{{ route('realisationCompetences.show', ['realisationCompetence' => $realisationCompetence->id]) }}" data-id="{{$realisationCompetence->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$realisationCompetence" actionName="delete">
                        @if($realisationCompetences_permissions['destroy-realisationCompetence'])
                        @if($realisationCompetences_permissionsByItem['delete'][$realisationCompetence->id])
                            <form class="context-state" action="{{ route('realisationCompetences.destroy',['realisationCompetence' => $realisationCompetence->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$realisationCompetence->id}}">
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
    @section('realisationCompetence-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $realisationCompetences_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>