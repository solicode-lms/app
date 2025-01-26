{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions([])
@if($accessiblePermissions->isNotEmpty())
<li class="nav-item has-treeview {{ Request::is('admin/PkgAutorisation*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgAutorisation*') ? 'active' : '' }}">
        <i class="nav-icon fas  {{__('PkgAutorisation::PkgAutorisation.icon')}}"></i>
        <p>
            {{__('PkgAutorisation::PkgAutorisation.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
    </ul>
</li>
@endif

