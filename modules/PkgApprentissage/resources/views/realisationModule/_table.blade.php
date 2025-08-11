{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationModule-table')
<div class="card-body p-0 crud-card-body" id="realisationModules-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $realisationModules_permissions['edit-realisationModule'] || $realisationModules_permissions['destroy-realisationModule'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="16.4" field="module_id" modelname="realisationModule" label="{!!ucfirst(__('PkgFormation::module.singular'))!!}" />
                <x-sortable-column :sortable="true" width="16.4" field="apprenant_id" modelname="realisationModule" label="{!!ucfirst(__('PkgApprenants::apprenant.singular'))!!}" />
                <x-sortable-column :sortable="true" width="16.4"  field="progression_cache" modelname="realisationModule" label="{!!ucfirst(__('PkgApprentissage::realisationModule.progression_cache'))!!}" />
                <x-sortable-column :sortable="true" width="16.4" field="etat_realisation_module_id" modelname="realisationModule" label="{!!ucfirst(__('PkgApprentissage::realisationModule.etat_realisation_module_id'))!!}" />
                <x-sortable-column :sortable="true" width="16.4"  field="note_cache" modelname="realisationModule" label="{!!ucfirst(__('PkgApprentissage::realisationModule.note_cache'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('realisationModule-table-tbody')
            @foreach ($realisationModules_data as $realisationModule)
                @php
                    $isEditable = $realisationModules_permissions['edit-realisationModule'] && $realisationModules_permissionsByItem['update'][$realisationModule->id];
                @endphp
                <tr id="realisationModule-row-{{$realisationModule->id}}" data-id="{{$realisationModule->id}}">
                    <x-checkbox-row :item="$realisationModule" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 16.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationModule->id}}" data-field="module_id"  data-toggle="tooltip" title="{{ $realisationModule->module }}" >
                        {{  $realisationModule->module }}

                    </td>
                    <td style="max-width: 16.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationModule->id}}" data-field="apprenant_id"  data-toggle="tooltip" title="{{ $realisationModule->apprenant }}" >
                        {{  $realisationModule->apprenant }}

                    </td>
                    <td style="max-width: 16.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationModule->id}}" data-field="progression_cache"  data-toggle="tooltip" title="{{ $realisationModule->progression_cache }}" >
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-green" role="progressbar" aria-valuenow="{{ $realisationModule->progression_cache }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $realisationModule->progression_cache }}%">
                            </div>
                        </div>
                        <small>
                            {{ $realisationModule->progression_cache }}% Terminé
                        </small>

                    </td>
                    <td style="max-width: 16.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationModule->id}}" data-field="etat_realisation_module_id"  data-toggle="tooltip" title="{{ $realisationModule->etatRealisationModule }}" >
                        @if(!empty($realisationModule->etatRealisationModule))
                        <x-badge 
                        :text="$realisationModule->etatRealisationModule" 
                        :background="$realisationModule->etatRealisationModule->sysColor->hex ?? '#6c757d'" 
                        />
                        @endif

                    </td>
                    <td style="max-width: 16.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationModule->id}}" data-field="note_cache"  data-toggle="tooltip" title="{{ $realisationModule->note_cache }}" >
                        {{ $realisationModule->note_cache }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($realisationModules_permissions['edit-realisationModule'])
                        <x-action-button :entity="$realisationModule" actionName="edit">
                        @if($realisationModules_permissionsByItem['update'][$realisationModule->id])
                            <a href="{{ route('realisationModules.edit', ['realisationModule' => $realisationModule->id]) }}" data-id="{{$realisationModule->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($realisationModules_permissions['show-realisationModule'])
                        <x-action-button :entity="$realisationModule" actionName="show">
                        @if($realisationModules_permissionsByItem['view'][$realisationModule->id])
                            <a href="{{ route('realisationModules.show', ['realisationModule' => $realisationModule->id]) }}" data-id="{{$realisationModule->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$realisationModule" actionName="delete">
                        @if($realisationModules_permissions['destroy-realisationModule'])
                        @if($realisationModules_permissionsByItem['delete'][$realisationModule->id])
                            <form class="context-state" action="{{ route('realisationModules.destroy',['realisationModule' => $realisationModule->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$realisationModule->id}}">
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
    @section('realisationModule-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $realisationModules_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>