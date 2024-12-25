{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<li class="nav-item has-treeview {{ Request::is('PkgUtilisateurs*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('PkgUtilisateurs*') ? 'active' : '' }}">
        <i class="nav-icon fas  {{__('PkgUtilisateurs::PkgUtilisateurs.icon')}}"></i>
        <p>
            {{__('PkgUtilisateurs::PkgUtilisateurs.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('apprenants.index') }}" class="nav-link {{ Request::is('PkgUtilisateurs/apprenants') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user-graduate"></i>
                <p>Apprenants</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('apprenantKonosies.index') }}" class="nav-link {{ Request::is('PkgUtilisateurs/apprenantKonosies') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                <p>ApprenantKonosies</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('formateurs.index') }}" class="nav-link {{ Request::is('PkgUtilisateurs/formateurs') ? 'active' : '' }}">
                <i class="nav-icon fas fa-chalkboard-teacher"></i>
                <p>Formateurs</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('groupes.index') }}" class="nav-link {{ Request::is('PkgUtilisateurs/groupes') ? 'active' : '' }}">
                <i class="nav-icon fas fa-cubes"></i>
                <p>Groupes</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('niveauxScolaires.index') }}" class="nav-link {{ Request::is('PkgUtilisateurs/niveauxScolaires') ? 'active' : '' }}">
                <i class="nav-icon fas fa-graduation-cap"></i>
                <p>NiveauxScolaires</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('specialites.index') }}" class="nav-link {{ Request::is('PkgUtilisateurs/specialites') ? 'active' : '' }}">
                <i class="nav-icon fas fa-award"></i>
                <p>Specialites</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('villes.index') }}" class="nav-link {{ Request::is('PkgUtilisateurs/villes') ? 'active' : '' }}">
                <i class="nav-icon fas fa-city"></i>
                <p>Villes</p>
            </a>
        </li>
    </ul>
</li>


