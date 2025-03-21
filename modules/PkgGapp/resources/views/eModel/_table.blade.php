{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('eModel-table')
<div class="card-body table-responsive p-0 crud-card-body" id="eModels-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                 <th>
                    IcôNe
                </th>
                <x-sortable-column field="name" modelname="eModel" label="{{ ucfirst(__('PkgGapp::eModel.name')) }}" />
                <x-sortable-column field="e_package_id" modelname="eModel" label="{{ ucfirst(__('PkgGapp::ePackage.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('eModel-table-tbody')
            @foreach ($eModels_data as $eModel)
                <tr id="eModel-row-{{$eModel->id}}">
                    <td>@limit($eModel->getIcone(), 50)</td>
                    <td>
                     <span @if(strlen($eModel->name) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $eModel->name }}" 
                        @endif>
                        {{ Str::limit($eModel->name, 40) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($eModel->ePackage) > 50) 
                            data-toggle="tooltip" 
                            title="{{ $eModel->ePackage }}" 
                        @endif>
                        {{ Str::limit($eModel->ePackage, 50) }}
                    </span>
                    </td>
                    <td class="text-right">

                        @can('show-eModel')
                        @can('view', $eModel)
                            <a href="{{ route('eModels.show', ['eModel' => $eModel->id]) }}" data-id="{{$eModel->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-eModel')
                        @can('update', $eModel)
                            <a href="{{ route('eModels.edit', ['eModel' => $eModel->id]) }}" data-id="{{$eModel->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-eModel')
                        @can('delete', $eModel)
                            <form class="context-state" action="{{ route('eModels.destroy',['eModel' => $eModel->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$eModel->id}}">
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
    @section('eModel-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $eModels_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>