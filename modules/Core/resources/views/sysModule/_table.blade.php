{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-table" id="sysModulesTable">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <th>{{ ucfirst(__('Core::sysModule.name')) }}</th>
                <th>{{ ucfirst(__('Core::sysModule.description')) }}</th>
                <th>{{ ucfirst(__('Core::sysModule.is_active')) }}</th>
                <th>{{ ucfirst(__('Core::sysColor.singular')) }}</th>
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sysModules_data as $sysModule)
                <tr>
                    <td>{{ $sysModule->name }}</td>
                    <td>{!! $sysModule->description !!}</td>
                    <td>{{ $sysModule->is_active }}</td>
                    <td>{{ $sysModule->sysColor->name ?? '-' }}</td>
                    <td class="text-center">
                        @can('show-sysModule')
                            <a href="{{ route('sysModules.show', array_merge($contextState, ['sysModule' => $sysModule->id])) }}" data-id="{{$sysModule->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-sysModule')
                            <a href="{{ route('sysModules.edit', array_merge($contextState, ['sysModule' => $sysModule->id])) }}" data-id="{{$sysModule->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-sysModule')
                            <form class="context-state" action="{{ route('sysModules.destroy',array_merge($contextState, ['sysModule' => $sysModule->id])) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$sysModule->id}}">
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
            @can('import-sysModule')
                <form action="{{ route('sysModules.import') }}" method="post" class="mt-2" enctype="multipart/form-data"
                    id="importForm">
                    @csrf
                    <label for="upload" class="btn btn-default btn-sm font-weight-normal">
                        <i class="fas fa-file-download"></i>
                        {{ __('Core::msg.import') }}
                    </label>
                    <input type="file" id="upload" name="file" style="display:none;" onchange="submitForm()" />
                </form>
            @endcan
            @can('export-sysModule')
                <form class="">
                    <a href="{{ route('sysModules.export') }}" class="btn btn-default btn-sm mt-0 mx-2">
                        <i class="fas fa-file-export"></i>
                        {{ __('Core::msg.export') }}</a>
                </form>
            @endcan
        </div>

        <ul class="pagination m-0 float-right">
            {{ $sysModules_data->onEachSide(1)->links() }}
        </ul>
    </div>

    <script>
        function submitForm() {
            document.getElementById("importForm").submit();
        }
    </script>
</div>