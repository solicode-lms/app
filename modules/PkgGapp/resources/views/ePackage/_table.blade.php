{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="ePackages-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="code" label="{{ ucfirst(__('PkgGapp::ePackage.code')) }}" />
                <x-sortable-column field="name" label="{{ ucfirst(__('PkgGapp::ePackage.name')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ePackages_data as $ePackage)
                <tr>
                    <td>@limit($ePackage->code, 80)</td>
                    <td>@limit($ePackage->name, 80)</td>
                    <td class="text-right">
                        @can('show-ePackage')
                            <a href="{{ route('ePackages.show', ['ePackage' => $ePackage->id]) }}" data-id="{{$ePackage->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-ePackage')
                        @can('update', $ePackage)
                            <a href="{{ route('ePackages.edit', ['ePackage' => $ePackage->id]) }}" data-id="{{$ePackage->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-ePackage')
                        @can('delete', $ePackage)
                            <form class="context-state" action="{{ route('ePackages.destroy',['ePackage' => $ePackage->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$ePackage->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endcan
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="card-footer">
    @section('ePackage-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $ePackages_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>