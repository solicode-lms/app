# Spécification Indicateur 04 : Charge Logistique Hebdomadaire du Formateur ($C_{\text{logistique}}$)

## 📌 1. Fiche d'Identité de l'Indicateur

- **Code / Symbole** : $C_{\text{logistique}}$
- **Nom complet** : Volume horaire hebdomadaire de charge logistique et administrative
- **Axe de Recherche** : Axe 2 — Formateur (Hypothèse H2 : Allègement des micro-tâches répétitives et libération de temps pour le tutorat direct et la médiation technique)
- **Acteurs Cibles** :
  - **Formateur** (prise de conscience de sa répartition temporelle administrative vs pédagogique)
  - **Responsable de formation** (monitoring de la charge enseignante et optimisation des processus organisationnels)
- **Unité de mesure** : Heures par semaine ($h/\text{semaine}$)
- **Périmètre des Activités Comptabilisées** :
  - $T_{\text{pilotage}}$ : Consultation et manipulation du tableau de bord de classe / Kanban.
  - $T_{\text{file}}$ : Ordonnancement et régulation de la file d'attente Live Coding.
  - $T_{\text{bordereaux}}$ : Vérification, ajustement et export des bordereaux officiels de notes.
  - *Exclusion expresse* : Les sessions de Live Coding de 40 minutes sont automatiquement exclues (elles relèvent de l'évaluation formative directe).
  - *Verrou d'inactivité* : Suspension automatique du décompte après 5 minutes consécutives sans interaction.

---

## 🧮 2. Modélisation Mathématique & Formules d'Agrégation

### 2.1. Formule Individuelle (Niveau Formateur $f$ pour la semaine $w$)
Volume horaire cumulé sur les trois activités administratives cibles :

$$C_{\text{logistique}}(f, w) = T_{\text{pilotage}}(f, w) + T_{\text{file}}(f, w) + T_{\text{bordereaux}}(f, w)$$

Où chaque composante représente le temps effectif passé en heures décimales :

$$T_{\text{activite}}(f, w) = \frac{\sum \text{durée\_interactions\_actives}(f, w)}{3600}$$

### 2.2. Formule Collective (Niveau Méso / Macro — Moyenne de l'Équipe Pédagogique)
Moyenne arithmétique pour $F$ formateurs suivis sur un horizon de $W$ semaines :

$$C_{\text{logistique}} = \frac{1}{F \times W} \sum_{f=1}^{F} \sum_{w=1}^{W} \left( T_{\text{pilotage}}(f, w) + T_{\text{file}}(f, w) + T_{\text{bordereaux}}(f, w) \right)$$

- Taux de libération temporelle didactique : $\tau_{\text{disponibilité}} = \frac{T_{\text{temps\_plein}} - C_{\text{logistique}}}{T_{\text{temps\_plein}}} \times 100$.

---

## 🔍 3. Audit de l'Existant dans Solicode LMS (Skills Métier)

L'audit croisé mené via `app-db-savoir` et les modules existants établit les points suivants :

### 3.1. Ce qui existe déjà dans SoliLMS :
- Authentification et profils utilisateurs (`users`, `formateurs`).
- Gestion des permissions via Spatie (`role_has_permissions`).
- Sessions Laravel standard (durée globale de connexion au serveur).

### 3.2. Écarts constatés (Ce qui manque pour calculer l'indicateur) :
1. **Absence de granularité par écran/action** :
   - Les sessions HTTP standard ne permettent pas de distinguer le temps passé sur la console Kanban, la file d'attente ou les bordereaux du reste de la navigation.
2. **Absence de verrou d'inactivité client** :
   - Si un formateur laisse son onglet de tableau de bord ouvert pendant sa pause déjeuner, le temps calculé serait artificiellement gonflé sans détection d'inactivité à 5 minutes.
3. **Absence de table de stockage des agrégats temporels** :
   - Il n'existe pas de table dédiée pour historiser la consommation horaire hebdomadaire par formateur.

---

## 🛠️ 4. Modifications à Apporter à Solicode LMS

### 4.1. Modifications Base de Données (Incluses dans le Sprint 1)

1. **Création de la table `log_activite_formateurs`** :
   - `id` : bigint unsigned auto-increment (PK).
   - `formateur_id` : bigint unsigned (FK $\rightarrow$ `formateurs.id`).
   - `type_activite` : varchar (50) (`PILOTAGE_KANBAN`, `GESTION_FILE_FIFO`, `EXPORT_BORDEREAU`).
   - `duree_secondes` : int unsigned (durée accumulée).
   - `date_activite` : date.
   - `semaine_annee` : varchar (10) (ex: `2026-W42`, indexé pour requêtes rapides).
   - `reference` : varchar unique conventionnelle Gapp.
   - `created_at`, `updated_at`.

### 4.2. Modifications Applicatives & Frontend

1. **Mécanisme de Heartbeat Frontend (Alpine.js / JS)** :
   - Un script léger sur les pages de pilotage (Tableau de bord, File Live Coding, Bordereaux) envoie un ping toutes les 60 secondes :
     - Écouteurs d'événements utilisateur (`mousemove`, `keydown`, `click`).
     - Après 5 minutes sans événement, le ping est suspendu automatiquement (verrou d'inactivité).
     - Exclusion des pages de Live Coding de 40 minutes.
2. **Endpoint API de Télémétrie (`ActiviteFormateurController`)** :
   - Reçoit le ping et incrémente `duree_secondes` dans `log_activite_formateurs` pour la journée et la semaine en cours.
3. **Service de Calcul dans `PkgTableauBord` (`DashboardCalculTrait`)** :
   - Méthode `calculerChargeLogistiqueHebdomadaire(int $formateurId, ?string $semaine = null): array` :
     ```php
     $semaine = $semaine ?? now()->format('Y-\WW');
     
     $activites = LogActiviteFormateur::where('formateur_id', $formateurId)
         ->where('semaine_annee', $semaine)
         ->get();

     $secondesPilotage = $activites->where('type_activite', 'PILOTAGE_KANBAN')->sum('duree_secondes');
     $secondesFile = $activites->where('type_activite', 'GESTION_FILE_FIFO')->sum('duree_secondes');
     $secondesBordereaux = $activites->where('type_activite', 'EXPORT_BORDEREAU')->sum('duree_secondes');

     $totalSecondes = $secondesPilotage + $secondesFile + $secondesBordereaux;
     $totalHeures = round($totalSecondes / 3600.0, 1);

     return [
         'total_heures' => $totalHeures,
         'pilotage_heures' => round($secondesPilotage / 3600.0, 1),
         'file_heures' => round($secondesFile / 3600.0, 1),
         'bordereaux_heures' => round($secondesBordereaux / 3600.0, 1),
     ];
     ```

---

## 🚦 5. Seuils d'Alerte & Actionnabilité Didactique

| Zone | Seuil $C_{\text{logistique}}$ | Diagnostic Didactique | Action Dashboard Immédiate |
| :--- | :---: | :--- | :--- |
| 🟢 **Optimal (Allégé)** | $\le 2{,}5\,h/\text{semaine}$ | L'automatisation logicielle prend efficacement en charge le micro-suivi. Temps maximal libéré pour le tutorat direct. | Badge vert *"Logistique optimisée"*. |
| 🟡 **Vigilance** | $2{,}5\,h < C \le 5{,}0\,h/\text{semaine}$ | Charge administrative notable mais gérable. | Badge bleu/neutre. |
| 🔴 **Saturation Administrative** | $> 5{,}0\,h/\text{semaine}$ | **Surcharge bureaucratique** : le formateur passe trop de temps sur le pointage au détriment de la remédiation active. | **Action Responsable** : Revoir la configuration des alertes automatiques et simplifier les flux d'export. |

---

## 📊 6. Rendu UI Prévu dans les Maquettes

- **Vue Formateur (`dashboard-formateur.html`)** :
  - Widget discret dans le header : *"Temps administratif cette semaine : 1h45 / cible max 3h"* avec ventilation infobulle (Kanban : 50 min, File : 40 min, Notes : 15 min).
- **Vue Responsable (`dashboard-responsable.html`)** :
  - KPI de Gouvernance : *"Charge Logistique Moyenne : 2.1 h/semaine"* (en baisse de 45% grâce au LMS).
  - Graphique en barres empilées montrant la charge comparée par centre et par filière.
