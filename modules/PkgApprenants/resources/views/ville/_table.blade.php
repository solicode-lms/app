{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('ville-table')
<div class="card-body p-0 crud-card-body" id="villes-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-ville') || Auth::user()->can('destroy-ville');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="82"  field="nom" modelname="ville" label="{{ucfirst(__('PkgApprenants::ville.nom'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('ville-table-tbody')
            @foreach ($villes_data as $ville)
                @php
                    $isEditable = Auth::user()->can('edit-ville') && Auth::user()->can('update', $ville);
                @endphp
                <tr id="ville-row-{{$ville->id}}" data-id="{{$ville->id}}">
                    <x-checkbox-row :item="$ville" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 82%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$ville->id}}" data-field="nom"  data-toggle="tooltip" title="{{ $ville->nom }}" >
                    <x-field :entity="$ville" field="nom">
                        {{ $ville->nom }}
                    </x-field>
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @can('edit-ville')
                        <x-action-button :entity="$ville" actionName="edit">
                        @can('update', $ville)
                            <a href="{{ route('villes.edit', ['ville' => $ville->id]) }}" data-id="{{$ville->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan
                        @can('show-ville')
                        <x-action-button :entity="$ville" actionName="show">
                        @can('view', $ville)
                            <a href="{{ route('villes.show', ['ville' => $ville->id]) }}" data-id="{{$ville->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan

                        <x-action-button :entity="$ville" actionName="delete">
                        @can('destroy-ville')
                        @can('delete', $ville)
                            <form class="context-state" action="{{ route('villes.destroy',['ville' => $ville->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$ville->id}}">
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
    @section('ville-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $villes_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>