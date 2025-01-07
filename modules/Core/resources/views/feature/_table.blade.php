{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="features-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="name" label="{{ ucfirst(__('Core::feature.name')) }}" />
                <x-sortable-column field="description" label="{{ ucfirst(__('Core::feature.description')) }}" />
                <x-sortable-column field="domain_id" label="{{ ucfirst(__('Core::featureDomain.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($features_data as $feature)
                <tr>
                    <td>@limit($feature->name, 80)</td>
                    <td>{!! $feature->description !!}</td>
                    <td>@limit($feature->featureDomain->name ?? '-', 80)</td>
                    <td class="text-right">
                        @can('show-feature')
                            <a href="{{ route('features.show', ['feature' => $feature->id]) }}" data-id="{{$feature->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-feature')
                            <a href="{{ route('features.edit', ['feature' => $feature->id]) }}" data-id="{{$feature->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-feature')
                            <form class="context-state" action="{{ route('features.destroy',['feature' => $feature->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$feature->id}}">
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
    @section('feature-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $features_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>