{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('profile-table')
<div class="card-body table-responsive p-0 crud-card-body" id="profiles-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="user_id" modelname="profile" label="{{ ucfirst(__('PkgAutorisation::user.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('profile-table-tbody')
            @foreach ($profiles_data as $profile)
                <tr id="profile-row-{{$profile->id}}">
                    <td>
                     <span @if(strlen($profile->user) > 50) 
                            data-toggle="tooltip" 
                            title="{{ $profile->user }}" 
                        @endif>
                        {{ Str::limit($profile->user, 50) }}
                    </span>
                    </td>
                    <td class="text-right">

                        @can('show-profile')
                        @can('view', $profile)
                            <a href="{{ route('profiles.show', ['profile' => $profile->id]) }}" data-id="{{$profile->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
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
            @show
        </tbody>
    </table>
</div>
@show

<div class="card-footer">
    @section('profile-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $profiles_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>