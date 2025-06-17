{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('tache-table')
<div class="card-body p-0 crud-card-body" id="taches-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $taches_permissions['edit-tache'] || $taches_permissions['destroy-tache'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="5"  field="ordre" modelname="tache" label="{{ucfirst(__('PkgGestionTaches::tache.ordre'))}}" />
                <x-sortable-column :sortable="true" width="20"  field="titre" modelname="tache" label="{{ucfirst(__('PkgGestionTaches::tache.titre'))}}" />
                <x-sortable-column :sortable="true" width="8" field="priorite_tache_id" modelname="tache" label="{{ucfirst(__('PkgGestionTaches::tache.priorite_tache_id'))}}" />
                <x-sortable-column :sortable="true" width="12.25" field="projet_id" modelname="tache" label="{{ucfirst(__('PkgCreationProjet::projet.singular'))}}" />
                <x-sortable-column :sortable="true" width="12.25"  field="dateFin" modelname="tache" label="{{ucfirst(__('PkgGestionTaches::tache.dateFin'))}}" />
                <x-sortable-column :sortable="true" width="12.25"  field="note" modelname="tache" label="{{ucfirst(__('PkgGestionTaches::tache.note'))}}" />
                <x-sortable-column :sortable="true" width="12.25"  field="livrables" modelname="tache" label="{{ucfirst(__('PkgCreationProjet::livrable.plural'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('tache-table-tbody')
            @foreach ($taches_data as $tache)
                @php
                    $isEditable = $taches_permissions['edit-tache'] && $taches_permissionsByItem['update'][$tache->id];
                @endphp
                <tr id="tache-row-{{$tache->id}}" data-id="{{$tache->id}}">
                    <x-checkbox-row :item="$tache" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$tache->id}}" data-field="ordre"  data-toggle="tooltip" title="{{ $tache->ordre }}" >
                            <div class="sortable-button d-flex justify-content-left align-items-center" style="height: 100%;  min-height: 26px;">
                            <i class="fas fa-th-list" title="{{ $tache->ordre }}"  data-toggle="tooltip" ></i>  
                        </div>

                    </td>
                    <td style="max-width: 20%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$tache->id}}" data-field="titre"  data-toggle="tooltip" title="{{ $tache->titre }}" >
                        {{ $tache->titre }}

                    </td>
                    <td style="max-width: 8%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$tache->id}}" data-field="priorite_tache_id"  data-toggle="tooltip" title="{{ $tache->prioriteTache }}" >
                        {{  $tache->prioriteTache }}

                    </td>
                    <td style="max-width: 12.25%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$tache->id}}" data-field="projet_id"  data-toggle="tooltip" title="{{ $tache->projet }}" >
                        {{  $tache->projet }}

                    </td>
                    <td style="max-width: 12.25%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$tache->id}}" data-field="dateFin"  data-toggle="tooltip" title="{{ $tache->dateFin }}" >
                        <x-deadline-display :value="$tache->dateFin" />
                    </td>
                    <td style="max-width: 12.25%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$tache->id}}" data-field="note"  data-toggle="tooltip" title="{{ $tache->note }}" >
                        @include('PkgGestionTaches::tache.custom.fields.note', ['entity' => $tache])
                    </td>
                    <td style="max-width: 12.25%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$tache->id}}" data-field="livrables"  data-toggle="tooltip" title="{{ $tache->livrables }}" >
                        <ul>
                            @foreach ($tache->livrables as $livrable)
                                <li @if(strlen($livrable) > 30) data-toggle="tooltip" title="{{$livrable}}"  @endif>@limit($livrable, 30)</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($taches_permissions['edit-tache'])
                        <x-action-button :entity="$tache" actionName="edit">
                        @if($taches_permissionsByItem['update'][$tache->id])
                            <a href="{{ route('taches.edit', ['tache' => $tache->id]) }}" data-id="{{$tache->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($taches_permissions['show-tache'])
                        <x-action-button :entity="$tache" actionName="show">
                        @if($taches_permissionsByItem['view'][$tache->id])
                            <a href="{{ route('taches.show', ['tache' => $tache->id]) }}" data-id="{{$tache->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$tache" actionName="delete">
                        @if($taches_permissions['destroy-tache'])
                        @if($taches_permissionsByItem['delete'][$tache->id])
                            <form class="context-state" action="{{ route('taches.destroy',['tache' => $tache->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$tache->id}}">
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
    @section('tache-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $taches_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>