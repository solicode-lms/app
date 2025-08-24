{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('niveauxScolaire-table')
<div class="card-body p-0 crud-card-body" id="niveauxScolaires-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $niveauxScolaires_permissions['edit-niveauxScolaire'] || $niveauxScolaires_permissions['destroy-niveauxScolaire'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="82"  field="code" modelname="niveauxScolaire" label="{!!ucfirst(__('PkgApprenants::niveauxScolaire.code'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('niveauxScolaire-table-tbody')
            @foreach ($niveauxScolaires_data as $niveauxScolaire)
                @php
                    $isEditable = $niveauxScolaires_permissions['edit-niveauxScolaire'] && $niveauxScolaires_permissionsByItem['update'][$niveauxScolaire->id];
                @endphp
                <tr id="niveauxScolaire-row-{{$niveauxScolaire->id}}" data-id="{{$niveauxScolaire->id}}">
                    <x-checkbox-row :item="$niveauxScolaire" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 82%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$niveauxScolaire->id}}" data-field="code">
                        {{ $niveauxScolaire->code }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($niveauxScolaires_permissions['edit-niveauxScolaire'])
                        <x-action-button :entity="$niveauxScolaire" actionName="edit">
                        @if($niveauxScolaires_permissionsByItem['update'][$niveauxScolaire->id])
                            <a href="{{ route('niveauxScolaires.edit', ['niveauxScolaire' => $niveauxScolaire->id]) }}" data-id="{{$niveauxScolaire->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($niveauxScolaires_permissions['show-niveauxScolaire'])
                        <x-action-button :entity="$niveauxScolaire" actionName="show">
                        @if($niveauxScolaires_permissionsByItem['view'][$niveauxScolaire->id])
                            <a href="{{ route('niveauxScolaires.show', ['niveauxScolaire' => $niveauxScolaire->id]) }}" data-id="{{$niveauxScolaire->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$niveauxScolaire" actionName="delete">
                        @if($niveauxScolaires_permissions['destroy-niveauxScolaire'])
                        @if($niveauxScolaires_permissionsByItem['delete'][$niveauxScolaire->id])
                            <form class="context-state" action="{{ route('niveauxScolaires.destroy',['niveauxScolaire' => $niveauxScolaire->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$niveauxScolaire->id}}">
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
    @section('niveauxScolaire-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $niveauxScolaires_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>