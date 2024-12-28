{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0" id="featureDomainsTable">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <th>{{ ucfirst(__('Core::featureDomain.name')) }}</th>
                <th>{{ ucfirst(__('Core::featureDomain.description')) }}</th>
                <th>{{ ucfirst(__('Core::sysModule.singular')) }}</th>
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $featureDomain)
                <tr>
                    <td>{{ $featureDomain->name }}</td>
                    <td>{{ $featureDomain->description }}</td>
                    <td>{{ $featureDomain->sysModule->name ?? '-' }}</td>
                    <td class="text-center">
                        @can('show-featureDomain')
                            <a href="{{ route('featureDomains.show', $featureDomain) }}" data-id="{{$featureDomain->id}}" class="btn btn-default btn-sm showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-featureDomain')
                            <a href="{{ route('featureDomains.edit', $featureDomain) }}" data-id="{{$featureDomain->id}}" class="btn btn-sm btn-default editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-featureDomain')
                            <form action="{{ route('featureDomains.destroy', $featureDomain) }}" method="POST" style="display: inline;">
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

