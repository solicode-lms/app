{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0" id="featuresTable">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <th>{{ ucfirst(__('Core::feature.name')) }}</th>
                <th>{{ ucfirst(__('Core::feature.description')) }}</th>
                <th>{{ ucfirst(__('Core::featureDomain.singular')) }}</th>
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $feature)
                <tr>
                    <td>{{ $feature->name }}</td>
                    <td>{{ $feature->description }}</td>
                    <td>{{ $feature->featureDomain->name ?? '-' }}</td>
                    <td class="text-center">
                        @can('show-feature')
                            <a href="{{ route('features.show', $feature) }}" data-id="{{$feature->id}}" class="btn btn-default btn-sm showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-feature')
                            <a href="{{ route('features.edit', $feature) }}" data-id="{{$feature->id}}" class="btn btn-sm btn-default editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-feature')
                            <form action="{{ route('features.destroy', $feature) }}" method="POST" style="display: inline;">
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

