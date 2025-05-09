{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('profile-table')
<div class="card-body p-0 crud-card-body" id="profiles-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-profile') || Auth::user()->can('destroy-profile');
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
                    $isEditable = Auth::user()->can('edit-profile') && Auth::user()->can('update', $profile);
                @endphp
                <tr id="profile-row-{{$profile->id}}" data-id="{{$profile->id}}">
                    <x-checkbox-row :item="$profile" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 82%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$profile->id}}" data-field="user_id"  data-toggle="tooltip" title="{{ $profile->user }}" >
                    <x-field :entity="$profile" field="user">
                       
                         {{  $profile->user }}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-profile')
                        <x-action-button :entity="$profile" actionName="edit">
                        @can('update', $profile)
                            <a href="{{ route('profiles.edit', ['profile' => $profile->id]) }}" data-id="{{$profile->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan
                        @can('show-profile')
                        <x-action-button :entity="$profile" actionName="show">
                        @can('view', $profile)
                            <a href="{{ route('profiles.show', ['profile' => $profile->id]) }}" data-id="{{$profile->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan

                        <x-action-button :entity="$profile" actionName="delete">
                        @can('destroy-profile')
                        @can('delete', $profile)
                            <form class="context-state" action="{{ route('profiles.destroy',['profile' => $profile->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$profile->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endcan
                        @endcan
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