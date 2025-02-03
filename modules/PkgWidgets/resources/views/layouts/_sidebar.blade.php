{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-widgetType', 'index-widgetOperation', 'index-widget'])
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
        @can('index-widgetType') 
        <li class="nav-item">
            <a href="{{ route('widgetTypes.index') }}" class="nav-link {{ Request::is('admin/PkgWidgets/widgetTypes') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgWidgets::widgetType.plural')}}
            </a>
        </li>
        @endcan
        @can('index-widgetOperation') 
        <li class="nav-item">
            <a href="{{ route('widgetOperations.index') }}" class="nav-link {{ Request::is('admin/PkgWidgets/widgetOperations') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgWidgets::widgetOperation.plural')}}
            </a>
        </li>
        @endcan
        @can('index-widget') 
        <li class="nav-item">
            <a href="{{ route('widgets.index') }}" class="nav-link {{ Request::is('admin/PkgWidgets/widgets') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgWidgets::widget.plural')}}
            </a>
        </li>
        @endcan
    </ul>
</li>
@endif

