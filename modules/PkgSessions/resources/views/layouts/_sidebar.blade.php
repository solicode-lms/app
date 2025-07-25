{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions([])
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
    </ul>
</li>
@endif

