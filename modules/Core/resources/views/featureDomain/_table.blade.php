{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="featureDomains-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="name" label="{{ ucfirst(__('Core::featureDomain.name')) }}" />
                <x-sortable-column field="description" label="{{ ucfirst(__('Core::featureDomain.description')) }}" />
                <x-sortable-column field="module_id" label="{{ ucfirst(__('Core::sysModule.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($featureDomains_data as $featureDomain)
                <tr>
                    <td>@limit($featureDomain->name, 80)</td>
                    <td>{!! $featureDomain->description !!}</td>
                    <td>@limit($featureDomain->sysModule->name ?? '-', 80)</td>
                    <td class="text-right">
                        @can('show-featureDomain')
                            <a href="{{ route('featureDomains.show', ['featureDomain' => $featureDomain->id]) }}" data-id="{{$featureDomain->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-featureDomain')
                            <a href="{{ route('featureDomains.edit', ['featureDomain' => $featureDomain->id]) }}" data-id="{{$featureDomain->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-featureDomain')
                            <form class="context-state" action="{{ route('featureDomains.destroy',['featureDomain' => $featureDomain->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$featureDomain->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="card-footer">
    @section('crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $featureDomains_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>