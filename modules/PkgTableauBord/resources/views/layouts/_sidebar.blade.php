{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-logActiviteFormateur'])
@if($accessiblePermissions->isNotEmpty())
    @if($accessiblePermissions->count() === 1)
        {{-- Cas d’un seul élément accessible --}}
            @can('index-logActiviteFormateur')
            <li class="nav-item" id="menu-logActiviteFormateurs">
                <a href="{{ route('logActiviteFormateurs.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgTableauBord/logActiviteFormateurs') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-table"></i>
                    <p>{{__('PkgTableauBord::logActiviteFormateur.plural')}}</p>
                </a>
            </li>
            @endcan

    @else
    <li id="menu-PkgTableauBord" class="nav-item has-treeview  {{ Request::is('admin/PkgTableauBord*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgTableauBord*') ? 'active' : '' }}">
            <i class="nav-icon {{__('PkgTableauBord::PkgTableauBord.icon')}}"></i>
            <p>
                {{__('PkgTableauBord::PkgTableauBord.name')}}
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            @can('index-logActiviteFormateur') 
            <li class="nav-item" id="menu-logActiviteFormateurs">
                <a href="{{ route('logActiviteFormateurs.index') }}" class="nav-link {{ Request::is('admin/PkgTableauBord/logActiviteFormateurs') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-table"></i>
                    <p>{{__('PkgTableauBord::logActiviteFormateur.plural')}}</p>
                </a>
            </li>
            @endcan
        </ul>
    </li>
  @endif
@endif

