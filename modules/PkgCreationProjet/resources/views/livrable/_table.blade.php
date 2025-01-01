{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-table" id="livrablesTable">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <th>{{ ucfirst(__('PkgCreationProjet::livrable.titre')) }}</th>
                <th>{{ ucfirst(__('PkgCreationProjet::projet.singular')) }}</th>
                <th>{{ ucfirst(__('PkgCreationProjet::natureLivrable.singular')) }}</th>
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($livrables_data as $livrable)
                <tr>
                    <td>{{ $livrable->titre }}</td>
                    <td>{{ $livrable->projet->titre ?? '-' }}</td>
                    <td>{{ $livrable->natureLivrable->nom ?? '-' }}</td>
                    <td class="text-center">
                        @can('show-livrable')
                            <a href="{{ route('livrables.show', array_merge($contextState, ['livrable' => $livrable->id])) }}" data-id="{{$livrable->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-livrable')
                            <a href="{{ route('livrables.edit', array_merge($contextState, ['livrable' => $livrable->id])) }}" data-id="{{$livrable->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-livrable')
                            <form action="{{ route('livrables.destroy',array_merge($contextState, ['livrable' => $livrable->id])) }}" method="POST" style="display: inline;" class="context-state">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$livrable->id}}">
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


<div class="card-footer">

    <div class="d-md-flex justify-content-between align-items-center p-2">
        <div class="d-flex align-items-center mb-2 ml-2 mt-2">
            @can('import-livrable')
                <form action="{{ route('livrables.import') }}" method="post" class="mt-2" enctype="multipart/form-data"
                    id="importForm">
                    @csrf
                    <label for="upload" class="btn btn-default btn-sm font-weight-normal">
                        <i class="fas fa-file-download"></i>
                        {{ __('Core::msg.import') }}
                    </label>
                    <input type="file" id="upload" name="file" style="display:none;" onchange="submitForm()" />
                </form>
            @endcan
            @can('export-livrable')
                <form class="">
                    <a href="{{ route('livrables.export') }}" class="btn btn-default btn-sm mt-0 mx-2">
                        <i class="fas fa-file-export"></i>
                        {{ __('Core::msg.export') }}</a>
                </form>
            @endcan
        </div>

        <ul class="pagination m-0 float-right">
            {{ $livrables_data->onEachSide(1)->links() }}
        </ul>
    </div>

    <script>
        function submitForm() {
            document.getElementById("importForm").submit();
        }
    </script>
</div>