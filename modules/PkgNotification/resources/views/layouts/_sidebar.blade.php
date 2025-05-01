{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions([])
@if($accessiblePermissions->isNotEmpty())
<li id="menu-PkgNotification" class="nav-item has-treeview  {{ Request::is('admin/PkgNotification*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgNotification*') ? 'active' : '' }}">
        <i class="nav-icon {{__('PkgNotification::PkgNotification.icon')}}"></i>
        <p>
            {{__('PkgNotification::PkgNotification.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
    </ul>
</li>
@endif

