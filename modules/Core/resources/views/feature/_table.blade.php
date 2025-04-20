{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('feature-table')
<div class="card-body table-responsive p-0 crud-card-body" id="features-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-feature') || Auth::user()->can('destroy-feature');
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
                <tr id="feature-row-{{$feature->id}}" data-id="{{$feature->id}}">
                    <x-checkbox-row :item="$feature" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 27.333333333333332%;" class="text-truncate" data-toggle="tooltip" title="{{ $feature->name }}" >
                    <x-field :entity="$feature" field="name">
                        {{ $feature->name }}
                    </x-field>
                    </td>
                    <td style="max-width: 27.333333333333332%;" class="text-truncate" data-toggle="tooltip" title="{{ $feature->featureDomain }}" >
                    <x-field :entity="$feature" field="featureDomain">
                       
                         {{  $feature->featureDomain }}
                    </x-field>
                    </td>
                    <td style="max-width: 27.333333333333332%;" class="text-truncate" data-toggle="tooltip" title="{{ $feature->permissions }}" >
                    <x-field :entity="$feature" field="permissions">
                        <ul>
                            @foreach ($feature->permissions as $permission)
                                <li @if(strlen($permission) > 30) data-toggle="tooltip" title="{{$permission}}"  @endif>@limit($permission, 30)</li>
                            @endforeach
                        </ul>
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-feature')
                        @can('update', $feature)
                            <a href="{{ route('features.edit', ['feature' => $feature->id]) }}" data-id="{{$feature->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-feature')
                        @can('view', $feature)
                            <a href="{{ route('features.show', ['feature' => $feature->id]) }}" data-id="{{$feature->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-feature')
                        @can('delete', $feature)
                            <form class="context-state" action="{{ route('features.destroy',['feature' => $feature->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$feature->id}}">
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
    @section('feature-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $features_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>