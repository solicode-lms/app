{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-workflowChapitre', 'index-workflowFormation', 'index-etatFormation', 'index-etatChapitre', 'index-formation', 'index-chapitre', 'index-realisationFormation', 'index-realisationChapitre'])
@if($accessiblePermissions->isNotEmpty())
<li id="menu-PkgAutoformation" class="nav-item has-treeview  {{ Request::is('admin/PkgAutoformation*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgAutoformation*') ? 'active' : '' }}">
        <i class="nav-icon fas  {{__('PkgAutoformation::PkgAutoformation.icon')}}"></i>
        <p>
            {{__('PkgAutoformation::PkgAutoformation.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @can('index-workflowChapitre') 
        <li class="nav-item" id="menu-workflowChapitres">
            <a href="{{ route('workflowChapitres.index') }}" class="nav-link {{ Request::is('admin/PkgAutoformation/workflowChapitres') ? 'active' : '' }}">
                <i class="nav-icon fas fa-check-square"></i>
                {{__('PkgAutoformation::workflowChapitre.plural')}}
            </a>
        </li>
        @endcan
        @can('index-workflowFormation') 
        <li class="nav-item" id="menu-workflowFormations">
            <a href="{{ route('workflowFormations.index') }}" class="nav-link {{ Request::is('admin/PkgAutoformation/workflowFormations') ? 'active' : '' }}">
                <i class="nav-icon fas fa-check-square"></i>
                {{__('PkgAutoformation::workflowFormation.plural')}}
            </a>
        </li>
        @endcan
        @can('index-etatFormation') 
        <li class="nav-item" id="menu-etatFormations">
            <a href="{{ route('etatFormations.index') }}" class="nav-link {{ Request::is('admin/PkgAutoformation/etatFormations') ? 'active' : '' }}">
                <i class="nav-icon fas fa-check"></i>
                {{__('PkgAutoformation::etatFormation.plural')}}
            </a>
        </li>
        @endcan
        @can('index-etatChapitre') 
        <li class="nav-item" id="menu-etatChapitres">
            <a href="{{ route('etatChapitres.index') }}" class="nav-link {{ Request::is('admin/PkgAutoformation/etatChapitres') ? 'active' : '' }}">
                <i class="nav-icon fas fa-check"></i>
                {{__('PkgAutoformation::etatChapitre.plural')}}
            </a>
        </li>
        @endcan
        @can('index-formation') 
        <li class="nav-item" id="menu-formations">
            <a href="{{ route('formations.index') }}" class="nav-link {{ Request::is('admin/PkgAutoformation/formations') ? 'active' : '' }}">
                <i class="nav-icon fas fa-chalkboard-teacher"></i>
                {{__('PkgAutoformation::formation.plural')}}
            </a>
        </li>
        @endcan
        @can('index-chapitre') 
        <li class="nav-item" id="menu-chapitres">
            <a href="{{ route('chapitres.index') }}" class="nav-link {{ Request::is('admin/PkgAutoformation/chapitres') ? 'active' : '' }}">
                <i class="nav-icon fas fa-chalkboard"></i>
                {{__('PkgAutoformation::chapitre.plural')}}
            </a>
        </li>
        @endcan
        @can('index-realisationFormation') 
        <li class="nav-item" id="menu-realisationFormations">
            <a href="{{ route('realisationFormations.index') }}" class="nav-link {{ Request::is('admin/PkgAutoformation/realisationFormations') ? 'active' : '' }}">
                <i class="nav-icon fas fa-coffee"></i>
                {{__('PkgAutoformation::realisationFormation.plural')}}
            </a>
        </li>
        @endcan
        @can('index-realisationChapitre') 
        <li class="nav-item" id="menu-realisationChapitres">
            <a href="{{ route('realisationChapitres.index') }}" class="nav-link {{ Request::is('admin/PkgAutoformation/realisationChapitres') ? 'active' : '' }}">
                <i class="nav-icon fas fa-code"></i>
                {{__('PkgAutoformation::realisationChapitre.plural')}}
            </a>
        </li>
        @endcan
    </ul>
</li>
@endif

