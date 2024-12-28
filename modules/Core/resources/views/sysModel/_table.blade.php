{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0" id="sysModelsTable">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <th>{{ ucfirst(__('Core::sysModel.name')) }}</th>
                <th>{{ ucfirst(__('Core::sysModel.description')) }}</th>
                <th>{{ ucfirst(__('Core::sysModule.singular')) }}</th>
                <th>{{ ucfirst(__('Core::sysColor.singular')) }}</th>
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $sysModel)
                <tr>
                    <td>{{ $sysModel->name }}</td>
                    <td>{{ $sysModel->description }}</td>
                    <td>{{ $sysModel->sysModule->name ?? '-' }}</td>
                    <td>{{ $sysModel->sysColor->name ?? '-' }}</td>
                    <td class="text-center">
                        @can('show-sysModel')
                            <a href="{{ route('sysModels.show', $sysModel) }}" data-id="{{$sysModel->id}}" class="btn btn-default btn-sm showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-sysModel')
                            <a href="{{ route('sysModels.edit', $sysModel) }}" data-id="{{$sysModel->id}}" class="btn btn-sm btn-default editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-sysModel')
                            <form action="{{ route('sysModels.destroy', $sysModel) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$sysModel->id}}">
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

