    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button">
             <i class="fas fa-bars"></i>
            </a>
        </li>

        @can('index-widgetUtilisateur') 
        <li class="nav-item d-none d-sm-inline-block" style="font-size: 1.3em">
          <a href="{{ route(name: 'widgetUtilisateurs.index') }}" data-toggle="tooltip" title="Tableau de bord" class="nav-link">
            <i class="fas fa-home"></i>
          </a>
        </li>
        @endcan

        @can('index-realisationTache')
        <li class="nav-item d-none d-sm-inline-block" style="font-size: 1.3em">
          <a href="{{ route('realisationTaches.index') }}" data-toggle="tooltip" title="{{ ucfirst(__('PkgRealisationTache::realisationTache.plural')) }}" class="nav-link">
           <i class="fas fa-laptop-code"></i>
          </a>
        </li>
        @endcan
      
  
       
        {{-- @can('index-affectationProjet')
        <li class="nav-item d-none d-sm-inline-block" style="font-size: 1.3em">
          <a href="{{ route(name: 'affectationProjets.index') }}" data-toggle="tooltip" title="Affectation de projets" class="nav-link">
            <i class="fas fa-calendar-check"></i>
          </a>
        </li>
        @endcan --}}
        @can('index-realisationProjet')
        <li class="nav-item d-none d-sm-inline-block" style="font-size: 1.3em">
          <a href="{{ route(name: 'realisationProjets.index') }}" data-toggle="tooltip" title="{{ ucfirst(__('PkgRealisationProjets::realisationProjet.plural')) }}" class="nav-link">
            <i class="fas fa-laptop"></i>
          </a>
        </li>
        @endcan

     
        @can('index-projet')
        <li class="nav-item d-none d-sm-inline-block" style="font-size: 1.3em">
          <a href="{{ route(name: 'projets.index') }}" data-toggle="tooltip" title="{{ ucfirst(__('PkgCreationProjet::projet.plural')) }}" class="nav-link">
            <i class="fas fa-rocket"></i>
          </a>
        </li>
        @endcan
       
       
        {{-- @can('index-realisationChapitre')
        <li class="nav-item d-none d-sm-inline-block" style="font-size: 1.3em">
          <a href="{{ route('realisationChapitres.index') }}" data-toggle="tooltip" title="{{ ucfirst(__('PkgApprentissage::realisationChapitre.plural')) }}" class="nav-link">
           <i class="fas fas fa-code"></i>
          </a>
        </li>
        @endcan --}}
       

        @if(!Auth::user()->hasRole('admin'))

        @can('index-realisationMicroCompetence')
        <li class="nav-item d-none d-sm-inline-block" style="font-size: 1.3em">
          <a href="{{ route('realisationMicroCompetences.index') }}" data-toggle="tooltip" title="{{ ucfirst(__('PkgApprentissage::realisationMicroCompetence.plural')) }}" class="nav-link">
           <i class="fas fa-certificate"></i>
          </a>
        </li>
        @endcan

        @can('index-realisationCompetence')
        <li class="nav-item d-none d-sm-inline-block" style="font-size: 1.3em">
          <a href="{{ route('realisationCompetences.index') }}" data-toggle="tooltip" title="{{ ucfirst(__('PkgApprentissage::realisationCompetence.plural')) }}" class="nav-link">
           <i class="fas fa-award"></i>
          </a>
        </li>
        @endcan

        @endif


        @can('index-realisationModule')
        <li class="nav-item d-none d-sm-inline-block" style="font-size: 1.3em">
          <a href="{{ route('realisationModules.index') }}" data-toggle="tooltip" title="{{ ucfirst(__('PkgApprentissage::realisationModule.plural')) }}" class="nav-link">
           <i class="fas fa-medal"></i>
          </a>
        </li>
        @endcan

        @can('index-apprenant')
        <li class="nav-item d-none d-sm-inline-block" style="font-size: 1.3em">
          <a href="{{ route(name: 'apprenants.index') }}" data-toggle="tooltip" title="{{ ucfirst(__('PkgApprenants::apprenant.plural')) }}" class="nav-link">
            <i class="fas fa-id-card"></i>
          </a>
        </li>
        @endcan


        @can('index-evaluationRealisationProjet')
        <li class="nav-item d-none d-sm-inline-block" style="font-size: 1.3em">
          <a href="{{ route('evaluationRealisationProjets.index') }}" data-toggle="tooltip" title="{{ ucfirst(__('PkgEvaluateurs::evaluationRealisationProjet.plural')) }}" class="nav-link">
           <i class="fas fa-check-square"></i>
          </a>
        </li>
        @endcan
       
      </ul>


@if(getenv('XDEBUG_MODE') !== 'off')
     {{ getenv('XDEBUG_MODE') }}
@endif