{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions([])
@if($accessiblePermissions->isNotEmpty())
<li id="menu-PkgGestionTaches" class="nav-item has-treeview  {{ Request::is('admin/PkgGestionTaches*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgGestionTaches*') ? 'active' : '' }}">
        <i class="nav-icon fas  {{__('PkgGestionTaches::PkgGestionTaches.icon')}}"></i>
        <p>
            {{__('PkgGestionTaches::PkgGestionTaches.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
    </ul>
</li>
@endif

