{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('feature-table')
<div class="card-body p-0 crud-card-body" id="features-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $features_permissions['edit-feature'] || $devfeatures_permissions['destroy-feature'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="name" modelname="feature" label="{{ucfirst(__('Core::feature.name'))}}" />
                <x-sortable-column :sortable="true" width="27.333333333333332" field="feature_domain_id" modelname="feature" label="{{ucfirst(__('Core::featureDomain.singular'))}}" />
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="permissions" modelname="feature" label="{{ucfirst(__('PkgAutorisation::permission.plural'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('feature-table-tbody')
            @foreach ($features_data as $feature)
                @php
                    $isEditable = $features_permissions['edit-feature'] && $features_permissionsByItem['update'][$feature->id];
                @endphp
                <tr id="feature-row-{{$feature->id}}" data-id="{{$feature->id}}">
                    <x-checkbox-row :item="$feature" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$feature->id}}" data-field="name"  data-toggle="tooltip" title="{{ $feature->name }}" >
                        {{ $feature->name }}

                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$feature->id}}" data-field="feature_domain_id"  data-toggle="tooltip" title="{{ $feature->featureDomain }}" >
                        {{  $feature->featureDomain }}

                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$feature->id}}" data-field="permissions"  data-toggle="tooltip" title="{{ $feature->permissions }}" >
                        <ul>
                            @foreach ($feature->permissions as $permission)
                                <li @if(strlen($permission) > 30) data-toggle="tooltip" title="{{$permission}}"  @endif>@limit($permission, 30)</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($features_permissions['edit-feature'])
                        <x-action-button :entity="$feature" actionName="edit">
                        @if($features_permissionsByItem['update'][$feature->id])
                            <a href="{{ route('features.edit', ['feature' => $feature->id]) }}" data-id="{{$feature->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($features_permissions['show-feature'])
                        <x-action-button :entity="$feature" actionName="show">
                        @if($features_permissionsByItem['view'][$feature->id])
                            <a href="{{ route('features.show', ['feature' => $feature->id]) }}" data-id="{{$feature->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$feature" actionName="delete">
                        @if($features_permissions['destroy-feature'])
                        @if($features_permissionsByItem['delete'][$feature->id])
                            <form class="context-state" action="{{ route('features.destroy',['feature' => $feature->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$feature->id}}">
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
    @section('feature-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $features_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>