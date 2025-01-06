{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['show-apprenant', 'show-apprenantKonosy', 'show-formateur', 'show-groupe', 'show-nationalite', 'show-niveauxScolaire', 'show-specialite', 'show-ville'])
@if($accessiblePermissions->isNotEmpty())
<li class="nav-item has-treeview {{ Request::is('admin/PkgUtilisateurs*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgUtilisateurs*') ? 'active' : '' }}">
        <i class="nav-icon fas  {{__('PkgUtilisateurs::PkgUtilisateurs.icon')}}"></i>
        <p>
            {{__('PkgUtilisateurs::PkgUtilisateurs.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @can('show-apprenant') 
        <li class="nav-item">
            <a href="{{ route('apprenants.index') }}" class="nav-link {{ Request::is('admin/PkgUtilisateurs/apprenants') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user-graduate"></i>
                <p>Apprenants</p>
            </a>
        </li>
        @endcan
        @can('show-apprenantKonosy') 
        <li class="nav-item">
            <a href="{{ route('apprenantKonosies.index') }}" class="nav-link {{ Request::is('admin/PkgUtilisateurs/apprenantKonosies') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                <p>ApprenantKonosies</p>
            </a>
        </li>
        @endcan
        @can('show-formateur') 
        <li class="nav-item">
            <a href="{{ route('formateurs.index') }}" class="nav-link {{ Request::is('admin/PkgUtilisateurs/formateurs') ? 'active' : '' }}">
                <i class="nav-icon fas fa-chalkboard-teacher"></i>
                <p>Formateurs</p>
            </a>
        </li>
        @endcan
        @can('show-groupe') 
        <li class="nav-item">
            <a href="{{ route('groupes.index') }}" class="nav-link {{ Request::is('admin/PkgUtilisateurs/groupes') ? 'active' : '' }}">
                <i class="nav-icon fas fa-cubes"></i>
                <p>Groupes</p>
            </a>
        </li>
        @endcan
        @can('show-nationalite') 
        <li class="nav-item">
            <a href="{{ route('nationalites.index') }}" class="nav-link {{ Request::is('admin/PkgUtilisateurs/nationalites') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                <p>Nationalites</p>
            </a>
        </li>
        @endcan
        @can('show-niveauxScolaire') 
        <li class="nav-item">
            <a href="{{ route('niveauxScolaires.index') }}" class="nav-link {{ Request::is('admin/PkgUtilisateurs/niveauxScolaires') ? 'active' : '' }}">
                <i class="nav-icon fas fa-graduation-cap"></i>
                <p>NiveauxScolaires</p>
            </a>
        </li>
        @endcan
        @can('show-specialite') 
        <li class="nav-item">
            <a href="{{ route('specialites.index') }}" class="nav-link {{ Request::is('admin/PkgUtilisateurs/specialites') ? 'active' : '' }}">
                <i class="nav-icon fas fa-award"></i>
                <p>Specialites</p>
            </a>
        </li>
        @endcan
        @can('show-ville') 
        <li class="nav-item">
            <a href="{{ route('villes.index') }}" class="nav-link {{ Request::is('admin/PkgUtilisateurs/villes') ? 'active' : '' }}">
                <i class="nav-icon fas fa-city"></i>
                <p>Villes</p>
            </a>
        </li>
        @endcan
    </ul>
</li>
@endif

