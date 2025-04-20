{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('ville-table')
<div class="card-body table-responsive p-0 crud-card-body" id="villes-crud-card-body">
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
                <tr id="ville-row-{{$ville->id}}" data-id="{{$ville->id}}">
                    <x-checkbox-row :item="$ville" :bulkEdit="$bulkEdit" />
                    <td class="editable-cell text-truncate" 
                        data-id="{{ $ville->id }}" 
                        data-field="nom"
                        title="{{ $ville->nom }}" 
                        data-toggle="tooltip" 
                        style="max-width: 82%;">
                    <x-field :entity="$ville" field="nom">
                        {{ $ville->nom }}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-ville')
                        @can('update', $ville)
                            <a href="{{ route('villes.edit', ['ville' => $ville->id]) }}" data-id="{{$ville->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-ville')
                        @can('view', $ville)
                            <a href="{{ route('villes.show', ['ville' => $ville->id]) }}" data-id="{{$ville->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-ville')
                        @can('delete', $ville)
                            <form class="context-state" action="{{ route('villes.destroy',['ville' => $ville->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$ville->id}}">
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
    @section('ville-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $villes_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>