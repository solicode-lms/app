{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-categoryTechnology', 'index-competence', 'index-niveauCompetence', 'index-niveauDifficulte', 'index-technology'])
@if($accessiblePermissions->isNotEmpty())
<li class="nav-item has-treeview {{ Request::is('admin/PkgCompetences*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgCompetences*') ? 'active' : '' }}">
        <i class="nav-icon fas  {{__('PkgCompetences::PkgCompetences.icon')}}"></i>
        <p>
            {{__('PkgCompetences::PkgCompetences.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @can('index-categoryTechnology') 
        <li class="nav-item">
            <a href="{{ route('categoryTechnologies.index') }}" class="nav-link {{ Request::is('admin/PkgCompetences/categoryTechnologies') ? 'active' : '' }}">
                <i class="nav-icon fas fa-bolt"></i>
                {{__('PkgCompetences::CategoryTechnology.plural')}}
            </a>
        </li>
        @endcan
        @can('index-competence') 
        <li class="nav-item">
            <a href="{{ route('competences.index') }}" class="nav-link {{ Request::is('admin/PkgCompetences/competences') ? 'active' : '' }}">
                <i class="nav-icon fas fa-tools"></i>
                {{__('PkgCompetences::Competence.plural')}}
            </a>
        </li>
        @endcan
        @can('index-niveauCompetence') 
        <li class="nav-item">
            <a href="{{ route('niveauCompetences.index') }}" class="nav-link {{ Request::is('admin/PkgCompetences/niveauCompetences') ? 'active' : '' }}">
                <i class="nav-icon fas fa-bars"></i>
                {{__('PkgCompetences::NiveauCompetence.plural')}}
            </a>
        </li>
        @endcan
        @can('index-niveauDifficulte') 
        <li class="nav-item">
            <a href="{{ route('niveauDifficultes.index') }}" class="nav-link {{ Request::is('admin/PkgCompetences/niveauDifficultes') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgCompetences::NiveauDifficulte.plural')}}
            </a>
        </li>
        @endcan
        @can('index-technology') 
        <li class="nav-item">
            <a href="{{ route('technologies.index') }}" class="nav-link {{ Request::is('admin/PkgCompetences/technologies') ? 'active' : '' }}">
                <i class="nav-icon fas fa-bolt"></i>
                {{__('PkgCompetences::Technology.plural')}}
            </a>
        </li>
        @endcan
    </ul>
</li>
@endif

