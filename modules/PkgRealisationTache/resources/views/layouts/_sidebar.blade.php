{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-etatRealisationTache', 'index-realisationTache', 'index-workflowTache'])
@if($accessiblePermissions->isNotEmpty())
    @if($accessiblePermissions->count() === 1)
        {{-- Cas d’un seul élément accessible --}}
            @can('index-etatRealisationTache')
            <li class="nav-item" id="menu-etatRealisationTaches">
                <a href="{{ route('etatRealisationTaches.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgRealisationTache/etatRealisationTaches') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-check"></i>
                    <p>{{__('PkgRealisationTache::etatRealisationTache.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-realisationTache')
            <li class="nav-item" id="menu-realisationTaches">
                <a href="{{ route('realisationTaches.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgRealisationTache/realisationTaches') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-laptop-code"></i>
                    <p>{{__('PkgRealisationTache::realisationTache.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-workflowTache')
            <li class="nav-item" id="menu-workflowTaches">
                <a href="{{ route('workflowTaches.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgRealisationTache/workflowTaches') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-check-square"></i>
                    <p>{{__('PkgRealisationTache::workflowTache.plural')}}</p>
                </a>
            </li>
            @endcan

    @else
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
                    <p>{{__('PkgRealisationTache::etatRealisationTache.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-realisationTache') 
            <li class="nav-item" id="menu-realisationTaches">
                <a href="{{ route('realisationTaches.index') }}" class="nav-link {{ Request::is('admin/PkgRealisationTache/realisationTaches') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-laptop-code"></i>
                    <p>{{__('PkgRealisationTache::realisationTache.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-workflowTache') 
            <li class="nav-item" id="menu-workflowTaches">
                <a href="{{ route('workflowTaches.index') }}" class="nav-link {{ Request::is('admin/PkgRealisationTache/workflowTaches') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-check-square"></i>
                    <p>{{__('PkgRealisationTache::workflowTache.plural')}}</p>
                </a>
            </li>
            @endcan
        </ul>
    </li>
  @endif
@endif

