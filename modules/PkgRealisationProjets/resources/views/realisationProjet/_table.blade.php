{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationProjet-table')
<div class="card-body table-responsive p-0 crud-card-body" id="realisationProjets-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-realisationProjet') || Auth::user()->can('destroy-realisationProjet');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="16.4" field="affectation_projet_id" modelname="realisationProjet" label="{{ucfirst(__('PkgRealisationProjets::affectationProjet.singular'))}}" />
                <x-sortable-column :sortable="true" width="16.4" field="apprenant_id" modelname="realisationProjet" label="{{ucfirst(__('PkgApprenants::apprenant.singular'))}}" />
                <x-sortable-column :sortable="true" width="16.4" field="etats_realisation_projet_id" modelname="realisationProjet" label="{{ucfirst(__('PkgRealisationProjets::etatsRealisationProjet.singular'))}}" />
                <x-sortable-column :sortable="true" width="16.4"  field="avancement_projet" modelname="realisationProjet" label="{{ucfirst(__('PkgRealisationProjets::realisationProjet.avancement_projet'))}}" />
                <x-sortable-column :sortable="true" width="16.4"  field="LivrablesRealisation" modelname="realisationProjet" label="{{ucfirst(__('PkgRealisationProjets::livrablesRealisation.plural'))}}" />

                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('realisationProjet-table-tbody')
            @foreach ($realisationProjets_data as $realisationProjet)
                @php
                    $isEditable = Auth::user()->can('edit-realisationProjet') && Auth::user()->can('update', $realisationProjet);
                @endphp
                <tr id="realisationProjet-row-{{$realisationProjet->id}}" data-id="{{$realisationProjet->id}}">
                    <x-checkbox-row :item="$realisationProjet" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 16.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationProjet->id}}" data-field="affectation_projet_id"  data-toggle="tooltip" title="{{ $realisationProjet->affectationProjet }}" >
                    <x-field :entity="$realisationProjet" field="affectationProjet">
                       
                         {{  $realisationProjet->affectationProjet }}
                    </x-field>
                    </td>
                    <td style="max-width: 16.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationProjet->id}}" data-field="apprenant_id"  data-toggle="tooltip" title="{{ $realisationProjet->apprenant }}" >
                    <x-field :entity="$realisationProjet" field="apprenant">
                       
                         {{  $realisationProjet->apprenant }}
                    </x-field>
                    </td>
                    <td style="max-width: 16.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationProjet->id}}" data-field="etats_realisation_projet_id"  data-toggle="tooltip" title="{{ $realisationProjet->etatsRealisationProjet }}" >
                    <x-field :entity="$realisationProjet" field="etatsRealisationProjet">
                        @if(!empty($realisationProjet->etatsRealisationProjet))
                        <x-badge 
                        :text="$realisationProjet->etatsRealisationProjet" 
                        :background="$realisationProjet->etatsRealisationProjet->sysColor->hex ?? '#6c757d'" 
                        />
                        @endif
                    </x-field>
                    </td>
                    <td style="max-width: 16.4%;" class=" text-truncate" data-id="{{$realisationProjet->id}}" data-field="avancement_projet"  data-toggle="tooltip" title="{{ $realisationProjet->avancement_projet }}" >
                    <x-field :entity="$realisationProjet" field="avancement_projet">
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-green" role="progressbar" aria-valuenow="{{ $realisationProjet->avancement_projet }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $realisationProjet->avancement_projet }}%">
                            </div>
                        </div>
                        <small>
                            {{ $realisationProjet->avancement_projet }}% Terminé
                        </small>
                    </x-field>
                    </td>
                    <td style="max-width: 16.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationProjet->id}}" data-field="LivrablesRealisation"  data-toggle="tooltip" title="{{ $realisationProjet->livrablesRealisations }}" >
                    <x-field :entity="$realisationProjet" field="livrablesRealisations">
                        <ul>
                            @foreach ($realisationProjet->livrablesRealisations as $livrablesRealisation)
                                <li>{{$livrablesRealisation}} </li>
                            @endforeach
                        </ul>
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-realisationProjet')
                        @can('update', $realisationProjet)
                            <a href="{{ route('realisationProjets.edit', ['realisationProjet' => $realisationProjet->id]) }}" data-id="{{$realisationProjet->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-realisationProjet')
                        @can('view', $realisationProjet)
                            <a href="{{ route('realisationProjets.show', ['realisationProjet' => $realisationProjet->id]) }}" data-id="{{$realisationProjet->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-realisationProjet')
                        @can('delete', $realisationProjet)
                            <form class="context-state" action="{{ route('realisationProjets.destroy',['realisationProjet' => $realisationProjet->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$realisationProjet->id}}">
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
    @section('realisationProjet-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $realisationProjets_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>