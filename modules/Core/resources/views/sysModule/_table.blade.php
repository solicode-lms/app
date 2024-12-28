{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0" id="sysModulesTable">
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
            @foreach ($data as $sysModule)
                <tr>
                    <td>{{ $sysModule->name }}</td>
                    <td>{{ $sysModule->description }}</td>
                    <td>{{ $sysModule->is_active }}</td>
                    <td>{{ $sysModule->sysColor->name ?? '-' }}</td>
                    <td class="text-center">
                        @can('show-sysModule')
                            <a href="{{ route('sysModules.show', $sysModule) }}" data-id="{{$sysModule->id}}" class="btn btn-default btn-sm showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-sysModule')
                            <a href="{{ route('sysModules.edit', $sysModule) }}" data-id="{{$sysModule->id}}" class="btn btn-sm btn-default editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-sysModule')
                            <form action="{{ route('sysModules.destroy', $sysModule) }}" method="POST" style="display: inline;">
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

