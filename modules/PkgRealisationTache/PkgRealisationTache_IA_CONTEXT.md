# Contexte IA du Module : PkgRealisationTache

## 1. Responsabilité Métier
Le package **PkgRealisationTache** gère l'exécution opérationnelle, le suivi d'avancement, la détection d'inactivité et la validation des tâches par les apprenants :
1. **Suivi d'Exécution :** État d'avancement des tâches (`RealisationTache`), transitions d'états (À faire ➔ En cours ➔ En validation ➔ Terminé).
2. **Workflow & États :** Modélisation des statuts (`EtatRealisationTache`), règles de transition (`WorkflowTache`).
3. **Traçabilité & Échanges :** Historique chronologique des changements d'états (`HistoriqueRealisationTache`) et échanges formateurs-apprenants (`CommentaireRealisationTache`).
4. **Dispositifs Pédagogiques Spécifiques :** Suivi des sessions de codage en direct (`LiveCoding`) et des cycles de révision technique (`RevisionTache`).
5. **Suivi Temporel & Rythme d'Établissement :** Gestion du calendrier journalier des séances (`SeanceService`), détection proactive et audit de l'inactivité des apprenants en temps réel (séance active ou journée en cours).

---

## 2. Modèles & Relations Principales

### `RealisationTache` (Table : `realisation_taches`)
- **Hérite de :** `BaseRealisationTache`
- **Champs clés :** `tache_id`, `realisation_projet_id`, `etat_realisation_tache_id`, `date_debut`, `date_fin`, `is_live_coding`, `reference`.
- **Eager Loading par défaut :** `tache.livrables`, `realisationProjet.apprenant`, `etatRealisationTache`, `livrablesRealisations`.
- **Attribut Dynamique :** `formateur_name` (requête SQL dynamique liant la réalisation au formateur responsable du projet).
- **Scope Global :** `apprenant_actif` restreint la sélection aux apprenants en statut actif.
- **Relations directes :**
  - `tache` : `belongsTo(Tache::class, 'tache_id')` *(PkgCreationTache)*.
  - `realisationProjet` : `belongsTo(RealisationProjet::class, 'realisation_projet_id')` *(PkgRealisationProjets)*.
  - `etatRealisationTache` : `belongsTo(EtatRealisationTache::class)`.
  - `commentaires` : `hasMany(CommentaireRealisationTache::class)`.
  - `historiques` : `hasMany(HistoriqueRealisationTache::class)`.
  - `revisions` : `hasMany(RevisionTache::class)`.
  - `liveCodings` : `hasMany(LiveCoding::class)`.

### `EtatRealisationTache` (Table : `etat_realisation_taches`)
- Statuts de progression configurables par formateur et par défaut institutionnel.

---

## 3. Services & Logique Métier

### `RealisationTacheService` (`Modules\PkgRealisationTache\Services\RealisationTacheService`)
- **Traits spécialisés :** Utilise `RealisationTacheGetterTrait` pour le filtrage avancé multi-critères, le calcul des situations pédagogiques et la détection d'inactivité.
- **Gestion des Transitions :** Validation des prérequis avant passage à l'état suivant (ex: vérification des livrables déposés).
- **Audit Automatique :** Enregistrement systématique dans `HistoriqueRealisationTache` à chaque transition d'état.
- **Règles d'Intégrité BLL :** Déclenchement d'exceptions `BlException` en cas de violation de flux ou de règles d'accès.

### `SeanceService` (`Modules\PkgRealisationTache\Services\SeanceService`)
- **Rôle :** Fournit le découpage temporel des séances de l'établissement (par défaut Solicode : S1 `09:00 - 11:30`, S2 `11:30 - 14:00`, S3 `14:30 - 17:00`).
- **Configurable :** Surchargeable via `config('etablissement.seances')` ou initialisable par injection de dépendances.
- **Méthodes clés :**
  - `getCurrentSeance()` : Retourne la séance en cours ou `null` en dehors des créneaux.
  - `getLastOrCurrentSeance()` : Retourne la séance actuelle ou la dernière séance écoulée de la journée.
  - `getSeanceTimeRange($seance)` : Fournit l'intervalle `[Carbon $start, Carbon $end]` d'une séance.
  - `getTodayTimeRange()` : Fournit l'intervalle de début de la première séance jusqu'à l'heure actuelle.
  - `formatDuree($minutes)` : Formate une durée d'inactivité en texte lisible (ex: `1h 45min` ou `35min`).

