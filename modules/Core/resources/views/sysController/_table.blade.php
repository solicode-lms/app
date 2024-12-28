{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0" id="sysControllersTable">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <th>{{ ucfirst(__('Core::sysModule.singular')) }}</th>
                <th>{{ ucfirst(__('Core::sysController.name')) }}</th>
                <th>{{ ucfirst(__('Core::sysController.description')) }}</th>
                <th>{{ ucfirst(__('Core::sysController.is_active')) }}</th>
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $sysController)
                <tr>
                    <td>{{ $sysController->sysModule->name ?? '-' }}</td>
                    <td>{{ $sysController->name }}</td>
                    <td>{{ $sysController->description }}</td>
                    <td>{{ $sysController->is_active }}</td>
                    <td class="text-center">
                        @can('show-sysController')
                            <a href="{{ route('sysControllers.show', $sysController) }}" data-id="{{$sysController->id}}" class="btn btn-default btn-sm showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-sysController')
                            <a href="{{ route('sysControllers.edit', $sysController) }}" data-id="{{$sysController->id}}" class="btn btn-sm btn-default editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-sysController')
                            <form action="{{ route('sysControllers.destroy', $sysController) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$sysController->id}}">
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

