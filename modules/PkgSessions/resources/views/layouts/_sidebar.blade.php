{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-sessionFormation'])
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

