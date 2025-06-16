{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('eModel-table')
<div class="card-body p-0 crud-card-body" id="eModels-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $eModels_permissions['edit-eModel'] || $deveModels_permissions['destroy-eModel'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="7"  field="icone" modelname="eModel" label="{{ucfirst(__('PkgGapp::eModel.icone'))}}" />
                <x-sortable-column :sortable="true" width="37.5"  field="name" modelname="eModel" label="{{ucfirst(__('PkgGapp::eModel.name'))}}" />
                <x-sortable-column :sortable="true" width="37.5" field="e_package_id" modelname="eModel" label="{{ucfirst(__('PkgGapp::ePackage.singular'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('eModel-table-tbody')
            @foreach ($eModels_data as $eModel)
                @php
                    $isEditable = $eModels_permissions['edit-eModel'] && $eModels_permissionsByItem['update'][$eModel->id];
                @endphp
                <tr id="eModel-row-{{$eModel->id}}" data-id="{{$eModel->id}}">
                    <x-checkbox-row :item="$eModel" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 7%;" class=" text-truncate" data-id="{{$eModel->id}}" data-field="icone"  data-toggle="tooltip" title="{{ $eModel->icone }}" >
                        <div class="d-flex justify-content-center align-items-center" style="height: 100%;">
                            <i class="{{ $eModel->icone }}" ></i>
                        </div>

                    </td>
                    <td style="max-width: 37.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$eModel->id}}" data-field="name"  data-toggle="tooltip" title="{{ $eModel->name }}" >
                        {{ $eModel->name }}

                    </td>
                    <td style="max-width: 37.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$eModel->id}}" data-field="e_package_id"  data-toggle="tooltip" title="{{ $eModel->ePackage }}" >
                        {{  $eModel->ePackage }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($eModels_permissions['edit-eModel'])
                        <x-action-button :entity="$eModel" actionName="edit">
                        @if($eModels_permissionsByItem['update'][$eModel->id])
                            <a href="{{ route('eModels.edit', ['eModel' => $eModel->id]) }}" data-id="{{$eModel->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($eModels_permissions['show-eModel'])
                        <x-action-button :entity="$eModel" actionName="show">
                        @if($eModels_permissionsByItem['view'][$eModel->id])
                            <a href="{{ route('eModels.show', ['eModel' => $eModel->id]) }}" data-id="{{$eModel->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$eModel" actionName="delete">
                        @if($eModels_permissions['destroy-eModel'])
                        @if($eModels_permissionsByItem['delete'][$eModel->id])
                            <form class="context-state" action="{{ route('eModels.destroy',['eModel' => $eModel->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$eModel->id}}">
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
    @section('eModel-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $eModels_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>