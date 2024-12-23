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
    </ul>
</li>


