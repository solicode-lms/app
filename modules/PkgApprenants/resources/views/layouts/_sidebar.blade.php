{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions([])
@if($accessiblePermissions->isNotEmpty())
<li class="nav-item has-treeview {{ Request::is('admin/PkgApprenants*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgApprenants*') ? 'active' : '' }}">
        <i class="nav-icon fas  {{__('PkgApprenants::PkgApprenants.icon')}}"></i>
        <p>
            {{__('PkgApprenants::PkgApprenants.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
    </ul>
</li>
@endif

