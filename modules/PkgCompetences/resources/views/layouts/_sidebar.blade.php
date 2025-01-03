{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<li class="nav-item has-treeview {{ Request::is('PkgCompetences*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('PkgCompetences*') ? 'active' : '' }}">
        <i class="nav-icon fas  {{__('PkgCompetences::PkgCompetences.icon')}}"></i>
        <p>
            {{__('PkgCompetences::PkgCompetences.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('appreciations.index') }}" class="nav-link {{ Request::is('PkgCompetences/appreciations') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                <p>Appreciations</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('categoryTechnologies.index') }}" class="nav-link {{ Request::is('PkgCompetences/categoryTechnologies') ? 'active' : '' }}">
                <i class="nav-icon fas fa-bolt"></i>
                <p>CategoryTechnologies</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('competences.index') }}" class="nav-link {{ Request::is('PkgCompetences/competences') ? 'active' : '' }}">
                <i class="nav-icon fas fa-brain"></i>
                <p>Competences</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('filieres.index') }}" class="nav-link {{ Request::is('PkgCompetences/filieres') ? 'active' : '' }}">
                <i class="nav-icon fas fa-book"></i>
                <p>Filieres</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('modules.index') }}" class="nav-link {{ Request::is('PkgCompetences/modules') ? 'active' : '' }}">
                <i class="nav-icon fas fa-puzzle-piece"></i>
                <p>Modules</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('niveauCompetences.index') }}" class="nav-link {{ Request::is('PkgCompetences/niveauCompetences') ? 'active' : '' }}">
                <i class="nav-icon fas fa-bars"></i>
                <p>NiveauCompetences</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('technologies.index') }}" class="nav-link {{ Request::is('PkgCompetences/technologies') ? 'active' : '' }}">
                <i class="nav-icon fas fa-bolt"></i>
                <p>Technologies</p>
            </a>
        </li>
    </ul>
</li>


