{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-etatRealisationTache', 'index-realisationTache', 'index-workflowTache'])
@if($accessiblePermissions->isNotEmpty())
<li id="menu-PkgRealisationTache" class="nav-item has-treeview  {{ Request::is('admin/PkgRealisationTache*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgRealisationTache*') ? 'active' : '' }}">
        <i class="nav-icon {{__('PkgRealisationTache::PkgRealisationTache.icon')}}"></i>
        <p>
            {{__('PkgRealisationTache::PkgRealisationTache.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @can('index-etatRealisationTache') 
        <li class="nav-item" id="menu-etatRealisationTaches">
            <a href="{{ route('etatRealisationTaches.index') }}" class="nav-link {{ Request::is('admin/PkgRealisationTache/etatRealisationTaches') ? 'active' : '' }}">
                <i class="nav-icon fas fa-check"></i>
                {{__('PkgRealisationTache::etatRealisationTache.plural')}}
            </a>
        </li>
        @endcan
        @can('index-realisationTache') 
        <li class="nav-item" id="menu-realisationTaches">
            <a href="{{ route('realisationTaches.index') }}" class="nav-link {{ Request::is('admin/PkgRealisationTache/realisationTaches') ? 'active' : '' }}">
                <i class="nav-icon fas fa-laptop-code"></i>
                {{__('PkgRealisationTache::realisationTache.plural')}}
            </a>
        </li>
        @endcan
        @can('index-workflowTache') 
        <li class="nav-item" id="menu-workflowTaches">
            <a href="{{ route('workflowTaches.index') }}" class="nav-link {{ Request::is('admin/PkgRealisationTache/workflowTaches') ? 'active' : '' }}">
                <i class="nav-icon fas fa-check-square"></i>
                {{__('PkgRealisationTache::workflowTache.plural')}}
            </a>
        </li>
        @endcan
    </ul>
</li>
@endif

