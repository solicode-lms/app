{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<li class="nav-item has-treeview {{ Request::is('PkgAutorisation*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('PkgAutorisation*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-table"></i>
        <p>
            PkgAutorisation
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('permissions.index') }}" class="nav-link {{ Request::is('PkgAutorisation/permissions') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                <p>Permissions</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('roles.index') }}" class="nav-link {{ Request::is('PkgAutorisation/roles') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                <p>Roles</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('users.index') }}" class="nav-link {{ Request::is('PkgAutorisation/users') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                <p>Users</p>
            </a>
        </li>
    </ul>
</li>


