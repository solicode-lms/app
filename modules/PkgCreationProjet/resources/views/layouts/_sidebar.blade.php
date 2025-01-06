{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['show-livrable', 'show-natureLivrable', 'show-projet', 'show-resource', 'show-transfertCompetence'])
@if($accessiblePermissions->isNotEmpty())
<li class="nav-item has-treeview {{ Request::is('admin/PkgCreationProjet*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgCreationProjet*') ? 'active' : '' }}">
        <i class="nav-icon fas  {{__('PkgCreationProjet::PkgCreationProjet.icon')}}"></i>
        <p>
            {{__('PkgCreationProjet::PkgCreationProjet.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @can('show-livrable') 
        <li class="nav-item">
            <a href="{{ route('livrables.index') }}" class="nav-link {{ Request::is('admin/PkgCreationProjet/livrables') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgCreationProjet::Livrable.plural')}}
            </a>
        </li>
        @endcan
        @can('show-natureLivrable') 
        <li class="nav-item">
            <a href="{{ route('natureLivrables.index') }}" class="nav-link {{ Request::is('admin/PkgCreationProjet/natureLivrables') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgCreationProjet::NatureLivrable.plural')}}
            </a>
        </li>
        @endcan
        @can('show-projet') 
        <li class="nav-item">
            <a href="{{ route('projets.index') }}" class="nav-link {{ Request::is('admin/PkgCreationProjet/projets') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgCreationProjet::Projet.plural')}}
            </a>
        </li>
        @endcan
        @can('show-resource') 
        <li class="nav-item">
            <a href="{{ route('resources.index') }}" class="nav-link {{ Request::is('admin/PkgCreationProjet/resources') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgCreationProjet::Resource.plural')}}
            </a>
        </li>
        @endcan
        @can('show-transfertCompetence') 
        <li class="nav-item">
            <a href="{{ route('transfertCompetences.index') }}" class="nav-link {{ Request::is('admin/PkgCreationProjet/transfertCompetences') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgCreationProjet::TransfertCompetence.plural')}}
            </a>
        </li>
        @endcan
    </ul>
</li>
@endif

