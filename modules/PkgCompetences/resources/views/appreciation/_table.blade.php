{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-table" id="appreciationsTable">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <th>{{ ucfirst(__('PkgCompetences::appreciation.nom')) }}</th>
                <th>{{ ucfirst(__('PkgUtilisateurs::formateur.singular')) }}</th>
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $appreciation)
                <tr>
                    <td>{{ $appreciation->nom }}</td>
                    <td>{{ $appreciation->formateur->nom ?? '-' }}</td>
                    <td class="text-center">
                        @can('show-appreciation')
                            <a href="{{ route('appreciations.show', $appreciation) }}" data-id="{{$appreciation->id}}" class="btn btn-default btn-sm showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-appreciation')
                            <a href="{{ route('appreciations.edit', $appreciation) }}" data-id="{{$appreciation->id}}" class="btn btn-sm btn-default editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-appreciation')
                            <form action="{{ route('appreciations.destroy', $appreciation) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$appreciation->id}}">
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
            @can('import-appreciation')
                <form action="{{ route('appreciations.import') }}" method="post" class="mt-2" enctype="multipart/form-data"
                    id="importForm">
                    @csrf
                    <label for="upload" class="btn btn-default btn-sm font-weight-normal">
                        <i class="fas fa-file-download"></i>
                        {{ __('Core::msg.import') }}
                    </label>
                    <input type="file" id="upload" name="file" style="display:none;" onchange="submitForm()" />
                </form>
            @endcan
            @can('export-appreciation')
                <form class="">
                    <a href="{{ route('appreciations.export') }}" class="btn btn-default btn-sm mt-0 mx-2">
                        <i class="fas fa-file-export"></i>
                        {{ __('Core::msg.export') }}</a>
                </form>
            @endcan
        </div>

        <ul class="pagination m-0 float-right">
            {{ $data->onEachSide(1)->links() }}
        </ul>
    </div>

    <script>
        function submitForm() {
            document.getElementById("importForm").submit();
        }
    </script>
</div>