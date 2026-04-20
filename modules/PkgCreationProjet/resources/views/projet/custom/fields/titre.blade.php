<div class="d-flex flex-column" style="gap: 8px;">
    {{-- Header : Titre, Session et Formateur --}}
    <div>
        <div class="mb-1">
            <h6 class="mb-0 font-weight-bold text-primary" style="font-size: 1rem; line-height: 1.2;">
                {{ $entity->titre }}
            </h6>
        </div>
        <div class="d-flex flex-wrap align-items-center text-muted" style="font-size: 0.8rem; gap: 8px;">
            @if($entity->sessionFormation)
                <span title="Session de formation" data-toggle="tooltip"><i class="fas fa-calendar-alt text-secondary"></i> {{ $entity->sessionFormation->titre }}</span>
            @endif
            
            @if($entity->affectationProjets->isNotEmpty())
                <span class="text-light">|</span>
                @if($entity->affectationProjets->count() == 1)
                    @php $affectationProjet = $entity->affectationProjets->first(); @endphp
                    <span title="Réalisation du groupe affecté" data-toggle="tooltip">
                        <i class="fas fa-users text-info"></i> 
                        <a href="/admin/PkgRealisationProjets/realisationProjets?filter.realisationProjet.affectation_projet_id={{ $affectationProjet->id }}" class="font-weight-600 font-italic">{{ $affectationProjet->groupe->code }}</a>
                        @if($affectationProjet->date_debut && $affectationProjet->date_fin)
                            <span class="badge badge-light border text-muted ml-1" style="font-size: 0.65rem;">
                                <i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($affectationProjet->date_debut)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($affectationProjet->date_fin)->format('d/m/Y') }}
                            </span>
                        @endif
                    </span>
                @else
                    <span title="Réalisation des groupes affectés" data-toggle="tooltip">
                        <i class="fas fa-users text-info"></i> 
                        @foreach ($entity->affectationProjets as $affectationProjet)
                            <span title="Du {{ $affectationProjet->date_debut ? \Carbon\Carbon::parse($affectationProjet->date_debut)->format('d/m/Y') : '--' }} au {{ $affectationProjet->date_fin ? \Carbon\Carbon::parse($affectationProjet->date_fin)->format('d/m/Y') : '--' }}">
                                <a href="/admin/PkgRealisationProjets/realisationProjets?filter.realisationProjet.affectation_projet_id={{ $affectationProjet->id }}" class="font-weight-600 font-italic">{{ $affectationProjet->groupe->code }}</a>@if($affectationProjet->date_debut && $affectationProjet->date_fin)<span class="text-muted mx-1" style="font-size: 0.65rem;">({{ \Carbon\Carbon::parse($affectationProjet->date_debut)->format('d/m') }}-{{ \Carbon\Carbon::parse($affectationProjet->date_fin)->format('d/m') }})</span>@endif{{ !$loop->last ? ',' : '' }}
                            </span>
                        @endforeach
                    </span>
                @endif
            @endif

            @if($entity->formateur)
                <span class="text-light">|</span>
                <span title="Formateur assigné" data-toggle="tooltip">
                    <i class="fas fa-user-tie text-secondary"></i> {{ $entity->formateur }}
                </span>
            @endif

            <span class="text-light">|</span>
            @if($entity->is_auto_insert_chapitres)
                <span class="badge badge-success-light border text-success px-1 shadow-sm" data-toggle="tooltip" style="font-size: 0.65rem;" title="L'insertion automatique des chapitres est activée">
                    <i class="fas fa-chalkboard"></i> Auto
                </span>
            @else
                <span class="badge badge-light border text-secondary px-1 shadow-sm" style="font-size: 0.65rem;"  data-toggle="tooltip" title="L'insertion des chapitres est manuelle">
                    <i class="fas fa-chalkboard"></i> Manuel
                </span>
            @endif
        </div>
    </div>

    {{-- Mobilisations (UA) --}}
    @if($entity->mobilisationUas && $entity->mobilisationUas->isNotEmpty())
        <div class="bg-light rounded p-2 border" style="font-size: 0.85rem;">
            <div class="text-uppercase text-secondary font-weight-bold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                <i class="fas fa-bullseye text-danger mr-1"></i> Unités d'Apprentissage & Compétences
            </div>
            <ul class="list-unstyled mb-0 pl-1">
                @foreach ($entity->mobilisationUas as $mobilisation)
                    @php
                        $ua = $mobilisation->uniteApprentissage;
                        $comp = $ua?->microCompetence?->competence;
                    @endphp
                    <li class="mb-1 text-truncate">
                        <i class="fas fa-check text-success mr-1" style="font-size: 0.7rem;"></i>
                        <span class="font-weight-600 text-dark">{{ $ua ? $ua->nom : 'N/A' }}</span>
                        @if($comp)
                            <span class="text-muted mx-1">&middot;</span>
                            <span class="text-muted align-middle" style="font-size: 0.75rem;" data-toggle="tooltip" title="{{ $comp->nom }}">{{ $comp->code }}</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endif



    {{-- Ressources --}}
    @if($entity->resources->isNotEmpty())
        <div class="d-flex flex-wrap align-items-center" style="gap: 4px;">
            <span class="text-muted mr-1" style="font-size: 0.75rem;"><i class="fas fa-paperclip"></i> Ressources:</span>
            @foreach ($entity->resources as $resource)
                <span class="badge badge-secondary" style="font-weight: normal; font-size: 0.7rem;">
                    {{ Str::limit($resource->nom, 20) }}
                </span>
            @endforeach
        </div>
    @endif


    
</div>

<style>
    .font-weight-600 { font-weight: 600; }
</style>

