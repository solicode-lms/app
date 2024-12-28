{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0" id="apprenantKonosiesTable">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <th>{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.Nom')) }}</th>
                <th>{{ ucfirst(__('PkgUtilisateurs::apprenantKonosy.Adresse')) }}</th>
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $apprenantKonosy)
                <tr>
                    <td>{{ $apprenantKonosy->Nom }}</td>
                    <td>{{ $apprenantKonosy->Adresse }}</td>
                    <td class="text-center">
                        @can('show-apprenantKonosy')
                            <a href="{{ route('apprenantKonosies.show', $apprenantKonosy) }}" data-id="{{$apprenantKonosy->id}}" class="btn btn-default btn-sm showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-apprenantKonosy')
                            <a href="{{ route('apprenantKonosies.edit', $apprenantKonosy) }}" data-id="{{$apprenantKonosy->id}}" class="btn btn-sm btn-default editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-apprenantKonosy')
                            <form action="{{ route('apprenantKonosies.destroy', $apprenantKonosy) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$apprenantKonosy->id}}">
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

