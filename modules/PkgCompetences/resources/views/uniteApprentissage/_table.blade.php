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
                <x-sortable-column :sortable="true" width="4"  field="ordre" modelname="uniteApprentissage" label="{{ucfirst(__('PkgCompetences::uniteApprentissage.ordre'))}}" />
                <x-sortable-column :sortable="true" width="8"  field="code" modelname="uniteApprentissage" label="{{ucfirst(__('PkgCompetences::uniteApprentissage.code'))}}" />
                <x-sortable-column :sortable="true" width="20"  field="nom" modelname="uniteApprentissage" label="{{ucfirst(__('PkgCompetences::uniteApprentissage.nom'))}}" />
                <x-sortable-column :sortable="true" width="6"  field="lien" modelname="uniteApprentissage" label="{{ucfirst(__('PkgCompetences::uniteApprentissage.lien'))}}" />
                <x-sortable-column :sortable="true" width="15" field="micro_competence_id" modelname="uniteApprentissage" label="{{ucfirst(__('PkgCompetences::microCompetence.singular'))}}" />
                <x-sortable-column :sortable="false" width="29"  field="Chapitre" modelname="uniteApprentissage" label="{{ucfirst(__('PkgCompetences::chapitre.plural'))}}" />
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
                    <td style="max-width: 4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$uniteApprentissage->id}}" data-field="ordre"  data-toggle="tooltip" title="{{ $uniteApprentissage->ordre }}" >
                            <div class="sortable-button d-flex justify-content-left align-items-center" style="height: 100%;  min-height: 26px;">
                            <i class="fas fa-th-list" title="{{ $uniteApprentissage->ordre }}"  data-toggle="tooltip" ></i>  
                        </div>

                    </td>
                    <td style="max-width: 8%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$uniteApprentissage->id}}" data-field="code"  data-toggle="tooltip" title="{{ $uniteApprentissage->code }}" >
                        {{ $uniteApprentissage->code }}

                    </td>
                    <td style="max-width: 20%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$uniteApprentissage->id}}" data-field="nom"  data-toggle="tooltip" title="{{ $uniteApprentissage->nom }}" >
                        {{ $uniteApprentissage->nom }}

                    </td>
                    <td style="max-width: 6%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$uniteApprentissage->id}}" data-field="lien"  data-toggle="tooltip" title="{{ $uniteApprentissage->lien }}" >
                        {{ $uniteApprentissage->lien }}

                    </td>
                    <td style="max-width: 15%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$uniteApprentissage->id}}" data-field="micro_competence_id"  data-toggle="tooltip" title="{{ $uniteApprentissage->microCompetence }}" >
                        {{  $uniteApprentissage->microCompetence }}

                    </td>
                    <td style="max-width: 29%;" class=" text-truncate" data-id="{{$uniteApprentissage->id}}" data-field="Chapitre"  data-toggle="tooltip" title="{{ $uniteApprentissage->chapitres }}" >
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
                                <i class="far fa-eye"></i>
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