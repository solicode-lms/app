{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-competence', 'index-microCompetence', 'index-uniteApprentissage', 'index-chapitre', 'index-phaseEvaluation', 'index-critereEvaluation'])
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
                <i class="nav-icon fas fa-book"></i>
                {{__('PkgCompetences::microCompetence.plural')}}
            </a>
        </li>
        @endcan
        @can('index-uniteApprentissage') 
        <li class="nav-item" id="menu-uniteApprentissages">
            <a href="{{ route('uniteApprentissages.index') }}" class="nav-link {{ Request::is('admin/PkgCompetences/uniteApprentissages') ? 'active' : '' }}">
                <i class="nav-icon fas fa-puzzle-piece"></i>
                {{__('PkgCompetences::uniteApprentissage.plural')}}
            </a>
        </li>
        @endcan
        @can('index-chapitre') 
        <li class="nav-item" id="menu-chapitres">
            <a href="{{ route('chapitres.index') }}" class="nav-link {{ Request::is('admin/PkgCompetences/chapitres') ? 'active' : '' }}">
                <i class="nav-icon fas fa-chalkboard"></i>
                {{__('PkgCompetences::chapitre.plural')}}
            </a>
        </li>
        @endcan
        @can('index-phaseEvaluation') 
        <li class="nav-item" id="menu-phaseEvaluations">
            <a href="{{ route('phaseEvaluations.index') }}" class="nav-link {{ Request::is('admin/PkgCompetences/phaseEvaluations') ? 'active' : '' }}">
                <i class="nav-icon fas fa-battery-three-quarters"></i>
                {{__('PkgCompetences::phaseEvaluation.plural')}}
            </a>
        </li>
        @endcan
        @can('index-critereEvaluation') 
        <li class="nav-item" id="menu-critereEvaluations">
            <a href="{{ route('critereEvaluations.index') }}" class="nav-link {{ Request::is('admin/PkgCompetences/critereEvaluations') ? 'active' : '' }}">
                <i class="nav-icon fas fa-check-circle"></i>
                {{__('PkgCompetences::critereEvaluation.plural')}}
            </a>
        </li>
        @endcan
    </ul>
</li>
@endif

