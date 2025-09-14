{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('uniteApprentissage-table')
<div class="card-body p-0 crud-card-body" id="uniteApprentissages-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $uniteApprentissages_permissions['edit-uniteApprentissage'] || $uniteApprentissages_permissions['destroy-uniteApprentissage'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="4"  field="ordre" modelname="uniteApprentissage" label="{!!ucfirst(__('PkgCompetences::uniteApprentissage.ordre'))!!}" />
                <x-sortable-column :sortable="true" width="20"  field="nom" modelname="uniteApprentissage" label="{!!ucfirst(__('PkgCompetences::uniteApprentissage.nom'))!!}" />
                <x-sortable-column :sortable="true" width="20" field="micro_competence_id" modelname="uniteApprentissage" label="{!!ucfirst(__('PkgCompetences::microCompetence.singular'))!!}" />
                <x-sortable-column :sortable="true" width="5"  field="lien" modelname="uniteApprentissage" label="{!!ucfirst(__('PkgCompetences::uniteApprentissage.lien'))!!}" />
                <x-sortable-column :sortable="false" width="33"  field="Chapitre" modelname="uniteApprentissage" label="{!!ucfirst(__('PkgCompetences::chapitre.plural'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('uniteApprentissage-table-tbody')
            @foreach ($uniteApprentissages_data as $uniteApprentissage)
                @php
                    $isEditable = $uniteApprentissages_permissions['edit-uniteApprentissage'] && $uniteApprentissages_permissionsByItem['update'][$uniteApprentissage->id];
                @endphp
                <tr id="uniteApprentissage-row-{{$uniteApprentissage->id}}" data-id="{{$uniteApprentissage->id}}">
                    <x-checkbox-row :item="$uniteApprentissage" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 4%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$uniteApprentissage->id}}" data-field="ordre">
                            <div class="sortable-button d-flex justify-content-left align-items-center" style="height: 100%;  min-height: 26px;">
                            <i class="fas fa-th-list" title="{{ $uniteApprentissage->ordre }}"  data-toggle="tooltip" ></i>  
                        </div>

                    </td>
                    <td style="max-width: 20%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$uniteApprentissage->id}}" data-field="nom" >
                        @include('PkgCompetences::uniteApprentissage.custom.fields.nom', ['entity' => $uniteApprentissage])
                    </td>
                    <td style="max-width: 20%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$uniteApprentissage->id}}" data-field="micro_competence_id" >
                        @include('PkgCompetences::uniteApprentissage.custom.fields.microCompetence', ['entity' => $uniteApprentissage])
                    </td>
                    <td style="max-width: 5%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$uniteApprentissage->id}}" data-field="lien">
     @if($uniteApprentissage->lien)
    <a href="{{ $uniteApprentissage->lien }}" target="_blank">
        <i class="fas fa-link"></i>
    </a>
    @else
    —
    @endif


                    </td>
                    <td style="max-width: 33%;white-space: normal;" class=" text-truncate" data-id="{{$uniteApprentissage->id}}" data-field="Chapitre">
                        <ul>
                            @foreach ($uniteApprentissage->chapitres as $chapitre)
                                <li>{{$chapitre}} </li>
                            @endforeach
                        </ul>

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($uniteApprentissages_permissions['edit-uniteApprentissage'])
                        <x-action-button :entity="$uniteApprentissage" actionName="edit">
                        @if($uniteApprentissages_permissionsByItem['update'][$uniteApprentissage->id])
                            <a href="{{ route('uniteApprentissages.edit', ['uniteApprentissage' => $uniteApprentissage->id]) }}" data-id="{{$uniteApprentissage->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($uniteApprentissages_permissions['show-uniteApprentissage'])
                        <x-action-button :entity="$uniteApprentissage" actionName="show">
                        @if($uniteApprentissages_permissionsByItem['view'][$uniteApprentissage->id])
                            <a href="{{ route('uniteApprentissages.show', ['uniteApprentissage' => $uniteApprentissage->id]) }}" data-id="{{$uniteApprentissage->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$uniteApprentissage" actionName="delete">
                        @if($uniteApprentissages_permissions['destroy-uniteApprentissage'])
                        @if($uniteApprentissages_permissionsByItem['delete'][$uniteApprentissage->id])
                            <form class="context-state" action="{{ route('uniteApprentissages.destroy',['uniteApprentissage' => $uniteApprentissage->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$uniteApprentissage->id}}">
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
    @section('uniteApprentissage-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $uniteApprentissages_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>