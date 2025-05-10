{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-projet'])
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
        @can('index-projet') 
        <li class="nav-item" id="menu-projets">
            <a href="{{ route('projets.index') }}" class="nav-link {{ Request::is('admin/PkgCreationProjet/projets') ? 'active' : '' }}">
                <i class="nav-icon fas fa-lightbulb"></i>
                {{__('PkgCreationProjet::projet.plural')}}
            </a>
        </li>
        @endcan
    </ul>
</li>
@endif

