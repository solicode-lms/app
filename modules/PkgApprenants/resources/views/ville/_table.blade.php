{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('ville-table')
<div class="card-body p-0 crud-card-body" id="villes-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $villes_permissions['edit-ville'] || $villes_permissions['destroy-ville'];
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
                    $isEditable = $villes_permissions['edit-ville'] && $villes_permissionsByItem['update'][$ville->id];
                @endphp
                <tr id="ville-row-{{$ville->id}}" data-id="{{$ville->id}}">
                    <x-checkbox-row :item="$ville" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 82%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$ville->id}}" data-field="nom"  data-toggle="tooltip" title="{{ $ville->nom }}" >
                        {{ $ville->nom }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($villes_permissions['edit-ville'])
                        <x-action-button :entity="$ville" actionName="edit">
                        @if($villes_permissionsByItem['update'][$ville->id])
                            <a href="{{ route('villes.edit', ['ville' => $ville->id]) }}" data-id="{{$ville->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($villes_permissions['show-ville'])
                        <x-action-button :entity="$ville" actionName="show">
                        @if($villes_permissionsByItem['view'][$ville->id])
                            <a href="{{ route('villes.show', ['ville' => $ville->id]) }}" data-id="{{$ville->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$ville" actionName="delete">
                        @if($villes_permissions['destroy-ville'])
                        @if($villes_permissionsByItem['delete'][$ville->id])
                            <form class="context-state" action="{{ route('villes.destroy',['ville' => $ville->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$ville->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                        @endif
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