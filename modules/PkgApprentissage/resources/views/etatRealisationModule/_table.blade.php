{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatRealisationModule-table')
<div class="card-body p-0 crud-card-body" id="etatRealisationModules-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $etatRealisationModules_permissions['edit-etatRealisationModule'] || $etatRealisationModules_permissions['destroy-etatRealisationModule'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="4"  field="ordre" modelname="etatRealisationModule" label="{!!ucfirst(__('PkgApprentissage::etatRealisationModule.ordre'))!!}" />
                <x-sortable-column :sortable="true" width="26"  field="code" modelname="etatRealisationModule" label="{!!ucfirst(__('PkgApprentissage::etatRealisationModule.code'))!!}" />
                <x-sortable-column :sortable="true" width="26"  field="nom" modelname="etatRealisationModule" label="{!!ucfirst(__('PkgApprentissage::etatRealisationModule.nom'))!!}" />
                <x-sortable-column :sortable="true" width="26" field="sys_color_id" modelname="etatRealisationModule" label="{!!ucfirst(__('Core::sysColor.singular'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('etatRealisationModule-table-tbody')
            @foreach ($etatRealisationModules_data as $etatRealisationModule)
                @php
                    $isEditable = $etatRealisationModules_permissions['edit-etatRealisationModule'] && $etatRealisationModules_permissionsByItem['update'][$etatRealisationModule->id];
                @endphp
                <tr id="etatRealisationModule-row-{{$etatRealisationModule->id}}" data-id="{{$etatRealisationModule->id}}">
                    <x-checkbox-row :item="$etatRealisationModule" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 4%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatRealisationModule->id}}" data-field="ordre">
                            <div class="sortable-button d-flex justify-content-left align-items-center" style="height: 100%;  min-height: 26px;">
                            <i class="fas fa-th-list" title="{{ $etatRealisationModule->ordre }}"  data-toggle="tooltip" ></i>  
                        </div>

                    </td>
                    <td style="max-width: 26%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatRealisationModule->id}}" data-field="code">
                        {{ $etatRealisationModule->code }}

                    </td>
                    <td style="max-width: 26%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatRealisationModule->id}}" data-field="nom">
                        {{ $etatRealisationModule->nom }}

                    </td>
                    <td style="max-width: 26%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatRealisationModule->id}}" data-field="sys_color_id">
                        <x-badge 
                        :text="$etatRealisationModule->sysColor->name ?? ''" 
                        :background="$etatRealisationModule->sysColor->hex ?? '#6c757d'" 
                        />

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($etatRealisationModules_permissions['edit-etatRealisationModule'])
                        <x-action-button :entity="$etatRealisationModule" actionName="edit">
                        @if($etatRealisationModules_permissionsByItem['update'][$etatRealisationModule->id])
                            <a href="{{ route('etatRealisationModules.edit', ['etatRealisationModule' => $etatRealisationModule->id]) }}" data-id="{{$etatRealisationModule->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($etatRealisationModules_permissions['show-etatRealisationModule'])
                        <x-action-button :entity="$etatRealisationModule" actionName="show">
                        @if($etatRealisationModules_permissionsByItem['view'][$etatRealisationModule->id])
                            <a href="{{ route('etatRealisationModules.show', ['etatRealisationModule' => $etatRealisationModule->id]) }}" data-id="{{$etatRealisationModule->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$etatRealisationModule" actionName="delete">
                        @if($etatRealisationModules_permissions['destroy-etatRealisationModule'])
                        @if($etatRealisationModules_permissionsByItem['delete'][$etatRealisationModule->id])
                            <form class="context-state" action="{{ route('etatRealisationModules.destroy',['etatRealisationModule' => $etatRealisationModule->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$etatRealisationModule->id}}">
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
    @section('etatRealisationModule-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $etatRealisationModules_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>