{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-sysColor', 'index-sysModule', 'index-sysController', 'index-featureDomain', 'index-feature', 'index-sysModel', 'index-userModelFilter'])
@if($accessiblePermissions->isNotEmpty())
    @if($accessiblePermissions->count() === 1)
        {{-- Cas d’un seul élément accessible --}}
            @can('index-sysColor')
            <li class="nav-item" id="menu-sysColors">
                <a href="{{ route('sysColors.index') }}" 
                   class="nav-link {{ Request::is('admin/Core/sysColors') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-palette"></i>
                    <p>{{__('Core::sysColor.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-sysModule')
            <li class="nav-item" id="menu-sysModules">
                <a href="{{ route('sysModules.index') }}" 
                   class="nav-link {{ Request::is('admin/Core/sysModules') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-box"></i>
                    <p>{{__('Core::sysModule.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-sysController')
            <li class="nav-item" id="menu-sysControllers">
                <a href="{{ route('sysControllers.index') }}" 
                   class="nav-link {{ Request::is('admin/Core/sysControllers') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-server"></i>
                    <p>{{__('Core::sysController.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-featureDomain')
            <li class="nav-item" id="menu-featureDomains">
                <a href="{{ route('featureDomains.index') }}" 
                   class="nav-link {{ Request::is('admin/Core/featureDomains') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-th-large"></i>
                    <p>{{__('Core::featureDomain.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-feature')
            <li class="nav-item" id="menu-features">
                <a href="{{ route('features.index') }}" 
                   class="nav-link {{ Request::is('admin/Core/features') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-plug"></i>
                    <p>{{__('Core::feature.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-sysModel')
            <li class="nav-item" id="menu-sysModels">
                <a href="{{ route('sysModels.index') }}" 
                   class="nav-link {{ Request::is('admin/Core/sysModels') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-cubes"></i>
                    <p>{{__('Core::sysModel.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-userModelFilter')
            <li class="nav-item" id="menu-userModelFilters">
                <a href="{{ route('userModelFilters.index') }}" 
                   class="nav-link {{ Request::is('admin/Core/userModelFilters') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-table"></i>
                    <p>{{__('Core::userModelFilter.plural')}}</p>
                </a>
            </li>
            @endcan

    @else
    <li id="menu-Core" class="nav-item has-treeview  {{ Request::is('admin/Core*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link nav-link {{ Request::is('admin/Core*') ? 'active' : '' }}">
            <i class="nav-icon {{__('Core::Core.icon')}}"></i>
            <p>
                {{__('Core::Core.name')}}
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            @can('index-sysColor') 
            <li class="nav-item" id="menu-sysColors">
                <a href="{{ route('sysColors.index') }}" class="nav-link {{ Request::is('admin/Core/sysColors') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-palette"></i>
                    <p>{{__('Core::sysColor.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-sysModule') 
            <li class="nav-item" id="menu-sysModules">
                <a href="{{ route('sysModules.index') }}" class="nav-link {{ Request::is('admin/Core/sysModules') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-box"></i>
                    <p>{{__('Core::sysModule.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-sysController') 
            <li class="nav-item" id="menu-sysControllers">
                <a href="{{ route('sysControllers.index') }}" class="nav-link {{ Request::is('admin/Core/sysControllers') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-server"></i>
                    <p>{{__('Core::sysController.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-featureDomain') 
            <li class="nav-item" id="menu-featureDomains">
                <a href="{{ route('featureDomains.index') }}" class="nav-link {{ Request::is('admin/Core/featureDomains') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-th-large"></i>
                    <p>{{__('Core::featureDomain.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-feature') 
            <li class="nav-item" id="menu-features">
                <a href="{{ route('features.index') }}" class="nav-link {{ Request::is('admin/Core/features') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-plug"></i>
                    <p>{{__('Core::feature.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-sysModel') 
            <li class="nav-item" id="menu-sysModels">
                <a href="{{ route('sysModels.index') }}" class="nav-link {{ Request::is('admin/Core/sysModels') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-cubes"></i>
                    <p>{{__('Core::sysModel.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-userModelFilter') 
            <li class="nav-item" id="menu-userModelFilters">
                <a href="{{ route('userModelFilters.index') }}" class="nav-link {{ Request::is('admin/Core/userModelFilters') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-table"></i>
                    <p>{{__('Core::userModelFilter.plural')}}</p>
                </a>
            </li>
            @endcan
        </ul>
    </li>
  @endif
@endif

