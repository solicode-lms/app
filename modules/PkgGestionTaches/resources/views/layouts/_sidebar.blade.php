{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-etatRealisationTache', 'index-prioriteTache', 'index-typeDependanceTache', 'index-realisationTache', 'index-workflowTache'])
@if($accessiblePermissions->isNotEmpty())
<li id="menu-PkgGestionTaches" class="nav-item has-treeview  {{ Request::is('admin/PkgGestionTaches*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgGestionTaches*') ? 'active' : '' }}">
        <i class="nav-icon {{__('PkgGestionTaches::PkgGestionTaches.icon')}}"></i>
        <p>
            {{__('PkgGestionTaches::PkgGestionTaches.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @can('index-etatRealisationTache') 
        <li class="nav-item" id="menu-etatRealisationTaches">
            <a href="{{ route('etatRealisationTaches.index') }}" class="nav-link {{ Request::is('admin/PkgGestionTaches/etatRealisationTaches') ? 'active' : '' }}">
                <i class="nav-icon fas fa-check"></i>
                {{__('PkgGestionTaches::etatRealisationTache.plural')}}
            </a>
        </li>
        @endcan
        @can('index-prioriteTache') 
        <li class="nav-item" id="menu-prioriteTaches">
            <a href="{{ route('prioriteTaches.index') }}" class="nav-link {{ Request::is('admin/PkgGestionTaches/prioriteTaches') ? 'active' : '' }}">
                <i class="nav-icon fas fa-list-ol"></i>
                {{__('PkgGestionTaches::prioriteTache.plural')}}
            </a>
        </li>
        @endcan
        @can('index-typeDependanceTache') 
        <li class="nav-item" id="menu-typeDependanceTaches">
            <a href="{{ route('typeDependanceTaches.index') }}" class="nav-link {{ Request::is('admin/PkgGestionTaches/typeDependanceTaches') ? 'active' : '' }}">
                <i class="nav-icon fas fa-random"></i>
                {{__('PkgGestionTaches::typeDependanceTache.plural')}}
            </a>
        </li>
        @endcan
        @can('index-realisationTache') 
        <li class="nav-item" id="menu-realisationTaches">
            <a href="{{ route('realisationTaches.index') }}" class="nav-link {{ Request::is('admin/PkgGestionTaches/realisationTaches') ? 'active' : '' }}">
                <i class="nav-icon fas fa-laptop-code"></i>
                {{__('PkgGestionTaches::realisationTache.plural')}}
            </a>
        </li>
        @endcan
        @can('index-workflowTache') 
        <li class="nav-item" id="menu-workflowTaches">
            <a href="{{ route('workflowTaches.index') }}" class="nav-link {{ Request::is('admin/PkgGestionTaches/workflowTaches') ? 'active' : '' }}">
                <i class="nav-icon fas fa-check-square"></i>
                {{__('PkgGestionTaches::workflowTache.plural')}}
            </a>
        </li>
        @endcan
    </ul>
</li>
@endif

