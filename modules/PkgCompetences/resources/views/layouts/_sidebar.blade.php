{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-categoryTechnology', 'index-niveauCompetence', 'index-niveauDifficulte'])
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
                {{__('PkgCompetences::categoryTechnology.plural')}}
            </a>
        </li>
        @endcan
        @can('index-niveauCompetence') 
        <li class="nav-item">
            <a href="{{ route('niveauCompetences.index') }}" class="nav-link {{ Request::is('admin/PkgCompetences/niveauCompetences') ? 'active' : '' }}">
                <i class="nav-icon fas fa-bars"></i>
                {{__('PkgCompetences::niveauCompetence.plural')}}
            </a>
        </li>
        @endcan
        @can('index-niveauDifficulte') 
        <li class="nav-item">
            <a href="{{ route('niveauDifficultes.index') }}" class="nav-link {{ Request::is('admin/PkgCompetences/niveauDifficultes') ? 'active' : '' }}">
                <i class="nav-icon fas fa-chart-line"></i>
                {{__('PkgCompetences::niveauDifficulte.plural')}}
            </a>
        </li>
        @endcan
    </ul>
</li>
@endif

