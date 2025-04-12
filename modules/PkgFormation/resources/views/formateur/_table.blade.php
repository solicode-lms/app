{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('formateur-table')
<div class="card-body table-responsive p-0 crud-card-body" id="formateurs-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                <x-sortable-column width="21.25"  field="nom" modelname="formateur" label="{{ ucfirst(__('PkgFormation::formateur.nom')) }}" />
                <x-sortable-column width="21.25"  field="prenom" modelname="formateur" label="{{ ucfirst(__('PkgFormation::formateur.prenom')) }}" />
                <x-sortable-column width="21.25"  field="specialites" modelname="formateur" label="{{ ucfirst(__('PkgFormation::specialite.plural')) }}" />
                <x-sortable-column width="21.25"  field="groupes" modelname="formateur" label="{{ ucfirst(__('PkgApprenants::groupe.plural')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('formateur-table-tbody')
            @foreach ($formateurs_data as $formateur)
                <tr id="formateur-row-{{$formateur->id}}">
                    <td style="max-width: 21.25%;" class="text-truncate" data-toggle="tooltip" title="{{ $formateur->nom }}" >
                    <x-field :data="$formateur" field="nom">
                        {{ $formateur->nom }}
                    </x-field>
                    </td>
                    <td style="max-width: 21.25%;" class="text-truncate" data-toggle="tooltip" title="{{ $formateur->prenom }}" >
                    <x-field :data="$formateur" field="prenom">
                        {{ $formateur->prenom }}
                    </x-field>
                    </td>
                    <td style="max-width: 21.25%;" class="text-truncate" data-toggle="tooltip" title="{{ $formateur->specialites }}" >
                    <x-field :data="$formateur" field="specialites">
                        <ul>
                            @foreach ($formateur->specialites as $specialite)
                                <li @if(strlen($specialite) > 30) data-toggle="tooltip" title="{{$specialite}}"  @endif>@limit($specialite, 30)</li>
                            @endforeach
                        </ul>
                    </x-field>
                    </td>
                    <td style="max-width: 21.25%;" class="text-truncate" data-toggle="tooltip" title="{{ $formateur->groupes }}" >
                    <x-field :data="$formateur" field="groupes">
                        <ul>
                            @foreach ($formateur->groupes as $groupe)
                                <li @if(strlen($groupe) > 30) data-toggle="tooltip" title="{{$groupe}}"  @endif>@limit($groupe, 30)</li>
                            @endforeach
                        </ul>
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">
                       @can('initPassword-formateur')
                        <a 
                        data-toggle="tooltip" 
                        title="Initialiser le mot de passe" 
                        href="{{ route('formateurs.initPassword', ['id' => $formateur->id]) }}" 
                        data-id="{{$formateur->id}}" 
                        data-url="{{ route('formateurs.initPassword', ['id' => $formateur->id]) }}" 
                        data-action-type="confirm"
                        class="btn btn-default btn-sm context-state actionEntity">
                            <i class="fas fa-unlock-alt"></i>
                        </a>
                        @endcan
                        

                       

                        @can('edit-formateur')
                        @can('update', $formateur)
                            <a href="{{ route('formateurs.edit', ['formateur' => $formateur->id]) }}" data-id="{{$formateur->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-formateur')
                        @can('view', $formateur)
                            <a href="{{ route('formateurs.show', ['formateur' => $formateur->id]) }}" data-id="{{$formateur->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-formateur')
                        @can('delete', $formateur)
                            <form class="context-state" action="{{ route('formateurs.destroy',['formateur' => $formateur->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$formateur->id}}">
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
    @section('formateur-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $formateurs_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>