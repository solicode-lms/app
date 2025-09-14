{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatRealisationMicroCompetence-table')
<div class="card-body p-0 crud-card-body" id="etatRealisationMicroCompetences-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $etatRealisationMicroCompetences_permissions['edit-etatRealisationMicroCompetence'] || $etatRealisationMicroCompetences_permissions['destroy-etatRealisationMicroCompetence'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="4"  field="ordre" modelname="etatRealisationMicroCompetence" label="{!!ucfirst(__('PkgApprentissage::etatRealisationMicroCompetence.ordre'))!!}" />
                <x-sortable-column :sortable="true" width="19.5"  field="nom" modelname="etatRealisationMicroCompetence" label="{!!ucfirst(__('PkgApprentissage::etatRealisationMicroCompetence.nom'))!!}" />
                <x-sortable-column :sortable="true" width="19.5"  field="code" modelname="etatRealisationMicroCompetence" label="{!!ucfirst(__('PkgApprentissage::etatRealisationMicroCompetence.code'))!!}" />
                <x-sortable-column :sortable="true" width="19.5" field="sys_color_id" modelname="etatRealisationMicroCompetence" label="{!!ucfirst(__('Core::sysColor.singular'))!!}" />
                <x-sortable-column :sortable="true" width="19.5"  field="is_editable_only_by_formateur" modelname="etatRealisationMicroCompetence" label="{!!ucfirst(__('PkgApprentissage::etatRealisationMicroCompetence.is_editable_only_by_formateur'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('etatRealisationMicroCompetence-table-tbody')
            @foreach ($etatRealisationMicroCompetences_data as $etatRealisationMicroCompetence)
                @php
                    $isEditable = $etatRealisationMicroCompetences_permissions['edit-etatRealisationMicroCompetence'] && $etatRealisationMicroCompetences_permissionsByItem['update'][$etatRealisationMicroCompetence->id];
                @endphp
                <tr id="etatRealisationMicroCompetence-row-{{$etatRealisationMicroCompetence->id}}" data-id="{{$etatRealisationMicroCompetence->id}}">
                    <x-checkbox-row :item="$etatRealisationMicroCompetence" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 4%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatRealisationMicroCompetence->id}}" data-field="ordre">
                            <div class="sortable-button d-flex justify-content-left align-items-center" style="height: 100%;  min-height: 26px;">
                            <i class="fas fa-th-list" title="{{ $etatRealisationMicroCompetence->ordre }}"  data-toggle="tooltip" ></i>  
                        </div>

                    </td>
                    <td style="max-width: 19.5%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatRealisationMicroCompetence->id}}" data-field="nom">
                        {{ $etatRealisationMicroCompetence->nom }}

                    </td>
                    <td style="max-width: 19.5%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatRealisationMicroCompetence->id}}" data-field="code">
                        {{ $etatRealisationMicroCompetence->code }}

                    </td>
                    <td style="max-width: 19.5%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatRealisationMicroCompetence->id}}" data-field="sys_color_id">
                        <x-badge 
                        :text="$etatRealisationMicroCompetence->sysColor->name ?? ''" 
                        :background="$etatRealisationMicroCompetence->sysColor->hex ?? '#6c757d'" 
                        />

                    </td>
                    <td style="max-width: 19.5%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatRealisationMicroCompetence->id}}" data-field="is_editable_only_by_formateur">
                        <span class="{{ $etatRealisationMicroCompetence->is_editable_only_by_formateur ? 'text-success' : 'text-danger' }}">
                            {{ $etatRealisationMicroCompetence->is_editable_only_by_formateur ? 'Oui' : 'Non' }}
                        </span>

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($etatRealisationMicroCompetences_permissions['edit-etatRealisationMicroCompetence'])
                        <x-action-button :entity="$etatRealisationMicroCompetence" actionName="edit">
                        @if($etatRealisationMicroCompetences_permissionsByItem['update'][$etatRealisationMicroCompetence->id])
                            <a href="{{ route('etatRealisationMicroCompetences.edit', ['etatRealisationMicroCompetence' => $etatRealisationMicroCompetence->id]) }}" data-id="{{$etatRealisationMicroCompetence->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($etatRealisationMicroCompetences_permissions['show-etatRealisationMicroCompetence'])
                        <x-action-button :entity="$etatRealisationMicroCompetence" actionName="show">
                        @if($etatRealisationMicroCompetences_permissionsByItem['view'][$etatRealisationMicroCompetence->id])
                            <a href="{{ route('etatRealisationMicroCompetences.show', ['etatRealisationMicroCompetence' => $etatRealisationMicroCompetence->id]) }}" data-id="{{$etatRealisationMicroCompetence->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$etatRealisationMicroCompetence" actionName="delete">
                        @if($etatRealisationMicroCompetences_permissions['destroy-etatRealisationMicroCompetence'])
                        @if($etatRealisationMicroCompetences_permissionsByItem['delete'][$etatRealisationMicroCompetence->id])
                            <form class="context-state" action="{{ route('etatRealisationMicroCompetences.destroy',['etatRealisationMicroCompetence' => $etatRealisationMicroCompetence->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$etatRealisationMicroCompetence->id}}">
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
    @section('etatRealisationMicroCompetence-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $etatRealisationMicroCompetences_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>