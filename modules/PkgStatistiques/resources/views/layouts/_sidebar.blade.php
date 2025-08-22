{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions([])
@if($accessiblePermissions->isNotEmpty())
    @if($accessiblePermissions->count() === 1)
        {{-- Cas d’un seul élément accessible --}}

    @else
    <li id="menu-PkgStatistiques" class="nav-item has-treeview  {{ Request::is('admin/PkgStatistiques*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgStatistiques*') ? 'active' : '' }}">
            <i class="nav-icon {{__('PkgStatistiques::PkgStatistiques.icon')}}"></i>
            <p>
                {{__('PkgStatistiques::PkgStatistiques.name')}}
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
        </ul>
    </li>
  @endif
@endif

