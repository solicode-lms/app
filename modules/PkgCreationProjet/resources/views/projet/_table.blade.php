{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('projet-table')
<div class="card-body p-0 crud-card-body" id="projets-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $projets_permissions['edit-projet'] || $projets_permissions['destroy-projet'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="35"  field="titre" modelname="projet" label="{!!ucfirst(__('PkgCreationProjet::projet.titre'))!!}" />
                <x-sortable-column :sortable="true" width="30"  field="Tache" modelname="projet" label="{!!ucfirst(__('PkgCreationTache::tache.plural'))!!}" />
                <x-sortable-column :sortable="true" width="17"  field="Livrable" modelname="projet" label="{!!ucfirst(__('PkgCreationProjet::livrable.plural'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('projet-table-tbody')
            @foreach ($projets_data as $projet)
                @php
                    $isEditable = $projets_permissions['edit-projet'] && $projets_permissionsByItem['update'][$projet->id];
                @endphp
                <tr id="projet-row-{{$projet->id}}" data-id="{{$projet->id}}">
                    <x-checkbox-row :item="$projet" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 35%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$projet->id}}" data-field="titre"  data-toggle="tooltip" title="{{ $projet->titre }}" >
                        @include('PkgCreationProjet::projet.custom.fields.titre', ['entity' => $projet])
                    </td>
                    <td style="max-width: 30%;" class=" text-truncate" data-id="{{$projet->id}}" data-field="Tache"  data-toggle="tooltip" title="{{ $projet->taches }}" >
                        @include('PkgCreationProjet::projet.custom.fields.taches', ['entity' => $projet])
                    </td>
                    <td style="max-width: 17%;" class=" text-truncate" data-id="{{$projet->id}}" data-field="Livrable"  data-toggle="tooltip" title="{{ $projet->livrables }}" >
                        <ul>
                            @foreach ($projet->livrables as $livrable)
                                <li>{{$livrable}} </li>
                            @endforeach
                        </ul>

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">
                        @if($projets_permissions['clonerProjet-projet'])
                        <x-action-button :entity="$projet" actionName="clonerProjet">
                            <a 
                            data-toggle="tooltip" 
                            title="Cloner le projet" 
                            href="{{ route('projets.clonerProjet', ['id' => $projet->id]) }}" 
                            data-id="{{$projet->id}}" 
                            data-url="{{ route('projets.clonerProjet', ['id' => $projet->id]) }}" 
                            data-action-type="confirm"
                            class="btn btn-default btn-sm context-state actionEntity">
                                <i class="fas fa-clone"></i>
                            </a>
                        </x-action-button>
                        @endif
                        

                       

                        @if($projets_permissions['edit-projet'])
                        <x-action-button :entity="$projet" actionName="edit">
                        @if($projets_permissionsByItem['update'][$projet->id])
                            <a href="{{ route('projets.edit', ['projet' => $projet->id]) }}" data-id="{{$projet->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($projets_permissions['show-projet'])
                        <x-action-button :entity="$projet" actionName="show">
                        @if($projets_permissionsByItem['view'][$projet->id])
                            <a href="{{ route('projets.show', ['projet' => $projet->id]) }}" data-id="{{$projet->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$projet" actionName="delete">
                        @if($projets_permissions['destroy-projet'])
                        @if($projets_permissionsByItem['delete'][$projet->id])
                            <form class="context-state" action="{{ route('projets.destroy',['projet' => $projet->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$projet->id}}">
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
    @section('projet-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $projets_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>