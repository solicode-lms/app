{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions([])
@if($accessiblePermissions->isNotEmpty())
<li id="menu-PkgApprentissage" class="nav-item has-treeview  {{ Request::is('admin/PkgApprentissage*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgApprentissage*') ? 'active' : '' }}">
        <i class="nav-icon {{__('PkgApprentissage::PkgApprentissage.icon')}}"></i>
        <p>
            {{__('PkgApprentissage::PkgApprentissage.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
    </ul>
</li>
@endif

