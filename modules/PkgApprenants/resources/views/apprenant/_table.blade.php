{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('apprenant-table')
<div class="card-body p-0 crud-card-body" id="apprenants-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $apprenants_permissions['edit-apprenant'] || $apprenants_permissions['destroy-apprenant'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="20.5"  field="nom" modelname="apprenant" label="{!!ucfirst(__('PkgApprenants::apprenant.nom'))!!}" />
                <x-sortable-column :sortable="true" width="20.5"  field="prenom" modelname="apprenant" label="{!!ucfirst(__('PkgApprenants::apprenant.prenom'))!!}" />
                <x-sortable-column :sortable="true" width="20.5"  field="duree_sans_terminer_tache" modelname="apprenant" label="{!!ucfirst(__('PkgApprenants::apprenant.duree_sans_terminer_tache'))!!}" />
                <x-sortable-column :sortable="true" width="20.5"  field="groupes" modelname="apprenant" label="{!!ucfirst(__('PkgApprenants::groupe.plural'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('apprenant-table-tbody')
            @foreach ($apprenants_data as $apprenant)
                @php
                    $isEditable = $apprenants_permissions['edit-apprenant'] && $apprenants_permissionsByItem['update'][$apprenant->id];
                @endphp
                <tr id="apprenant-row-{{$apprenant->id}}" data-id="{{$apprenant->id}}">
                    <x-checkbox-row :item="$apprenant" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 20.5%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$apprenant->id}}" data-field="nom">
                        {{ $apprenant->nom }}

                    </td>
                    <td style="max-width: 20.5%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$apprenant->id}}" data-field="prenom">
                        {{ $apprenant->prenom }}

                    </td>
                    <td style="max-width: 20.5%;white-space: normal;" class=" text-truncate" data-id="{{$apprenant->id}}" data-field="duree_sans_terminer_tache">
                            <x-duree-affichage :heures="$apprenant->duree_sans_terminer_tache" />

                    </td>
                    <td style="max-width: 20.5%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$apprenant->id}}" data-field="groupes">
                        <ul>
                            @foreach ($apprenant->groupes as $groupe)
                                <li @if(strlen($groupe) > 30) data-toggle="tooltip" title="{{$groupe}}"  @endif>@limit($groupe, 30)</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">
                        @if($apprenants_permissions['initPassword-apprenant'])
                        <x-action-button :entity="$apprenant" actionName="initPassword">
                            <a 
                            data-toggle="tooltip" 
                            title="Initialiser le mot de passe" 
                            href="{{ route('apprenants.initPassword', ['id' => $apprenant->id]) }}" 
                            data-id="{{$apprenant->id}}" 
                            data-url="{{ route('apprenants.initPassword', ['id' => $apprenant->id]) }}" 
                            data-action-type="confirm"
                            class="btn btn-default btn-sm context-state actionEntity">
                                <i class="fas fa-unlock-alt"></i>
                            </a>
                        </x-action-button>
                        @endif
                        

                       

                        @if($apprenants_permissions['edit-apprenant'])
                        <x-action-button :entity="$apprenant" actionName="edit">
                        @if($apprenants_permissionsByItem['update'][$apprenant->id])
                            <a href="{{ route('apprenants.edit', ['apprenant' => $apprenant->id]) }}" data-id="{{$apprenant->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($apprenants_permissions['show-apprenant'])
                        <x-action-button :entity="$apprenant" actionName="show">
                        @if($apprenants_permissionsByItem['view'][$apprenant->id])
                            <a href="{{ route('apprenants.show', ['apprenant' => $apprenant->id]) }}" data-id="{{$apprenant->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$apprenant" actionName="delete">
                        @if($apprenants_permissions['destroy-apprenant'])
                        @if($apprenants_permissionsByItem['delete'][$apprenant->id])
                            <form class="context-state" action="{{ route('apprenants.destroy',['apprenant' => $apprenant->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$apprenant->id}}">
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
    @section('apprenant-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $apprenants_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>