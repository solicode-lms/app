{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('livrable-table')
<div class="card-body p-0 crud-card-body" id="livrables-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $livrables_permissions['edit-livrable'] || $livrables_permissions['destroy-livrable'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="41" field="nature_livrable_id" modelname="livrable" label="{!!ucfirst(__('PkgCreationProjet::natureLivrable.singular'))!!}" />
                <x-sortable-column :sortable="true" width="41"  field="titre" modelname="livrable" label="{!!ucfirst(__('PkgCreationProjet::livrable.titre'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('livrable-table-tbody')
            @foreach ($livrables_data as $livrable)
                @php
                    $isEditable = $livrables_permissions['edit-livrable'] && $livrables_permissionsByItem['update'][$livrable->id];
                @endphp
                <tr id="livrable-row-{{$livrable->id}}" data-id="{{$livrable->id}}">
                    <x-checkbox-row :item="$livrable" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 41%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$livrable->id}}" data-field="nature_livrable_id"  data-toggle="tooltip" title="{{ $livrable->natureLivrable }}" >
                        {{  $livrable->natureLivrable }}

                    </td>
                    <td style="max-width: 41%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$livrable->id}}" data-field="titre"  data-toggle="tooltip" title="{{ $livrable->titre }}" >
                        {{ $livrable->titre }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($livrables_permissions['edit-livrable'])
                        <x-action-button :entity="$livrable" actionName="edit">
                        @if($livrables_permissionsByItem['update'][$livrable->id])
                            <a href="{{ route('livrables.edit', ['livrable' => $livrable->id]) }}" data-id="{{$livrable->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($livrables_permissions['show-livrable'])
                        <x-action-button :entity="$livrable" actionName="show">
                        @if($livrables_permissionsByItem['view'][$livrable->id])
                            <a href="{{ route('livrables.show', ['livrable' => $livrable->id]) }}" data-id="{{$livrable->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$livrable" actionName="delete">
                        @if($livrables_permissions['destroy-livrable'])
                        @if($livrables_permissionsByItem['delete'][$livrable->id])
                            <form class="context-state" action="{{ route('livrables.destroy',['livrable' => $livrable->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$livrable->id}}">
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
    @section('livrable-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $livrables_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>