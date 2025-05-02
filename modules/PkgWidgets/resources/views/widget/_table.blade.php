{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('widget-table')
<div class="card-body p-0 crud-card-body" id="widgets-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-widget') || Auth::user()->can('destroy-widget');
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
                    $isEditable = Auth::user()->can('edit-widget') && Auth::user()->can('update', $widget);
                @endphp
                <tr id="widget-row-{{$widget->id}}" data-id="{{$widget->id}}">
                    <x-checkbox-row :item="$widget" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$widget->id}}" data-field="ordre"  data-toggle="tooltip" title="{{ $widget->ordre }}" >
                    <x-field :entity="$widget" field="ordre">
                         <div class="sortable-button d-flex justify-content-left align-items-center" style="height: 100%;  min-height: 26px;">
                            <i class="fas fa-th-list" title="{{ $widget->ordre }}"  data-toggle="tooltip" ></i>  
                        </div>
                    </x-field>
                    </td>
                    <td style="max-width: 4%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$widget->id}}" data-field="icon"  data-toggle="tooltip" title="{{ $widget->icon }}" >
                    <x-field :entity="$widget" field="icon">
                        <div class="d-flex justify-content-center align-items-center" style="height: 100%;">
                            <i class="{{ $widget->icon }}" ></i>
                        </div>
                    </x-field>
                    </td>

                    <td style="max-width: 19%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$widget->id}}" data-field="name"  data-toggle="tooltip" title="{{ $widget->name }}" >
                    <x-field :entity="$widget" field="name">
                         <x-badge 
                        :text="$widget->name ?? ''" 
                        :background="$widget->sysColor->hex ?? '#6c757d'" 
                        />

                    </x-field>
                    </td> 
                    <td style="max-width: 19%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$widget->id}}" data-field="label"  data-toggle="tooltip" title="{{ $widget->label }}" >
                    <x-field :entity="$widget" field="label">
                        {{ $widget->label }}
                    </x-field>
                    </td>
                    <td style="max-width: 7%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$widget->id}}" data-field="type_id"  data-toggle="tooltip" title="{{ $widget->type }}" >
                    <x-field :entity="$widget" field="type">
                       
                         {{  $widget->type }}
                    </x-field>
                    </td>
                    <td style="max-width: 10%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$widget->id}}" data-field="roles"  data-toggle="tooltip" title="{{ $widget->roles }}" >
                    <x-field :entity="$widget" field="roles">
                        <ul>
                            @foreach ($widget->roles as $role)
                                <li @if(strlen($role) > 30) data-toggle="tooltip" title="{{$role}}"  @endif>@limit($role, 30)</li>
                            @endforeach
                        </ul>
                    </x-field>
                    </td>
                    <td style="max-width: 19%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$widget->id}}" data-field="section_widget_id"  data-toggle="tooltip" title="{{ $widget->sectionWidget }}" >
                    <x-field :entity="$widget" field="sectionWidget">
                       
                         {{  $widget->sectionWidget }}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-widget')
                        <x-action-button :entity="$widget" actionName="edit">
                        @can('update', $widget)
                            <a href="{{ route('widgets.edit', ['widget' => $widget->id]) }}" data-id="{{$widget->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @elsecan('show-widget')
                        <x-action-button :entity="$widget" actionName="show">
                        @can('view', $widget)
                            <a href="{{ route('widgets.show', ['widget' => $widget->id]) }}" data-id="{{$widget->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan

                        <x-action-button :entity="$widget" actionName="delete">
                        @can('destroy-widget')
                        @can('delete', $widget)
                            <form class="context-state" action="{{ route('widgets.destroy',['widget' => $widget->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$widget->id}}">
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
    @section('widget-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $widgets_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>