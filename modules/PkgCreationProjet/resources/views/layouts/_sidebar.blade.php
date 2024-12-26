{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<li class="nav-item has-treeview {{ Request::is('PkgCreationProjet*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('PkgCreationProjet*') ? 'active' : '' }}">
        <i class="nav-icon fas  {{__('PkgCreationProjet::PkgCreationProjet.icon')}}"></i>
        <p>
            {{__('PkgCreationProjet::PkgCreationProjet.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
    </ul>
</li>


