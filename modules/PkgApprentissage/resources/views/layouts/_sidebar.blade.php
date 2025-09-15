{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-etatRealisationChapitre', 'index-etatRealisationCompetence', 'index-etatRealisationMicroCompetence', 'index-etatRealisationModule', 'index-etatRealisationUa', 'index-realisationCompetence', 'index-realisationMicroCompetence', 'index-realisationModule'])
@if($accessiblePermissions->isNotEmpty())
    @if($accessiblePermissions->count() === 1)
        {{-- Cas d’un seul élément accessible --}}
            @can('index-etatRealisationChapitre')
            <li class="nav-item" id="menu-etatRealisationChapitres">
                <a href="{{ route('etatRealisationChapitres.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgApprentissage/etatRealisationChapitres') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-check-square"></i>
                    <p>{{__('PkgApprentissage::etatRealisationChapitre.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-etatRealisationCompetence')
            <li class="nav-item" id="menu-etatRealisationCompetences">
                <a href="{{ route('etatRealisationCompetences.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgApprentissage/etatRealisationCompetences') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-check-square"></i>
                    <p>{{__('PkgApprentissage::etatRealisationCompetence.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-etatRealisationMicroCompetence')
            <li class="nav-item" id="menu-etatRealisationMicroCompetences">
                <a href="{{ route('etatRealisationMicroCompetences.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgApprentissage/etatRealisationMicroCompetences') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-check-square"></i>
                    <p>{{__('PkgApprentissage::etatRealisationMicroCompetence.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-etatRealisationModule')
            <li class="nav-item" id="menu-etatRealisationModules">
                <a href="{{ route('etatRealisationModules.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgApprentissage/etatRealisationModules') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-check-square"></i>
                    <p>{{__('PkgApprentissage::etatRealisationModule.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-etatRealisationUa')
            <li class="nav-item" id="menu-etatRealisationUas">
                <a href="{{ route('etatRealisationUas.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgApprentissage/etatRealisationUas') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-check-square"></i>
                    <p>{{__('PkgApprentissage::etatRealisationUa.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-realisationCompetence')
            <li class="nav-item" id="menu-realisationCompetences">
                <a href="{{ route('realisationCompetences.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgApprentissage/realisationCompetences') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-award"></i>
                    <p>{{__('PkgApprentissage::realisationCompetence.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-realisationMicroCompetence')
            <li class="nav-item" id="menu-realisationMicroCompetences">
                <a href="{{ route('realisationMicroCompetences.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgApprentissage/realisationMicroCompetences') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-certificate"></i>
                    <p>{{__('PkgApprentissage::realisationMicroCompetence.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-realisationModule')
            <li class="nav-item" id="menu-realisationModules">
                <a href="{{ route('realisationModules.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgApprentissage/realisationModules') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-medal"></i>
                    <p>{{__('PkgApprentissage::realisationModule.plural')}}</p>
                </a>
            </li>
            @endcan

    @else
    <li id="menu-PkgApprentissage" class="nav-item has-treeview  {{ Request::is('admin/PkgApprentissage*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgApprentissage*') ? 'active' : '' }}">
            <i class="nav-icon {{__('PkgApprentissage::PkgApprentissage.icon')}}"></i>
            <p>
                {{__('PkgApprentissage::PkgApprentissage.name')}}
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            @can('index-etatRealisationChapitre') 
            <li class="nav-item" id="menu-etatRealisationChapitres">
                <a href="{{ route('etatRealisationChapitres.index') }}" class="nav-link {{ Request::is('admin/PkgApprentissage/etatRealisationChapitres') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-check-square"></i>
                    <p>{{__('PkgApprentissage::etatRealisationChapitre.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-etatRealisationCompetence') 
            <li class="nav-item" id="menu-etatRealisationCompetences">
                <a href="{{ route('etatRealisationCompetences.index') }}" class="nav-link {{ Request::is('admin/PkgApprentissage/etatRealisationCompetences') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-check-square"></i>
                    <p>{{__('PkgApprentissage::etatRealisationCompetence.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-etatRealisationMicroCompetence') 
            <li class="nav-item" id="menu-etatRealisationMicroCompetences">
                <a href="{{ route('etatRealisationMicroCompetences.index') }}" class="nav-link {{ Request::is('admin/PkgApprentissage/etatRealisationMicroCompetences') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-check-square"></i>
                    <p>{{__('PkgApprentissage::etatRealisationMicroCompetence.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-etatRealisationModule') 
            <li class="nav-item" id="menu-etatRealisationModules">
                <a href="{{ route('etatRealisationModules.index') }}" class="nav-link {{ Request::is('admin/PkgApprentissage/etatRealisationModules') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-check-square"></i>
                    <p>{{__('PkgApprentissage::etatRealisationModule.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-etatRealisationUa') 
            <li class="nav-item" id="menu-etatRealisationUas">
                <a href="{{ route('etatRealisationUas.index') }}" class="nav-link {{ Request::is('admin/PkgApprentissage/etatRealisationUas') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-check-square"></i>
                    <p>{{__('PkgApprentissage::etatRealisationUa.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-realisationCompetence') 
            <li class="nav-item" id="menu-realisationCompetences">
                <a href="{{ route('realisationCompetences.index') }}" class="nav-link {{ Request::is('admin/PkgApprentissage/realisationCompetences') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-award"></i>
                    <p>{{__('PkgApprentissage::realisationCompetence.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-realisationMicroCompetence') 
            <li class="nav-item" id="menu-realisationMicroCompetences">
                <a href="{{ route('realisationMicroCompetences.index') }}" class="nav-link {{ Request::is('admin/PkgApprentissage/realisationMicroCompetences') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-certificate"></i>
                    <p>{{__('PkgApprentissage::realisationMicroCompetence.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-realisationModule') 
            <li class="nav-item" id="menu-realisationModules">
                <a href="{{ route('realisationModules.index') }}" class="nav-link {{ Request::is('admin/PkgApprentissage/realisationModules') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-medal"></i>
                    <p>{{__('PkgApprentissage::realisationModule.plural')}}</p>
                </a>
            </li>
            @endcan
        </ul>
    </li>
  @endif
@endif

