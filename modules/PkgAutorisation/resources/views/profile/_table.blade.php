{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('profile-table')
<div class="card-body p-0 crud-card-body" id="profiles-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $profiles_permissions['edit-profile'] || $profiles_permissions['destroy-profile'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="82" field="user_id" modelname="profile" label="{{ucfirst(__('PkgAutorisation::user.singular'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('profile-table-tbody')
            @foreach ($profiles_data as $profile)
                @php
                    $isEditable = $profiles_permissions['edit-profile'] && $profiles_permissionsByItem['update'][$profile->id];
                @endphp
                <tr id="profile-row-{{$profile->id}}" data-id="{{$profile->id}}">
                    <x-checkbox-row :item="$profile" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 82%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$profile->id}}" data-field="user_id"  data-toggle="tooltip" title="{{ $profile->user }}" >
                        {{  $profile->user }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($profiles_permissions['edit-profile'])
                        <x-action-button :entity="$profile" actionName="edit">
                        @if($profiles_permissionsByItem['update'][$profile->id])
                            <a href="{{ route('profiles.edit', ['profile' => $profile->id]) }}" data-id="{{$profile->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($profiles_permissions['show-profile'])
                        <x-action-button :entity="$profile" actionName="show">
                        @if($profiles_permissionsByItem['view'][$profile->id])
                            <a href="{{ route('profiles.show', ['profile' => $profile->id]) }}" data-id="{{$profile->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$profile" actionName="delete">
                        @if($profiles_permissions['destroy-profile'])
                        @if($profiles_permissionsByItem['delete'][$profile->id])
                            <form class="context-state" action="{{ route('profiles.destroy',['profile' => $profile->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$profile->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                        @endif
                        </x-action-button>
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