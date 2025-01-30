{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions([])
@if($accessiblePermissions->isNotEmpty())
<li class="nav-item has-treeview {{ Request::is('admin/PkgUtilisateurs*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgUtilisateurs*') ? 'active' : '' }}">
        <i class="nav-icon fas  {{__('PkgUtilisateurs::PkgUtilisateurs.icon')}}"></i>
        <p>
            {{__('PkgUtilisateurs::PkgUtilisateurs.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
    </ul>
</li>
@endif

