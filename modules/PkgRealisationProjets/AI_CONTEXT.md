# Contexte IA du Module : PkgRealisationProjets

## 1. Responsabilité Métier
Le package **PkgRealisationProjets** gère le déploiement opérationnel des projets auprès des promotions et le suivi global de leur réalisation :
1. **Affectation Promotionnelle :** Attribution d'un projet pédagogique (`Projet`) à un groupe ou sous-groupe d'apprenants (`AffectationProjet`) pour une année de formation donnée.
2. **Suivi Global de Réalisation :** Pilotage de l'avancement global de chaque apprenant sur le projet (`RealisationProjet`), incluant le statut de validation du prototype et le calcul des notes.
3. **Dépôt des Livrables :** Réception et qualification des livrables finaux remis par les apprenants (`LivrablesRealisation`).
4. **Calcul & Échelle des Notes :** Système de recalibrage proportionnel des notes selon une échelle cible paramétrable (`calculerNoteAvecEchelle()`).

---

## 2. Modèles & Relations Principales

### `AffectationProjet` (Table : `affectation_projets`)
- **Hérite de :** `BaseAffectationProjet`
- **Champs clés :** `projet_id`, `groupe_id`, `sous_groupe_id`, `annee_formation_id`, `date_debut`, `date_fin`, `echelle_note_cible`, `reference`.
- **Relations directes :**
  - `projet` : `belongsTo(Projet::class, 'projet_id')` *(PkgCreationProjet)*.
  - `groupe` : `belongsTo(Groupe::class, 'groupe_id')` *(PkgApprenants)*.
  - `sousGroupe` : `belongsTo(SousGroupe::class, 'sous_groupe_id')` *(PkgApprenants)*.
  - `anneeFormation` : `belongsTo(AnneeFormation::class, 'annee_formation_id')` *(PkgFormation)*.
  - `realisationProjets` : `hasMany(RealisationProjet::class)`.
- **Référence standard :** `{projet->reference}.{groupe->reference}`.

### `RealisationProjet` (Table : `realisation_projets`)
- **Hérite de :** `BaseRealisationProjet`
- **Champs clés :** `affectation_projet_id`, `apprenant_id`, `etats_realisation_projet_id`, `note_cache`, `bareme_cache`, `cache_est_valide_prototype_premier_essai`, `reference`.
- **Eager Loading par défaut :** `affectationProjet`, `apprenant`, `etatsRealisationProjet`.
- **Scope Global :** `apprenant_actif` filtre les réalisations pour ne cibler que les apprenants actifs.
- **Relations directes :**
  - `affectationProjet` : `belongsTo(AffectationProjet::class)`.
  - `apprenant` : `belongsTo(Apprenant::class)` *(PkgApprenants)*.
  - `etatsRealisationProjet` : `belongsTo(EtatsRealisationProjet::class)`.
  - `livrablesRealisations` : `hasMany(LivrablesRealisation::class)`.
  - `realisationTaches` : `hasMany(RealisationTache::class)` *(PkgRealisationTache)*.
- **Méthodes Métier Clés :**
  - `calculerNoteAvecEchelle(): float` : Applique la proportionnalité entre la note brute cumulée des tâches et l'échelle cible de l'affectation (avec arrondi au quart de point si échelle sur 4).
  - `generateReference()` : format `{affectationProjet->reference}-{apprenant->reference}`.

---

## 3. Services & Logique Métier

### `AffectationProjetService` (`Modules\PkgRealisationProjets\Services\AffectationProjetService`)
- Initialisation par lot des enregistrements `RealisationProjet` et des `RealisationTache` associées pour tous les apprenants du groupe affecté.

### `RealisationProjetService` (`Modules\PkgRealisationProjets\Services\RealisationProjetService`)
- Recalcul périodique ou événementiel des caches de progression et des notes finales.

---

## 4. Particularités UI & Filtres
- **Filtres TableUI :** Filtrage ManyToOne par Projet, Groupe, Apprenant et État d'avancement.
- **Affichage des Notes :** Intégration du composant de visualisation des barèmes et des notes recalculées.
