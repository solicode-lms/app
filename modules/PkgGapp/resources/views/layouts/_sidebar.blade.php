{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-eMetadataDefinition', 'index-ePackage', 'index-eModel', 'index-eRelationship', 'index-eDataField', 'index-eMetadatum'])
@if($accessiblePermissions->isNotEmpty())
<li class="nav-item has-treeview {{ Request::is('admin/PkgGapp*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link nav-link {{ Request::is('admin/PkgGapp*') ? 'active' : '' }}">
        <i class="nav-icon fas  {{__('PkgGapp::PkgGapp.icon')}}"></i>
        <p>
            {{__('PkgGapp::PkgGapp.name')}}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @can('index-eMetadataDefinition') 
        <li class="nav-item">
            <a href="{{ route('eMetadataDefinitions.index') }}" class="nav-link {{ Request::is('admin/PkgGapp/eMetadataDefinitions') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgGapp::EMetadataDefinition.plural')}}
            </a>
        </li>
        @endcan
        @can('index-ePackage') 
        <li class="nav-item">
            <a href="{{ route('ePackages.index') }}" class="nav-link {{ Request::is('admin/PkgGapp/ePackages') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgGapp::EPackage.plural')}}
            </a>
        </li>
        @endcan
        @can('index-eModel') 
        <li class="nav-item">
            <a href="{{ route('eModels.index') }}" class="nav-link {{ Request::is('admin/PkgGapp/eModels') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgGapp::EModel.plural')}}
            </a>
        </li>
        @endcan
        @can('index-eRelationship') 
        <li class="nav-item">
            <a href="{{ route('eRelationships.index') }}" class="nav-link {{ Request::is('admin/PkgGapp/eRelationships') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgGapp::ERelationship.plural')}}
            </a>
        </li>
        @endcan
        @can('index-eDataField') 
        <li class="nav-item">
            <a href="{{ route('eDataFields.index') }}" class="nav-link {{ Request::is('admin/PkgGapp/eDataFields') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgGapp::EDataField.plural')}}
            </a>
        </li>
        @endcan
        @can('index-eMetadatum') 
        <li class="nav-item">
            <a href="{{ route('eMetadata.index') }}" class="nav-link {{ Request::is('admin/PkgGapp/eMetadata') ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                {{__('PkgGapp::EMetadatum.plural')}}
            </a>
        </li>
        @endcan
    </ul>
</li>
@endif

