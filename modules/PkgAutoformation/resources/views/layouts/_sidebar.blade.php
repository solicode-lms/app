{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions([])
@if($accessiblePermissions->isNotEmpty())
<li id="menu-PkgAutoformation" class="nav-item has-treeview  {{ Request::is('admin/PkgAutoformation*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgAutoformation*') ? 'active' : '' }}">
        <i class="nav-icon {{__('PkgAutoformation::PkgAutoformation.icon')}}"></i>
        <p>
            {{__('PkgAutoformation::PkgAutoformation.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
    </ul>
</li>
@endif

