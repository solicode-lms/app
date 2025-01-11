{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="filieres-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="code" label="{{ ucfirst(__('PkgCompetences::filiere.code')) }}" />
                <x-sortable-column field="nom" label="{{ ucfirst(__('PkgCompetences::filiere.nom')) }}" />
                <x-sortable-column field="description" label="{{ ucfirst(__('PkgCompetences::filiere.description')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($filieres_data as $filiere)
                <tr>
                    <td>@limit($filiere->code, 80)</td>
                    <td>@limit($filiere->nom, 80)</td>
                    <td>{!! $filiere->description !!}</td>
                    <td class="text-right">
                        @can('show-filiere')
                            <a href="{{ route('filieres.show', ['filiere' => $filiere->id]) }}" data-id="{{$filiere->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-filiere')
                        @can('update', $filiere)
                            <a href="{{ route('filieres.edit', ['filiere' => $filiere->id]) }}" data-id="{{$filiere->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-filiere')
                        @can('delete', $filiere)
                            <form class="context-state" action="{{ route('filieres.destroy',['filiere' => $filiere->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$filiere->id}}">
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
    @section('filiere-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $filieres_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>