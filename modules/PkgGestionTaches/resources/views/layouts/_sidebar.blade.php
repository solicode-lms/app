{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-etatRealisationTache', 'index-labelRealisationTache', 'index-prioriteTache', 'index-tache', 'index-typeDependanceTache', 'index-dependanceTache', 'index-realisationTache', 'index-historiqueRealisationTache', 'index-commentaireRealisationTache'])
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
        @can('index-etatRealisationTache') 
        <li class="nav-item" id="menu-etatRealisationTaches">
            <a href="{{ route('etatRealisationTaches.index') }}" class="nav-link {{ Request::is('admin/PkgGestionTaches/etatRealisationTaches') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgGestionTaches::etatRealisationTache.plural')}}
            </a>
        </li>
        @endcan
        @can('index-labelRealisationTache') 
        <li class="nav-item" id="menu-labelRealisationTaches">
            <a href="{{ route('labelRealisationTaches.index') }}" class="nav-link {{ Request::is('admin/PkgGestionTaches/labelRealisationTaches') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgGestionTaches::labelRealisationTache.plural')}}
            </a>
        </li>
        @endcan
        @can('index-prioriteTache') 
        <li class="nav-item" id="menu-prioriteTaches">
            <a href="{{ route('prioriteTaches.index') }}" class="nav-link {{ Request::is('admin/PkgGestionTaches/prioriteTaches') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgGestionTaches::prioriteTache.plural')}}
            </a>
        </li>
        @endcan
        @can('index-tache') 
        <li class="nav-item" id="menu-taches">
            <a href="{{ route('taches.index') }}" class="nav-link {{ Request::is('admin/PkgGestionTaches/taches') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgGestionTaches::tache.plural')}}
            </a>
        </li>
        @endcan
        @can('index-typeDependanceTache') 
        <li class="nav-item" id="menu-typeDependanceTaches">
            <a href="{{ route('typeDependanceTaches.index') }}" class="nav-link {{ Request::is('admin/PkgGestionTaches/typeDependanceTaches') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgGestionTaches::typeDependanceTache.plural')}}
            </a>
        </li>
        @endcan
        @can('index-dependanceTache') 
        <li class="nav-item" id="menu-dependanceTaches">
            <a href="{{ route('dependanceTaches.index') }}" class="nav-link {{ Request::is('admin/PkgGestionTaches/dependanceTaches') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgGestionTaches::dependanceTache.plural')}}
            </a>
        </li>
        @endcan
        @can('index-realisationTache') 
        <li class="nav-item" id="menu-realisationTaches">
            <a href="{{ route('realisationTaches.index') }}" class="nav-link {{ Request::is('admin/PkgGestionTaches/realisationTaches') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgGestionTaches::realisationTache.plural')}}
            </a>
        </li>
        @endcan
        @can('index-historiqueRealisationTache') 
        <li class="nav-item" id="menu-historiqueRealisationTaches">
            <a href="{{ route('historiqueRealisationTaches.index') }}" class="nav-link {{ Request::is('admin/PkgGestionTaches/historiqueRealisationTaches') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgGestionTaches::historiqueRealisationTache.plural')}}
            </a>
        </li>
        @endcan
        @can('index-commentaireRealisationTache') 
        <li class="nav-item" id="menu-commentaireRealisationTaches">
            <a href="{{ route('commentaireRealisationTaches.index') }}" class="nav-link {{ Request::is('admin/PkgGestionTaches/commentaireRealisationTaches') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgGestionTaches::commentaireRealisationTache.plural')}}
            </a>
        </li>
        @endcan
    </ul>
</li>
@endif

