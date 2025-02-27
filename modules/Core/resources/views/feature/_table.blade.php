{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="features-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="name" modelname="feature" label="{{ ucfirst(__('Core::feature.name')) }}" />
                <x-sortable-column field="feature_domain_id" modelname="feature" label="{{ ucfirst(__('Core::featureDomain.singular')) }}" />
                <x-sortable-column field="permissions" modelname="feature" label="{{ ucfirst(__('PkgAutorisation::permission.plural')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('feature-table-tbody')
            @foreach ($features_data as $feature)
                <tr id="feature-row-{{$feature->id}}">
                    <td>@limit($feature->name, 50)</td>
                    <td>@limit($feature->featureDomain, 50)</td>
                    <td>
                        <ul>
                            @foreach ($feature->permissions as $permission)
                                <li>{{ $permission }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="text-right">

                        @can('show-feature')
                        @can('view', $feature)
                            <a href="{{ route('features.show', ['feature' => $feature->id]) }}" data-id="{{$feature->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-feature')
                        @can('update', $feature)
                            <a href="{{ route('features.edit', ['feature' => $feature->id]) }}" data-id="{{$feature->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-feature')
                        @can('delete', $feature)
                            <form class="context-state" action="{{ route('features.destroy',['feature' => $feature->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$feature->id}}">
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

<div class="card-footer">
    @section('feature-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $features_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>