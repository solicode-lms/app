# Plan d'Action — Phase 4 : Conception des Maquettes HTML & CSS

## 🎯 Objectif de la Phase
Concevoir trois maquettes statiques et interactives complètes en **HTML5, Tailwind CSS (CDN) et Alpine.js**, matérialisant les tableaux de bord pour chaque profil d'acteur identifié dans le [cahier des charges](file:///c:/AppServer/solicode-lms/cahiers-charges/PkgTableauBord/0.cahier_charge.md) :
1. **L'Apprenant** (`maquettes/dashboard-apprenant.html`)
2. **Le Formateur** (`maquettes/dashboard-formateur.html`)
3. **Le Responsable de formation** (`maquettes/dashboard-responsable.html`)

---

## 📂 1. Arborescence Cible des Maquettes

```
cahiers-charges/PkgTableauBord/maquettes/
├── dashboard-apprenant.html    # Cockpit de progression & auto-régulation
├── dashboard-formateur.html    # Console de pilotage temps réel (File FIFO, Remédiation)
└── dashboard-responsable.html  # Gouvernance, intégrité IA & conformité 65/35
```

---

## 🖥️ 2. Spécification Détaillée des 3 Maquettes

### 2.1. Maquette 1 : Tableau de Bord Apprenant (`dashboard-apprenant.html`)
- **En-tête & Statut** :
  - Profil apprenant, Unité d'Apprentissage (UA) et sprint en cours.
  - Statut actuel proéminent (ex: *"En attente de Live Coding Prototype — Rang 2"*).
- **Cartes KPI Individuels** :
  - **Temps d'amorçage ($T_{\text{amorçage}}$)** : Son temps personnel comparé au temps moyen de la promotion.
  - **Avancement du Sprint** : Pourcentage des tutoriels Niveau 1 et prototype Niveau 2 validés.
  - **Statut Gating Prototype** : Badge visuel (*Non présenté*, *Validé au 1er essai*, ou *En remédiation*).
  - **Estimation Note Continue (CC)** : Calcul indicatif basé sur les prototypes validés.
- **Section Opérationnelle** :
  - Kanban personnel réduit ou liste ordonnée des tâches.
  - Boîte d'alerte contextuelle si une tâche est renvoyée en *Révision nécessaire* avec bouton d'accès direct.

---

### 2.2. Maquette 2 : Tableau de Bord Formateur (`dashboard-formateur.html`)
- **En-tête de Pilotage** :
  - Sélecteur de classe / groupe, sélecteur d'UA active.
  - Indicateur de présence (effectif présent / total).
- **Cartes KPI de Cohorte** :
  - **File d'Attente ($T_{\text{file}}$)** : Longueur actuelle et temps d'attente moyen estimé.
  - **Taux de Réussite Gating ($R_{\text{gating}}$)** : Pourcentage validé au 1er essai sur le prototype.
  - **Taux de Remédiation ($T_{\text{intervention}}$)** : Pourcentage d'apprenants fragiles pris en charge.
  - **Charge Logistique ($C_{\text{logistique}}$)** : Volume horaire hebdomadaire estimé.
- **Console Opérationnelle Double Panneau** :
  - **Panneau A — File d'Attente FIFO Live Coding** :
    - Liste ordonnée des apprenants ayant soumis leur prototype.
    - Timers d'attente individuels.
    - Actions en un clic : *Valider*, *Ajourner / Séance remédiation*, *Lancer session collective 4x4*.
  - **Panneau B — Zone d'Alerte "Seuil 10 min Post-Live Coding"** :
    - Détection automatique des apprenants présents n'ayant pas soumis leur tutoriel 10 min après la session plénière.
    - Bouton pour affecter un trinôme d'entraide ou déclencher un étayage formateur.

---

### 2.3. Maquette 3 : Tableau de Bord Responsable de Formation (`dashboard-responsable.html`)
- **En-tête Stratégique** :
  - Sélecteur d'établissement / centre, filtre multi-filières, filtre par promotion.
  - Période académique (Semestre / Année).
- **Cartes KPI Institutionnels** :
  - **Couverture Pédagogique ($\text{ICP}$)** : Part des compétences du référentiel national couvertes.
  - **Conformité Docimologique 65/35 ($\tau_{\text{conformité}}$)** : Pourcentage de bordereaux validés sans divergence arithmétique.
  - **Intégrité face à l'IA ($r$ de Pearson)** : Corrélation entre notes Live Coding ($N2$) et projets finaux ($N3$).
  - **Taux de Réussite Global** : Moyenne générale et taux d'admission certifié.
- **Visualisations & Tableaux Analytiques** :
  - **Scatter Plot d'Intégrité IA** : Représentation graphique visualisant les cas suspects ($N3 \gg N2$).
  - **Matrice Comparative Inter-Groupes** : Comparatif des promotions sur les délais, le taux de révision et les scores.
  - **Module d'Export Docimologique** : Boutons d'export officiel des bordereaux CC et EFM prêts pour l'administration.

---

## 🛠️ 3. Technologies & Dépendances des Maquettes
- Framework CSS : **Tailwind CSS via CDN** (`<script src="https://cdn.tailwindcss.com"></script>`).
- Moteur Réactif : **Alpine.js via CDN** (`<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>`).
- Icônes : SVG inline (Heroicons).
- Polices : Google Fonts `Inter`.

---

## ✅ 4. Livrables & Validation de la Phase 4
- [ ] Création du dossier `maquettes/`.
- [ ] Réalisation et validation de `dashboard-apprenant.html`.
- [ ] Réalisation et validation de `dashboard-formateur.html`.
- [ ] Réalisation et validation de `dashboard-responsable.html`.
- [ ] Revue d'utilisabilité dans le navigateur.