### `RealisationTacheGetterTrait` (Macro-Situations & Inactivité)
- **Constantes de situations pédagogiques :**
  - `SITUATION_TRAVAIL` : Tâches en cours d'exécution active.
  - `SITUATION_BLOCAGE` : Tâches bloquées ou en difficulté nécessitant une intervention.
  - `SITUATION_ATTENTE` : Tâches soumises en attente de validation.
  - `SITUATION_TERMINEE` : Tâches validées et terminées.
  - `SITUATION_INACTIF_SEANCE` : Apprenants sans aucun événement pendant la séance en cours.
  - `SITUATION_INACTIF_JOUR` : Apprenants sans aucun événement pendant toute la journée.
  - `SITUATION_CUSTOM` : Filtrage libre multi-états personnalisés.
- **Matrice `SITUATION_WORKFLOW_MAP` :** Mappe chaque macro-situation aux codes `WorkflowTache` correspondants.
- **Méthode `getInactiviteData(string $mode, array $activeFilters)` :**
  - Analyse les transitions dans `HistoriqueRealisationTache` sur la plage horaire définie par `SeanceService`.
  - Applique rigoureusement les filtres de scoping parents (`Affectation_projet_id`, `Groupe_id`, `Filiere_id`, `EquipeProjet_id`).
  - Isole les apprenants supervisés sans activité et sélectionne 1 tâche représentative par apprenant (priorité : En cours > 1ère TODO > Dernière modifiée).
  - Met en cache les calculs dans la propriété `$cachedInactiviteData` pour éviter toute redondance SQL entre le filtrage de la pagination et la vue Blade (`prepareDataForIndexView`).

---

## 4. Particularités UI & Filtres

### Barre de Macro-Situations (`custom/_index.blade.php`)
- **Pills de Situation :** Boutons d'accès rapide pour basculer en un clic entre Travail en cours, Blocages, En attente, Terminées, Inactifs (Séance / Jour) et Personnalisé.
- **Panneau Déroulant Personnalisé (`#custom-etats-panel`) :** Affiché uniquement lorsque la macro-situation sélectionnée est `SITUATION_CUSTOM` pour permettre la multi-sélection précise d'états.
- **Compteur Contextuel :** Affiche le nombre d'apprenants inactifs détectés sans redondance avec le titre.

### Cartes de Tâches (`custom/fields/tache.blade.php`)
- **Badge d'Inactivité :** Lorsqu'un filtre d'inactivité est actif, affiche un badge stylisé indiquant la durée d'inactivité (`X h Y min`), la séance concernée et l'heure du dernier événement enregistré.

---

## 5. Pièges & Recommandations d'Architecture

1. ⚠️ **Scoping Parent Obligatoire (Règle d'or anti-fuite de données) :**
   - Tout calcul d'audit, de détection d'inactivité ou d'agrégation d'apprenants **DOIT** impérativement s'intersecter avec les filtres parents actifs (`Affectation_projet_id`, `Groupe_id`, `Filiere_id`, etc.) extraits du ViewState.
   - Ne jamais exécuter de requête globale sur `Apprenant` ou `HistoriqueRealisationTache` sans borner le périmètre aux apprenants assignés au projet ou au groupe filtré.
2. ⚠️ **Nomenclature ViewState & TableUI :**
   - `FilterUI.js` transmet les paramètres sous la forme `filter.realisationTache.[relation].[champ]`.
   - Les services et contrôleurs doivent inspecter de façon tolérante `$filterVariables`, `$this->viewState->get(...)` et `request(...)`.
3. ⚠️ **Purge des Paramètres URL :**
   - Lors de la commutation interactive d'une situation en JavaScript, purger les paramètres URL résiduels non-préfixés (`situation_pedagogique`, `Affectation_projet_id`) via `window.history.replaceState` pour prévenir tout conflit lors des rechargements ou paginations TableUI.
4. ⚠️ **Intégrité Gapp & Dossiers `custom/` :**
   - Ne jamais modifier les templates Blade générés par Gapp à la racine du module. Toute extension d'affichage ou logique d'injection JS/CSS doit résider dans `resources/views/realisationTache/custom/`.
