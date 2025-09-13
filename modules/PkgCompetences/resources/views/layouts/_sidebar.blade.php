{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


@accessiblePermissions(['index-competence', 'index-microCompetence'])
@if($accessiblePermissions->isNotEmpty())
    @if($accessiblePermissions->count() === 1)
        {{-- Cas d’un seul élément accessible --}}
            @can('index-competence')
            <li class="nav-item" id="menu-competences">
                <a href="{{ route('competences.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgCompetences/competences') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-user-graduate"></i>
                    <p>{{__('PkgCompetences::competence.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-microCompetence')
            <li class="nav-item" id="menu-microCompetences">
                <a href="{{ route('microCompetences.index') }}" 
                   class="nav-link {{ Request::is('admin/PkgCompetences/microCompetences') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-book"></i>
                    <p>{{__('PkgCompetences::microCompetence.plural')}}</p>
                </a>
            </li>
            @endcan

    @else
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
                    <p>{{__('PkgCompetences::competence.plural')}}</p>
                </a>
            </li>
            @endcan
            @can('index-microCompetence') 
            <li class="nav-item" id="menu-microCompetences">
                <a href="{{ route('microCompetences.index') }}" class="nav-link {{ Request::is('admin/PkgCompetences/microCompetences') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-book"></i>
                    <p>{{__('PkgCompetences::microCompetence.plural')}}</p>
                </a>
            </li>
            @endcan
        </ul>
    </li>
  @endif
@endif

