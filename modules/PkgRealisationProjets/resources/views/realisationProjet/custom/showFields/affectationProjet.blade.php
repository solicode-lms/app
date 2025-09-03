@php
    use Illuminate\Support\Str;

    $projet       = data_get($entity, 'affectationProjet.projet');
    $projetTitre  = data_get($projet, 'titre') ?: (string) data_get($entity, 'affectationProjet');
    $sessionTitre = data_get($projet, 'sessionFormation.titre');

    $travailHtml  = data_get($projet, 'travail_a_faire') ?? '';
    $criteresHtml = data_get($projet, 'critere_de_travail') ?? '';

    // On masque les sections vides (on teste le contenu textuel, sans HTML)
    $hasTravail   = Str::of($travailHtml)->stripTags()->trim()->isNotEmpty();
    $hasCriteres  = Str::of($criteresHtml)->stripTags()->trim()->isNotEmpty();

    $deadline     = data_get($entity, 'affectationProjet.date_fin') ?? data_get($entity, 'deadline');
@endphp

<article class="projet-show-card" role="article">
    <header class="projet-titre">
        <h2>{{ $projetTitre }}</h2>
        @if($sessionTitre)
            <small>Session : {{ $sessionTitre }}</small>
        @endif
    </header>

    {{-- Sections principales : Travail & Critères (affichées uniquement si non vides) --}}
    @if($hasTravail || $hasCriteres)
    <section class="projet-sections-grid">
        @if($hasTravail)
        <section class="projet-travail projet-section" aria-labelledby="travail-heading">
            <h3 id="travail-heading">
                <i class="fas fa-tasks" aria-hidden="true"></i>
                <span>Travail à faire</span>
            </h3>
            <div class="projet-richtext">
                {!! $travailHtml !!}
            </div>
        </section>
        @endif

        @if($hasCriteres)
        <section class="projet-criteres projet-section" aria-labelledby="criteres-heading">
            <h3 id="criteres-heading">
                <i class="fas fa-clipboard-check" aria-hidden="true"></i>
                <span>Critères de travail</span>
            </h3>
            <div class="projet-richtext">
                {!! $criteresHtml !!}
            </div>

            {{-- Exemple optionnel :
            <ul>
                <li class="critere">
                    <span class="intitule">Respect des consignes</span>
                    <span class="bareme">/ 4</span>
                    <div class="progress" style="--percent: 75%"><span></span></div>
                </li>
            </ul>
            --}}
        </section>
        @endif
    </section>
    @endif

    <section class="projet-infos">
        @if($deadline)
            <span class="projet-deadline">
                <i class="far fa-clock" aria-hidden="true"></i>
                <x-deadline-display :value="$deadline" />
            </span>
        @endif
    </section>

    <footer class="projet-footer">
        <i class="fas fa-user" aria-hidden="true"></i>
        <strong>Apprenant :</strong> {{ data_get($entity, 'apprenant') }}
    </footer>
</article>
