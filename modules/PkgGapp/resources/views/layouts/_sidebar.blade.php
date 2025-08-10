{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-eDataField', 'index-eMetadatum', 'index-eMetadataDefinition', 'index-eModel', 'index-ePackage', 'index-eRelationship'])
@if($accessiblePermissions->isNotEmpty())
    @if($accessiblePermissions->count() === 1)
        {{-- Cas d’un seul élément accessible --}}
            @can('index-eDataField')
            <li class="nav-item" id="menu-eDataFields">
                <a href="{{ route('eDataFields.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgGapp/eDataFields') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-th"></i>
                    {{__('PkgGapp::eDataField.plural')}}
                </a>
            </li>
            @endcan
            @can('index-eMetadatum')
            <li class="nav-item" id="menu-eMetadata">
                <a href="{{ route('eMetadata.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgGapp/eMetadata') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-th-list"></i>
                    {{__('PkgGapp::eMetadatum.plural')}}
                </a>
            </li>
            @endcan
            @can('index-eMetadataDefinition')
            <li class="nav-item" id="menu-eMetadataDefinitions">
                <a href="{{ route('eMetadataDefinitions.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgGapp/eMetadataDefinitions') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-database"></i>
                    {{__('PkgGapp::eMetadataDefinition.plural')}}
                </a>
            </li>
            @endcan
            @can('index-eModel')
            <li class="nav-item" id="menu-eModels">
                <a href="{{ route('eModels.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgGapp/eModels') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-table"></i>
                    {{__('PkgGapp::eModel.plural')}}
                </a>
            </li>
            @endcan
            @can('index-ePackage')
            <li class="nav-item" id="menu-ePackages">
                <a href="{{ route('ePackages.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgGapp/ePackages') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-box"></i>
                    {{__('PkgGapp::ePackage.plural')}}
                </a>
            </li>
            @endcan
            @can('index-eRelationship')
            <li class="nav-item" id="menu-eRelationships">
                <a href="{{ route('eRelationships.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgGapp/eRelationships') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-directions"></i>
                    {{__('PkgGapp::eRelationship.plural')}}
                </a>
            </li>
            @endcan

    @else
    <li id="menu-PkgGapp" class="nav-item has-treeview  {{ Request::is('admin/PkgGapp*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgGapp*') ? 'active' : '' }}">
            <i class="nav-icon {{__('PkgGapp::PkgGapp.icon')}}"></i>
            <p>
                {{__('PkgGapp::PkgGapp.name')}}
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            @can('index-eDataField') 
            <li class="nav-item" id="menu-eDataFields">
                <a href="{{ route('eDataFields.index') }}" class="nav-link {{ Request::is('admin/PkgGapp/eDataFields') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-th"></i>
                    {{__('PkgGapp::eDataField.plural')}}
                </a>
            </li>
            @endcan
            @can('index-eMetadatum') 
            <li class="nav-item" id="menu-eMetadata">
                <a href="{{ route('eMetadata.index') }}" class="nav-link {{ Request::is('admin/PkgGapp/eMetadata') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-th-list"></i>
                    {{__('PkgGapp::eMetadatum.plural')}}
                </a>
            </li>
            @endcan
            @can('index-eMetadataDefinition') 
            <li class="nav-item" id="menu-eMetadataDefinitions">
                <a href="{{ route('eMetadataDefinitions.index') }}" class="nav-link {{ Request::is('admin/PkgGapp/eMetadataDefinitions') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-database"></i>
                    {{__('PkgGapp::eMetadataDefinition.plural')}}
                </a>
            </li>
            @endcan
            @can('index-eModel') 
            <li class="nav-item" id="menu-eModels">
                <a href="{{ route('eModels.index') }}" class="nav-link {{ Request::is('admin/PkgGapp/eModels') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-table"></i>
                    {{__('PkgGapp::eModel.plural')}}
                </a>
            </li>
            @endcan
            @can('index-ePackage') 
            <li class="nav-item" id="menu-ePackages">
                <a href="{{ route('ePackages.index') }}" class="nav-link {{ Request::is('admin/PkgGapp/ePackages') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-box"></i>
                    {{__('PkgGapp::ePackage.plural')}}
                </a>
            </li>
            @endcan
            @can('index-eRelationship') 
            <li class="nav-item" id="menu-eRelationships">
                <a href="{{ route('eRelationships.index') }}" class="nav-link {{ Request::is('admin/PkgGapp/eRelationships') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-directions"></i>
                    {{__('PkgGapp::eRelationship.plural')}}
                </a>
            </li>
            @endcan
        </ul>
    </li>
  @endif
@endif

