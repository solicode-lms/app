{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-etatRealisationChapitre', 'index-etatRealisationMicroCompetence', 'index-etatRealisationUa', 'index-realisationChapitre', 'index-realisationMicroCompetence'])
@if($accessiblePermissions->isNotEmpty())
    @if($accessiblePermissions->count() === 1)
        {{-- Cas d’un seul élément accessible --}}
            @can('index-etatRealisationChapitre')
            <li class="nav-item" id="menu-etatRealisationChapitres">
                <a href="{{ route('etatRealisationChapitres.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgApprentissage/etatRealisationChapitres') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-check-square"></i>
                    {{__('PkgApprentissage::etatRealisationChapitre.plural')}}
                </a>
            </li>
            @endcan
            @can('index-etatRealisationMicroCompetence')
            <li class="nav-item" id="menu-etatRealisationMicroCompetences">
                <a href="{{ route('etatRealisationMicroCompetences.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgApprentissage/etatRealisationMicroCompetences') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-check-square"></i>
                    {{__('PkgApprentissage::etatRealisationMicroCompetence.plural')}}
                </a>
            </li>
            @endcan
            @can('index-etatRealisationUa')
            <li class="nav-item" id="menu-etatRealisationUas">
                <a href="{{ route('etatRealisationUas.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgApprentissage/etatRealisationUas') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-check-square"></i>
                    {{__('PkgApprentissage::etatRealisationUa.plural')}}
                </a>
            </li>
            @endcan
            @can('index-realisationChapitre')
            <li class="nav-item" id="menu-realisationChapitres">
                <a href="{{ route('realisationChapitres.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgApprentissage/realisationChapitres') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-code"></i>
                    {{__('PkgApprentissage::realisationChapitre.plural')}}
                </a>
            </li>
            @endcan
            @can('index-realisationMicroCompetence')
            <li class="nav-item" id="menu-realisationMicroCompetences">
                <a href="{{ route('realisationMicroCompetences.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgApprentissage/realisationMicroCompetences') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-coffee"></i>
                    {{__('PkgApprentissage::realisationMicroCompetence.plural')}}
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
                    {{__('PkgApprentissage::etatRealisationChapitre.plural')}}
                </a>
            </li>
            @endcan
            @can('index-etatRealisationMicroCompetence') 
            <li class="nav-item" id="menu-etatRealisationMicroCompetences">
                <a href="{{ route('etatRealisationMicroCompetences.index') }}" class="nav-link {{ Request::is('admin/PkgApprentissage/etatRealisationMicroCompetences') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-check-square"></i>
                    {{__('PkgApprentissage::etatRealisationMicroCompetence.plural')}}
                </a>
            </li>
            @endcan
            @can('index-etatRealisationUa') 
            <li class="nav-item" id="menu-etatRealisationUas">
                <a href="{{ route('etatRealisationUas.index') }}" class="nav-link {{ Request::is('admin/PkgApprentissage/etatRealisationUas') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-check-square"></i>
                    {{__('PkgApprentissage::etatRealisationUa.plural')}}
                </a>
            </li>
            @endcan
            @can('index-realisationChapitre') 
            <li class="nav-item" id="menu-realisationChapitres">
                <a href="{{ route('realisationChapitres.index') }}" class="nav-link {{ Request::is('admin/PkgApprentissage/realisationChapitres') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-code"></i>
                    {{__('PkgApprentissage::realisationChapitre.plural')}}
                </a>
            </li>
            @endcan
            @can('index-realisationMicroCompetence') 
            <li class="nav-item" id="menu-realisationMicroCompetences">
                <a href="{{ route('realisationMicroCompetences.index') }}" class="nav-link {{ Request::is('admin/PkgApprentissage/realisationMicroCompetences') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-coffee"></i>
                    {{__('PkgApprentissage::realisationMicroCompetence.plural')}}
                </a>
            </li>
            @endcan
        </ul>
    </li>
  @endif
@endif

