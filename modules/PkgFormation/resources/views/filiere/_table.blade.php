{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('filiere-table')
<div class="card-body p-0 crud-card-body" id="filieres-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $filieres_permissions['edit-filiere'] || $filieres_permissions['destroy-filiere'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="41"  field="code" modelname="filiere" label="{{ucfirst(__('PkgFormation::filiere.code'))}}" />
                <x-sortable-column :sortable="true" width="41"  field="nom" modelname="filiere" label="{{ucfirst(__('PkgFormation::filiere.nom'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('filiere-table-tbody')
            @foreach ($filieres_data as $filiere)
                @php
                    $isEditable = $filieres_permissions['edit-filiere'] && $filieres_permissionsByItem['update'][$filiere->id];
                @endphp
                <tr id="filiere-row-{{$filiere->id}}" data-id="{{$filiere->id}}">
                    <x-checkbox-row :item="$filiere" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 41%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$filiere->id}}" data-field="code"  data-toggle="tooltip" title="{{ $filiere->code }}" >
                        {{ $filiere->code }}

                    </td>
                    <td style="max-width: 41%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$filiere->id}}" data-field="nom"  data-toggle="tooltip" title="{{ $filiere->nom }}" >
                        {{ $filiere->nom }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($filieres_permissions['edit-filiere'])
                        <x-action-button :entity="$filiere" actionName="edit">
                        @if($filieres_permissionsByItem['update'][$filiere->id])
                            <a href="{{ route('filieres.edit', ['filiere' => $filiere->id]) }}" data-id="{{$filiere->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($filieres_permissions['show-filiere'])
                        <x-action-button :entity="$filiere" actionName="show">
                        @if($filieres_permissionsByItem['view'][$filiere->id])
                            <a href="{{ route('filieres.show', ['filiere' => $filiere->id]) }}" data-id="{{$filiere->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$filiere" actionName="delete">
                        @if($filieres_permissions['destroy-filiere'])
                        @if($filieres_permissionsByItem['delete'][$filiere->id])
                            <form class="context-state" action="{{ route('filieres.destroy',['filiere' => $filiere->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$filiere->id}}">
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
    @section('filiere-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $filieres_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>