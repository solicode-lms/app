{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<li class="nav-item has-treeview {{ Request::is('PkgWidgets*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('PkgWidgets*') ? 'active' : '' }}">
        <i class="nav-icon fas  {{__('PkgWidgets::PkgWidgets.icon')}}"></i>
        <p>
            {{__('PkgWidgets::PkgWidgets.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('widgets.index') }}" class="nav-link {{ Request::is('PkgWidgets/widgets') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                <p>Widgets</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('widgetOperations.index') }}" class="nav-link {{ Request::is('PkgWidgets/widgetOperations') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                <p>WidgetOperations</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('widgetTypes.index') }}" class="nav-link {{ Request::is('PkgWidgets/widgetTypes') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                <p>WidgetTypes</p>
            </a>
        </li>
    </ul>
</li>


