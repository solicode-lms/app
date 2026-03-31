# Capacité : Affichage Blade — PkgApprentissage

## 1. Principe Général

Toutes les données de progression et de note affichées dans les vues **doivent être lues depuis les colonnes `_cache`** du modèle `Realisation*`. Il ne faut **jamais** recalculer une progression ou une note dans la vue Blade.

---

## 2. Composant `<x-progression-bar>`

Le composant `x-progression-bar` est le composant standard pour afficher l'avancement d'un apprenant à n'importe quel niveau.

### Signature

```blade
<x-progression-bar 
    :progression="$entity->progression_cache ?? 0" 
    :progression-ideal="$entity->progression_ideal_cache ?? 0"
    :pourcentage-non-valide="$entity->pourcentage_non_valide_cache ?? 0"
    :bareme-non-evalue="$entity->bareme_non_evalue_cache ?? 0"
/>
```

### Paramètres

| Paramètre               | Source sur le modèle                  | Description                          |
|-------------------------|---------------------------------------|--------------------------------------|
| `progression`           | `$entity->progression_cache`          | Progression réelle (%)               |
| `progression-ideal`     | `$entity->progression_ideal_cache`    | Progression idéale (%)               |
| `pourcentage-non-valide`| `$entity->pourcentage_non_valide_cache` | % de tâches invalidées             |
| `bareme-non-evalue`     | `$entity->bareme_non_evalue_cache`    | Points en attente d'évaluation       |

---

## 3. Pattern Complet d'Affichage (Niveau Module)

Le champ personnalisé `progression_cache.blade.php` sert de référence pour chaque niveau.
Il se trouve dans : `Resources/views/[entite]/custom/fields/progression_cache.blade.php`

```blade
<div class="realisation-etat with-progress"
     style="--etat-color: {{ $entity->etatRealisationModule->sysColor->hex ?? '#6c757d' }};">

    {{-- Badge d'état (ex: TODO, IN_PROGRESS_AVANCE, DONE) --}}
    <x-badge 
        :text="$entity->etatRealisationModule->nom" 
        :background="$entity->etatRealisationModule->sysColor->hex ?? '#6c757d'" 
        class="badge-etat"
    />

    {{-- Barre de progression --}}
    <div class="etat-meta">
        <x-progression-bar 
            :progression="$entity->progression_cache ?? 0" 
            :progression-ideal="$entity->progression_ideal_cache ?? 0"
            :pourcentage-non-valide="$entity->pourcentage_non_valide_cache ?? 0"
            :bareme-non-evalue="$entity->bareme_non_evalue_cache ?? 0"
        />
    </div>

    {{-- Points en attente d'évaluation --}}
    @if(isset($entity->bareme_non_evalue_cache) && $entity->bareme_non_evalue_cache > 0)
    <span class="etat-meta" title="En attente d'évaluation" data-toggle="tooltip">
        <i class="fas fa-hourglass-half text-warning"></i>
        À évaluer : {{ $entity->bareme_non_evalue_cache }} Pts
    </span>
    @endif

    {{-- Taux de rythme avec icône dynamique --}}
    @if($entity->taux_rythme_cache)
    @php
        $rythme = $entity->taux_rythme_cache ?? 0;
        $icone = match(true) {
            $rythme < 20 => 'fas fa-bed',
            $rythme < 40 => 'fas fa-walking',
            $rythme < 60 => 'fas fa-running',
            $rythme < 80 => 'fas fa-biking',
            default      => 'fas fa-rocket',
        };
    @endphp
    <span class="etat-meta" title="{{ $entity->lecture_pedagogique }}" data-toggle="tooltip">
        <i class="{{ $icone }}"></i>
        Rythme : {{ $rythme }} %
    </span>
    @endif

    {{-- Date du dernier recalcul --}}
    @if($entity->dernier_update)
    <span class="etat-meta">
        <i class="fas fa-history"></i>
        {{ \Carbon\Carbon::parse($entity->dernier_update)?->diffForHumans() }}
    </span>
    @endif
</div>
```

---

## 4. Adaptations par Niveau

Le même pattern s'applique à tous les niveaux en changeant la relation d'état :

| Niveau           | Relation d'état                        | Vue source                    |
|------------------|----------------------------------------|-------------------------------|
| Module           | `etatRealisationModule`                | `realisationModule/custom/`   |
| Compétence       | `etatRealisationCompetence`            | `realisationCompetence/custom/`|
| MicroCompétence  | `etatRealisationMicroCompetence`       | `realisationMicroCompetence/custom/`|
| UA               | `etatRealisationUa`                    | `realisationUa/custom/`       |

---

## 5. Affichage de la Note (Tableau Index)

Pour afficher la note dans une colonne d'un tableau :

```blade
{{-- Note brute --}}
{{ $entity->note_cache ?? 0 }} / {{ $entity->bareme_cache ?? 0 }}

{{-- Note sur 40 --}}
@php
    $noteSur40 = ($entity->bareme_cache ?? 0) > 0
        ? round(($entity->note_cache / $entity->bareme_cache) * 40, 2)
        : 0;
@endphp
{{ $noteSur40 }} / 40
```

---

## 6. Règles à Respecter

1. **Ne jamais calculer** `note_cache` ou `progression_cache` dans la vue — utiliser uniquement les colonnes déjà persistées.
2. **Toujours utiliser** `?? 0` pour les valeurs nullables afin d'éviter les erreurs d'affichage.
3. **Le composant `x-progression-bar`** est le seul composant autorisé pour les barres de progression — ne pas recoder une barre inline.
4. **L'attribut `lecture_pedagogique`** est disponible sur les entités `RealisationModule` via le trait `LecturePedagogieTrait` — l'utiliser pour le tooltip du rythme.
