{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('natureLivrable-table')
<div class="card-body table-responsive p-0 crud-card-body" id="natureLivrables-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                <x-sortable-column width="85"  field="nom" modelname="natureLivrable" label="{{ ucfirst(__('PkgCreationProjet::natureLivrable.nom')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('natureLivrable-table-tbody')
            @foreach ($natureLivrables_data as $natureLivrable)
                <tr id="natureLivrable-row-{{$natureLivrable->id}}">
                    <td style="max-width: 85%;" class="text-truncate" data-toggle="tooltip" title="{{ $natureLivrable->nom }}" >
                    <x-field :data="$natureLivrable" field="nom">
                        {{ $natureLivrable->nom }}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-natureLivrable')
                        @can('update', $natureLivrable)
                            <a href="{{ route('natureLivrables.edit', ['natureLivrable' => $natureLivrable->id]) }}" data-id="{{$natureLivrable->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-natureLivrable')
                        @can('view', $natureLivrable)
                            <a href="{{ route('natureLivrables.show', ['natureLivrable' => $natureLivrable->id]) }}" data-id="{{$natureLivrable->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-natureLivrable')
                        @can('delete', $natureLivrable)
                            <form class="context-state" action="{{ route('natureLivrables.destroy',['natureLivrable' => $natureLivrable->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$natureLivrable->id}}">
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
    @section('natureLivrable-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $natureLivrables_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>