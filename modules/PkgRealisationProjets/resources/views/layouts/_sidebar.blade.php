{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-etatsRealisationProjet', 'index-affectationProjet', 'index-realisationProjet'])
@if($accessiblePermissions->isNotEmpty())
<li id="menu-PkgRealisationProjets" class="nav-item has-treeview  {{ Request::is('admin/PkgRealisationProjets*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgRealisationProjets*') ? 'active' : '' }}">
        <i class="nav-icon fas  {{__('PkgRealisationProjets::PkgRealisationProjets.icon')}}"></i>
        <p>
            {{__('PkgRealisationProjets::PkgRealisationProjets.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @can('index-etatsRealisationProjet') 
        <li class="nav-item" id="menu-etatsRealisationProjets">
            <a href="{{ route('etatsRealisationProjets.index') }}" class="nav-link {{ Request::is('admin/PkgRealisationProjets/etatsRealisationProjets') ? 'active' : '' }}">
                <i class="nav-icon fas fa-star-half"></i>
                {{__('PkgRealisationProjets::etatsRealisationProjet.plural')}}
            </a>
        </li>
        @endcan
        @can('index-affectationProjet') 
        <li class="nav-item" id="menu-affectationProjets">
            <a href="{{ route('affectationProjets.index') }}" class="nav-link {{ Request::is('admin/PkgRealisationProjets/affectationProjets') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user-check"></i>
                {{__('PkgRealisationProjets::affectationProjet.plural')}}
            </a>
        </li>
        @endcan
        @can('index-realisationProjet') 
        <li class="nav-item" id="menu-realisationProjets">
            <a href="{{ route('realisationProjets.index') }}" class="nav-link {{ Request::is('admin/PkgRealisationProjets/realisationProjets') ? 'active' : '' }}">
                <i class="nav-icon fas fa-laptop-code"></i>
                {{__('PkgRealisationProjets::realisationProjet.plural')}}
            </a>
        </li>
        @endcan
    </ul>
</li>
@endif

