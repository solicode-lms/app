{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-user', 'index-role', 'index-permission'])
@if($accessiblePermissions->isNotEmpty())
    @if($accessiblePermissions->count() === 1)
        {{-- Cas d’un seul élément accessible --}}
            @can('index-user')
            <li class="nav-item" id="menu-users">
                <a href="{{ route('users.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgAutorisation/users') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-user"></i>
                    <p>{{__('PkgAutorisation::user.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-role')
            <li class="nav-item" id="menu-roles">
                <a href="{{ route('roles.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgAutorisation/roles') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-id-badge"></i>
                    <p>{{__('PkgAutorisation::role.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-permission')
            <li class="nav-item" id="menu-permissions">
                <a href="{{ route('permissions.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgAutorisation/permissions') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-lock-open"></i>
                    <p>{{__('PkgAutorisation::permission.plural')}}</p>
                </a>
            </li>
            @endcan

    @else
    <li id="menu-PkgAutorisation" class="nav-item has-treeview  {{ Request::is('admin/PkgAutorisation*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgAutorisation*') ? 'active' : '' }}">
            <i class="nav-icon {{__('PkgAutorisation::PkgAutorisation.icon')}}"></i>
            <p>
                {{__('PkgAutorisation::PkgAutorisation.name')}}
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            @can('index-user') 
            <li class="nav-item" id="menu-users">
                <a href="{{ route('users.index') }}" class="nav-link {{ Request::is('admin/PkgAutorisation/users') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-user"></i>
                    <p>{{__('PkgAutorisation::user.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-role') 
            <li class="nav-item" id="menu-roles">
                <a href="{{ route('roles.index') }}" class="nav-link {{ Request::is('admin/PkgAutorisation/roles') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-id-badge"></i>
                    <p>{{__('PkgAutorisation::role.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-permission') 
            <li class="nav-item" id="menu-permissions">
                <a href="{{ route('permissions.index') }}" class="nav-link {{ Request::is('admin/PkgAutorisation/permissions') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-lock-open"></i>
                    <p>{{__('PkgAutorisation::permission.plural')}}</p>
                </a>
            </li>
            @endcan
        </ul>
    </li>
  @endif
@endif

