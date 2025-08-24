{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatRealisationUa-table')
<div class="card-body p-0 crud-card-body" id="etatRealisationUas-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $etatRealisationUas_permissions['edit-etatRealisationUa'] || $etatRealisationUas_permissions['destroy-etatRealisationUa'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="4"  field="ordre" modelname="etatRealisationUa" label="{!!ucfirst(__('PkgApprentissage::etatRealisationUa.ordre'))!!}" />
                <x-sortable-column :sortable="true" width="19.5"  field="nom" modelname="etatRealisationUa" label="{!!ucfirst(__('PkgApprentissage::etatRealisationUa.nom'))!!}" />
                <x-sortable-column :sortable="true" width="19.5"  field="code" modelname="etatRealisationUa" label="{!!ucfirst(__('PkgApprentissage::etatRealisationUa.code'))!!}" />
                <x-sortable-column :sortable="true" width="19.5" field="sys_color_id" modelname="etatRealisationUa" label="{!!ucfirst(__('Core::sysColor.singular'))!!}" />
                <x-sortable-column :sortable="true" width="19.5"  field="is_editable_only_by_formateur" modelname="etatRealisationUa" label="{!!ucfirst(__('PkgApprentissage::etatRealisationUa.is_editable_only_by_formateur'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('etatRealisationUa-table-tbody')
            @foreach ($etatRealisationUas_data as $etatRealisationUa)
                @php
                    $isEditable = $etatRealisationUas_permissions['edit-etatRealisationUa'] && $etatRealisationUas_permissionsByItem['update'][$etatRealisationUa->id];
                @endphp
                <tr id="etatRealisationUa-row-{{$etatRealisationUa->id}}" data-id="{{$etatRealisationUa->id}}">
                    <x-checkbox-row :item="$etatRealisationUa" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 4%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatRealisationUa->id}}" data-field="ordre">
                            <div class="sortable-button d-flex justify-content-left align-items-center" style="height: 100%;  min-height: 26px;">
                            <i class="fas fa-th-list" title="{{ $etatRealisationUa->ordre }}"  data-toggle="tooltip" ></i>  
                        </div>

                    </td>
                    <td style="max-width: 19.5%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatRealisationUa->id}}" data-field="nom">
                        {{ $etatRealisationUa->nom }}

                    </td>
                    <td style="max-width: 19.5%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatRealisationUa->id}}" data-field="code">
                        {{ $etatRealisationUa->code }}

                    </td>
                    <td style="max-width: 19.5%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatRealisationUa->id}}" data-field="sys_color_id">
                        <x-badge 
                        :text="$etatRealisationUa->sysColor->name ?? ''" 
                        :background="$etatRealisationUa->sysColor->hex ?? '#6c757d'" 
                        />

                    </td>
                    <td style="max-width: 19.5%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatRealisationUa->id}}" data-field="is_editable_only_by_formateur">
                        <span class="{{ $etatRealisationUa->is_editable_only_by_formateur ? 'text-success' : 'text-danger' }}">
                            {{ $etatRealisationUa->is_editable_only_by_formateur ? 'Oui' : 'Non' }}
                        </span>

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($etatRealisationUas_permissions['edit-etatRealisationUa'])
                        <x-action-button :entity="$etatRealisationUa" actionName="edit">
                        @if($etatRealisationUas_permissionsByItem['update'][$etatRealisationUa->id])
                            <a href="{{ route('etatRealisationUas.edit', ['etatRealisationUa' => $etatRealisationUa->id]) }}" data-id="{{$etatRealisationUa->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($etatRealisationUas_permissions['show-etatRealisationUa'])
                        <x-action-button :entity="$etatRealisationUa" actionName="show">
                        @if($etatRealisationUas_permissionsByItem['view'][$etatRealisationUa->id])
                            <a href="{{ route('etatRealisationUas.show', ['etatRealisationUa' => $etatRealisationUa->id]) }}" data-id="{{$etatRealisationUa->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$etatRealisationUa" actionName="delete">
                        @if($etatRealisationUas_permissions['destroy-etatRealisationUa'])
                        @if($etatRealisationUas_permissionsByItem['delete'][$etatRealisationUa->id])
                            <form class="context-state" action="{{ route('etatRealisationUas.destroy',['etatRealisationUa' => $etatRealisationUa->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$etatRealisationUa->id}}">
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
    @section('etatRealisationUa-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $etatRealisationUas_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>