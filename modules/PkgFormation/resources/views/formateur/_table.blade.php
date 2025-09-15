{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('formateur-table')
<div class="card-body p-0 crud-card-body" id="formateurs-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $formateurs_permissions['edit-formateur'] || $formateurs_permissions['destroy-formateur'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="20.5"  field="nom" modelname="formateur" label="{!!ucfirst(__('PkgFormation::formateur.nom'))!!}" />
                <x-sortable-column :sortable="true" width="20.5"  field="prenom" modelname="formateur" label="{!!ucfirst(__('PkgFormation::formateur.prenom'))!!}" />
                <x-sortable-column :sortable="true" width="20.5"  field="specialites" modelname="formateur" label="{!!ucfirst(__('PkgFormation::specialite.plural'))!!}" />
                <x-sortable-column :sortable="true" width="20.5"  field="groupes" modelname="formateur" label="{!!ucfirst(__('PkgApprenants::groupe.plural'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('formateur-table-tbody')
            @foreach ($formateurs_data as $formateur)
                @php
                    $isEditable = $formateurs_permissions['edit-formateur'] && $formateurs_permissionsByItem['update'][$formateur->id];
                @endphp
                <tr id="formateur-row-{{$formateur->id}}" data-id="{{$formateur->id}}">
                    <x-checkbox-row :item="$formateur" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 20.5%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$formateur->id}}" data-field="nom">
                        {{ $formateur->nom }}

                    </td>
                    <td style="max-width: 20.5%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$formateur->id}}" data-field="prenom">
                        {{ $formateur->prenom }}

                    </td>
                    <td style="max-width: 20.5%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$formateur->id}}" data-field="specialites">
                        <ul>
                            @foreach ($formateur->specialites as $specialite)
                                <li @if(strlen($specialite) > 30) data-toggle="tooltip" title="{{$specialite}}"  @endif>@limit($specialite, 30)</li>
                            @endforeach
                        </ul>
                    </td>
                    <td style="max-width: 20.5%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$formateur->id}}" data-field="groupes">
                        <ul>
                            @foreach ($formateur->groupes as $groupe)
                                <li @if(strlen($groupe) > 30) data-toggle="tooltip" title="{{$groupe}}"  @endif>@limit($groupe, 30)</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">
                        @if($formateurs_permissions['initPassword-formateur'])
                        <x-action-button :entity="$formateur" actionName="initPassword">
                            <a 
                            data-toggle="tooltip" 
                            title="Initialiser le mot de passe" 
                            href="{{ route('formateurs.initPassword', ['id' => $formateur->id]) }}" 
                            data-id="{{$formateur->id}}" 
                            data-url="{{ route('formateurs.initPassword', ['id' => $formateur->id]) }}" 
                            data-action-type="confirm"
                            class="btn btn-default btn-sm context-state actionEntity">
                                <i class="fas fa-unlock-alt"></i>
                            </a>
                        </x-action-button>
                        @endif
                        

                       

                        @if($formateurs_permissions['edit-formateur'])
                        <x-action-button :entity="$formateur" actionName="edit">
                        @if($formateurs_permissionsByItem['update'][$formateur->id])
                            <a href="{{ route('formateurs.edit', ['formateur' => $formateur->id]) }}" data-id="{{$formateur->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($formateurs_permissions['show-formateur'])
                        <x-action-button :entity="$formateur" actionName="show">
                        @if($formateurs_permissionsByItem['view'][$formateur->id])
                            <a href="{{ route('formateurs.show', ['formateur' => $formateur->id]) }}" data-id="{{$formateur->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$formateur" actionName="delete">
                        @if($formateurs_permissions['destroy-formateur'])
                        @if($formateurs_permissionsByItem['delete'][$formateur->id])
                            <form class="context-state" action="{{ route('formateurs.destroy',['formateur' => $formateur->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$formateur->id}}">
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
    @section('formateur-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $formateurs_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>