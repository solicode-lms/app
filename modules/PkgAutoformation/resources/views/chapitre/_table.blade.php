{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('chapitre-table')
<div class="card-body table-responsive p-0 crud-card-body" id="chapitres-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-chapitre') || Auth::user()->can('destroy-chapitre');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="13.666666666666666"  field="nom" modelname="chapitre" label="{{ ucfirst(__('PkgAutoformation::chapitre.nom')) }}" />
                <x-sortable-column :sortable="true" width="13.666666666666666"  field="lien" modelname="chapitre" label="{{ ucfirst(__('PkgAutoformation::chapitre.lien')) }}" />
                <x-sortable-column :sortable="true" width="13.666666666666666" field="formation_id" modelname="chapitre" label="{{ ucfirst(__('PkgAutoformation::formation.singular')) }}" />
                <x-sortable-column :sortable="true" width="13.666666666666666" field="niveau_competence_id" modelname="chapitre" label="{{ ucfirst(__('PkgCompetences::niveauCompetence.singular')) }}" />
                <x-sortable-column :sortable="true" width="13.666666666666666" field="formateur_id" modelname="chapitre" label="{{ ucfirst(__('PkgFormation::formateur.singular')) }}" />
                <x-sortable-column :sortable="true" width="13.666666666666666" field="chapitre_officiel_id" modelname="chapitre" label="{{ ucfirst(__('PkgAutoformation::chapitre.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('chapitre-table-tbody')
            @foreach ($chapitres_data as $chapitre)
                <tr id="chapitre-row-{{$chapitre->id}}">
                    <x-checkbox-row :item="$chapitre" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 13.666666666666666%;" class="text-truncate" data-toggle="tooltip" title="{{ $chapitre->nom }}" >
                    <x-field :entity="$chapitre" field="nom">
                        {{ $chapitre->nom }}
                    </x-field>
                    </td>
                    <td style="max-width: 13.666666666666666%;" class="text-truncate" data-toggle="tooltip" title="{{ $chapitre->lien }}" >
                    <x-field :entity="$chapitre" field="lien">
                        {{ $chapitre->lien }}
                    </x-field>
                    </td>
                    <td style="max-width: 13.666666666666666%;" class="text-truncate" data-toggle="tooltip" title="{{ $chapitre->formation }}" >
                    <x-field :entity="$chapitre" field="formation">
                       
                         {{  $chapitre->formation }}
                    </x-field>
                    </td>
                    <td style="max-width: 13.666666666666666%;" class="text-truncate" data-toggle="tooltip" title="{{ $chapitre->niveauCompetence }}" >
                    <x-field :entity="$chapitre" field="niveauCompetence">
                       
                         {{  $chapitre->niveauCompetence }}
                    </x-field>
                    </td>
                    <td style="max-width: 13.666666666666666%;" class="text-truncate" data-toggle="tooltip" title="{{ $chapitre->formateur }}" >
                    <x-field :entity="$chapitre" field="formateur">
                       
                         {{  $chapitre->formateur }}
                    </x-field>
                    </td>
                    <td style="max-width: 13.666666666666666%;" class="text-truncate" data-toggle="tooltip" title="{{ $chapitre->chapitreOfficiel }}" >
                    <x-field :entity="$chapitre" field="chapitreOfficiel">
                       
                         {{  $chapitre->chapitreOfficiel }}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-chapitre')
                        @can('update', $chapitre)
                            <a href="{{ route('chapitres.edit', ['chapitre' => $chapitre->id]) }}" data-id="{{$chapitre->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-chapitre')
                        @can('view', $chapitre)
                            <a href="{{ route('chapitres.show', ['chapitre' => $chapitre->id]) }}" data-id="{{$chapitre->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-chapitre')
                        @can('delete', $chapitre)
                            <form class="context-state" action="{{ route('chapitres.destroy',['chapitre' => $chapitre->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$chapitre->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endcan
                        @endcan
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