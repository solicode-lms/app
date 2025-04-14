{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('niveauxScolaire-table')
<div class="card-body table-responsive p-0 crud-card-body" id="niveauxScolaires-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                <x-sortable-column width="85"  field="code" modelname="niveauxScolaire" label="{{ ucfirst(__('PkgApprenants::niveauxScolaire.code')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('niveauxScolaire-table-tbody')
            @foreach ($niveauxScolaires_data as $niveauxScolaire)
                <tr id="niveauxScolaire-row-{{$niveauxScolaire->id}}">
                    <td style="max-width: 85%;" class="text-truncate" data-toggle="tooltip" title="{{ $niveauxScolaire->code }}" >
                    <x-field :entity="$niveauxScolaire" field="code">
                        {{ $niveauxScolaire->code }}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-niveauxScolaire')
                        @can('update', $niveauxScolaire)
                            <a href="{{ route('niveauxScolaires.edit', ['niveauxScolaire' => $niveauxScolaire->id]) }}" data-id="{{$niveauxScolaire->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-niveauxScolaire')
                        @can('view', $niveauxScolaire)
                            <a href="{{ route('niveauxScolaires.show', ['niveauxScolaire' => $niveauxScolaire->id]) }}" data-id="{{$niveauxScolaire->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-niveauxScolaire')
                        @can('delete', $niveauxScolaire)
                            <form class="context-state" action="{{ route('niveauxScolaires.destroy',['niveauxScolaire' => $niveauxScolaire->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$niveauxScolaire->id}}">
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
@show

<div class="card-footer">
    @section('niveauxScolaire-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $niveauxScolaires_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>