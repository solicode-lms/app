{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-apprenantKonosy', 'index-niveauxScolaire', 'index-nationalite', 'index-groupe', 'index-apprenant', 'index-sousGroupe'])
@if($accessiblePermissions->isNotEmpty())
    @if($accessiblePermissions->count() === 1)
        {{-- Cas d’un seul élément accessible --}}
            @can('index-apprenantKonosy')
            <li class="nav-item" id="menu-apprenantKonosies">
                <a href="{{ route('apprenantKonosies.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgApprenants/apprenantKonosies') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-id-badge"></i>
                    <p>{{__('PkgApprenants::apprenantKonosy.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-niveauxScolaire')
            <li class="nav-item" id="menu-niveauxScolaires">
                <a href="{{ route('niveauxScolaires.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgApprenants/niveauxScolaires') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-award"></i>
                    <p>{{__('PkgApprenants::niveauxScolaire.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-nationalite')
            <li class="nav-item" id="menu-nationalites">
                <a href="{{ route('nationalites.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgApprenants/nationalites') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-map-marked-alt"></i>
                    <p>{{__('PkgApprenants::nationalite.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-groupe')
            <li class="nav-item" id="menu-groupes">
                <a href="{{ route('groupes.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgApprenants/groupes') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-users"></i>
                    <p>{{__('PkgApprenants::groupe.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-apprenant')
            <li class="nav-item" id="menu-apprenants">
                <a href="{{ route('apprenants.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgApprenants/apprenants') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-id-card"></i>
                    <p>{{__('PkgApprenants::apprenant.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-sousGroupe')
            <li class="nav-item" id="menu-sousGroupes">
                <a href="{{ route('sousGroupes.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgApprenants/sousGroupes') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-user-friends"></i>
                    <p>{{__('PkgApprenants::sousGroupe.plural')}}</p>
                </a>
            </li>
            @endcan

    @else
    <li id="menu-PkgApprenants" class="nav-item has-treeview  {{ Request::is('admin/PkgApprenants*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgApprenants*') ? 'active' : '' }}">
            <i class="nav-icon {{__('PkgApprenants::PkgApprenants.icon')}}"></i>
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
                    <p>{{__('PkgApprenants::apprenantKonosy.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-niveauxScolaire') 
            <li class="nav-item" id="menu-niveauxScolaires">
                <a href="{{ route('niveauxScolaires.index') }}" class="nav-link {{ Request::is('admin/PkgApprenants/niveauxScolaires') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-award"></i>
                    <p>{{__('PkgApprenants::niveauxScolaire.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-nationalite') 
            <li class="nav-item" id="menu-nationalites">
                <a href="{{ route('nationalites.index') }}" class="nav-link {{ Request::is('admin/PkgApprenants/nationalites') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-map-marked-alt"></i>
                    <p>{{__('PkgApprenants::nationalite.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-groupe') 
            <li class="nav-item" id="menu-groupes">
                <a href="{{ route('groupes.index') }}" class="nav-link {{ Request::is('admin/PkgApprenants/groupes') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-users"></i>
                    <p>{{__('PkgApprenants::groupe.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-apprenant') 
            <li class="nav-item" id="menu-apprenants">
                <a href="{{ route('apprenants.index') }}" class="nav-link {{ Request::is('admin/PkgApprenants/apprenants') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-id-card"></i>
                    <p>{{__('PkgApprenants::apprenant.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-sousGroupe') 
            <li class="nav-item" id="menu-sousGroupes">
                <a href="{{ route('sousGroupes.index') }}" class="nav-link {{ Request::is('admin/PkgApprenants/sousGroupes') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-user-friends"></i>
                    <p>{{__('PkgApprenants::sousGroupe.plural')}}</p>
                </a>
            </li>
            @endcan
        </ul>
    </li>
  @endif
@endif

