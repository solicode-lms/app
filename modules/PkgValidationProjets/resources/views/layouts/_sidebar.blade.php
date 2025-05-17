{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions([])
@if($accessiblePermissions->isNotEmpty())
<li id="menu-PkgValidationProjets" class="nav-item has-treeview  {{ Request::is('admin/PkgValidationProjets*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgValidationProjets*') ? 'active' : '' }}">
        <i class="nav-icon {{__('PkgValidationProjets::PkgValidationProjets.icon')}}"></i>
        <p>
            {{__('PkgValidationProjets::PkgValidationProjets.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
    </ul>
</li>
@endif

