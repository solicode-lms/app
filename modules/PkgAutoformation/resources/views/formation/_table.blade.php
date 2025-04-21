{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('formation-table')
<div class="card-body table-responsive p-0 crud-card-body" id="formations-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-formation') || Auth::user()->can('destroy-formation');
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
                    $isEditable = Auth::user()->can('edit-formation') && Auth::user()->can('update', $formation);
                @endphp
                <tr id="formation-row-{{$formation->id}}" data-id="{{$formation->id}}">
                    <x-checkbox-row :item="$formation" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$formation->id}}" data-field="nom"  data-toggle="tooltip" title="{{ $formation->nom }}" >
                    <x-field :entity="$formation" field="nom">
                        {{ $formation->nom }}
                    </x-field>
                    </td>
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$formation->id}}" data-field="competence_id"  data-toggle="tooltip" title="{{ $formation->competence }}" >
                    <x-field :entity="$formation" field="competence">
                       
                         {{  $formation->competence }}
                    </x-field>
                    </td>
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$formation->id}}" data-field="is_officiel"  data-toggle="tooltip" title="{{ $formation->is_officiel }}" >
                    <x-field :entity="$formation" field="is_officiel">
                        <span class="{{ $formation->is_officiel ? 'text-success' : 'text-danger' }}">
                            {{ $formation->is_officiel ? 'Oui' : 'Non' }}
                        </span>
                    </x-field>
                    </td>
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$formation->id}}" data-field="formateur_id"  data-toggle="tooltip" title="{{ $formation->formateur }}" >
                    <x-field :entity="$formation" field="formateur">
                       
                         {{  $formation->formateur }}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-formation')
                        @can('update', $formation)
                            <a href="{{ route('formations.edit', ['formation' => $formation->id]) }}" data-id="{{$formation->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-formation')
                        @can('view', $formation)
                            <a href="{{ route('formations.show', ['formation' => $formation->id]) }}" data-id="{{$formation->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-formation')
                        @can('delete', $formation)
                            <form class="context-state" action="{{ route('formations.destroy',['formation' => $formation->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$formation->id}}">
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
    @section('formation-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $formations_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>