{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('niveauCompetence-table')
<div class="card-body p-0 crud-card-body" id="niveauCompetences-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $niveauCompetences_permissions['edit-niveauCompetence'] || $niveauCompetences_permissions['destroy-niveauCompetence'];
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
                    $isEditable = $niveauCompetences_permissions['edit-niveauCompetence'] && $niveauCompetences_permissionsByItem['update'][$niveauCompetence->id];
                @endphp
                <tr id="niveauCompetence-row-{{$niveauCompetence->id}}" data-id="{{$niveauCompetence->id}}">
                    <x-checkbox-row :item="$niveauCompetence" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 41%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$niveauCompetence->id}}" data-field="nom"  data-toggle="tooltip" title="{{ $niveauCompetence->nom }}" >
                        {{ $niveauCompetence->nom }}

                    </td>
                    <td style="max-width: 41%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$niveauCompetence->id}}" data-field="competence_id"  data-toggle="tooltip" title="{{ $niveauCompetence->competence }}" >
                        {{  $niveauCompetence->competence }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($niveauCompetences_permissions['edit-niveauCompetence'])
                        <x-action-button :entity="$niveauCompetence" actionName="edit">
                        @if($niveauCompetences_permissionsByItem['update'][$niveauCompetence->id])
                            <a href="{{ route('niveauCompetences.edit', ['niveauCompetence' => $niveauCompetence->id]) }}" data-id="{{$niveauCompetence->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($niveauCompetences_permissions['show-niveauCompetence'])
                        <x-action-button :entity="$niveauCompetence" actionName="show">
                        @if($niveauCompetences_permissionsByItem['view'][$niveauCompetence->id])
                            <a href="{{ route('niveauCompetences.show', ['niveauCompetence' => $niveauCompetence->id]) }}" data-id="{{$niveauCompetence->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$niveauCompetence" actionName="delete">
                        @if($niveauCompetences_permissions['destroy-niveauCompetence'])
                        @if($niveauCompetences_permissionsByItem['delete'][$niveauCompetence->id])
                            <form class="context-state" action="{{ route('niveauCompetences.destroy',['niveauCompetence' => $niveauCompetence->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$niveauCompetence->id}}">
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
    @section('niveauCompetence-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $niveauCompetences_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>