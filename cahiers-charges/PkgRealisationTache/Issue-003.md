# Issue : Filtrage des réalisations de tâches par équipe de projet

**Titre** : Afficher l'ensemble des tâches concernant les membres de l'équipe sélectionnée (tâches d'équipe et tâches communes)

---

## 📝 Contexte & Description du Problème

Dans le module **PkgRealisationTache** (écran d'index et de suivi des réalisations de tâches), un filtre par `EquipeProjet` est proposé dynamiquement lorsque l'affectation ou le projet sélectionné comporte des équipes.

Actuellement, ce filtre applique une restriction stricte sur la relation directe de la tâche : `tache.equipe_projet_id = :equipe_projet_id`.

Ce comportement entraîne deux limitations majeures :
1. **Exclusion des tâches communes / transversales** : Les tâches du projet qui n'ont pas d'équipe explicitement assignée (`tache.equipe_projet_id IS NULL`) ou les tâches générales à réaliser par les membres de l'équipe sont masquées. Le formateur ou l'apprenant ne peut donc pas visualiser l'intégralité de l'activité des membres de l'équipe.
2. **Filtrage trop restrictif du sélecteur de tâches (`tache_id`)** : Dans `RealisationTacheGetterTrait`, la liste déroulante des tâches est filtrée uniquement sur `equipe_projet_id == $equipeProjetId`, rendant impossible la sélection des tâches communes du projet lorsque l'équipe est filtrée.

En synthèse : **le filtrage par équipe doit restituer toutes les tâches qui concernent les membres de l'équipe sélectionnée**.

---

## 💡 Proposition de Solution & Règles Métier

### 1. Périmètre fonctionnel lors de la sélection d'une équipe

Lorsqu'une équipe projet $E$ est sélectionnée dans le filtre :
- **Identification des membres** : Identifier les apprenants rattachés à cette équipe (`apprenant_equipe_projet`).
- **Tâches à inclure dans les résultats** :
  - Les réalisations des tâches assignées directement à l'équipe sélectionnée (`tache.equipe_projet_id = $equipeId`).
  - **ET** les réalisations effectuées par les apprenants membres de cette équipe pour les tâches communes du projet (`tache.equipe_projet_id IS NULL`).
  - *(Exclure les tâches assignées à d'autres équipes spécifiques $E' \neq E$)*.

### 2. Logique de Requête Eloquent (`RealisationTacheGetterTrait`)

Adapter la condition appliquée sur `RealisationTache` lorsque le filtre équipe est actif :

```php
$builder->where(function (Builder $query) use ($equipeProjetId, $apprenantIds) {
    // 1. Tâches spécifiquement assignées à l'équipe sélectionnée
    $query->whereHas('tache', function ($q) use ($equipeProjetId) {
        $q->where('equipe_projet_id', $equipeProjetId);
    })
    // 2. Tâches communes (sans équipe assignée) réalisées par les membres de l'équipe
    ->orWhere(function ($q) use ($apprenantIds) {
        $q->whereHas('realisationProjet', function ($rp) use ($apprenantIds) {
            $rp->whereIn('apprenant_id', $apprenantIds);
        })->whereHas('tache', function ($t) {
            $t->whereNull('equipe_projet_id');
        });
    });
});
```

### 3. Ajustement de la liste déroulante des tâches (`tache_id`)

Dans `RealisationTacheGetterTrait::initFieldsFilterable()`, conserver les tâches communes lors du filtrage en cascade :

```php
$equipeProjetId = $this->viewState->get('filter.realisationTache.tache.equipe_projet_id');
if (!empty($equipeProjetId)) {
    $taches = collect($taches)->filter(function($tache) use ($equipeProjetId) {
        return (string) $tache->equipe_projet_id === (string) $equipeProjetId 
            || is_null($tache->equipe_projet_id);
    });
}
```

---

## 🔍 Analyse d'Impact par Composant

| Composant | Impact | Détails |
| :--- | :--- | :--- |
| **Base de Données** | Aucun impact | Aucune migration requise. Les relations BDD et tables pivots (`apprenant_equipe_projet`) existent déjà. |
| **Modèles Eloquent** | Aucun impact | Exploitation des relations existantes (`EquipeProjet::apprenants()`, `RealisationTache::tache()`, `RealisationTache::realisationProjet()`). |
| **Couche Service** | **Impact Principal** | Mise à jour de `RealisationTacheGetterTrait` (gestion de la requête de filtrage et du dropdown des tâches). |
| **Contrôleur** | Aucun impact | Respect du principe "Skinny Controller", la logique reste encapsulée dans le Service. |
| **Vues Blade / UI** | Aucun impact | Les cartes de tâches gèrent déjà l'affichage de l'équipe et de sa couleur associée. |
| **Générateur (Gapp)** | Aucun impact | Traitement localisé dans les traits personnalisés sans altérer les stubs du générateur. |

---

## 🛠️ Skills Requis pour Implémentation

- `pkg-realisation-tache` : Gestion des réalisations de tâches et de leur cycle de vie.
- `pkg-creation-projet` : Gestion des équipes de projet et de leurs membres.
- `app-service` : Implémentation des filtres et scopes dans `RealisationTacheGetterTrait`.