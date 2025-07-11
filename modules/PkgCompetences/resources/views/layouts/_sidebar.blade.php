{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions([])
@if($accessiblePermissions->isNotEmpty())
<li id="menu-PkgCompetences" class="nav-item has-treeview  {{ Request::is('admin/PkgCompetences*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgCompetences*') ? 'active' : '' }}">
        <i class="nav-icon {{__('PkgCompetences::PkgCompetences.icon')}}"></i>
        <p>
            {{__('PkgCompetences::PkgCompetences.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
    </ul>
</li>
@endif

