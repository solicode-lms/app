{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions([])
@if($accessiblePermissions->isNotEmpty())
    @if($accessiblePermissions->count() === 1)
        {{-- Cas d’un seul élément accessible --}}

    @else
    <li id="menu-PkgCreationTache" class="nav-item has-treeview  {{ Request::is('admin/PkgCreationTache*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgCreationTache*') ? 'active' : '' }}">
            <i class="nav-icon {{__('PkgCreationTache::PkgCreationTache.icon')}}"></i>
            <p>
                {{__('PkgCreationTache::PkgCreationTache.name')}}
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
        </ul>
    </li>
  @endif
@endif

