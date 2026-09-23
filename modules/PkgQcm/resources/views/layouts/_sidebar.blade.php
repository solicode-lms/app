{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-etatRealisationQcm', 'index-qcm', 'index-question', 'index-propositionReponse', 'index-affectationQcmProjet', 'index-realisationQcm', 'index-reponseQcm'])
@if($accessiblePermissions->isNotEmpty())
    @if($accessiblePermissions->count() === 1)
        {{-- Cas d’un seul élément accessible --}}
            @can('index-etatRealisationQcm')
            <li class="nav-item" id="menu-etatRealisationQcms">
                <a href="{{ route('etatRealisationQcms.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgQcm/etatRealisationQcms') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-table"></i>
                    <p>{{__('PkgQcm::etatRealisationQcm.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-qcm')
            <li class="nav-item" id="menu-qcms">
                <a href="{{ route('qcms.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgQcm/qcms') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-table"></i>
                    <p>{{__('PkgQcm::qcm.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-question')
            <li class="nav-item" id="menu-questions">
                <a href="{{ route('questions.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgQcm/questions') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-table"></i>
                    <p>{{__('PkgQcm::question.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-propositionReponse')
            <li class="nav-item" id="menu-propositionReponses">
                <a href="{{ route('propositionReponses.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgQcm/propositionReponses') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-table"></i>
                    <p>{{__('PkgQcm::propositionReponse.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-affectationQcmProjet')
            <li class="nav-item" id="menu-affectationQcmProjets">
                <a href="{{ route('affectationQcmProjets.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgQcm/affectationQcmProjets') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-table"></i>
                    <p>{{__('PkgQcm::affectationQcmProjet.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-realisationQcm')
            <li class="nav-item" id="menu-realisationQcms">
                <a href="{{ route('realisationQcms.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgQcm/realisationQcms') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-table"></i>
                    <p>{{__('PkgQcm::realisationQcm.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-reponseQcm')
            <li class="nav-item" id="menu-reponseQcms">
                <a href="{{ route('reponseQcms.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgQcm/reponseQcms') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-table"></i>
                    <p>{{__('PkgQcm::reponseQcm.plural')}}</p>
                </a>
            </li>
            @endcan

    @else
    <li id="menu-PkgQcm" class="nav-item has-treeview  {{ Request::is('admin/PkgQcm*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgQcm*') ? 'active' : '' }}">
            <i class="nav-icon {{__('PkgQcm::PkgQcm.icon')}}"></i>
            <p>
                {{__('PkgQcm::PkgQcm.name')}}
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            @can('index-etatRealisationQcm') 
            <li class="nav-item" id="menu-etatRealisationQcms">
                <a href="{{ route('etatRealisationQcms.index') }}" class="nav-link {{ Request::is('admin/PkgQcm/etatRealisationQcms') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-table"></i>
                    <p>{{__('PkgQcm::etatRealisationQcm.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-qcm') 
            <li class="nav-item" id="menu-qcms">
                <a href="{{ route('qcms.index') }}" class="nav-link {{ Request::is('admin/PkgQcm/qcms') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-table"></i>
                    <p>{{__('PkgQcm::qcm.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-question') 
            <li class="nav-item" id="menu-questions">
                <a href="{{ route('questions.index') }}" class="nav-link {{ Request::is('admin/PkgQcm/questions') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-table"></i>
                    <p>{{__('PkgQcm::question.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-propositionReponse') 
            <li class="nav-item" id="menu-propositionReponses">
                <a href="{{ route('propositionReponses.index') }}" class="nav-link {{ Request::is('admin/PkgQcm/propositionReponses') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-table"></i>
                    <p>{{__('PkgQcm::propositionReponse.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-affectationQcmProjet') 
            <li class="nav-item" id="menu-affectationQcmProjets">
                <a href="{{ route('affectationQcmProjets.index') }}" class="nav-link {{ Request::is('admin/PkgQcm/affectationQcmProjets') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-table"></i>
                    <p>{{__('PkgQcm::affectationQcmProjet.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-realisationQcm') 
            <li class="nav-item" id="menu-realisationQcms">
                <a href="{{ route('realisationQcms.index') }}" class="nav-link {{ Request::is('admin/PkgQcm/realisationQcms') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-table"></i>
                    <p>{{__('PkgQcm::realisationQcm.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-reponseQcm') 
            <li class="nav-item" id="menu-reponseQcms">
                <a href="{{ route('reponseQcms.index') }}" class="nav-link {{ Request::is('admin/PkgQcm/reponseQcms') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-table"></i>
                    <p>{{__('PkgQcm::reponseQcm.plural')}}</p>
                </a>
            </li>
            @endcan
        </ul>
    </li>
  @endif
@endif

