{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-etatRealisationQcm', 'index-qcm', 'index-questionLib', 'index-reponseQcm'])
@if($accessiblePermissions->isNotEmpty())
    @if($accessiblePermissions->count() === 1)
        {{-- Cas d’un seul élément accessible --}}
            @can('index-etatRealisationQcm')
            <li class="nav-item" id="menu-etatRealisationQcms">
                <a href="{{ route('etatRealisationQcms.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgQcm/etatRealisationQcms') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-check-square"></i>
                    <p>{{__('PkgQcm::etatRealisationQcm.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-qcm')
            <li class="nav-item" id="menu-qcms">
                <a href="{{ route('qcms.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgQcm/qcms') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-graduation-cap"></i>
                    <p>{{__('PkgQcm::qcm.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-questionLib')
            <li class="nav-item" id="menu-questionLibs">
                <a href="{{ route('questionLibs.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgQcm/questionLibs') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-cube"></i>
                    <p>{{__('PkgQcm::questionLib.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-reponseQcm')
            <li class="nav-item" id="menu-reponseQcms">
                <a href="{{ route('reponseQcms.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgQcm/reponseQcms') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-file-alt"></i>
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
                    <i class="nav-icon fas fa-check-square"></i>
                    <p>{{__('PkgQcm::etatRealisationQcm.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-qcm') 
            <li class="nav-item" id="menu-qcms">
                <a href="{{ route('qcms.index') }}" class="nav-link {{ Request::is('admin/PkgQcm/qcms') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-graduation-cap"></i>
                    <p>{{__('PkgQcm::qcm.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-questionLib') 
            <li class="nav-item" id="menu-questionLibs">
                <a href="{{ route('questionLibs.index') }}" class="nav-link {{ Request::is('admin/PkgQcm/questionLibs') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-cube"></i>
                    <p>{{__('PkgQcm::questionLib.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-reponseQcm') 
            <li class="nav-item" id="menu-reponseQcms">
                <a href="{{ route('reponseQcms.index') }}" class="nav-link {{ Request::is('admin/PkgQcm/reponseQcms') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-file-alt"></i>
                    <p>{{__('PkgQcm::reponseQcm.plural')}}</p>
                </a>
            </li>
            @endcan
        </ul>
    </li>
  @endif
@endif

