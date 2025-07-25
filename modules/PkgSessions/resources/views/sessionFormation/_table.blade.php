{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('sessionFormation-table')
<div class="card-body p-0 crud-card-body" id="sessionFormations-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $sessionFormations_permissions['edit-sessionFormation'] || $sessionFormations_permissions['destroy-sessionFormation'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="4"  field="ordre" modelname="sessionFormation" label="{!!ucfirst(__('PkgSessions::sessionFormation.ordre'))!!}" />
                <x-sortable-column :sortable="true" width="26"  field="titre" modelname="sessionFormation" label="{!!ucfirst(__('PkgSessions::sessionFormation.titre'))!!}" />
                <x-sortable-column :sortable="true" width="26" field="filiere_id" modelname="sessionFormation" label="{!!ucfirst(__('PkgFormation::filiere.singular'))!!}" />
                <x-sortable-column :sortable="false" width="26"  field="AlignementUa" modelname="sessionFormation" label="{!!ucfirst(__('PkgSessions::alignementUa.plural'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('sessionFormation-table-tbody')
            @foreach ($sessionFormations_data as $sessionFormation)
                @php
                    $isEditable = $sessionFormations_permissions['edit-sessionFormation'] && $sessionFormations_permissionsByItem['update'][$sessionFormation->id];
                @endphp
                <tr id="sessionFormation-row-{{$sessionFormation->id}}" data-id="{{$sessionFormation->id}}">
                    <x-checkbox-row :item="$sessionFormation" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$sessionFormation->id}}" data-field="ordre"  data-toggle="tooltip" title="{{ $sessionFormation->ordre }}" >
                            <div class="sortable-button d-flex justify-content-left align-items-center" style="height: 100%;  min-height: 26px;">
                            <i class="fas fa-th-list" title="{{ $sessionFormation->ordre }}"  data-toggle="tooltip" ></i>  
                        </div>

                    </td>
                    <td style="max-width: 26%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$sessionFormation->id}}" data-field="titre"  data-toggle="tooltip" title="{{ $sessionFormation->titre }}" >
                        @include('PkgSessions::sessionFormation.custom.fields.titre', ['entity' => $sessionFormation])
                    </td>
                    <td style="max-width: 26%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$sessionFormation->id}}" data-field="filiere_id"  data-toggle="tooltip" title="{{ $sessionFormation->filiere }}" >
                        {{  $sessionFormation->filiere }}

                    </td>
                    <td style="max-width: 26%;" class=" text-truncate" data-id="{{$sessionFormation->id}}" data-field="AlignementUa"  data-toggle="tooltip" title="{{ $sessionFormation->alignementUas }}" >
                        <ul>
                            @foreach ($sessionFormation->alignementUas as $alignementUa)
                                <li>{{$alignementUa}} </li>
                            @endforeach
                        </ul>

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($sessionFormations_permissions['edit-sessionFormation'])
                        <x-action-button :entity="$sessionFormation" actionName="edit">
                        @if($sessionFormations_permissionsByItem['update'][$sessionFormation->id])
                            <a href="{{ route('sessionFormations.edit', ['sessionFormation' => $sessionFormation->id]) }}" data-id="{{$sessionFormation->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($sessionFormations_permissions['show-sessionFormation'])
                        <x-action-button :entity="$sessionFormation" actionName="show">
                        @if($sessionFormations_permissionsByItem['view'][$sessionFormation->id])
                            <a href="{{ route('sessionFormations.show', ['sessionFormation' => $sessionFormation->id]) }}" data-id="{{$sessionFormation->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$sessionFormation" actionName="delete">
                        @if($sessionFormations_permissions['destroy-sessionFormation'])
                        @if($sessionFormations_permissionsByItem['delete'][$sessionFormation->id])
                            <form class="context-state" action="{{ route('sessionFormations.destroy',['sessionFormation' => $sessionFormation->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$sessionFormation->id}}">
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
    @section('sessionFormation-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $sessionFormations_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>