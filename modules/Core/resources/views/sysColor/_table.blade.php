{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0" id="sysColorsTable">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <th>{{ ucfirst(__('Core::sysColor.name')) }}</th>
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $sysColor)
                <tr>
                    <td>{{ $sysColor->name }}</td>
                    <td class="text-center">
                        @can('show-sysColor')
                            <a href="{{ route('sysColors.show', $sysColor) }}" data-id="{{$sysColor->id}}" class="btn btn-default btn-sm showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-sysColor')
                            <a href="{{ route('sysColors.edit', $sysColor) }}" data-id="{{$sysColor->id}}" class="btn btn-sm btn-default editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-sysColor')
                            <form action="{{ route('sysColors.destroy', $sysColor) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$sysColor->id}}"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce syscolor ?')">
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

