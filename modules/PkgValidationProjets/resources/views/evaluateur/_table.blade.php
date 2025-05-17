{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('evaluateur-table')
<div class="card-body p-0 crud-card-body" id="evaluateurs-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-evaluateur') || Auth::user()->can('destroy-evaluateur');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="20.5"  field="nom" modelname="evaluateur" label="{{ucfirst(__('PkgValidationProjets::evaluateur.nom'))}}" />
                <x-sortable-column :sortable="true" width="20.5"  field="prenom" modelname="evaluateur" label="{{ucfirst(__('PkgValidationProjets::evaluateur.prenom'))}}" />
                <x-sortable-column :sortable="true" width="20.5"  field="organism" modelname="evaluateur" label="{{ucfirst(__('PkgValidationProjets::evaluateur.organism'))}}" />
                <x-sortable-column :sortable="true" width="20.5" field="user_id" modelname="evaluateur" label="{{ucfirst(__('PkgAutorisation::user.singular'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('evaluateur-table-tbody')
            @foreach ($evaluateurs_data as $evaluateur)
                @php
                    $isEditable = Auth::user()->can('edit-evaluateur') && Auth::user()->can('update', $evaluateur);
                @endphp
                <tr id="evaluateur-row-{{$evaluateur->id}}" data-id="{{$evaluateur->id}}">
                    <x-checkbox-row :item="$evaluateur" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$evaluateur->id}}" data-field="nom"  data-toggle="tooltip" title="{{ $evaluateur->nom }}" >
                    <x-field :entity="$evaluateur" field="nom">
                        {{ $evaluateur->nom }}
                    </x-field>
                    </td>
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$evaluateur->id}}" data-field="prenom"  data-toggle="tooltip" title="{{ $evaluateur->prenom }}" >
                    <x-field :entity="$evaluateur" field="prenom">
                        {{ $evaluateur->prenom }}
                    </x-field>
                    </td>
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$evaluateur->id}}" data-field="organism"  data-toggle="tooltip" title="{{ $evaluateur->organism }}" >
                    <x-field :entity="$evaluateur" field="organism">
                        {{ $evaluateur->organism }}
                    </x-field>
                    </td>
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$evaluateur->id}}" data-field="user_id"  data-toggle="tooltip" title="{{ $evaluateur->user }}" >
                    <x-field :entity="$evaluateur" field="user">
                       
                         {{  $evaluateur->user }}
                    </x-field>
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @can('edit-evaluateur')
                        <x-action-button :entity="$evaluateur" actionName="edit">
                        @can('update', $evaluateur)
                            <a href="{{ route('evaluateurs.edit', ['evaluateur' => $evaluateur->id]) }}" data-id="{{$evaluateur->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan
                        @can('show-evaluateur')
                        <x-action-button :entity="$evaluateur" actionName="show">
                        @can('view', $evaluateur)
                            <a href="{{ route('evaluateurs.show', ['evaluateur' => $evaluateur->id]) }}" data-id="{{$evaluateur->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan

                        <x-action-button :entity="$evaluateur" actionName="delete">
                        @can('destroy-evaluateur')
                        @can('delete', $evaluateur)
                            <form class="context-state" action="{{ route('evaluateurs.destroy',['evaluateur' => $evaluateur->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$evaluateur->id}}">
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
    @section('evaluateur-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $evaluateurs_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>