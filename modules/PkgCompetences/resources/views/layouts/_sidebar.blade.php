{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['show-filiere', 'show-module', 'show-categoryTechnology', 'show-competence', 'show-niveauCompetence', 'show-technology', 'show-appreciation'])
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
        @can('show-filiere') 
        <li class="nav-item">
            <a href="{{ route('filieres.index') }}" class="nav-link {{ Request::is('admin/PkgCompetences/filieres') ? 'active' : '' }}">
                <i class="nav-icon fas fa-book"></i>
                {{__('PkgCompetences::Filiere.plural')}}
            </a>
        </li>
        @endcan
        @can('show-module') 
        <li class="nav-item">
            <a href="{{ route('modules.index') }}" class="nav-link {{ Request::is('admin/PkgCompetences/modules') ? 'active' : '' }}">
                <i class="nav-icon fas fa-puzzle-piece"></i>
                {{__('PkgCompetences::Module.plural')}}
            </a>
        </li>
        @endcan
        @can('show-categoryTechnology') 
        <li class="nav-item">
            <a href="{{ route('categoryTechnologies.index') }}" class="nav-link {{ Request::is('admin/PkgCompetences/categoryTechnologies') ? 'active' : '' }}">
                <i class="nav-icon fas fa-bolt"></i>
                {{__('PkgCompetences::CategoryTechnology.plural')}}
            </a>
        </li>
        @endcan
        @can('show-competence') 
        <li class="nav-item">
            <a href="{{ route('competences.index') }}" class="nav-link {{ Request::is('admin/PkgCompetences/competences') ? 'active' : '' }}">
                <i class="nav-icon fas fa-tools"></i>
                {{__('PkgCompetences::Competence.plural')}}
            </a>
        </li>
        @endcan
        @can('show-niveauCompetence') 
        <li class="nav-item">
            <a href="{{ route('niveauCompetences.index') }}" class="nav-link {{ Request::is('admin/PkgCompetences/niveauCompetences') ? 'active' : '' }}">
                <i class="nav-icon fas fa-bars"></i>
                {{__('PkgCompetences::NiveauCompetence.plural')}}
            </a>
        </li>
        @endcan
        @can('show-technology') 
        <li class="nav-item">
            <a href="{{ route('technologies.index') }}" class="nav-link {{ Request::is('admin/PkgCompetences/technologies') ? 'active' : '' }}">
                <i class="nav-icon fas fa-bolt"></i>
                {{__('PkgCompetences::Technology.plural')}}
            </a>
        </li>
        @endcan
        @can('show-appreciation') 
        <li class="nav-item">
            <a href="{{ route('appreciations.index') }}" class="nav-link {{ Request::is('admin/PkgCompetences/appreciations') ? 'active' : '' }}">
                <i class="nav-icon fas fa-chart-line"></i>
                {{__('PkgCompetences::Appreciation.plural')}}
            </a>
        </li>
        @endcan
    </ul>
</li>
@endif

