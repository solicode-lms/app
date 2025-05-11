{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('realisationChapitre-table')
<div class="card-body p-0 crud-card-body" id="realisationChapitres-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-realisationChapitre') || Auth::user()->can('destroy-realisationChapitre');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="16.4"  field="date_debut" modelname="realisationChapitre" label="{{ucfirst(__('PkgAutoformation::realisationChapitre.date_debut'))}}" />
                <x-sortable-column :sortable="true" width="16.4"  field="date_fin" modelname="realisationChapitre" label="{{ucfirst(__('PkgAutoformation::realisationChapitre.date_fin'))}}" />
                <x-sortable-column :sortable="true" width="16.4" field="chapitre_id" modelname="realisationChapitre" label="{{ucfirst(__('PkgAutoformation::chapitre.singular'))}}" />
                <x-sortable-column :sortable="true" width="16.4" field="realisation_formation_id" modelname="realisationChapitre" label="{{ucfirst(__('PkgAutoformation::realisationFormation.singular'))}}" />
                <x-sortable-column :sortable="true" width="16.4" field="etat_chapitre_id" modelname="realisationChapitre" label="{{ucfirst(__('PkgAutoformation::etatChapitre.singular'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('realisationChapitre-table-tbody')
            @foreach ($realisationChapitres_data as $realisationChapitre)
                @php
                    $isEditable = Auth::user()->can('edit-realisationChapitre') && Auth::user()->can('update', $realisationChapitre);
                @endphp
                <tr id="realisationChapitre-row-{{$realisationChapitre->id}}" data-id="{{$realisationChapitre->id}}">
                    <x-checkbox-row :item="$realisationChapitre" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 16.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationChapitre->id}}" data-field="date_debut"  data-toggle="tooltip" title="{{ $realisationChapitre->date_debut }}" >
                    <x-field :entity="$realisationChapitre" field="date_debut">
                        {{ $realisationChapitre->date_debut }}
                    </x-field>
                    </td>
                    <td style="max-width: 16.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationChapitre->id}}" data-field="date_fin"  data-toggle="tooltip" title="{{ $realisationChapitre->date_fin }}" >
                    <x-field :entity="$realisationChapitre" field="date_fin">
                        {{ $realisationChapitre->date_fin }}
                    </x-field>
                    </td>
                    <td style="max-width: 16.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationChapitre->id}}" data-field="chapitre_id"  data-toggle="tooltip" title="{{ $realisationChapitre->chapitre }}" >
                    <x-field :entity="$realisationChapitre" field="chapitre">
                       
                         {{  $realisationChapitre->chapitre }}
                    </x-field>
                    </td>
                    <td style="max-width: 16.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationChapitre->id}}" data-field="realisation_formation_id"  data-toggle="tooltip" title="{{ $realisationChapitre->realisationFormation }}" >
                    <x-field :entity="$realisationChapitre" field="realisationFormation">
                       
                         {{  $realisationChapitre->realisationFormation }}
                    </x-field>
                    </td>
                    <td style="max-width: 16.4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$realisationChapitre->id}}" data-field="etat_chapitre_id"  data-toggle="tooltip" title="{{ $realisationChapitre->etatChapitre }}" >
                    <x-field :entity="$realisationChapitre" field="etatChapitre">
                        @if(!empty($realisationChapitre->etatChapitre))
                        <x-badge 
                        :text="$realisationChapitre->etatChapitre" 
                        :background="$realisationChapitre->etatChapitre->sysColor->hex ?? '#6c757d'" 
                        />
                        @endif
                    </x-field>
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @can('edit-realisationChapitre')
                        <x-action-button :entity="$realisationChapitre" actionName="edit">
                        @can('update', $realisationChapitre)
                            <a href="{{ route('realisationChapitres.edit', ['realisationChapitre' => $realisationChapitre->id]) }}" data-id="{{$realisationChapitre->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan
                        @can('show-realisationChapitre')
                        <x-action-button :entity="$realisationChapitre" actionName="show">
                        @can('view', $realisationChapitre)
                            <a href="{{ route('realisationChapitres.show', ['realisationChapitre' => $realisationChapitre->id]) }}" data-id="{{$realisationChapitre->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan

                        <x-action-button :entity="$realisationChapitre" actionName="delete">
                        @can('destroy-realisationChapitre')
                        @can('delete', $realisationChapitre)
                            <form class="context-state" action="{{ route('realisationChapitres.destroy',['realisationChapitre' => $realisationChapitre->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$realisationChapitre->id}}">
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
    @section('realisationChapitre-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $realisationChapitres_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>