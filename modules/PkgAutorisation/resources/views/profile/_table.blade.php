{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="profiles-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="user_id" modelname="profile" label="{{ ucfirst(__('PkgAutorisation::user.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($profiles_data as $profile)
                <tr id="profile-row-{{$profile->id}}">
                    <td>@limit($profile->user, 80)</td>
                    <td class="text-right">

                        @can('show-profile')
                            <a href="{{ route('profiles.show', ['profile' => $profile->id]) }}" data-id="{{$profile->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-profile')
                        @can('update', $profile)
                            <a href="{{ route('profiles.edit', ['profile' => $profile->id]) }}" data-id="{{$profile->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-profile')
                        @can('delete', $profile)
                            <form class="context-state" action="{{ route('profiles.destroy',['profile' => $profile->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$profile->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endcan
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="card-footer">
    @section('profile-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $profiles_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>