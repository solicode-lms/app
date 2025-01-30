{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="anneeFormations-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="titre" label="{{ ucfirst(__('PkgFormation::anneeFormation.titre')) }}" />
                <x-sortable-column field="date_debut" label="{{ ucfirst(__('PkgFormation::anneeFormation.date_debut')) }}" />
                <x-sortable-column field="date_fin" label="{{ ucfirst(__('PkgFormation::anneeFormation.date_fin')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($anneeFormations_data as $anneeFormation)
                <tr id="anneeFormation-row-{{$anneeFormation->id}}">
                    <td>@limit($anneeFormation->titre, 80)</td>
                    <td>@limit($anneeFormation->date_debut, 80)</td>
                    <td>@limit($anneeFormation->date_fin, 80)</td>
                    <td class="text-right">
                        @can('show-anneeFormation')
                            <a href="{{ route('anneeFormations.show', ['anneeFormation' => $anneeFormation->id]) }}" data-id="{{$anneeFormation->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-anneeFormation')
                        @can('update', $anneeFormation)
                            <a href="{{ route('anneeFormations.edit', ['anneeFormation' => $anneeFormation->id]) }}" data-id="{{$anneeFormation->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-anneeFormation')
                        @can('delete', $anneeFormation)
                            <form class="context-state" action="{{ route('anneeFormations.destroy',['anneeFormation' => $anneeFormation->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$anneeFormation->id}}">
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
    @section('anneeFormation-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $anneeFormations_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>