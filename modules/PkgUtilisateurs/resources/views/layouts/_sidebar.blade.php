{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['show-apprenantKonosy'])
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
        @can('show-apprenantKonosy') 
        <li class="nav-item">
            <a href="{{ route('apprenantKonosies.index') }}" class="nav-link {{ Request::is('admin/PkgUtilisateurs/apprenantKonosies') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgUtilisateurs::ApprenantKonosy.plural')}}
            </a>
        </li>
        @endcan
    </ul>
</li>
@endif

