{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('typeDependanceTache-table')
<div class="card-body p-0 crud-card-body" id="typeDependanceTaches-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-typeDependanceTache') || Auth::user()->can('destroy-typeDependanceTache');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="82"  field="titre" modelname="typeDependanceTache" label="{{ucfirst(__('PkgGestionTaches::typeDependanceTache.titre'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('typeDependanceTache-table-tbody')
            @foreach ($typeDependanceTaches_data as $typeDependanceTache)
                @php
                    $isEditable = Auth::user()->can('edit-typeDependanceTache') && Auth::user()->can('update', $typeDependanceTache);
                @endphp
                <tr id="typeDependanceTache-row-{{$typeDependanceTache->id}}" data-id="{{$typeDependanceTache->id}}">
                    <x-checkbox-row :item="$typeDependanceTache" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 82%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$typeDependanceTache->id}}" data-field="titre"  data-toggle="tooltip" title="{{ $typeDependanceTache->titre }}" >
                    <x-field :entity="$typeDependanceTache" field="titre">
                        {{ $typeDependanceTache->titre }}
                    </x-field>
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @can('edit-typeDependanceTache')
                        <x-action-button :entity="$typeDependanceTache" actionName="edit">
                        @can('update', $typeDependanceTache)
                            <a href="{{ route('typeDependanceTaches.edit', ['typeDependanceTache' => $typeDependanceTache->id]) }}" data-id="{{$typeDependanceTache->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan
                        @can('show-typeDependanceTache')
                        <x-action-button :entity="$typeDependanceTache" actionName="show">
                        @can('view', $typeDependanceTache)
                            <a href="{{ route('typeDependanceTaches.show', ['typeDependanceTache' => $typeDependanceTache->id]) }}" data-id="{{$typeDependanceTache->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan

                        <x-action-button :entity="$typeDependanceTache" actionName="delete">
                        @can('destroy-typeDependanceTache')
                        @can('delete', $typeDependanceTache)
                            <form class="context-state" action="{{ route('typeDependanceTaches.destroy',['typeDependanceTache' => $typeDependanceTache->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$typeDependanceTache->id}}">
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
    @section('typeDependanceTache-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $typeDependanceTaches_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>