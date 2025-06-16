{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('widget-table')
<div class="card-body p-0 crud-card-body" id="widgets-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $widgets_permissions['edit-widget'] || $devwidgets_permissions['destroy-widget'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="4"  field="ordre" modelname="widget" label="{{ucfirst(__('PkgWidgets::widget.ordre'))}}" />
                <x-sortable-column :sortable="true" width="4"  field="icon" modelname="widget" label="{{ucfirst(__('PkgWidgets::widget.icon'))}}" />
                <x-sortable-column :sortable="true" width="19"  field="name" modelname="widget" label="{{ucfirst(__('PkgWidgets::widget.name'))}}" />
                <x-sortable-column :sortable="true" width="19"  field="label" modelname="widget" label="{{ucfirst(__('PkgWidgets::widget.label'))}}" />
                <x-sortable-column :sortable="true" width="7" field="type_id" modelname="widget" label="{{ucfirst(__('PkgWidgets::widgetType.singular'))}}" />
                <x-sortable-column :sortable="true" width="10"  field="roles" modelname="widget" label="{{ucfirst(__('PkgAutorisation::role.plural'))}}" />
                <x-sortable-column :sortable="true" width="19" field="section_widget_id" modelname="widget" label="{{ucfirst(__('PkgWidgets::sectionWidget.singular'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('widget-table-tbody')
            @foreach ($widgets_data as $widget)
                @php
                    $isEditable = $widgets_permissions['edit-widget'] && $widgets_permissionsByItem['update'][$widget->id];
                @endphp
                <tr id="widget-row-{{$widget->id}}" data-id="{{$widget->id}}">
                    <x-checkbox-row :item="$widget" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$widget->id}}" data-field="ordre"  data-toggle="tooltip" title="{{ $widget->ordre }}" >
                            <div class="sortable-button d-flex justify-content-left align-items-center" style="height: 100%;  min-height: 26px;">
                            <i class="fas fa-th-list" title="{{ $widget->ordre }}"  data-toggle="tooltip" ></i>  
                        </div>

                    </td>
                    <td style="max-width: 4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$widget->id}}" data-field="icon"  data-toggle="tooltip" title="{{ $widget->icon }}" >
                        <div class="d-flex justify-content-center align-items-center" style="height: 100%;">
                            <i class="{{ $widget->icon }}" ></i>
                        </div>

                    </td>
                    <td style="max-width: 19%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$widget->id}}" data-field="name"  data-toggle="tooltip" title="{{ $widget->name }}" >
                    <x-badge 
                        :text="$widget->name ?? ''" 
                        :background="$widget->sysColor->hex ?? '#6c757d'" 
                        />

                    </td>
                    <td style="max-width: 19%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$widget->id}}" data-field="label"  data-toggle="tooltip" title="{{ $widget->label }}" >
                        {{ $widget->label }}

                    </td>
                    <td style="max-width: 7%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$widget->id}}" data-field="type_id"  data-toggle="tooltip" title="{{ $widget->type }}" >
                        {{  $widget->type }}

                    </td>
                    <td style="max-width: 10%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$widget->id}}" data-field="roles"  data-toggle="tooltip" title="{{ $widget->roles }}" >
                        <ul>
                            @foreach ($widget->roles as $role)
                                <li @if(strlen($role) > 30) data-toggle="tooltip" title="{{$role}}"  @endif>@limit($role, 30)</li>
                            @endforeach
                        </ul>
                    </td>
                    <td style="max-width: 19%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$widget->id}}" data-field="section_widget_id"  data-toggle="tooltip" title="{{ $widget->sectionWidget }}" >
                        {{  $widget->sectionWidget }}

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($widgets_permissions['edit-widget'])
                        <x-action-button :entity="$widget" actionName="edit">
                        @if($widgets_permissionsByItem['update'][$widget->id])
                            <a href="{{ route('widgets.edit', ['widget' => $widget->id]) }}" data-id="{{$widget->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($widgets_permissions['show-widget'])
                        <x-action-button :entity="$widget" actionName="show">
                        @if($widgets_permissionsByItem['view'][$widget->id])
                            <a href="{{ route('widgets.show', ['widget' => $widget->id]) }}" data-id="{{$widget->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$widget" actionName="delete">
                        @if($widgets_permissions['destroy-widget'])
                        @if($widgets_permissionsByItem['delete'][$widget->id])
                            <form class="context-state" action="{{ route('widgets.destroy',['widget' => $widget->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$widget->id}}">
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
    @section('widget-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $widgets_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>