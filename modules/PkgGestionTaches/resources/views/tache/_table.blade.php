{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('tache-table')
<div class="card-body p-0 crud-card-body" id="taches-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-tache') || Auth::user()->can('destroy-tache');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="5"  field="ordre" modelname="tache" label="{{ucfirst(__('PkgGestionTaches::tache.ordre'))}}" />
                <x-sortable-column :sortable="true" width="20"  field="titre" modelname="tache" label="{{ucfirst(__('PkgGestionTaches::tache.titre'))}}" />
                <x-sortable-column :sortable="true" width="8" field="priorite_tache_id" modelname="tache" label="{{ucfirst(__('PkgGestionTaches::tache.priorite_tache_id'))}}" />
                <x-sortable-column :sortable="true" width="16.333333333333332" field="projet_id" modelname="tache" label="{{ucfirst(__('PkgCreationProjet::projet.singular'))}}" />
                <x-sortable-column :sortable="true" width="16.333333333333332"  field="dateFin" modelname="tache" label="{{ucfirst(__('PkgGestionTaches::tache.dateFin'))}}" />
                <x-sortable-column :sortable="true" width="16.333333333333332"  field="livrables" modelname="tache" label="{{ucfirst(__('PkgCreationProjet::livrable.plural'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('tache-table-tbody')
            @foreach ($taches_data as $tache)
                @php
                    $isEditable = Auth::user()->can('edit-tache') && Auth::user()->can('update', $tache);
                @endphp
                <tr id="tache-row-{{$tache->id}}" data-id="{{$tache->id}}">
                    <x-checkbox-row :item="$tache" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$tache->id}}" data-field="ordre"  data-toggle="tooltip" title="{{ $tache->ordre }}" >
                    <x-field :entity="$tache" field="ordre">
                         <div class="sortable-button d-flex justify-content-left align-items-center" style="height: 100%;  min-height: 26px;">
                            <i class="fas fa-th-list" title="{{ $tache->ordre }}"  data-toggle="tooltip" ></i>  
                        </div>
                    </x-field>
                    </td>
                    <td style="max-width: 20%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$tache->id}}" data-field="titre"  data-toggle="tooltip" title="{{ $tache->titre }}" >
                    <x-field :entity="$tache" field="titre">
                        {{ $tache->titre }}
                    </x-field>
                    </td>
                    <td style="max-width: 8%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$tache->id}}" data-field="priorite_tache_id"  data-toggle="tooltip" title="{{ $tache->prioriteTache }}" >
                    <x-field :entity="$tache" field="prioriteTache">
                       
                         {{  $tache->prioriteTache }}
                    </x-field>
                    </td>
                    <td style="max-width: 16.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$tache->id}}" data-field="projet_id"  data-toggle="tooltip" title="{{ $tache->projet }}" >
                    <x-field :entity="$tache" field="projet">
                       
                         {{  $tache->projet }}
                    </x-field>
                    </td>
                    <td style="max-width: 16.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$tache->id}}" data-field="dateFin"  data-toggle="tooltip" title="{{ $tache->dateFin }}" >
                    <x-field :entity="$tache" field="dateFin">
                        <x-deadline-display :value="$tache->dateFin" />
                    </x-field>
                    </td>
                    <td style="max-width: 16.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$tache->id}}" data-field="livrables"  data-toggle="tooltip" title="{{ $tache->livrables }}" >
                    <x-field :entity="$tache" field="livrables">
                        <ul>
                            @foreach ($tache->livrables as $livrable)
                                <li @if(strlen($livrable) > 30) data-toggle="tooltip" title="{{$livrable}}"  @endif>@limit($livrable, 30)</li>
                            @endforeach
                        </ul>
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-tache')
                        <x-action-button :entity="$tache" actionName="edit">
                        @can('update', $tache)
                            <a href="{{ route('taches.edit', ['tache' => $tache->id]) }}" data-id="{{$tache->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @elsecan('show-tache')
                        <x-action-button :entity="$tache" actionName="show">
                        @can('view', $tache)
                            <a href="{{ route('taches.show', ['tache' => $tache->id]) }}" data-id="{{$tache->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan

                        <x-action-button :entity="$tache" actionName="delete">
                        @can('destroy-tache')
                        @can('delete', $tache)
                            <form class="context-state" action="{{ route('taches.destroy',['tache' => $tache->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$tache->id}}">
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
    @section('tache-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $taches_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>