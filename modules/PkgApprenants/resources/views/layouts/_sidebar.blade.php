{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-apprenantKonosy', 'index-niveauxScolaire', 'index-ville', 'index-nationalite', 'index-groupe', 'index-apprenant'])
@if($accessiblePermissions->isNotEmpty())
<li class="nav-item has-treeview {{ Request::is('admin/PkgApprenants*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgApprenants*') ? 'active' : '' }}">
        <i class="nav-icon fas  {{__('PkgApprenants::PkgApprenants.icon')}}"></i>
        <p>
            {{__('PkgApprenants::PkgApprenants.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @can('index-apprenantKonosy') 
        <li class="nav-item">
            <a href="{{ route('apprenantKonosies.index') }}" class="nav-link {{ Request::is('admin/PkgApprenants/apprenantKonosies') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgApprenants::apprenantKonosy.plural')}}
            </a>
        </li>
        @endcan
        @can('index-niveauxScolaire') 
        <li class="nav-item">
            <a href="{{ route('niveauxScolaires.index') }}" class="nav-link {{ Request::is('admin/PkgApprenants/niveauxScolaires') ? 'active' : '' }}">
                <i class="nav-icon fas fa-graduation-cap"></i>
                {{__('PkgApprenants::niveauxScolaire.plural')}}
            </a>
        </li>
        @endcan
        @can('index-ville') 
        <li class="nav-item">
            <a href="{{ route('villes.index') }}" class="nav-link {{ Request::is('admin/PkgApprenants/villes') ? 'active' : '' }}">
                <i class="nav-icon fas fa-city"></i>
                {{__('PkgApprenants::ville.plural')}}
            </a>
        </li>
        @endcan
        @can('index-nationalite') 
        <li class="nav-item">
            <a href="{{ route('nationalites.index') }}" class="nav-link {{ Request::is('admin/PkgApprenants/nationalites') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgApprenants::nationalite.plural')}}
            </a>
        </li>
        @endcan
        @can('index-groupe') 
        <li class="nav-item">
            <a href="{{ route('groupes.index') }}" class="nav-link {{ Request::is('admin/PkgApprenants/groupes') ? 'active' : '' }}">
                <i class="nav-icon fas fa-cubes"></i>
                {{__('PkgApprenants::groupe.plural')}}
            </a>
        </li>
        @endcan
        @can('index-apprenant') 
        <li class="nav-item">
            <a href="{{ route('apprenants.index') }}" class="nav-link {{ Request::is('admin/PkgApprenants/apprenants') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user-graduate"></i>
                {{__('PkgApprenants::apprenant.plural')}}
            </a>
        </li>
        @endcan
    </ul>
</li>
@endif

