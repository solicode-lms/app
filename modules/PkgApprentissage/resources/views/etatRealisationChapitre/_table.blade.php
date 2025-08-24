{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatRealisationChapitre-table')
<div class="card-body p-0 crud-card-body" id="etatRealisationChapitres-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $etatRealisationChapitres_permissions['edit-etatRealisationChapitre'] || $etatRealisationChapitres_permissions['destroy-etatRealisationChapitre'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="4"  field="ordre" modelname="etatRealisationChapitre" label="{!!ucfirst(__('PkgApprentissage::etatRealisationChapitre.ordre'))!!}" />
                <x-sortable-column :sortable="true" width="19.5"  field="nom" modelname="etatRealisationChapitre" label="{!!ucfirst(__('PkgApprentissage::etatRealisationChapitre.nom'))!!}" />
                <x-sortable-column :sortable="true" width="19.5"  field="code" modelname="etatRealisationChapitre" label="{!!ucfirst(__('PkgApprentissage::etatRealisationChapitre.code'))!!}" />
                <x-sortable-column :sortable="true" width="19.5" field="sys_color_id" modelname="etatRealisationChapitre" label="{!!ucfirst(__('Core::sysColor.singular'))!!}" />
                <x-sortable-column :sortable="true" width="19.5"  field="is_editable_only_by_formateur" modelname="etatRealisationChapitre" label="{!!ucfirst(__('PkgApprentissage::etatRealisationChapitre.is_editable_only_by_formateur'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('etatRealisationChapitre-table-tbody')
            @foreach ($etatRealisationChapitres_data as $etatRealisationChapitre)
                @php
                    $isEditable = $etatRealisationChapitres_permissions['edit-etatRealisationChapitre'] && $etatRealisationChapitres_permissionsByItem['update'][$etatRealisationChapitre->id];
                @endphp
                <tr id="etatRealisationChapitre-row-{{$etatRealisationChapitre->id}}" data-id="{{$etatRealisationChapitre->id}}">
                    <x-checkbox-row :item="$etatRealisationChapitre" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 4%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatRealisationChapitre->id}}" data-field="ordre">
                            <div class="sortable-button d-flex justify-content-left align-items-center" style="height: 100%;  min-height: 26px;">
                            <i class="fas fa-th-list" title="{{ $etatRealisationChapitre->ordre }}"  data-toggle="tooltip" ></i>  
                        </div>

                    </td>
                    <td style="max-width: 19.5%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatRealisationChapitre->id}}" data-field="nom">
                        {{ $etatRealisationChapitre->nom }}

                    </td>
                    <td style="max-width: 19.5%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatRealisationChapitre->id}}" data-field="code">
                        {{ $etatRealisationChapitre->code }}

                    </td>
                    <td style="max-width: 19.5%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatRealisationChapitre->id}}" data-field="sys_color_id">
                        <x-badge 
                        :text="$etatRealisationChapitre->sysColor->name ?? ''" 
                        :background="$etatRealisationChapitre->sysColor->hex ?? '#6c757d'" 
                        />

                    </td>
                    <td style="max-width: 19.5%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatRealisationChapitre->id}}" data-field="is_editable_only_by_formateur">
                        <span class="{{ $etatRealisationChapitre->is_editable_only_by_formateur ? 'text-success' : 'text-danger' }}">
                            {{ $etatRealisationChapitre->is_editable_only_by_formateur ? 'Oui' : 'Non' }}
                        </span>

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($etatRealisationChapitres_permissions['edit-etatRealisationChapitre'])
                        <x-action-button :entity="$etatRealisationChapitre" actionName="edit">
                        @if($etatRealisationChapitres_permissionsByItem['update'][$etatRealisationChapitre->id])
                            <a href="{{ route('etatRealisationChapitres.edit', ['etatRealisationChapitre' => $etatRealisationChapitre->id]) }}" data-id="{{$etatRealisationChapitre->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($etatRealisationChapitres_permissions['show-etatRealisationChapitre'])
                        <x-action-button :entity="$etatRealisationChapitre" actionName="show">
                        @if($etatRealisationChapitres_permissionsByItem['view'][$etatRealisationChapitre->id])
                            <a href="{{ route('etatRealisationChapitres.show', ['etatRealisationChapitre' => $etatRealisationChapitre->id]) }}" data-id="{{$etatRealisationChapitre->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$etatRealisationChapitre" actionName="delete">
                        @if($etatRealisationChapitres_permissions['destroy-etatRealisationChapitre'])
                        @if($etatRealisationChapitres_permissionsByItem['delete'][$etatRealisationChapitre->id])
                            <form class="context-state" action="{{ route('etatRealisationChapitres.destroy',['etatRealisationChapitre' => $etatRealisationChapitre->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$etatRealisationChapitre->id}}">
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
    @section('etatRealisationChapitre-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $etatRealisationChapitres_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>