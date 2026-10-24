# Capacité : Calcul de Progression — PkgApprentissage

## 1. Principe Général

La progression est calculée **au niveau de la UA** puis agrégée vers le haut.
Elle est décomposée en **4 indicateurs** distincts stockés dans des colonnes `_cache`.

| Colonne                        | Signification                                         |
|--------------------------------|-------------------------------------------------------|
| `progression_cache`            | % réel d'avancement (tâches activées / total)         |
| `progression_ideal_cache`      | % idéal (tâches qui devraient être commencées)        |
| `taux_rythme_cache`            | Ratio progression_reelle / progression_ideale × 100   |
| `pourcentage_non_valide_cache` | % de tâches refusées (état NOT_VALIDATED)             |

---

## 2. Calcul au Niveau UA (`RealisationUaService::calculerProgression()`)

### Parties et Poids

| Partie               | Poids (si chapitres existent) | Poids (sans chapitres) |
|----------------------|-------------------------------|------------------------|
| RealisationChapitre  | 20%                           | 0%                     |
| RealisationUaPrototype | 30%                         | 50%                    |
| RealisationUaProjet  | 50%                           | 50%                    |

### Formules

```php
// Progression Réelle (tâches avancées / total)
$termines         = $items->filter(fn($e) => $this->isActiveProgress($e))->count();
$progressionReelle += ($termines / $countAll) * $poids;

// Progression Idéale (tâches activées / total)
$progressionIdeale += ($countActif / $countAll) * $poids;

// Pourcentage Non Valide
$nonValides = $items->filter(fn($e) => $this->isNonValide($e))->count();
$pourcentageNonValide += ($nonValides / $countAll) * $poids;

// Taux de Rythme
$taux_rythme_cache = $progression_ideal_cache > 0
    ? ($progressionReelle / $progression_ideal_cache) * 100
    : null;
```

### États des Tâches utilisés

| Méthode              | États concernés                                                  | Signification           |
|----------------------|------------------------------------------------------------------|-------------------------|
| `isActiveProgress()` | `READY_FOR_LIVE_CODING`, `IN_LIVE_CODING`, `TO_APPROVE`, `APPROVED` | Tâche en progression |
| `isActive()`         | Tout sauf `TODO`, `IN_PROGRESS`, `REVISION_NECESSAIRE`           | Tâche activée           |
| `isNonValide()`      | `NOT_VALIDATED`                                                  | Tâche refusée           |

---

## 3. Agrégation Compétence et Module

### Niveau Compétence & MicroCompétence
La progression est la **moyenne** des progressions des niveaux inférieurs :

```php
// Depuis les UAs vers la MicroCompétence (idem pour Compétence)
$totalProgression      = $uas->sum(fn($ua) => $ua->progression_cache ?? 0);
$totalProgressionIdeal = $uas->sum(fn($ua) => $ua->progression_ideal_cache ?? 0);

$rmc->progression_cache       = round($totalProgression / $countUas, 1);
$rmc->progression_ideal_cache = round($totalProgressionIdeal / $countUas, 1);
```

### Niveau Module
```php
// Depuis les Compétences
$rm->progression_cache       = round($totalProgression / $totalComp, 1);
$rm->progression_ideal_cache = round($totalProgressionIdeal / $totalComp, 1);
$rm->taux_rythme_cache       = $rm->progression_ideal_cache > 0
    ? round(($rm->progression_cache / $rm->progression_ideal_cache) * 100, 1)
    : null;
```

---

## 4. Calcul de l'État en Cascade

### Etat UA (`calculerEtat()`)

| Condition                                  | État Résultant         |
|--------------------------------------------|------------------------|
| Tous chapitres en `TODO`                  | `TODO`                 |
| Tous (chapitres + prototypes + projets) `APPROVED` | `DONE`        |
| Chapitres + prototypes `APPROVED`         | `IN_PROGRESS_PROJET`   |
| Chapitres seuls `APPROVED`                | `IN_PROGRESS_PROTOTYPE`|
| Au moins un chapitre `IN_PROGRESS`        | `IN_PROGRESS_CHAPITRE` |

### État Module (via `calculerEtatDepuisCompetences()`)
Les états compétences sont traduits en états module via un mapping :

```php
$mapping = [
    'IN_PROGRESS_CHAPITRE'  => 'IN_PROGRESS_INTRO',
    'IN_PROGRESS_PROTOTYPE' => 'IN_PROGRESS_INTERMEDIAIRE',
    'IN_PROGRESS_PROJET'    => 'IN_PROGRESS_AVANCE',
    'DONE'                  => 'DONE',
    'TODO'                  => 'TODO',
];
```
L'état final du module est celui de **la priorité la plus haute** présente parmi ses compétences.

---

## 5. Règles Techniques Importantes

- **`saveQuietly()`** : OBLIGATOIRE lors des recalculs en cascade pour éviter les boucles Eloquent.
- **`dernier_update`** : Toujours mettre à jour `$realisationUa->dernier_update = now()` lors d'un recalcul.
- **Formatage** : Utiliser `number_format($val, 2, '.', '')` pour éviter les arrondis flottants problématiques.
