{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('sysController-table')
<div class="card-body table-responsive p-0 crud-card-body" id="sysControllers-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-sysController') || Auth::user()->can('destroy-sysController');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="20.5" field="sys_module_id" modelname="sysController" label="{{ucfirst(__('Core::sysModule.singular'))}}" />
                <x-sortable-column :sortable="true" width="20.5"  field="name" modelname="sysController" label="{{ucfirst(__('Core::sysController.name'))}}" />
                <x-sortable-column :sortable="true" width="20.5"  field="is_active" modelname="sysController" label="{{ucfirst(__('Core::sysController.is_active'))}}" />
                <x-sortable-column :sortable="true" width="20.5"  field="Permission" modelname="sysController" label="{{ucfirst(__('PkgAutorisation::permission.plural'))}}" />

                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('sysController-table-tbody')
            @foreach ($sysControllers_data as $sysController)
                <tr id="sysController-row-{{$sysController->id}}" data-id="{{$sysController->id}}">
                    <x-checkbox-row :item="$sysController" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 20.5%;" class="editable-cell text-truncate" data-id="{{$sysController->id}}" data-field="sys_module_id"  data-toggle="tooltip" title="{{ $sysController->sysModule }}" >
                    <x-field :entity="$sysController" field="sysModule">
                       
                         {{  $sysController->sysModule }}
                    </x-field>
                    </td>
                    <td style="max-width: 20.5%;" class="editable-cell text-truncate" data-id="{{$sysController->id}}" data-field="name"  data-toggle="tooltip" title="{{ $sysController->name }}" >
                    <x-field :entity="$sysController" field="name">
                        {{ $sysController->name }}
                    </x-field>
                    </td>
                    <td style="max-width: 20.5%;" class="editable-cell text-truncate" data-id="{{$sysController->id}}" data-field="is_active"  data-toggle="tooltip" title="{{ $sysController->is_active }}" >
                    <x-field :entity="$sysController" field="is_active">
                        <span class="{{ $sysController->is_active ? 'text-success' : 'text-danger' }}">
                            {{ $sysController->is_active ? 'Oui' : 'Non' }}
                        </span>
                    </x-field>
                    </td>
                    <td style="max-width: 20.5%;" class="editable-cell text-truncate" data-id="{{$sysController->id}}" data-field="Permission"  data-toggle="tooltip" title="{{ $sysController->controllerIdPermissions }}" >
                    <x-field :entity="$sysController" field="controllerIdPermissions">
                        <ul>
                            @foreach ($sysController->controllerIdPermissions as $permission)
                                <li>{{$permission}} </li>
                            @endforeach
                        </ul>
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-sysController')
                        @can('update', $sysController)
                            <a href="{{ route('sysControllers.edit', ['sysController' => $sysController->id]) }}" data-id="{{$sysController->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-sysController')
                        @can('view', $sysController)
                            <a href="{{ route('sysControllers.show', ['sysController' => $sysController->id]) }}" data-id="{{$sysController->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-sysController')
                        @can('delete', $sysController)
                            <form class="context-state" action="{{ route('sysControllers.destroy',['sysController' => $sysController->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$sysController->id}}">
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
    @section('sysController-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $sysControllers_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>