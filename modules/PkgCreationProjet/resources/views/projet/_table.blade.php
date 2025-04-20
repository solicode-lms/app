{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('projet-table')
<div class="card-body table-responsive p-0 crud-card-body" id="projets-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-projet') || Auth::user()->can('destroy-projet');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="35"  field="titre" modelname="projet" label="{{ucfirst(__('PkgCreationProjet::projet.titre'))}}" />
                <x-sortable-column :sortable="true" width="30"  field="Tache" modelname="projet" label="{{ucfirst(__('PkgGestionTaches::tache.plural'))}}" />

                <x-sortable-column :sortable="true" width="17"  field="Livrable" modelname="projet" label="{{ucfirst(__('PkgCreationProjet::livrable.plural'))}}" />

                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('projet-table-tbody')
            @foreach ($projets_data as $projet)
                <tr id="projet-row-{{$projet->id}}" data-id="{{$projet->id}}">
                    <x-checkbox-row :item="$projet" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 35%;" class="text-truncate" data-toggle="tooltip" title="{{ $projet->titre }}" >
                    <x-field :entity="$projet" field="titre">
                        {{ $projet->titre }}
                    </x-field>
                    </td>
                    <td style="max-width: 30%;" class="text-truncate" data-toggle="tooltip" title="{{ $projet->taches }}" >
                    <x-field :entity="$projet" field="taches">
                        <ul>
                            @foreach ($projet->taches as $tache)
                                <li>{{$tache}} </li>
                            @endforeach
                        </ul>
                    </x-field>
                    </td>
                    <td style="max-width: 17%;" class="text-truncate" data-toggle="tooltip" title="{{ $projet->livrables }}" >
                    <x-field :entity="$projet" field="livrables">
                        <ul>
                            @foreach ($projet->livrables as $livrable)
                                <li>{{$livrable}} </li>
                            @endforeach
                        </ul>
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-projet')
                        @can('update', $projet)
                            <a href="{{ route('projets.edit', ['projet' => $projet->id]) }}" data-id="{{$projet->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-projet')
                        @can('view', $projet)
                            <a href="{{ route('projets.show', ['projet' => $projet->id]) }}" data-id="{{$projet->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-projet')
                        @can('delete', $projet)
                            <form class="context-state" action="{{ route('projets.destroy',['projet' => $projet->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$projet->id}}">
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
    @section('projet-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $projets_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>