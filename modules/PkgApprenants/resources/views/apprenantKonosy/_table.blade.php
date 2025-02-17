{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="apprenantKonosies-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="Nom" label="{{ ucfirst(__('PkgApprenants::apprenantKonosy.Nom')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($apprenantKonosies_data as $apprenantKonosy)
                <tr id="apprenantKonosy-row-{{$apprenantKonosy->id}}">
                    <td>@limit($apprenantKonosy->Nom, 80)</td>
                    <td class="text-right">

                        @can('show-apprenantKonosy')
                            <a href="{{ route('apprenantKonosies.show', ['apprenantKonosy' => $apprenantKonosy->id]) }}" data-id="{{$apprenantKonosy->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-apprenantKonosy')
                        @can('update', $apprenantKonosy)
                            <a href="{{ route('apprenantKonosies.edit', ['apprenantKonosy' => $apprenantKonosy->id]) }}" data-id="{{$apprenantKonosy->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-apprenantKonosy')
                        @can('delete', $apprenantKonosy)
                            <form class="context-state" action="{{ route('apprenantKonosies.destroy',['apprenantKonosy' => $apprenantKonosy->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$apprenantKonosy->id}}">
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
    @section('apprenantKonosy-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $apprenantKonosies_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>