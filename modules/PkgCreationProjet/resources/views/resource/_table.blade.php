{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0" id="resourcesTable">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <th>{{ ucfirst(__('PkgCreationProjet::resource.nom')) }}</th>
                <th>{{ ucfirst(__('PkgCreationProjet::resource.lien')) }}</th>
                <th>{{ ucfirst(__('PkgCreationProjet::projet.singular')) }}</th>
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $resource)
                <tr>
                    <td>{{ $resource->nom }}</td>
                    <td>{{ $resource->lien }}</td>
                    <td>{{ $resource->projet->titre ?? '-' }}</td>
                    <td class="text-center">
                        @can('show-resource')
                            <a href="{{ route('resources.show', $resource) }}" data-id="{{$resource->id}}" class="btn btn-default btn-sm showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-resource')
                            <a href="{{ route('resources.edit', $resource) }}" data-id="{{$resource->id}}" class="btn btn-sm btn-default editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-resource')
                            <form action="{{ route('resources.destroy', $resource) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$resource->id}}">
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

