{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('sysModel-table')
<div class="card-body table-responsive p-0 crud-card-body" id="sysModels-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                <x-sortable-column width="21.25"  field="name" modelname="sysModel" label="{{ ucfirst(__('Core::sysModel.name')) }}" />
                <x-sortable-column width="21.25" field="sys_module_id" modelname="sysModel" label="{{ ucfirst(__('Core::sysModule.singular')) }}" />
                <x-sortable-column width="21.25" field="sys_color_id" modelname="sysModel" label="{{ ucfirst(__('Core::sysColor.singular')) }}" />
                <x-sortable-column width="21.25"  field="icone" modelname="sysModel" label="{{ ucfirst(__('Core::sysModel.icone')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('sysModel-table-tbody')
            @foreach ($sysModels_data as $sysModel)
                <tr id="sysModel-row-{{$sysModel->id}}">
                    <td style="max-width: 21.25%;" class="text-truncate" data-toggle="tooltip" title="{{ $sysModel->name }}" >
                    <x-field :data="$sysModel" field="name">
                        {{ $sysModel->name }}
                    </x-field>
                    </td>
                    <td style="max-width: 21.25%;" class="text-truncate" data-toggle="tooltip" title="{{ $sysModel->sysModule }}" >
                    <x-field :data="$sysModel" field="sysModule">
                       
                         {{  $sysModel->sysModule }}
                    </x-field>
                    </td>
                    <td style="max-width: 21.25%;" class="text-truncate" data-toggle="tooltip" title="{{ $sysModel->sysColor }}" >
                    <x-field :data="$sysModel" field="sysColor">
                       
                         {{  $sysModel->sysColor }}
                    </x-field>
                    </td>
                    <td style="max-width: 21.25%;" class="text-truncate" data-toggle="tooltip" title="{{ $sysModel->icone }}" >
                    <x-field :data="$sysModel" field="icone">
                        {{ $sysModel->icone }}
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
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$sysModel->id}}">
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