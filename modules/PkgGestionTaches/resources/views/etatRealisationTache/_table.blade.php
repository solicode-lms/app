{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatRealisationTache-table')
<div class="card-body p-0 crud-card-body" id="etatRealisationTaches-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $etatRealisationTaches_permissions['edit-etatRealisationTache'] || $devetatRealisationTaches_permissions['destroy-etatRealisationTache'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="20.5"  field="nom" modelname="etatRealisationTache" label="{{ucfirst(__('PkgGestionTaches::etatRealisationTache.nom'))}}" />
                <x-sortable-column :sortable="true" width="20.5" field="workflow_tache_id" modelname="etatRealisationTache" label="{{ucfirst(__('PkgGestionTaches::workflowTache.singular'))}}" />
                <x-sortable-column :sortable="true" width="20.5" field="sys_color_id" modelname="etatRealisationTache" label="{{ucfirst(__('Core::sysColor.singular'))}}" />
                <x-sortable-column :sortable="true" width="20.5" field="formateur_id" modelname="etatRealisationTache" label="{{ucfirst(__('PkgFormation::formateur.singular'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('etatRealisationTache-table-tbody')
            @foreach ($etatRealisationTaches_data as $etatRealisationTache)
                @php
                    $isEditable = $etatRealisationTaches_permissions['edit-etatRealisationTache'] && $etatRealisationTaches_permissionsByItem['update'][$etatRealisationTache->id];
                @endphp
                <tr id="etatRealisationTache-row-{{$etatRealisationTache->id}}" data-id="{{$etatRealisationTache->id}}">
                    <x-checkbox-row :item="$etatRealisationTache" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatRealisationTache->id}}" data-field="nom"  data-toggle="tooltip" title="{{ $etatRealisationTache->nom }}" >
                        {{ $etatRealisationTache->nom }}

                    </td>
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatRealisationTache->id}}" data-field="workflow_tache_id"  data-toggle="tooltip" title="{{ $etatRealisationTache->workflowTache }}" >
                        {{  $etatRealisationTache->workflowTache }}

                    </td>
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatRealisationTache->id}}" data-field="sys_color_id"  data-toggle="tooltip" title="{{ $etatRealisationTache->sysColor }}" >
                        <x-badge 
                        :text="$etatRealisationTache->sysColor->name ?? ''" 
                        :background="$etatRealisationTache->sysColor->hex ?? '#6c757d'" 
                        />

                    </td>
                    <td style="max-width: 20.5%;" class=" text-truncate" data-id="{{$etatRealisationTache->id}}" data-field="formateur_id"  data-toggle="tooltip" title="{{ $etatRealisationTache->formateur }}" >
                        {{  $etatRealisationTache->formateur }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($etatRealisationTaches_permissions['edit-etatRealisationTache'])
                        <x-action-button :entity="$etatRealisationTache" actionName="edit">
                        @if($etatRealisationTaches_permissionsByItem['update'][$etatRealisationTache->id])
                            <a href="{{ route('etatRealisationTaches.edit', ['etatRealisationTache' => $etatRealisationTache->id]) }}" data-id="{{$etatRealisationTache->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($etatRealisationTaches_permissions['show-etatRealisationTache'])
                        <x-action-button :entity="$etatRealisationTache" actionName="show">
                        @if($etatRealisationTaches_permissionsByItem['view'][$etatRealisationTache->id])
                            <a href="{{ route('etatRealisationTaches.show', ['etatRealisationTache' => $etatRealisationTache->id]) }}" data-id="{{$etatRealisationTache->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$etatRealisationTache" actionName="delete">
                        @if($etatRealisationTaches_permissions['destroy-etatRealisationTache'])
                        @if($etatRealisationTaches_permissionsByItem['delete'][$etatRealisationTache->id])
                            <form class="context-state" action="{{ route('etatRealisationTaches.destroy',['etatRealisationTache' => $etatRealisationTache->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$etatRealisationTache->id}}">
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
    @section('etatRealisationTache-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $etatRealisationTaches_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>