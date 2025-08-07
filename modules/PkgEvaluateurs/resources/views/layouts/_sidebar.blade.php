{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-etatEvaluationProjet', 'index-evaluateur', 'index-evaluationRealisationProjet'])
@if($accessiblePermissions->isNotEmpty())
    @if($accessiblePermissions->count() === 1)
        {{-- Cas d’un seul élément accessible --}}
            @can('index-etatEvaluationProjet')
            <li class="nav-item" id="menu-etatEvaluationProjets">
                <a href="{{ route('etatEvaluationProjets.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgEvaluateurs/etatEvaluationProjets') ? 'active' : '' }}">
                    <i class="nav-icon fa-table"></i>
                    {{__('PkgEvaluateurs::etatEvaluationProjet.plural')}}
                </a>
            </li>
            @endcan
            @can('index-evaluateur')
            <li class="nav-item" id="menu-evaluateurs">
                <a href="{{ route('evaluateurs.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgEvaluateurs/evaluateurs') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-user-check"></i>
                    {{__('PkgEvaluateurs::evaluateur.plural')}}
                </a>
            </li>
            @endcan
            @can('index-evaluationRealisationProjet')
            <li class="nav-item" id="menu-evaluationRealisationProjets">
                <a href="{{ route('evaluationRealisationProjets.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgEvaluateurs/evaluationRealisationProjets') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-check-square"></i>
                    {{__('PkgEvaluateurs::evaluationRealisationProjet.plural')}}
                </a>
            </li>
            @endcan

    @else
    <li id="menu-PkgEvaluateurs" class="nav-item has-treeview  {{ Request::is('admin/PkgEvaluateurs*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgEvaluateurs*') ? 'active' : '' }}">
            <i class="nav-icon {{__('PkgEvaluateurs::PkgEvaluateurs.icon')}}"></i>
            <p>
                {{__('PkgEvaluateurs::PkgEvaluateurs.name')}}
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            @can('index-etatEvaluationProjet') 
            <li class="nav-item" id="menu-etatEvaluationProjets">
                <a href="{{ route('etatEvaluationProjets.index') }}" class="nav-link {{ Request::is('admin/PkgEvaluateurs/etatEvaluationProjets') ? 'active' : '' }}">
                    <i class="nav-icon fa-table"></i>
                    {{__('PkgEvaluateurs::etatEvaluationProjet.plural')}}
                </a>
            </li>
            @endcan
            @can('index-evaluateur') 
            <li class="nav-item" id="menu-evaluateurs">
                <a href="{{ route('evaluateurs.index') }}" class="nav-link {{ Request::is('admin/PkgEvaluateurs/evaluateurs') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-user-check"></i>
                    {{__('PkgEvaluateurs::evaluateur.plural')}}
                </a>
            </li>
            @endcan
            @can('index-evaluationRealisationProjet') 
            <li class="nav-item" id="menu-evaluationRealisationProjets">
                <a href="{{ route('evaluationRealisationProjets.index') }}" class="nav-link {{ Request::is('admin/PkgEvaluateurs/evaluationRealisationProjets') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-check-square"></i>
                    {{__('PkgEvaluateurs::evaluationRealisationProjet.plural')}}
                </a>
            </li>
            @endcan
        </ul>
    </li>
  @endif
@endif

