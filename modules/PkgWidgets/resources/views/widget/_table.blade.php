{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('widget-table')
<div class="card-body table-responsive p-0 crud-card-body" id="widgets-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-widget') || Auth::user()->can('destroy-widget');
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
               
                <x-sortable-column width="8"  field="ordre" modelname="widget" label="{{ ucfirst(__('PkgWidgets::widget.ordre')) }}" />
                <x-sortable-column width="19"  field="name" modelname="widget" label="{{ ucfirst(__('PkgWidgets::widget.name')) }}" />
                <x-sortable-column width="19"  field="label" modelname="widget" label="{{ ucfirst(__('PkgWidgets::widget.label')) }}" />
                <x-sortable-column width="7" field="type_id" modelname="widget" label="{{ ucfirst(__('PkgWidgets::widgetType.singular')) }}" />
                <x-sortable-column width="10"  field="roles" modelname="widget" label="{{ ucfirst(__('PkgAutorisation::role.plural')) }}" />
                <x-sortable-column width="19" field="section_widget_id" modelname="widget" label="{{ ucfirst(__('PkgWidgets::sectionWidget.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('widget-table-tbody')
            @foreach ($widgets_data as $widget)
                <tr id="widget-row-{{$widget->id}}">
                    <x-checkbox-row :item="$widget" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 8%;" class="text-truncate" data-toggle="tooltip" title="{{ $widget->ordre }}" >
                    <x-field :entity="$widget" field="ordre">
                        {{ $widget->ordre }}
                    </x-field>
                    </td>
                    <td style="max-width: 19%;" class="text-truncate" data-toggle="tooltip" title="{{ $widget->name }}" >
                    <x-field :entity="$widget" field="name">
                         <x-badge 
                        :text="$widget->name ?? ''" 
                        :background="$widget->sysColor->hex ?? '#6c757d'" 
                        />

                    </x-field>
                    </td>    
                    <td style="max-width: 19%;" class="text-truncate" data-toggle="tooltip" title="{{ $widget->label }}" >
                    <x-field :entity="$widget" field="label">
                        {{ $widget->label }}
                    </x-field>
                    </td>
                    <td style="max-width: 7%;" class="text-truncate" data-toggle="tooltip" title="{{ $widget->type }}" >
                    <x-field :entity="$widget" field="type">
                       
                         {{  $widget->type }}
                    </x-field>
                    </td>
                    <td style="max-width: 10%;" class="text-truncate" data-toggle="tooltip" title="{{ $widget->roles }}" >
                    <x-field :entity="$widget" field="roles">
                        <ul>
                            @foreach ($widget->roles as $role)
                                <li @if(strlen($role) > 30) data-toggle="tooltip" title="{{$role}}"  @endif>@limit($role, 30)</li>
                            @endforeach
                        </ul>
                    </x-field>
                    </td>
                    <td style="max-width: 19%;" class="text-truncate" data-toggle="tooltip" title="{{ $widget->sectionWidget }}" >
                    <x-field :entity="$widget" field="sectionWidget">
                       
                         {{  $widget->sectionWidget }}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-widget')
                        @can('update', $widget)
                            <a href="{{ route('widgets.edit', ['widget' => $widget->id]) }}" data-id="{{$widget->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @elsecan('show-widget')
                        @can('view', $widget)
                            <a href="{{ route('widgets.show', ['widget' => $widget->id]) }}" data-id="{{$widget->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
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