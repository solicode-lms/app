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
      
        @can('index-projet')
        <li class="nav-item d-none d-sm-inline-block" style="font-size: 1.3em">
          <a href="{{ route(name: 'projets.index') }}" data-toggle="tooltip" title="Projets" class="nav-link">
            <i class="fas fa-lightbulb"></i>
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
          <a href="{{ route(name: 'realisationProjets.index') }}" data-toggle="tooltip" title="Réalisation des projets" class="nav-link">
            <i class="fas fa-laptop"></i>
          </a>
        </li>
        @endcan
        @can('index-realisationTache')
        <li class="nav-item d-none d-sm-inline-block" style="font-size: 1.3em">
          <a href="{{ route('realisationTaches.index') }}" data-toggle="tooltip" title="Réalisation des tâches" class="nav-link">
           <i class="fas fa-laptop-code"></i>
          </a>
        </li>
        @endcan

        @can('index-realisationMicroCompetence')
        <li class="nav-item d-none d-sm-inline-block" style="font-size: 1.3em">
          <a href="{{ route('realisationMicroCompetences.index') }}" data-toggle="tooltip" title="Réalisation autoformations" class="nav-link">
           <i class="fas fas fa-coffee"></i>
          </a>
        </li>
        @endcan
       
       

        @can('index-apprenant')
        <li class="nav-item d-none d-sm-inline-block" style="font-size: 1.3em">
          <a href="{{ route(name: 'apprenants.index') }}" data-toggle="tooltip" title="Apprenants" class="nav-link">
            <i class="fas fa-id-card"></i>
          </a>
        </li>
        @endcan


        @can('index-evaluationRealisationProjet')
        <li class="nav-item d-none d-sm-inline-block" style="font-size: 1.3em">
          <a href="{{ route('evaluationRealisationProjets.index') }}" data-toggle="tooltip" title="Évaluations des Projets" class="nav-link">
           <i class="fas fa-check-square"></i>
          </a>
        </li>
        @endcan
       
      </ul>