{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-etatEvaluationProjet', 'index-evaluateur', 'index-evaluationRealisationProjet'])
@if($accessiblePermissions->isNotEmpty())
<li id="menu-PkgValidationProjets" class="nav-item has-treeview  {{ Request::is('admin/PkgValidationProjets*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgValidationProjets*') ? 'active' : '' }}">
        <i class="nav-icon {{__('PkgValidationProjets::PkgValidationProjets.icon')}}"></i>
        <p>
            {{__('PkgValidationProjets::PkgValidationProjets.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @can('index-etatEvaluationProjet') 
        <li class="nav-item" id="menu-etatEvaluationProjets">
            <a href="{{ route('etatEvaluationProjets.index') }}" class="nav-link {{ Request::is('admin/PkgValidationProjets/etatEvaluationProjets') ? 'active' : '' }}">
                <i class="nav-icon fa-table"></i>
                {{__('PkgValidationProjets::etatEvaluationProjet.plural')}}
            </a>
        </li>
        @endcan
        @can('index-evaluateur') 
        <li class="nav-item" id="menu-evaluateurs">
            <a href="{{ route('evaluateurs.index') }}" class="nav-link {{ Request::is('admin/PkgValidationProjets/evaluateurs') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user-check"></i>
                {{__('PkgValidationProjets::evaluateur.plural')}}
            </a>
        </li>
        @endcan
        @can('index-evaluationRealisationProjet') 
        <li class="nav-item" id="menu-evaluationRealisationProjets">
            <a href="{{ route('evaluationRealisationProjets.index') }}" class="nav-link {{ Request::is('admin/PkgValidationProjets/evaluationRealisationProjets') ? 'active' : '' }}">
                <i class="nav-icon fas fa-check-square"></i>
                {{__('PkgValidationProjets::evaluationRealisationProjet.plural')}}
            </a>
        </li>
        @endcan
    </ul>
</li>
@endif

