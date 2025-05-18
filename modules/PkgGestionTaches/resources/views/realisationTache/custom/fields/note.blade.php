@php
    use Illuminate\Support\Facades\Auth;

    $user = Auth::user();

    // TODO : clean et reformuler : getPersonalEval
    // Liste des évaluateurs assignés
    $evaluateurs = $entity->realisationProjet
        ->affectationProjet
        ->evaluateurs
        ->pluck('id');

    // Récupère évaluation perso si l'utilisateur est évaluateur du projet
    $personalEval = $user->hasRole('evaluateur') && $evaluateurs->contains($user->evaluateur->id)
        ? $entity->evaluationRealisationTaches()
            ->where('evaluateur_id', $user->evaluateur->id)
            ->first()
        : null;
    $myNote = $personalEval?->note;

    // Moyenne stockée dans note
    $avgNote = number_format($entity->note, 2, '.', '');

    // Valeur principale affichée : note perso si existe, sinon moyenne
    $displayPrimary = $myNote !== null
        ? number_format($myNote, 2, '.', '')
        : $avgNote;
@endphp

<div class="text-center align-middle">
    <div class="d-flex flex-column align-items-center">
        <span class="font-weight-bold">{{ $displayPrimary }}</span>
        <small class="text-muted">Moyenne : {{ $avgNote }}</small>
    </div>
</div>

