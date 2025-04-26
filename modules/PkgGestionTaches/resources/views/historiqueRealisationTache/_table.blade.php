{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('historiqueRealisationTache-table')
<div class="card-body p-0 crud-card-body" id="historiqueRealisationTaches-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-historiqueRealisationTache') || Auth::user()->can('destroy-historiqueRealisationTache');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="82" field="realisation_tache_id" modelname="historiqueRealisationTache" label="{{ucfirst(__('PkgGestionTaches::realisationTache.singular'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('historiqueRealisationTache-table-tbody')
            @foreach ($historiqueRealisationTaches_data as $historiqueRealisationTache)
                @php
                    $isEditable = Auth::user()->can('edit-historiqueRealisationTache') && Auth::user()->can('update', $historiqueRealisationTache);
                @endphp
                <tr id="historiqueRealisationTache-row-{{$historiqueRealisationTache->id}}" data-id="{{$historiqueRealisationTache->id}}">
                    <x-checkbox-row :item="$historiqueRealisationTache" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 82%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$historiqueRealisationTache->id}}" data-field="realisation_tache_id"  data-toggle="tooltip" title="{{ $historiqueRealisationTache->realisationTache }}" >
                    <x-field :entity="$historiqueRealisationTache" field="realisationTache">
                       
                         {{  $historiqueRealisationTache->realisationTache }}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-historiqueRealisationTache')
                        @can('update', $historiqueRealisationTache)
                            <a href="{{ route('historiqueRealisationTaches.edit', ['historiqueRealisationTache' => $historiqueRealisationTache->id]) }}" data-id="{{$historiqueRealisationTache->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-historiqueRealisationTache')
                        @can('view', $historiqueRealisationTache)
                            <a href="{{ route('historiqueRealisationTaches.show', ['historiqueRealisationTache' => $historiqueRealisationTache->id]) }}" data-id="{{$historiqueRealisationTache->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-historiqueRealisationTache')
                        @can('delete', $historiqueRealisationTache)
                            <form class="context-state" action="{{ route('historiqueRealisationTaches.destroy',['historiqueRealisationTache' => $historiqueRealisationTache->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$historiqueRealisationTache->id}}">
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
    @section('historiqueRealisationTache-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $historiqueRealisationTaches_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>