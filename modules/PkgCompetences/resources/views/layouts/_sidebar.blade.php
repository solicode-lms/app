{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-categoryTechnology', 'index-niveauCompetence', 'index-technology', 'index-niveauDifficulte'])
@if($accessiblePermissions->isNotEmpty())
<li id="menu-PkgCompetences" class="nav-item has-treeview  {{ Request::is('admin/PkgCompetences*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgCompetences*') ? 'active' : '' }}">
        <i class="nav-icon fas  {{__('PkgCompetences::PkgCompetences.icon')}}"></i>
        <p>
            {{__('PkgCompetences::PkgCompetences.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @can('index-categoryTechnology') 
        <li class="nav-item" id="menu-categoryTechnologies">
            <a href="{{ route('categoryTechnologies.index') }}" class="nav-link {{ Request::is('admin/PkgCompetences/categoryTechnologies') ? 'active' : '' }}">
                <i class="nav-icon fas fa-tags"></i>
                {{__('PkgCompetences::categoryTechnology.plural')}}
            </a>
        </li>
        @endcan
        @can('index-niveauCompetence') 
        <li class="nav-item" id="menu-niveauCompetences">
            <a href="{{ route('niveauCompetences.index') }}" class="nav-link {{ Request::is('admin/PkgCompetences/niveauCompetences') ? 'active' : '' }}">
                <i class="nav-icon fas fa-battery-three-quarters"></i>
                {{__('PkgCompetences::niveauCompetence.plural')}}
            </a>
        </li>
        @endcan
        @can('index-technology') 
        <li class="nav-item" id="menu-technologies">
            <a href="{{ route('technologies.index') }}" class="nav-link {{ Request::is('admin/PkgCompetences/technologies') ? 'active' : '' }}">
                <i class="nav-icon fas fa-tag"></i>
                {{__('PkgCompetences::technology.plural')}}
            </a>
        </li>
        @endcan
        @can('index-niveauDifficulte') 
        <li class="nav-item" id="menu-niveauDifficultes">
            <a href="{{ route('niveauDifficultes.index') }}" class="nav-link {{ Request::is('admin/PkgCompetences/niveauDifficultes') ? 'active' : '' }}">
                <i class="nav-icon fas fa-battery-three-quarters"></i>
                {{__('PkgCompetences::niveauDifficulte.plural')}}
            </a>
        </li>
        @endcan
    </ul>
</li>
@endif

