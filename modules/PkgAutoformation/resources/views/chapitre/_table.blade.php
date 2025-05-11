{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('chapitre-table')
<div class="card-body p-0 crud-card-body" id="chapitres-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-chapitre') || Auth::user()->can('destroy-chapitre');
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
                    $isEditable = Auth::user()->can('edit-chapitre') && Auth::user()->can('update', $chapitre);
                @endphp
                <tr id="chapitre-row-{{$chapitre->id}}" data-id="{{$chapitre->id}}">
                    <x-checkbox-row :item="$chapitre" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 12.833333333333334%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$chapitre->id}}" data-field="nom"  data-toggle="tooltip" title="{{ $chapitre->nom }}" >
                    <x-field :entity="$chapitre" field="nom">
                        {{ $chapitre->nom }}
                    </x-field>
                    </td>
                    <td style="max-width: 12.833333333333334%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$chapitre->id}}" data-field="lien"  data-toggle="tooltip" title="{{ $chapitre->lien }}" >
                    <x-field :entity="$chapitre" field="lien">
                        {{ $chapitre->lien }}
                    </x-field>
                    </td>
                    <td style="max-width: 5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$chapitre->id}}" data-field="ordre"  data-toggle="tooltip" title="{{ $chapitre->ordre }}" >
                    <x-field :entity="$chapitre" field="ordre">
                         <div class="sortable-button d-flex justify-content-left align-items-center" style="height: 100%;  min-height: 26px;">
                            <i class="fas fa-th-list" title="{{ $chapitre->ordre }}"  data-toggle="tooltip" ></i>  
                        </div>
                    </x-field>
                    </td>
                    <td style="max-width: 12.833333333333334%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$chapitre->id}}" data-field="formation_id"  data-toggle="tooltip" title="{{ $chapitre->formation }}" >
                    <x-field :entity="$chapitre" field="formation">
                       
                         {{  $chapitre->formation }}
                    </x-field>
                    </td>
                    <td style="max-width: 12.833333333333334%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$chapitre->id}}" data-field="niveau_competence_id"  data-toggle="tooltip" title="{{ $chapitre->niveauCompetence }}" >
                    <x-field :entity="$chapitre" field="niveauCompetence">
                       
                         {{  $chapitre->niveauCompetence }}
                    </x-field>
                    </td>
                    <td style="max-width: 12.833333333333334%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$chapitre->id}}" data-field="formateur_id"  data-toggle="tooltip" title="{{ $chapitre->formateur }}" >
                    <x-field :entity="$chapitre" field="formateur">
                       
                         {{  $chapitre->formateur }}
                    </x-field>
                    </td>
                    <td style="max-width: 12.833333333333334%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$chapitre->id}}" data-field="chapitre_officiel_id"  data-toggle="tooltip" title="{{ $chapitre->chapitreOfficiel }}" >
                    <x-field :entity="$chapitre" field="chapitreOfficiel">
                       
                         {{  $chapitre->chapitreOfficiel }}
                    </x-field>
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @can('edit-chapitre')
                        <x-action-button :entity="$chapitre" actionName="edit">
                        @can('update', $chapitre)
                            <a href="{{ route('chapitres.edit', ['chapitre' => $chapitre->id]) }}" data-id="{{$chapitre->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan
                        @can('show-chapitre')
                        <x-action-button :entity="$chapitre" actionName="show">
                        @can('view', $chapitre)
                            <a href="{{ route('chapitres.show', ['chapitre' => $chapitre->id]) }}" data-id="{{$chapitre->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan

                        <x-action-button :entity="$chapitre" actionName="delete">
                        @can('destroy-chapitre')
                        @can('delete', $chapitre)
                            <form class="context-state" action="{{ route('chapitres.destroy',['chapitre' => $chapitre->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$chapitre->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endcan
                        @endcan
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