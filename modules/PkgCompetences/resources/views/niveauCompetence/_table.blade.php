{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('niveauCompetence-table')
<div class="card-body p-0 crud-card-body" id="niveauCompetences-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-niveauCompetence') || Auth::user()->can('destroy-niveauCompetence');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="41"  field="nom" modelname="niveauCompetence" label="{{ucfirst(__('PkgCompetences::niveauCompetence.nom'))}}" />
                <x-sortable-column :sortable="true" width="41" field="competence_id" modelname="niveauCompetence" label="{{ucfirst(__('PkgCompetences::competence.singular'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('niveauCompetence-table-tbody')
            @foreach ($niveauCompetences_data as $niveauCompetence)
                @php
                    $isEditable = Auth::user()->can('edit-niveauCompetence') && Auth::user()->can('update', $niveauCompetence);
                @endphp
                <tr id="niveauCompetence-row-{{$niveauCompetence->id}}" data-id="{{$niveauCompetence->id}}">
                    <x-checkbox-row :item="$niveauCompetence" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 41%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$niveauCompetence->id}}" data-field="nom"  data-toggle="tooltip" title="{{ $niveauCompetence->nom }}" >
                    <x-field :entity="$niveauCompetence" field="nom">
                        {{ $niveauCompetence->nom }}
                    </x-field>
                    </td>
                    <td style="max-width: 41%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$niveauCompetence->id}}" data-field="competence_id"  data-toggle="tooltip" title="{{ $niveauCompetence->competence }}" >
                    <x-field :entity="$niveauCompetence" field="competence">
                       
                         {{  $niveauCompetence->competence }}
                    </x-field>
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @can('edit-niveauCompetence')
                        <x-action-button :entity="$niveauCompetence" actionName="edit">
                        @can('update', $niveauCompetence)
                            <a href="{{ route('niveauCompetences.edit', ['niveauCompetence' => $niveauCompetence->id]) }}" data-id="{{$niveauCompetence->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan
                        @can('show-niveauCompetence')
                        <x-action-button :entity="$niveauCompetence" actionName="show">
                        @can('view', $niveauCompetence)
                            <a href="{{ route('niveauCompetences.show', ['niveauCompetence' => $niveauCompetence->id]) }}" data-id="{{$niveauCompetence->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan

                        <x-action-button :entity="$niveauCompetence" actionName="delete">
                        @can('destroy-niveauCompetence')
                        @can('delete', $niveauCompetence)
                            <form class="context-state" action="{{ route('niveauCompetences.destroy',['niveauCompetence' => $niveauCompetence->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$niveauCompetence->id}}">
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
    @section('niveauCompetence-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $niveauCompetences_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>