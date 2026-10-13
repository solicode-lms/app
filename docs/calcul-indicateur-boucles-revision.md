# Calcul & Fonctionnement : Indicateur des Boucles de Révision ($F_{\text{révision}}$)

> **Module principal** : `PkgTableauBord`  
> **Modules contributeurs** : `PkgRealisationTache`, `PkgRealisationProjets`, `PkgCreationProjet`  
> **Traits backend** : `DashboardIndicateur03Trait`, `RealisationTacheJobTrait`  
> **Composant UI V2** : `kpi-revision.blade.php`

---

## 1. Définition Métier & Rôle Didactique

L'indicateur **$F_{\text{révision}}$** (Fréquence Moyenne des Boucles de Révision) mesure la stabilité et l'autonomie de l'apprenant dès sa première soumission, ainsi que le nombre moyen d'itérations correctives exigées par le formateur avant validation finale :

$$F_{\text{révision}} = \frac{1}{K} \sum_{k=1}^{K} C_{\text{rejets}}(k)$$
$$\tau_{\text{conforme\_1er\_jet}} = \frac{K_{(C_{\text{rejets}}=0)}}{K} \times 100$$

- **Rôle pédagogique** :
  - **Apprenant** : Réduire la démarche de tâtonnement par essais-erreurs, accroître la rigueur avant de soumettre.
  - **Formateur** : Identifier immédiatement les **tâches piégeuses** ou aux consignes floues (Top 3 des tâches à forte itération) pour organiser un point de clarification collectif.
  - **Responsable** : Cartographier les modules générant une forte friction cognitive et harmoniser l'exigence docimologique.

---

## 2. Détection des Révisions & Cycle de Vie Événementiel

### A. Colonne Dénormalisée de Cache (`realisation_taches`)

| Colonne | Type | Description |
| :--- | :---: | :--- |
| `cache_nombre_revisions` | `unsigned int` | Nombre cumulé de renvois vers le statut `REVISION_NECESSAIRE` prononcés par le formateur. |

### B. Cycle de Vie lors du Rejet (`RealisationTacheJobTrait`)

Lorsqu'un formateur évalue un livrable et le déclare incomplet ou erroné :
1. L'état passe à `REVISION_NECESSAIRE` (`workflow_taches.code = 'REVISION_NECESSAIRE'`).
2. L'Observer / JobManager intercepte l'événement :
   ```php
   if ($etatWorkflowCode === 'REVISION_NECESSAIRE') {
       $this->incrementerNombreRevisions($realisationTache);
   }
   ```
3. La méthode `incrementerNombreRevisions()` incrémente `cache_nombre_revisions` de 1 de manière atomique.
4. Si l'apprenant modifie son code sans que le formateur ne change à nouveau l'état, le compteur ne bouge pas.
5. Si l'apprenant resoumet et que le formateur rejette une seconde fois, le compteur passe à 2.

---

## 3. Seuils Didactiques & Statuts Visuels

| Zone | Seuil $F_{\text{révision}}$ | Diagnostic Didactique | Statut & Badge UI |
| :--- | :---: | :--- | :--- |
| 🟢 **Optimal** | $< 0{,}5$ itération | Consignes limpides, critères d'acceptation maîtrisés dès la 1ère soumission. | `bg-emerald-50 text-emerald-700` *"Qualité initiale élevée"* |
| 🟡 **Vigilance** | $0{,}5 \le F \le 1{,}2$ itérations | Ajustements ordinaires du code, démarche d'apprentissage saine. | `bg-amber-50 text-amber-700` *"Ajustements en cours"* |
| 🔴 **Alerte Tâtonnement** | $> 1{,}2$ itérations | **Surcharge extrinsèque ou flou des critères** : démarche par essais-erreurs. | `bg-rose-50 text-rose-700` *"Réalignement requis"* |

---

## 4. Déclinaison par Acteur (Tableaux de Bord V2)

### 1. Vue Apprenant (`tableau-bord.apprenant`)
- **Carte *"Qualité du premier rendu"*** :
  - Taux de conformité au 1er jet (ex: `85% de réussite directe`).
  - Nombre moyen de reprises par tâche (ex: `0.2 reprise / tâche`).
  - Décompte précis : ex: `3 / 4 validées sans retouche`.

### 2. Vue Formateur (`tableau-bord.formateur`)
- **Carte KPI *"Fréquence des Révisions"*** :
  - Moyenne cohorte (ex: `0.8 reprise / tâche`).
  - Total des rejets cumulés de la promotion.
- **Panel *"Tâches Nécessitant un Réalignement Didactique"*** :
  - Classement du **Top 3 des tâches** concentrant le plus grand nombre de rejets formateurs.
  - Fréquence moyenne de rejet pour chaque tâche et nombre d'apprenants impactés.

### 3. Vue Responsable (`tableau-bord.responsable`)
- **Carte Macroscopique Globale** : Fréquence moyenne de révision sur toutes les promotions actives récentes.
- **Matrice Comparative Inter-Promotions** :
  - Colonne ajoutée : **Stabilité & Reprises ($F_{\text{révision}}$)** affichant le ratio moyen et le taux de 1er jet.

---

## 5. Commande Artisan de Rattrapage

```bash
# Rattrapage standard depuis l'historique existant
php artisan dashboard:recalculer-revisions

# Forcer le recalcul
php artisan dashboard:recalculer-revisions --force
```
