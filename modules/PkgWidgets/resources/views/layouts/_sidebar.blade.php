{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['show-widget', 'show-widgetOperation', 'show-widgetType'])
@if($accessiblePermissions->isNotEmpty())
<li class="nav-item has-treeview {{ Request::is('admin/PkgWidgets*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgWidgets*') ? 'active' : '' }}">
        <i class="nav-icon fas  {{__('PkgWidgets::PkgWidgets.icon')}}"></i>
        <p>
            {{__('PkgWidgets::PkgWidgets.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @can('show-widget') 
        <li class="nav-item">
            <a href="{{ route('widgets.index') }}" class="nav-link {{ Request::is('admin/PkgWidgets/widgets') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                <p>Widgets</p>
            </a>
        </li>
        @endcan
        @can('show-widgetOperation') 
        <li class="nav-item">
            <a href="{{ route('widgetOperations.index') }}" class="nav-link {{ Request::is('admin/PkgWidgets/widgetOperations') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                <p>WidgetOperations</p>
            </a>
        </li>
        @endcan
        @can('show-widgetType') 
        <li class="nav-item">
            <a href="{{ route('widgetTypes.index') }}" class="nav-link {{ Request::is('admin/PkgWidgets/widgetTypes') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                <p>WidgetTypes</p>
            </a>
        </li>
        @endcan
    </ul>
</li>
@endif

