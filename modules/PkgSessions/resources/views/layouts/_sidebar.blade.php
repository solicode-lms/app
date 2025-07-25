{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-alignementUa', 'index-livrableSession', 'index-sessionFormation'])
@if($accessiblePermissions->isNotEmpty())
<li id="menu-PkgSessions" class="nav-item has-treeview  {{ Request::is('admin/PkgSessions*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgSessions*') ? 'active' : '' }}">
        <i class="nav-icon {{__('PkgSessions::PkgSessions.icon')}}"></i>
        <p>
            {{__('PkgSessions::PkgSessions.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @can('index-alignementUa') 
        <li class="nav-item" id="menu-alignementUas">
            <a href="{{ route('alignementUas.index') }}" class="nav-link {{ Request::is('admin/PkgSessions/alignementUas') ? 'active' : '' }}">
                <i class="nav-icon fas fa-road"></i>
                {{__('PkgSessions::alignementUa.plural')}}
            </a>
        </li>
        @endcan
        @can('index-livrableSession') 
        <li class="nav-item" id="menu-livrableSessions">
            <a href="{{ route('livrableSessions.index') }}" class="nav-link {{ Request::is('admin/PkgSessions/livrableSessions') ? 'active' : '' }}">
                <i class="nav-icon fas fa-folder"></i>
                {{__('PkgSessions::livrableSession.plural')}}
            </a>
        </li>
        @endcan
        @can('index-sessionFormation') 
        <li class="nav-item" id="menu-sessionFormations">
            <a href="{{ route('sessionFormations.index') }}" class="nav-link {{ Request::is('admin/PkgSessions/sessionFormations') ? 'active' : '' }}">
                <i class="nav-icon fas fa-map"></i>
                {{__('PkgSessions::sessionFormation.plural')}}
            </a>
        </li>
        @endcan
    </ul>
</li>
@endif

