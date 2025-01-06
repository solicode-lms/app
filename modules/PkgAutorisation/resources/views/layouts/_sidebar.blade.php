{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['show-permission', 'show-role', 'show-user'])
@if($accessiblePermissions->isNotEmpty())
<li class="nav-item has-treeview {{ Request::is('admin/PkgAutorisation*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgAutorisation*') ? 'active' : '' }}">
        <i class="nav-icon fas  {{__('PkgAutorisation::PkgAutorisation.icon')}}"></i>
        <p>
            {{__('PkgAutorisation::PkgAutorisation.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @can('show-permission') 
        <li class="nav-item">
            <a href="{{ route('permissions.index') }}" class="nav-link {{ Request::is('admin/PkgAutorisation/permissions') ? 'active' : '' }}">
                <i class="nav-icon fas fa-lock-open"></i>
                <p>Permissions</p>
            </a>
        </li>
        @endcan
        @can('show-role') 
        <li class="nav-item">
            <a href="{{ route('roles.index') }}" class="nav-link {{ Request::is('admin/PkgAutorisation/roles') ? 'active' : '' }}">
                <i class="nav-icon fas fa-id-badge"></i>
                <p>Roles</p>
            </a>
        </li>
        @endcan
        @can('show-user') 
        <li class="nav-item">
            <a href="{{ route('users.index') }}" class="nav-link {{ Request::is('admin/PkgAutorisation/users') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user-circle"></i>
                <p>Users</p>
            </a>
        </li>
        @endcan
    </ul>
</li>
@endif

