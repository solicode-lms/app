{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<li class="nav-item has-treeview {{ Request::is('PkgAuthentification*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('PkgAuthentification*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-table"></i>
        <p>
            {{__('PkgAuthentification::module.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('roles.index') }}" class="nav-link {{ Request::is('PkgAuthentification/roles') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                <p>Roles</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('users.index') }}" class="nav-link {{ Request::is('PkgAuthentification/users') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                <p>Users</p>
            </a>
        </li>
    </ul>
</li>


