{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('widgetUtilisateur-table')
<div class="card-body p-0 crud-card-body" id="widgetUtilisateurs-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $widgetUtilisateurs_permissions['edit-widgetUtilisateur'] || $widgetUtilisateurs_permissions['destroy-widgetUtilisateur'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="5"  field="ordre" modelname="widgetUtilisateur" label="{!!ucfirst(__('PkgWidgets::widgetUtilisateur.ordre'))!!}" />
                <x-sortable-column :sortable="true" width="19.25" field="widget_id" modelname="widgetUtilisateur" label="{!!ucfirst(__('PkgWidgets::widget.singular'))!!}" />
                <x-sortable-column :sortable="true" width="19.25"  field="package" modelname="widgetUtilisateur" label="{!!ucfirst(__('PkgWidgets::widgetUtilisateur.package'))!!}" />
                <x-sortable-column :sortable="true" width="19.25"  field="type" modelname="widgetUtilisateur" label="{!!ucfirst(__('PkgWidgets::widgetUtilisateur.type'))!!}" />
                <x-sortable-column :sortable="true" width="19.25"  field="visible" modelname="widgetUtilisateur" label="{!!ucfirst(__('PkgWidgets::widgetUtilisateur.visible'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('widgetUtilisateur-table-tbody')
            @foreach ($widgetUtilisateurs_data as $widgetUtilisateur)
                @php
                    $isEditable = $widgetUtilisateurs_permissions['edit-widgetUtilisateur'] && $widgetUtilisateurs_permissionsByItem['update'][$widgetUtilisateur->id];
                @endphp
                <tr id="widgetUtilisateur-row-{{$widgetUtilisateur->id}}" data-id="{{$widgetUtilisateur->id}}">
                    <x-checkbox-row :item="$widgetUtilisateur" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 5%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$widgetUtilisateur->id}}" data-field="ordre">
                            <div class="sortable-button d-flex justify-content-left align-items-center" style="height: 100%;  min-height: 26px;">
                            <i class="fas fa-th-list" title="{{ $widgetUtilisateur->ordre }}"  data-toggle="tooltip" ></i>  
                        </div>

                    </td>
                    <td style="max-width: 19.25%;white-space: normal;" class=" text-truncate" data-id="{{$widgetUtilisateur->id}}" data-field="widget_id">
                        {{  $widgetUtilisateur->widget }}

                    </td>
                    <td style="max-width: 19.25%;white-space: normal;" class=" text-truncate" data-id="{{$widgetUtilisateur->id}}" data-field="package">
                        {{ $widgetUtilisateur->package }}

                    </td>
                    <td style="max-width: 19.25%;white-space: normal;" class=" text-truncate" data-id="{{$widgetUtilisateur->id}}" data-field="type">
                        {{ $widgetUtilisateur->type }}

                    </td>
                    <td style="max-width: 19.25%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$widgetUtilisateur->id}}" data-field="visible">
                        <span class="{{ $widgetUtilisateur->visible ? 'text-success' : 'text-danger' }}">
                            {{ $widgetUtilisateur->visible ? 'Oui' : 'Non' }}
                        </span>

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($widgetUtilisateurs_permissions['edit-widgetUtilisateur'])
                        <x-action-button :entity="$widgetUtilisateur" actionName="edit">
                        @if($widgetUtilisateurs_permissionsByItem['update'][$widgetUtilisateur->id])
                            <a href="{{ route('widgetUtilisateurs.edit', ['widgetUtilisateur' => $widgetUtilisateur->id]) }}" data-id="{{$widgetUtilisateur->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($widgetUtilisateurs_permissions['show-widgetUtilisateur'])
                        <x-action-button :entity="$widgetUtilisateur" actionName="show">
                        @if($widgetUtilisateurs_permissionsByItem['view'][$widgetUtilisateur->id])
                            <a href="{{ route('widgetUtilisateurs.show', ['widgetUtilisateur' => $widgetUtilisateur->id]) }}" data-id="{{$widgetUtilisateur->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$widgetUtilisateur" actionName="delete">
                        @if($widgetUtilisateurs_permissions['destroy-widgetUtilisateur'])
                        @if($widgetUtilisateurs_permissionsByItem['delete'][$widgetUtilisateur->id])
                            <form class="context-state" action="{{ route('widgetUtilisateurs.destroy',['widgetUtilisateur' => $widgetUtilisateur->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$widgetUtilisateur->id}}">
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
    @section('widgetUtilisateur-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $widgetUtilisateurs_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>