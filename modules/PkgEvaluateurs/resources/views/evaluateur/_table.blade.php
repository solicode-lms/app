{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('evaluateur-table')
<div class="card-body p-0 crud-card-body" id="evaluateurs-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $evaluateurs_permissions['edit-evaluateur'] || $evaluateurs_permissions['destroy-evaluateur'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="20.5"  field="nom" modelname="evaluateur" label="{!!ucfirst(__('PkgEvaluateurs::evaluateur.nom'))!!}" />
                <x-sortable-column :sortable="true" width="20.5"  field="prenom" modelname="evaluateur" label="{!!ucfirst(__('PkgEvaluateurs::evaluateur.prenom'))!!}" />
                <x-sortable-column :sortable="true" width="20.5"  field="organism" modelname="evaluateur" label="{!!ucfirst(__('PkgEvaluateurs::evaluateur.organism'))!!}" />
                <x-sortable-column :sortable="true" width="20.5" field="user_id" modelname="evaluateur" label="{!!ucfirst(__('PkgAutorisation::user.singular'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('evaluateur-table-tbody')
            @foreach ($evaluateurs_data as $evaluateur)
                @php
                    $isEditable = $evaluateurs_permissions['edit-evaluateur'] && $evaluateurs_permissionsByItem['update'][$evaluateur->id];
                @endphp
                <tr id="evaluateur-row-{{$evaluateur->id}}" data-id="{{$evaluateur->id}}">
                    <x-checkbox-row :item="$evaluateur" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 20.5%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$evaluateur->id}}" data-field="nom">
                        {{ $evaluateur->nom }}

                    </td>
                    <td style="max-width: 20.5%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$evaluateur->id}}" data-field="prenom">
                        {{ $evaluateur->prenom }}

                    </td>
                    <td style="max-width: 20.5%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$evaluateur->id}}" data-field="organism">
                        {{ $evaluateur->organism }}

                    </td>
                    <td style="max-width: 20.5%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$evaluateur->id}}" data-field="user_id">
                        {{  $evaluateur->user }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">
                        @if($evaluateurs_permissions['initPassword-evaluateur'])
                        <x-action-button :entity="$evaluateur" actionName="initPassword">
                            <a 
                            data-toggle="tooltip" 
                            title="Initialiser le mot de passe" 
                            href="{{ route('evaluateurs.initPassword', ['id' => $evaluateur->id]) }}" 
                            data-id="{{$evaluateur->id}}" 
                            data-url="{{ route('evaluateurs.initPassword', ['id' => $evaluateur->id]) }}" 
                            data-action-type="confirm"
                            class="btn btn-default btn-sm context-state actionEntity">
                                <i class="fas fa-unlock-alt"></i>
                            </a>
                        </x-action-button>
                        @endif
                        

                       

                        @if($evaluateurs_permissions['edit-evaluateur'])
                        <x-action-button :entity="$evaluateur" actionName="edit">
                        @if($evaluateurs_permissionsByItem['update'][$evaluateur->id])
                            <a href="{{ route('evaluateurs.edit', ['evaluateur' => $evaluateur->id]) }}" data-id="{{$evaluateur->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($evaluateurs_permissions['show-evaluateur'])
                        <x-action-button :entity="$evaluateur" actionName="show">
                        @if($evaluateurs_permissionsByItem['view'][$evaluateur->id])
                            <a href="{{ route('evaluateurs.show', ['evaluateur' => $evaluateur->id]) }}" data-id="{{$evaluateur->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$evaluateur" actionName="delete">
                        @if($evaluateurs_permissions['destroy-evaluateur'])
                        @if($evaluateurs_permissionsByItem['delete'][$evaluateur->id])
                            <form class="context-state" action="{{ route('evaluateurs.destroy',['evaluateur' => $evaluateur->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$evaluateur->id}}">
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
    @section('evaluateur-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $evaluateurs_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>