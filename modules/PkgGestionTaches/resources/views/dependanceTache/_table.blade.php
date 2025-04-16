{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('dependanceTache-table')
<div class="card-body table-responsive p-0 crud-card-body" id="dependanceTaches-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-dependanceTache') || Auth::user()->can('destroy-dependanceTache');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column width="27.333333333333332" field="tache_id" modelname="dependanceTache" label="{{ ucfirst(__('PkgGestionTaches::tache.singular')) }}" />
                <x-sortable-column width="27.333333333333332" field="type_dependance_tache_id" modelname="dependanceTache" label="{{ ucfirst(__('PkgGestionTaches::typeDependanceTache.singular')) }}" />
                <x-sortable-column width="27.333333333333332" field="tache_cible_id" modelname="dependanceTache" label="{{ ucfirst(__('PkgGestionTaches::tache.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('dependanceTache-table-tbody')
            @foreach ($dependanceTaches_data as $dependanceTache)
                <tr id="dependanceTache-row-{{$dependanceTache->id}}">
                    <x-checkbox-row :item="$dependanceTache" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 27.333333333333332%;" class="text-truncate" data-toggle="tooltip" title="{{ $dependanceTache->tache }}" >
                    <x-field :entity="$dependanceTache" field="tache">
                       
                         {{  $dependanceTache->tache }}
                    </x-field>
                    </td>
                    <td style="max-width: 27.333333333333332%;" class="text-truncate" data-toggle="tooltip" title="{{ $dependanceTache->typeDependanceTache }}" >
                    <x-field :entity="$dependanceTache" field="typeDependanceTache">
                       
                         {{  $dependanceTache->typeDependanceTache }}
                    </x-field>
                    </td>
                    <td style="max-width: 27.333333333333332%;" class="text-truncate" data-toggle="tooltip" title="{{ $dependanceTache->tacheCible }}" >
                    <x-field :entity="$dependanceTache" field="tacheCible">
                       
                         {{  $dependanceTache->tacheCible }}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-dependanceTache')
                        @can('update', $dependanceTache)
                            <a href="{{ route('dependanceTaches.edit', ['dependanceTache' => $dependanceTache->id]) }}" data-id="{{$dependanceTache->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-dependanceTache')
                        @can('view', $dependanceTache)
                            <a href="{{ route('dependanceTaches.show', ['dependanceTache' => $dependanceTache->id]) }}" data-id="{{$dependanceTache->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-dependanceTache')
                        @can('delete', $dependanceTache)
                            <form class="context-state" action="{{ route('dependanceTaches.destroy',['dependanceTache' => $dependanceTache->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$dependanceTache->id}}">
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
    @section('dependanceTache-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $dependanceTaches_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>