{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-filiere', 'index-module', 'index-anneeFormation', 'index-specialite', 'index-formateur'])
@if($accessiblePermissions->isNotEmpty())
<li id="menu-PkgFormation" class="nav-item has-treeview  {{ Request::is('admin/PkgFormation*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgFormation*') ? 'active' : '' }}">
        <i class="nav-icon fas  {{__('PkgFormation::PkgFormation.icon')}}"></i>
        <p>
            {{__('PkgFormation::PkgFormation.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @can('index-filiere') 
        <li class="nav-item" id="menu-filieres">
            <a href="{{ route('filieres.index') }}" class="nav-link {{ Request::is('admin/PkgFormation/filieres') ? 'active' : '' }}">
                <i class="nav-icon fas fa-book"></i>
                {{__('PkgFormation::filiere.plural')}}
            </a>
        </li>
        @endcan
        @can('index-module') 
        <li class="nav-item" id="menu-modules">
            <a href="{{ route('modules.index') }}" class="nav-link {{ Request::is('admin/PkgFormation/modules') ? 'active' : '' }}">
                <i class="nav-icon fas fa-puzzle-piece"></i>
                {{__('PkgFormation::module.plural')}}
            </a>
        </li>
        @endcan
        @can('index-anneeFormation') 
        <li class="nav-item" id="menu-anneeFormations">
            <a href="{{ route('anneeFormations.index') }}" class="nav-link {{ Request::is('admin/PkgFormation/anneeFormations') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgFormation::anneeFormation.plural')}}
            </a>
        </li>
        @endcan
        @can('index-specialite') 
        <li class="nav-item" id="menu-specialites">
            <a href="{{ route('specialites.index') }}" class="nav-link {{ Request::is('admin/PkgFormation/specialites') ? 'active' : '' }}">
                <i class="nav-icon fas fa-award"></i>
                {{__('PkgFormation::specialite.plural')}}
            </a>
        </li>
        @endcan
        @can('index-formateur') 
        <li class="nav-item" id="menu-formateurs">
            <a href="{{ route('formateurs.index') }}" class="nav-link {{ Request::is('admin/PkgFormation/formateurs') ? 'active' : '' }}">
                <i class="nav-icon fas fa-chalkboard-teacher"></i>
                {{__('PkgFormation::formateur.plural')}}
            </a>
        </li>
        @endcan
    </ul>
</li>
@endif

