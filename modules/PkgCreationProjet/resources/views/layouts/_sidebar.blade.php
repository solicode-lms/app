{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions([])
@if($accessiblePermissions->isNotEmpty())
<li id="menu-PkgCreationProjet" class="nav-item has-treeview  {{ Request::is('admin/PkgCreationProjet*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgCreationProjet*') ? 'active' : '' }}">
        <i class="nav-icon {{__('PkgCreationProjet::PkgCreationProjet.icon')}}"></i>
        <p>
            {{__('PkgCreationProjet::PkgCreationProjet.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
    </ul>
</li>
@endif

