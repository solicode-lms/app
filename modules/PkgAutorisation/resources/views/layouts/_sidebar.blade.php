{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-user', 'index-role', 'index-permission'])
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
        @can('index-user') 
        <li class="nav-item">
            <a href="{{ route('users.index') }}" class="nav-link {{ Request::is('admin/PkgAutorisation/users') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user-circle1"></i>
                {{__('PkgAutorisation::User.plural')}}
            </a>
        </li>
        @endcan
        @can('index-role') 
        <li class="nav-item">
            <a href="{{ route('roles.index') }}" class="nav-link {{ Request::is('admin/PkgAutorisation/roles') ? 'active' : '' }}">
                <i class="nav-icon fas fa-id-badge"></i>
                {{__('PkgAutorisation::Role.plural')}}
            </a>
        </li>
        @endcan
        @can('index-permission') 
        <li class="nav-item">
            <a href="{{ route('permissions.index') }}" class="nav-link {{ Request::is('admin/PkgAutorisation/permissions') ? 'active' : '' }}">
                <i class="nav-icon fas fa-lock-open"></i>
                {{__('PkgAutorisation::Permission.plural')}}
            </a>
        </li>
        @endcan
    </ul>
</li>
@endif

