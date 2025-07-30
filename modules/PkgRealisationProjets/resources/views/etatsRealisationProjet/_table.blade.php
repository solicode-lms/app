{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('etatsRealisationProjet-table')
<div class="card-body p-0 crud-card-body" id="etatsRealisationProjets-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $etatsRealisationProjets_permissions['edit-etatsRealisationProjet'] || $etatsRealisationProjets_permissions['destroy-etatsRealisationProjet'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="4"  field="ordre" modelname="etatsRealisationProjet" label="{!!ucfirst(__('PkgRealisationProjets::etatsRealisationProjet.ordre'))!!}" />
                <x-sortable-column :sortable="true" width="15"  field="titre" modelname="etatsRealisationProjet" label="{!!ucfirst(__('PkgRealisationProjets::etatsRealisationProjet.titre'))!!}" />
                <x-sortable-column :sortable="true" width="40"  field="description" modelname="etatsRealisationProjet" label="{!!ucfirst(__('PkgRealisationProjets::etatsRealisationProjet.description'))!!}" />
                <x-sortable-column :sortable="true" width="15" field="sys_color_id" modelname="etatsRealisationProjet" label="{!!ucfirst(__('Core::sysColor.singular'))!!}" />
                <x-sortable-column :sortable="true" width="15"  field="is_editable_by_formateur" modelname="etatsRealisationProjet" label="{!!ucfirst(__('PkgRealisationProjets::etatsRealisationProjet.is_editable_by_formateur'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('etatsRealisationProjet-table-tbody')
            @foreach ($etatsRealisationProjets_data as $etatsRealisationProjet)
                @php
                    $isEditable = $etatsRealisationProjets_permissions['edit-etatsRealisationProjet'] && $etatsRealisationProjets_permissionsByItem['update'][$etatsRealisationProjet->id];
                @endphp
                <tr id="etatsRealisationProjet-row-{{$etatsRealisationProjet->id}}" data-id="{{$etatsRealisationProjet->id}}">
                    <x-checkbox-row :item="$etatsRealisationProjet" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatsRealisationProjet->id}}" data-field="ordre"  data-toggle="tooltip" title="{{ $etatsRealisationProjet->ordre }}" >
                            <div class="sortable-button d-flex justify-content-left align-items-center" style="height: 100%;  min-height: 26px;">
                            <i class="fas fa-th-list" title="{{ $etatsRealisationProjet->ordre }}"  data-toggle="tooltip" ></i>  
                        </div>

                    </td>
                    <td style="max-width: 15%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatsRealisationProjet->id}}" data-field="titre"  data-toggle="tooltip" title="{{ $etatsRealisationProjet->titre }}" >
                        {{ $etatsRealisationProjet->titre }}

                    </td>
                    <td style="max-width: 40%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatsRealisationProjet->id}}" data-field="description"  data-toggle="tooltip" title="{{ $etatsRealisationProjet->description }}" >
                  
                        {!! \App\Helpers\TextHelper::formatHtmlWithLineBreaks($etatsRealisationProjet->description, 30) !!}
                   

                    </td>
                    <td style="max-width: 15%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatsRealisationProjet->id}}" data-field="sys_color_id"  data-toggle="tooltip" title="{{ $etatsRealisationProjet->sysColor }}" >
                        <x-badge 
                        :text="$etatsRealisationProjet->sysColor->name ?? ''" 
                        :background="$etatsRealisationProjet->sysColor->hex ?? '#6c757d'" 
                        />

                    </td>
                    <td style="max-width: 15%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$etatsRealisationProjet->id}}" data-field="is_editable_by_formateur"  data-toggle="tooltip" title="{{ $etatsRealisationProjet->is_editable_by_formateur }}" >
                        <span class="{{ $etatsRealisationProjet->is_editable_by_formateur ? 'text-success' : 'text-danger' }}">
                            {{ $etatsRealisationProjet->is_editable_by_formateur ? 'Oui' : 'Non' }}
                        </span>

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($etatsRealisationProjets_permissions['edit-etatsRealisationProjet'])
                        <x-action-button :entity="$etatsRealisationProjet" actionName="edit">
                        @if($etatsRealisationProjets_permissionsByItem['update'][$etatsRealisationProjet->id])
                            <a href="{{ route('etatsRealisationProjets.edit', ['etatsRealisationProjet' => $etatsRealisationProjet->id]) }}" data-id="{{$etatsRealisationProjet->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($etatsRealisationProjets_permissions['show-etatsRealisationProjet'])
                        <x-action-button :entity="$etatsRealisationProjet" actionName="show">
                        @if($etatsRealisationProjets_permissionsByItem['view'][$etatsRealisationProjet->id])
                            <a href="{{ route('etatsRealisationProjets.show', ['etatsRealisationProjet' => $etatsRealisationProjet->id]) }}" data-id="{{$etatsRealisationProjet->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$etatsRealisationProjet" actionName="delete">
                        @if($etatsRealisationProjets_permissions['destroy-etatsRealisationProjet'])
                        @if($etatsRealisationProjets_permissionsByItem['delete'][$etatsRealisationProjet->id])
                            <form class="context-state" action="{{ route('etatsRealisationProjets.destroy',['etatsRealisationProjet' => $etatsRealisationProjet->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$etatsRealisationProjet->id}}">
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
    @section('etatsRealisationProjet-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $etatsRealisationProjets_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>