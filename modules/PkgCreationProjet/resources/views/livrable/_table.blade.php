{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('livrable-table')
<div class="card-body table-responsive p-0 crud-card-body" id="livrables-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                <x-sortable-column width="42.5" field="nature_livrable_id" modelname="livrable" label="{{ ucfirst(__('PkgCreationProjet::natureLivrable.singular')) }}" />
                <x-sortable-column width="42.5"  field="titre" modelname="livrable" label="{{ ucfirst(__('PkgCreationProjet::livrable.titre')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('livrable-table-tbody')
            @foreach ($livrables_data as $livrable)
                <tr id="livrable-row-{{$livrable->id}}">
                    <td style="max-width: 42.5%;" class="text-truncate" data-toggle="tooltip" title="{{ $livrable->natureLivrable }}" >
                    <x-field :data="$livrable" field="natureLivrable">
                       
                         {{  $livrable->natureLivrable }}
                    </x-field>
                    </td>
                    <td style="max-width: 42.5%;" class="text-truncate" data-toggle="tooltip" title="{{ $livrable->titre }}" >
                    <x-field :data="$livrable" field="titre">
                        {{ $livrable->titre }}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-livrable')
                        @can('update', $livrable)
                            <a href="{{ route('livrables.edit', ['livrable' => $livrable->id]) }}" data-id="{{$livrable->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-livrable')
                        @can('view', $livrable)
                            <a href="{{ route('livrables.show', ['livrable' => $livrable->id]) }}" data-id="{{$livrable->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-livrable')
                        @can('delete', $livrable)
                            <form class="context-state" action="{{ route('livrables.destroy',['livrable' => $livrable->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$livrable->id}}">
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
    @section('livrable-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $livrables_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>