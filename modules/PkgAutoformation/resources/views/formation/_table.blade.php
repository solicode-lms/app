{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('formation-table')
<div class="card-body p-0 crud-card-body" id="formations-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $formations_permissions['edit-formation'] || $devformations_permissions['destroy-formation'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="20.5"  field="nom" modelname="formation" label="{{ucfirst(__('PkgAutoformation::formation.nom'))}}" />
                <x-sortable-column :sortable="true" width="20.5" field="competence_id" modelname="formation" label="{{ucfirst(__('PkgCompetences::competence.singular'))}}" />
                <x-sortable-column :sortable="true" width="20.5"  field="is_officiel" modelname="formation" label="{{ucfirst(__('PkgAutoformation::formation.is_officiel'))}}" />
                <x-sortable-column :sortable="true" width="20.5" field="formateur_id" modelname="formation" label="{{ucfirst(__('PkgFormation::formateur.singular'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('formation-table-tbody')
            @foreach ($formations_data as $formation)
                @php
                    $isEditable = $formations_permissions['edit-formation'] && $formations_permissionsByItem['update'][$formation->id];
                @endphp
                <tr id="formation-row-{{$formation->id}}" data-id="{{$formation->id}}">
                    <x-checkbox-row :item="$formation" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$formation->id}}" data-field="nom"  data-toggle="tooltip" title="{{ $formation->nom }}" >
                        {{ $formation->nom }}

                    </td>
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$formation->id}}" data-field="competence_id"  data-toggle="tooltip" title="{{ $formation->competence }}" >
                        {{  $formation->competence }}

                    </td>
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$formation->id}}" data-field="is_officiel"  data-toggle="tooltip" title="{{ $formation->is_officiel }}" >
                        <span class="{{ $formation->is_officiel ? 'text-success' : 'text-danger' }}">
                            {{ $formation->is_officiel ? 'Oui' : 'Non' }}
                        </span>

                    </td>
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$formation->id}}" data-field="formateur_id"  data-toggle="tooltip" title="{{ $formation->formateur }}" >
                        {{  $formation->formateur }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($formations_permissions['edit-formation'])
                        <x-action-button :entity="$formation" actionName="edit">
                        @if($formations_permissionsByItem['update'][$formation->id])
                            <a href="{{ route('formations.edit', ['formation' => $formation->id]) }}" data-id="{{$formation->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($formations_permissions['show-formation'])
                        <x-action-button :entity="$formation" actionName="show">
                        @if($formations_permissionsByItem['view'][$formation->id])
                            <a href="{{ route('formations.show', ['formation' => $formation->id]) }}" data-id="{{$formation->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$formation" actionName="delete">
                        @if($formations_permissions['destroy-formation'])
                        @if($formations_permissionsByItem['delete'][$formation->id])
                            <form class="context-state" action="{{ route('formations.destroy',['formation' => $formation->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$formation->id}}">
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
    @section('formation-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $formations_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>