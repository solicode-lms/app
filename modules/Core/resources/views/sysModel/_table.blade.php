{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('sysModel-table')
<div class="card-body table-responsive p-0 crud-card-body" id="sysModels-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-sysModel') || Auth::user()->can('destroy-sysModel');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="20.5"  field="name" modelname="sysModel" label="{{ucfirst(__('Core::sysModel.name'))}}" />
                <x-sortable-column :sortable="true" width="20.5" field="sys_module_id" modelname="sysModel" label="{{ucfirst(__('Core::sysModule.singular'))}}" />
                <x-sortable-column :sortable="true" width="20.5" field="sys_color_id" modelname="sysModel" label="{{ucfirst(__('Core::sysColor.singular'))}}" />
                <x-sortable-column :sortable="true" width="20.5"  field="icone" modelname="sysModel" label="{{ucfirst(__('Core::sysModel.icone'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('sysModel-table-tbody')
            @foreach ($sysModels_data as $sysModel)
                @php
                    $isEditable = Auth::user()->can('edit-sysModel') && Auth::user()->can('update', $sysModel);
                @endphp
                <tr id="sysModel-row-{{$sysModel->id}}" data-id="{{$sysModel->id}}">
                    <x-checkbox-row :item="$sysModel" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$sysModel->id}}" data-field="name"  data-toggle="tooltip" title="{{ $sysModel->name }}" >
                    <x-field :entity="$sysModel" field="name">
                        {{ $sysModel->name }}
                    </x-field>
                    </td>
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$sysModel->id}}" data-field="sys_module_id"  data-toggle="tooltip" title="{{ $sysModel->sysModule }}" >
                    <x-field :entity="$sysModel" field="sysModule">
                       
                         {{  $sysModel->sysModule }}
                    </x-field>
                    </td>
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$sysModel->id}}" data-field="sys_color_id"  data-toggle="tooltip" title="{{ $sysModel->sysColor }}" >
                    <x-field :entity="$sysModel" field="sysColor">
                        <x-badge 
                        :text="$sysModel->sysColor->name ?? ''" 
                        :background="$sysModel->sysColor->hex ?? '#6c757d'" 
                        />
                    </x-field>
                    </td>
                    <td style="max-width: 20.5%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$sysModel->id}}" data-field="icone"  data-toggle="tooltip" title="{{ $sysModel->icone }}" >
                    <x-field :entity="$sysModel" field="icone">
                        <div class="d-flex justify-content-center align-items-center" style="height: 100%;">
                            <i class="{{ $sysModel->icone }}" ></i>
                        </div>
                    </x-field>
                    </td>

                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-sysModel')
                        @can('update', $sysModel)
                            <a href="{{ route('sysModels.edit', ['sysModel' => $sysModel->id]) }}" data-id="{{$sysModel->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-sysModel')
                        @can('view', $sysModel)
                            <a href="{{ route('sysModels.show', ['sysModel' => $sysModel->id]) }}" data-id="{{$sysModel->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-sysModel')
                        @can('delete', $sysModel)
                            <form class="context-state" action="{{ route('sysModels.destroy',['sysModel' => $sysModel->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$sysModel->id}}">
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
    @section('sysModel-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $sysModels_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>