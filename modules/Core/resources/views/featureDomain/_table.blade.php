{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="featureDomains-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="name" modelname="featureDomain" label="{{ ucfirst(__('Core::featureDomain.name')) }}" />
                <x-sortable-column field="sys_module_id" modelname="featureDomain" label="{{ ucfirst(__('Core::sysModule.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($featureDomains_data as $featureDomain)
                <tr id="featureDomain-row-{{$featureDomain->id}}">
                    <td>@limit($featureDomain->name, 50)</td>
                    <td>@limit($featureDomain->sysModule, 50)</td>
                    <td class="text-right">

                        @can('show-featureDomain')
                            <a href="{{ route('featureDomains.show', ['featureDomain' => $featureDomain->id]) }}" data-id="{{$featureDomain->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-featureDomain')
                        @can('update', $featureDomain)
                            <a href="{{ route('featureDomains.edit', ['featureDomain' => $featureDomain->id]) }}" data-id="{{$featureDomain->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-featureDomain')
                        @can('delete', $featureDomain)
                            <form class="context-state" action="{{ route('featureDomains.destroy',['featureDomain' => $featureDomain->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$featureDomain->id}}">
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
    @section('featureDomain-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $featureDomains_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>