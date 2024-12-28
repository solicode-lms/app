{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0" id="niveauxScolairesTable">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <th>{{ ucfirst(__('PkgUtilisateurs::niveauxScolaire.code')) }}</th>
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $niveauxScolaire)
                <tr>
                    <td>{{ $niveauxScolaire->code }}</td>
                    <td class="text-center">
                        @can('show-niveauxScolaire')
                            <a href="{{ route('niveauxScolaires.show', $niveauxScolaire) }}" data-id="{{$niveauxScolaire->id}}" class="btn btn-default btn-sm showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-niveauxScolaire')
                            <a href="{{ route('niveauxScolaires.edit', $niveauxScolaire) }}" data-id="{{$niveauxScolaire->id}}" class="btn btn-sm btn-default editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-niveauxScolaire')
                            <form action="{{ route('niveauxScolaires.destroy', $niveauxScolaire) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$niveauxScolaire->id}}">
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

