{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('chapitre-table')
<div class="card-body p-0 crud-card-body" id="chapitres-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $chapitres_permissions['edit-chapitre'] || $chapitres_permissions['destroy-chapitre'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="12.833333333333334"  field="nom" modelname="chapitre" label="{{ucfirst(__('PkgAutoformation::chapitre.nom'))}}" />
                <x-sortable-column :sortable="true" width="12.833333333333334"  field="lien" modelname="chapitre" label="{{ucfirst(__('PkgAutoformation::chapitre.lien'))}}" />
                <x-sortable-column :sortable="true" width="5"  field="ordre" modelname="chapitre" label="{{ucfirst(__('PkgAutoformation::chapitre.ordre'))}}" />
                <x-sortable-column :sortable="true" width="12.833333333333334" field="formation_id" modelname="chapitre" label="{{ucfirst(__('PkgAutoformation::formation.singular'))}}" />
                <x-sortable-column :sortable="true" width="12.833333333333334" field="niveau_competence_id" modelname="chapitre" label="{{ucfirst(__('PkgCompetences::niveauCompetence.singular'))}}" />
                <x-sortable-column :sortable="true" width="12.833333333333334" field="formateur_id" modelname="chapitre" label="{{ucfirst(__('PkgFormation::formateur.singular'))}}" />
                <x-sortable-column :sortable="true" width="12.833333333333334" field="chapitre_officiel_id" modelname="chapitre" label="{{ucfirst(__('PkgAutoformation::chapitre.singular'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('chapitre-table-tbody')
            @foreach ($chapitres_data as $chapitre)
                @php
                    $isEditable = $chapitres_permissions['edit-chapitre'] && $chapitres_permissionsByItem['update'][$chapitre->id];
                @endphp
                <tr id="chapitre-row-{{$chapitre->id}}" data-id="{{$chapitre->id}}">
                    <x-checkbox-row :item="$chapitre" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 12.833333333333334%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$chapitre->id}}" data-field="nom"  data-toggle="tooltip" title="{{ $chapitre->nom }}" >
                        {{ $chapitre->nom }}

                    </td>
                    <td style="max-width: 12.833333333333334%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$chapitre->id}}" data-field="lien"  data-toggle="tooltip" title="{{ $chapitre->lien }}" >
                        {{ $chapitre->lien }}

                    </td>
                    <td style="max-width: 5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$chapitre->id}}" data-field="ordre"  data-toggle="tooltip" title="{{ $chapitre->ordre }}" >
                            <div class="sortable-button d-flex justify-content-left align-items-center" style="height: 100%;  min-height: 26px;">
                            <i class="fas fa-th-list" title="{{ $chapitre->ordre }}"  data-toggle="tooltip" ></i>  
                        </div>

                    </td>
                    <td style="max-width: 12.833333333333334%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$chapitre->id}}" data-field="formation_id"  data-toggle="tooltip" title="{{ $chapitre->formation }}" >
                        {{  $chapitre->formation }}

                    </td>
                    <td style="max-width: 12.833333333333334%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$chapitre->id}}" data-field="niveau_competence_id"  data-toggle="tooltip" title="{{ $chapitre->niveauCompetence }}" >
                        {{  $chapitre->niveauCompetence }}

                    </td>
                    <td style="max-width: 12.833333333333334%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$chapitre->id}}" data-field="formateur_id"  data-toggle="tooltip" title="{{ $chapitre->formateur }}" >
                        {{  $chapitre->formateur }}

                    </td>
                    <td style="max-width: 12.833333333333334%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$chapitre->id}}" data-field="chapitre_officiel_id"  data-toggle="tooltip" title="{{ $chapitre->chapitreOfficiel }}" >
                        {{  $chapitre->chapitreOfficiel }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($chapitres_permissions['edit-chapitre'])
                        <x-action-button :entity="$chapitre" actionName="edit">
                        @if($chapitres_permissionsByItem['update'][$chapitre->id])
                            <a href="{{ route('chapitres.edit', ['chapitre' => $chapitre->id]) }}" data-id="{{$chapitre->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($chapitres_permissions['show-chapitre'])
                        <x-action-button :entity="$chapitre" actionName="show">
                        @if($chapitres_permissionsByItem['view'][$chapitre->id])
                            <a href="{{ route('chapitres.show', ['chapitre' => $chapitre->id]) }}" data-id="{{$chapitre->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$chapitre" actionName="delete">
                        @if($chapitres_permissions['destroy-chapitre'])
                        @if($chapitres_permissionsByItem['delete'][$chapitre->id])
                            <form class="context-state" action="{{ route('chapitres.destroy',['chapitre' => $chapitre->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$chapitre->id}}">
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
    @section('chapitre-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $chapitres_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>