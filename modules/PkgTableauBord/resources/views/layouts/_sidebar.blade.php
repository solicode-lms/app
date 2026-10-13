{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions([])
@if($accessiblePermissions->isNotEmpty())
    @if($accessiblePermissions->count() === 1)
        {{-- Cas d’un seul élément accessible --}}

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
        </ul>
    </li>
  @endif
@endif

