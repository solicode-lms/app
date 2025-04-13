{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('eModel-table')
<div class="card-body table-responsive p-0 crud-card-body" id="eModels-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                <x-sortable-column width="28.333333333333332"  field="icone" modelname="eModel" label="{{ ucfirst(__('PkgGapp::eModel.icone')) }}" />
                <x-sortable-column width="28.333333333333332"  field="name" modelname="eModel" label="{{ ucfirst(__('PkgGapp::eModel.name')) }}" />
                <x-sortable-column width="28.333333333333332" field="e_package_id" modelname="eModel" label="{{ ucfirst(__('PkgGapp::ePackage.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('eModel-table-tbody')
            @foreach ($eModels_data as $eModel)
                <tr id="eModel-row-{{$eModel->id}}">
                    <td style="max-width: 28.333333333333332%;" class="text-truncate" data-toggle="tooltip" title="{{ $eModel->icone }}" >
                    <x-field :entity="$eModel" field="icone">
                        <i class="{{ $eModel->icone }}" ></i>
                    </x-field>
                    </td>
                    <td style="max-width: 28.333333333333332%;" class="text-truncate" data-toggle="tooltip" title="{{ $eModel->name }}" >
                    <x-field :entity="$eModel" field="name">
                        {{ $eModel->name }}
                    </x-field>
                    </td>
                    <td style="max-width: 28.333333333333332%;" class="text-truncate" data-toggle="tooltip" title="{{ $eModel->ePackage }}" >
                    <x-field :entity="$eModel" field="ePackage">
                       
                         {{  $eModel->ePackage }}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-eModel')
                        @can('update', $eModel)
                            <a href="{{ route('eModels.edit', ['eModel' => $eModel->id]) }}" data-id="{{$eModel->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-eModel')
                        @can('view', $eModel)
                            <a href="{{ route('eModels.show', ['eModel' => $eModel->id]) }}" data-id="{{$eModel->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
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