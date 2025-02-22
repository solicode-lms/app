{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-projet'])
@if($accessiblePermissions->isNotEmpty())
<li class="nav-item has-treeview id='menu-PkgCreationProjet' {{ Request::is('admin/PkgCreationProjet*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgCreationProjet*') ? 'active' : '' }}">
        <i class="nav-icon fas  {{__('PkgCreationProjet::PkgCreationProjet.icon')}}"></i>
        <p>
            {{__('PkgCreationProjet::PkgCreationProjet.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @can('index-projet') 
        <li class="nav-item" id='menu-projets'>
            <a href="{{ route('projets.index') }}" class="nav-link {{ Request::is('admin/PkgCreationProjet/projets') ? 'active' : '' }}">
                <i class="nav-icon fas fa-calendar-alt"></i>
                {{__('PkgCreationProjet::projet.plural')}}
            </a>
        </li>
        @endcan
    </ul>
</li>
@endif

