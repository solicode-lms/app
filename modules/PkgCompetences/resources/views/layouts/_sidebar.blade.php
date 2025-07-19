{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-chapitre', 'index-competence', 'index-microCompetence', 'index-uniteApprentissage'])
@if($accessiblePermissions->isNotEmpty())
<li id="menu-PkgCompetences" class="nav-item has-treeview  {{ Request::is('admin/PkgCompetences*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgCompetences*') ? 'active' : '' }}">
        <i class="nav-icon {{__('PkgCompetences::PkgCompetences.icon')}}"></i>
        <p>
            {{__('PkgCompetences::PkgCompetences.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @can('index-chapitre') 
        <li class="nav-item" id="menu-chapitres">
            <a href="{{ route('chapitres.index') }}" class="nav-link {{ Request::is('admin/PkgCompetences/chapitres') ? 'active' : '' }}">
                <i class="nav-icon fa-table"></i>
                {{__('PkgCompetences::chapitre.plural')}}
            </a>
        </li>
        @endcan
        @can('index-competence') 
        <li class="nav-item" id="menu-competences">
            <a href="{{ route('competences.index') }}" class="nav-link {{ Request::is('admin/PkgCompetences/competences') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user-graduate"></i>
                {{__('PkgCompetences::competence.plural')}}
            </a>
        </li>
        @endcan
        @can('index-microCompetence') 
        <li class="nav-item" id="menu-microCompetences">
            <a href="{{ route('microCompetences.index') }}" class="nav-link {{ Request::is('admin/PkgCompetences/microCompetences') ? 'active' : '' }}">
                <i class="nav-icon fa-table"></i>
                {{__('PkgCompetences::microCompetence.plural')}}
            </a>
        </li>
        @endcan
        @can('index-uniteApprentissage') 
        <li class="nav-item" id="menu-uniteApprentissages">
            <a href="{{ route('uniteApprentissages.index') }}" class="nav-link {{ Request::is('admin/PkgCompetences/uniteApprentissages') ? 'active' : '' }}">
                <i class="nav-icon fa-table"></i>
                {{__('PkgCompetences::uniteApprentissage.plural')}}
            </a>
        </li>
        @endcan
    </ul>
</li>
@endif

