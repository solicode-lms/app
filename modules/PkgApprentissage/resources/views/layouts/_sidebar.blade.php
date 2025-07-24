{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-etatRealisationMicroCompetence', 'index-etatRealisationUa', 'index-etatRealisationChapitre', 'index-realisationMicroCompetence', 'index-realisationUa', 'index-realisationChapitre', 'index-realisationUaPrototype', 'index-realisationUaProjet'])
@if($accessiblePermissions->isNotEmpty())
<li id="menu-PkgApprentissage" class="nav-item has-treeview  {{ Request::is('admin/PkgApprentissage*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgApprentissage*') ? 'active' : '' }}">
        <i class="nav-icon {{__('PkgApprentissage::PkgApprentissage.icon')}}"></i>
        <p>
            {{__('PkgApprentissage::PkgApprentissage.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @can('index-etatRealisationMicroCompetence') 
        <li class="nav-item" id="menu-etatRealisationMicroCompetences">
            <a href="{{ route('etatRealisationMicroCompetences.index') }}" class="nav-link {{ Request::is('admin/PkgApprentissage/etatRealisationMicroCompetences') ? 'active' : '' }}">
                <i class="nav-icon fa-table"></i>
                {{__('PkgApprentissage::etatRealisationMicroCompetence.plural')}}
            </a>
        </li>
        @endcan
        @can('index-etatRealisationUa') 
        <li class="nav-item" id="menu-etatRealisationUas">
            <a href="{{ route('etatRealisationUas.index') }}" class="nav-link {{ Request::is('admin/PkgApprentissage/etatRealisationUas') ? 'active' : '' }}">
                <i class="nav-icon fa-table"></i>
                {{__('PkgApprentissage::etatRealisationUa.plural')}}
            </a>
        </li>
        @endcan
        @can('index-etatRealisationChapitre') 
        <li class="nav-item" id="menu-etatRealisationChapitres">
            <a href="{{ route('etatRealisationChapitres.index') }}" class="nav-link {{ Request::is('admin/PkgApprentissage/etatRealisationChapitres') ? 'active' : '' }}">
                <i class="nav-icon fa-table"></i>
                {{__('PkgApprentissage::etatRealisationChapitre.plural')}}
            </a>
        </li>
        @endcan
        @can('index-realisationMicroCompetence') 
        <li class="nav-item" id="menu-realisationMicroCompetences">
            <a href="{{ route('realisationMicroCompetences.index') }}" class="nav-link {{ Request::is('admin/PkgApprentissage/realisationMicroCompetences') ? 'active' : '' }}">
                <i class="nav-icon fa-table"></i>
                {{__('PkgApprentissage::realisationMicroCompetence.plural')}}
            </a>
        </li>
        @endcan
        @can('index-realisationUa') 
        <li class="nav-item" id="menu-realisationUas">
            <a href="{{ route('realisationUas.index') }}" class="nav-link {{ Request::is('admin/PkgApprentissage/realisationUas') ? 'active' : '' }}">
                <i class="nav-icon fa-table"></i>
                {{__('PkgApprentissage::realisationUa.plural')}}
            </a>
        </li>
        @endcan
        @can('index-realisationChapitre') 
        <li class="nav-item" id="menu-realisationChapitres">
            <a href="{{ route('realisationChapitres.index') }}" class="nav-link {{ Request::is('admin/PkgApprentissage/realisationChapitres') ? 'active' : '' }}">
                <i class="nav-icon fa-table"></i>
                {{__('PkgApprentissage::realisationChapitre.plural')}}
            </a>
        </li>
        @endcan
        @can('index-realisationUaPrototype') 
        <li class="nav-item" id="menu-realisationUaPrototypes">
            <a href="{{ route('realisationUaPrototypes.index') }}" class="nav-link {{ Request::is('admin/PkgApprentissage/realisationUaPrototypes') ? 'active' : '' }}">
                <i class="nav-icon fa-table"></i>
                {{__('PkgApprentissage::realisationUaPrototype.plural')}}
            </a>
        </li>
        @endcan
        @can('index-realisationUaProjet') 
        <li class="nav-item" id="menu-realisationUaProjets">
            <a href="{{ route('realisationUaProjets.index') }}" class="nav-link {{ Request::is('admin/PkgApprentissage/realisationUaProjets') ? 'active' : '' }}">
                <i class="nav-icon fa-table"></i>
                {{__('PkgApprentissage::realisationUaProjet.plural')}}
            </a>
        </li>
        @endcan
    </ul>
</li>
@endif

