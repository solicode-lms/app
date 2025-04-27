{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-sectionWidget', 'index-widgetOperation', 'index-widgetType', 'index-widgetUtilisateur', 'index-widget'])
@if($accessiblePermissions->isNotEmpty())
<li id="menu-PkgWidgets" class="nav-item has-treeview  {{ Request::is('admin/PkgWidgets*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgWidgets*') ? 'active' : '' }}">
        <i class="nav-icon {{__('PkgWidgets::PkgWidgets.icon')}}"></i>
        <p>
            {{__('PkgWidgets::PkgWidgets.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @can('index-sectionWidget') 
        <li class="nav-item" id="menu-sectionWidgets">
            <a href="{{ route('sectionWidgets.index') }}" class="nav-link {{ Request::is('admin/PkgWidgets/sectionWidgets') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgWidgets::sectionWidget.plural')}}
            </a>
        </li>
        @endcan
        @can('index-widgetOperation') 
        <li class="nav-item" id="menu-widgetOperations">
            <a href="{{ route('widgetOperations.index') }}" class="nav-link {{ Request::is('admin/PkgWidgets/widgetOperations') ? 'active' : '' }}">
                <i class="nav-icon fas fa-calculator"></i>
                {{__('PkgWidgets::widgetOperation.plural')}}
            </a>
        </li>
        @endcan
        @can('index-widgetType') 
        <li class="nav-item" id="menu-widgetTypes">
            <a href="{{ route('widgetTypes.index') }}" class="nav-link {{ Request::is('admin/PkgWidgets/widgetTypes') ? 'active' : '' }}">
                <i class="nav-icon fas fa-cube"></i>
                {{__('PkgWidgets::widgetType.plural')}}
            </a>
        </li>
        @endcan
        @can('index-widgetUtilisateur') 
        <li class="nav-item" id="menu-widgetUtilisateurs">
            <a href="{{ route('widgetUtilisateurs.index') }}" class="nav-link {{ Request::is('admin/PkgWidgets/widgetUtilisateurs') ? 'active' : '' }}">
                <i class="nav-icon fas fa-chart-pie"></i>
                {{__('PkgWidgets::widgetUtilisateur.plural')}}
            </a>
        </li>
        @endcan
        @can('index-widget') 
        <li class="nav-item" id="menu-widgets">
            <a href="{{ route('widgets.index') }}" class="nav-link {{ Request::is('admin/PkgWidgets/widgets') ? 'active' : '' }}">
                <i class="nav-icon fas fa-chart-bar"></i>
                {{__('PkgWidgets::widget.plural')}}
            </a>
        </li>
        @endcan
    </ul>
</li>
@endif

