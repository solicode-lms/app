# Capacité : Calcul de Note — PkgApprentissage

## 1. Principe Général

Le calcul de note est **en cascade ascendante** : le niveau le plus bas calcule sa note, puis appelle le niveau supérieur.

```
RealisationTache (note brute)
  → RealisationUaProjet / RealisationUaPrototype (note + bareme)
    → RealisationUa::calculerProgression()
      → RealisationMicroCompetenceService::calculerProgression()
        → RealisationCompetenceService::calculerProgression()
          → RealisationModuleService::calculerProgression()
```

**Déclencheur** : Chaque `afterUpdateRules()` du service enfant appelle `calculerProgression()` puis remonte au niveau supérieur.

---

## 2. Calcul par Niveau

### Niveau UA (`RealisationUaService::calculerProgression()`)

La note de la UA est la **somme brute** des notes de ses 3 parties :
- `realisationChapitre` (poids 20%)
- `realisationUaPrototype` (poids 30% si chapitres existent, 50% sinon)
- `realisationUaProjet` (poids 50%)

```php
// Pour chaque partie :
$bareme               = $items->sum(fn($e) => $e->note !== null ? ($e->bareme ?? 0) : 0);
$baremeNonEvalue      = $items->sum(fn($e) => $e->note === null ? ($e->bareme ?? 0) : 0);
$note                 = $items->sum(fn($e) => $e->note ?? 0);

$realisationUa->note_cache   = number_format($totalNote, 2, '.', '');
$realisationUa->bareme_cache = number_format($totalBareme, 2, '.', '');
```

> **Important** : Le barème ne compte que les tâches **effectivement notées** (`note !== null`).

---

### Niveau MicroCompétence (`RealisationMicroCompetenceService::calculerProgression()`)

```php
$totalNote   = $uas->sum(fn($ua) => $ua->note_cache ?? 0);
$totalBareme = $uas->sum(fn($ua) => $ua->bareme_cache ?? 0);

$rmc->note_cache   = round($totalNote, 2);
$rmc->bareme_cache = round($totalBareme, 2);
```

---

### Niveau Compétence (`RealisationCompetenceService::calculerProgression()`)

```php
$totalNote   = $rmcs->sum(fn($rmc) => $rmc->note_cache ?? 0);
$totalBareme = $rmcs->sum(fn($rmc) => $rmc->bareme_cache ?? 0);

$rc->note_cache   = round($totalNote, 2);
$rc->bareme_cache = round($totalBareme, 2);
```

---

### Niveau Module (`RealisationModuleService::calculerProgression()`)

```php
$totalNote   = $competences->sum(fn($c) => $c->note_cache ?? 0);
$totalBareme = $competences->sum(fn($c) => $c->bareme_cache ?? 0);

$rm->note_cache   = round($totalNote, 2);
$rm->bareme_cache = round($totalBareme, 2);
```

---

## 3. Calcul de la Note sur 40 (Export)

Pour l'export Excel/CSV, la note brute est convertie en note sur 40 via une proportionnalité :

```php
$noteSur40 = $bareme_cache > 0
    ? round(($note_cache / $bareme_cache) * 40, 2)
    : 0;
```

---

## 4. Hooks de Déclenchement

| Niveau          | Service                          | Hook déclencheur          |
|-----------------|----------------------------------|---------------------------|
| RealisationUa   | `RealisationUaService`           | `afterUpdateRules()`      |
| MicroCompétence | `RealisationMicroCompetenceService` | appelé par UA            |
| Compétence      | `RealisationCompetenceService`   | appelé par MicroCompétence|
| Module          | `RealisationModuleService`       | appelé par Compétence     |

> **Règle** : Toujours utiliser `saveQuietly()` lors des recalculs en cascade pour éviter les boucles infinies de hooks Eloquent.
