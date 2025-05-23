{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('eMetadataDefinition-table')
<div class="card-body p-0 crud-card-body" id="eMetadataDefinitions-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-eMetadataDefinition') || Auth::user()->can('destroy-eMetadataDefinition');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="name" modelname="eMetadataDefinition" label="{{ucfirst(__('PkgGapp::eMetadataDefinition.name'))}}" />
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="groupe" modelname="eMetadataDefinition" label="{{ucfirst(__('PkgGapp::eMetadataDefinition.groupe'))}}" />
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="description" modelname="eMetadataDefinition" label="{{ucfirst(__('PkgGapp::eMetadataDefinition.description'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('eMetadataDefinition-table-tbody')
            @foreach ($eMetadataDefinitions_data as $eMetadataDefinition)
                @php
                    $isEditable = Auth::user()->can('edit-eMetadataDefinition') && Auth::user()->can('update', $eMetadataDefinition);
                @endphp
                <tr id="eMetadataDefinition-row-{{$eMetadataDefinition->id}}" data-id="{{$eMetadataDefinition->id}}">
                    <x-checkbox-row :item="$eMetadataDefinition" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$eMetadataDefinition->id}}" data-field="name"  data-toggle="tooltip" title="{{ $eMetadataDefinition->name }}" >
                    <x-field :entity="$eMetadataDefinition" field="name">
                        {{ $eMetadataDefinition->name }}
                    </x-field>
                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$eMetadataDefinition->id}}" data-field="groupe"  data-toggle="tooltip" title="{{ $eMetadataDefinition->groupe }}" >
                    <x-field :entity="$eMetadataDefinition" field="groupe">
                        {{ $eMetadataDefinition->groupe }}
                    </x-field>
                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$eMetadataDefinition->id}}" data-field="description"  data-toggle="tooltip" title="{{ $eMetadataDefinition->description }}" >
                    <x-field :entity="$eMetadataDefinition" field="description">
                        {!! \App\Helpers\TextHelper::formatHtmlWithLineBreaks($eMetadataDefinition->description, 30) !!}
                    </x-field>
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @can('edit-eMetadataDefinition')
                        <x-action-button :entity="$eMetadataDefinition" actionName="edit">
                        @can('update', $eMetadataDefinition)
                            <a href="{{ route('eMetadataDefinitions.edit', ['eMetadataDefinition' => $eMetadataDefinition->id]) }}" data-id="{{$eMetadataDefinition->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan
                        @can('show-eMetadataDefinition')
                        <x-action-button :entity="$eMetadataDefinition" actionName="show">
                        @can('view', $eMetadataDefinition)
                            <a href="{{ route('eMetadataDefinitions.show', ['eMetadataDefinition' => $eMetadataDefinition->id]) }}" data-id="{{$eMetadataDefinition->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan

                        <x-action-button :entity="$eMetadataDefinition" actionName="delete">
                        @can('destroy-eMetadataDefinition')
                        @can('delete', $eMetadataDefinition)
                            <form class="context-state" action="{{ route('eMetadataDefinitions.destroy',['eMetadataDefinition' => $eMetadataDefinition->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$eMetadataDefinition->id}}">
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
    @section('eMetadataDefinition-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $eMetadataDefinitions_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>