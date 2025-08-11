{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatRealisationCompetence-table')
<div class="card-body p-0 crud-card-body" id="etatRealisationCompetences-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $etatRealisationCompetences_permissions['edit-etatRealisationCompetence'] || $etatRealisationCompetences_permissions['destroy-etatRealisationCompetence'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="4"  field="ordre" modelname="etatRealisationCompetence" label="{!!ucfirst(__('PkgApprentissage::etatRealisationCompetence.ordre'))!!}" />
                <x-sortable-column :sortable="true" width="26"  field="code" modelname="etatRealisationCompetence" label="{!!ucfirst(__('PkgApprentissage::etatRealisationCompetence.code'))!!}" />
                <x-sortable-column :sortable="true" width="26"  field="nom" modelname="etatRealisationCompetence" label="{!!ucfirst(__('PkgApprentissage::etatRealisationCompetence.nom'))!!}" />
                <x-sortable-column :sortable="true" width="26" field="sys_color_id" modelname="etatRealisationCompetence" label="{!!ucfirst(__('Core::sysColor.singular'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('etatRealisationCompetence-table-tbody')
            @foreach ($etatRealisationCompetences_data as $etatRealisationCompetence)
                @php
                    $isEditable = $etatRealisationCompetences_permissions['edit-etatRealisationCompetence'] && $etatRealisationCompetences_permissionsByItem['update'][$etatRealisationCompetence->id];
                @endphp
                <tr id="etatRealisationCompetence-row-{{$etatRealisationCompetence->id}}" data-id="{{$etatRealisationCompetence->id}}">
                    <x-checkbox-row :item="$etatRealisationCompetence" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatRealisationCompetence->id}}" data-field="ordre"  data-toggle="tooltip" title="{{ $etatRealisationCompetence->ordre }}" >
                            <div class="sortable-button d-flex justify-content-left align-items-center" style="height: 100%;  min-height: 26px;">
                            <i class="fas fa-th-list" title="{{ $etatRealisationCompetence->ordre }}"  data-toggle="tooltip" ></i>  
                        </div>

                    </td>
                    <td style="max-width: 26%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatRealisationCompetence->id}}" data-field="code"  data-toggle="tooltip" title="{{ $etatRealisationCompetence->code }}" >
                        {{ $etatRealisationCompetence->code }}

                    </td>
                    <td style="max-width: 26%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatRealisationCompetence->id}}" data-field="nom"  data-toggle="tooltip" title="{{ $etatRealisationCompetence->nom }}" >
                        {{ $etatRealisationCompetence->nom }}

                    </td>
                    <td style="max-width: 26%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatRealisationCompetence->id}}" data-field="sys_color_id"  data-toggle="tooltip" title="{{ $etatRealisationCompetence->sysColor }}" >
                        <x-badge 
                        :text="$etatRealisationCompetence->sysColor->name ?? ''" 
                        :background="$etatRealisationCompetence->sysColor->hex ?? '#6c757d'" 
                        />

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($etatRealisationCompetences_permissions['edit-etatRealisationCompetence'])
                        <x-action-button :entity="$etatRealisationCompetence" actionName="edit">
                        @if($etatRealisationCompetences_permissionsByItem['update'][$etatRealisationCompetence->id])
                            <a href="{{ route('etatRealisationCompetences.edit', ['etatRealisationCompetence' => $etatRealisationCompetence->id]) }}" data-id="{{$etatRealisationCompetence->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($etatRealisationCompetences_permissions['show-etatRealisationCompetence'])
                        <x-action-button :entity="$etatRealisationCompetence" actionName="show">
                        @if($etatRealisationCompetences_permissionsByItem['view'][$etatRealisationCompetence->id])
                            <a href="{{ route('etatRealisationCompetences.show', ['etatRealisationCompetence' => $etatRealisationCompetence->id]) }}" data-id="{{$etatRealisationCompetence->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$etatRealisationCompetence" actionName="delete">
                        @if($etatRealisationCompetences_permissions['destroy-etatRealisationCompetence'])
                        @if($etatRealisationCompetences_permissionsByItem['delete'][$etatRealisationCompetence->id])
                            <form class="context-state" action="{{ route('etatRealisationCompetences.destroy',['etatRealisationCompetence' => $etatRealisationCompetence->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$etatRealisationCompetence->id}}">
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
    @section('etatRealisationCompetence-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $etatRealisationCompetences_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>