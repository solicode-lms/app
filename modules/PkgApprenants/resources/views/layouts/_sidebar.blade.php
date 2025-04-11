{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-apprenantKonosy', 'index-apprenant', 'index-groupe', 'index-nationalite', 'index-niveauxScolaire', 'index-ville'])
@if($accessiblePermissions->isNotEmpty())
<li id="menu-PkgApprenants" class="nav-item has-treeview  {{ Request::is('admin/PkgApprenants*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgApprenants*') ? 'active' : '' }}">
        <i class="nav-icon fas  {{__('PkgApprenants::PkgApprenants.icon')}}"></i>
        <p>
            {{__('PkgApprenants::PkgApprenants.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @can('index-apprenantKonosy') 
        <li class="nav-item" id="menu-apprenantKonosies">
            <a href="{{ route('apprenantKonosies.index') }}" class="nav-link {{ Request::is('admin/PkgApprenants/apprenantKonosies') ? 'active' : '' }}">
                <i class="nav-icon fas fa-id-badge"></i>
                {{__('PkgApprenants::apprenantKonosy.plural')}}
            </a>
        </li>
        @endcan
        @can('index-apprenant') 
        <li class="nav-item" id="menu-apprenants">
            <a href="{{ route('apprenants.index') }}" class="nav-link {{ Request::is('admin/PkgApprenants/apprenants') ? 'active' : '' }}">
                <i class="nav-icon fas fa-id-card"></i>
                {{__('PkgApprenants::apprenant.plural')}}
            </a>
        </li>
        @endcan
        @can('index-groupe') 
        <li class="nav-item" id="menu-groupes">
            <a href="{{ route('groupes.index') }}" class="nav-link {{ Request::is('admin/PkgApprenants/groupes') ? 'active' : '' }}">
                <i class="nav-icon fas fa-users"></i>
                {{__('PkgApprenants::groupe.plural')}}
            </a>
        </li>
        @endcan
        @can('index-nationalite') 
        <li class="nav-item" id="menu-nationalites">
            <a href="{{ route('nationalites.index') }}" class="nav-link {{ Request::is('admin/PkgApprenants/nationalites') ? 'active' : '' }}">
                <i class="nav-icon fas fa-map-marked-alt"></i>
                {{__('PkgApprenants::nationalite.plural')}}
            </a>
        </li>
        @endcan
        @can('index-niveauxScolaire') 
        <li class="nav-item" id="menu-niveauxScolaires">
            <a href="{{ route('niveauxScolaires.index') }}" class="nav-link {{ Request::is('admin/PkgApprenants/niveauxScolaires') ? 'active' : '' }}">
                <i class="nav-icon fas fa-award"></i>
                {{__('PkgApprenants::niveauxScolaire.plural')}}
            </a>
        </li>
        @endcan
        @can('index-ville') 
        <li class="nav-item" id="menu-villes">
            <a href="{{ route('villes.index') }}" class="nav-link {{ Request::is('admin/PkgApprenants/villes') ? 'active' : '' }}">
                <i class="nav-icon fas fa-city"></i>
                {{__('PkgApprenants::ville.plural')}}
            </a>
        </li>
        @endcan
    </ul>
</li>
@endif

