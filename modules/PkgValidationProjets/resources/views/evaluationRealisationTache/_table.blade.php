{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('evaluationRealisationTache-table')
<div class="card-body p-0 crud-card-body" id="evaluationRealisationTaches-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-evaluationRealisationTache') || Auth::user()->can('destroy-evaluationRealisationTache');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="20.5" field="realisation_tache_id" modelname="evaluationRealisationTache" label="{{ucfirst(__('PkgGestionTaches::realisationTache.singular'))}}" />
                <x-sortable-column :sortable="true" width="20.5" field="evaluateur_id" modelname="evaluationRealisationTache" label="{{ucfirst(__('PkgValidationProjets::evaluateur.singular'))}}" />
                <x-sortable-column :sortable="true" width="20.5"  field="note" modelname="evaluationRealisationTache" label="{{ucfirst(__('PkgValidationProjets::evaluationRealisationTache.note'))}}" />
                <x-sortable-column :sortable="false" width="20.5"  field="message" modelname="evaluationRealisationTache" label="{{ucfirst(__('PkgValidationProjets::evaluationRealisationTache.message'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('evaluationRealisationTache-table-tbody')
            @foreach ($evaluationRealisationTaches_data as $evaluationRealisationTache)
                @php
                    $isEditable = Auth::user()->can('edit-evaluationRealisationTache') && Auth::user()->can('update', $evaluationRealisationTache);
                @endphp
                <tr id="evaluationRealisationTache-row-{{$evaluationRealisationTache->id}}" data-id="{{$evaluationRealisationTache->id}}">
                    <x-checkbox-row :item="$evaluationRealisationTache" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$evaluationRealisationTache->id}}" data-field="realisation_tache_id"  data-toggle="tooltip" title="{{ $evaluationRealisationTache->realisationTache }}" >
                    <x-field :entity="$evaluationRealisationTache" field="realisationTache">
                       
                         {{  $evaluationRealisationTache->realisationTache }}
                    </x-field>
                    </td>
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$evaluationRealisationTache->id}}" data-field="evaluateur_id"  data-toggle="tooltip" title="{{ $evaluationRealisationTache->evaluateur }}" >
                    <x-field :entity="$evaluationRealisationTache" field="evaluateur">
                       
                         {{  $evaluationRealisationTache->evaluateur }}
                    </x-field>
                    </td>
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$evaluationRealisationTache->id}}" data-field="note"  data-toggle="tooltip" title="{{ $evaluationRealisationTache->note }}" >
                    <x-field :entity="$evaluationRealisationTache" field="note">
                        {{ $evaluationRealisationTache->note }}
                    </x-field>
                    </td>
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$evaluationRealisationTache->id}}" data-field="message"  data-toggle="tooltip" title="{{ $evaluationRealisationTache->message }}" >
                    <x-field :entity="$evaluationRealisationTache" field="message">
                        {!! \App\Helpers\TextHelper::formatHtmlWithLineBreaks($evaluationRealisationTache->message, 30) !!}
                    </x-field>
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @can('edit-evaluationRealisationTache')
                        <x-action-button :entity="$evaluationRealisationTache" actionName="edit">
                        @can('update', $evaluationRealisationTache)
                            <a href="{{ route('evaluationRealisationTaches.edit', ['evaluationRealisationTache' => $evaluationRealisationTache->id]) }}" data-id="{{$evaluationRealisationTache->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan
                        @can('show-evaluationRealisationTache')
                        <x-action-button :entity="$evaluationRealisationTache" actionName="show">
                        @can('view', $evaluationRealisationTache)
                            <a href="{{ route('evaluationRealisationTaches.show', ['evaluationRealisationTache' => $evaluationRealisationTache->id]) }}" data-id="{{$evaluationRealisationTache->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan

                        <x-action-button :entity="$evaluationRealisationTache" actionName="delete">
                        @can('destroy-evaluationRealisationTache')
                        @can('delete', $evaluationRealisationTache)
                            <form class="context-state" action="{{ route('evaluationRealisationTaches.destroy',['evaluationRealisationTache' => $evaluationRealisationTache->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$evaluationRealisationTache->id}}">
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
    @section('evaluationRealisationTache-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $evaluationRealisationTaches_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>