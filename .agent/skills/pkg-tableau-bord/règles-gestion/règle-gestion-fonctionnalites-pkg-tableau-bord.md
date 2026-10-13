# Règle de Gestion : Fonctionnalités PkgTableauBord

Ce document décrit les fonctionnalités et cas d'utilisation opérationnels du module `PkgTableauBord` déclinés par profil d'acteur.

---

## 1. Tableau de Bord Apprenant (Auto-Régulation & Cap Pédagogique)

- **Acteur** : Apprenant.
- **Objectif** : Fournir une visibilité complète sur son rythme, réduire l'anxiété de démarrage et expliciter les conditions de passage d'étape.
- **Fonctionnalités Clés** :
  1. **Suivi d'Amorçage Individuel** : Visualisation de son temps d'amorçage ($T_{\text{amorçage}}$) comparé au rythme de la classe, avec lien direct vers le tutoriel d'amorce.
  2. **Statut Gating Prototype** : Visualisation claire de la condition d'accès au projet fil rouge (*En préparation*, *Validé au 1er essai*, ou *En remédiation*).
  3. **File d'Attente Live Coding** : Affichage en direct de son rang dans la file FIFO et du temps d'attente estimé.
  4. **Jauge de Progression & Note Estimée** : Calcul indicatif continu de la note de contrôle continu (CC sur 20) selon les prototypes validés.
  5. **Zone d'Assistance Post-Live Coding** : Notification bienveillante d'aide si la tâche bascule à l'état `BESOIN_REVISION`.

---

## 2. Tableau de Bord Formateur (Console de Pilotage & Régulation Temps Réel)

- **Acteur** : Formateur.
- **Objectif** : Disposer d'un cockpit unifié pour piloter la classe en direct, fluidifier les évaluations et intervenir immédiatement sur les blocages.
- **Fonctionnalités Clés** :
  1. **Console Interactive de File FIFO** :
     - Liste ordonnée en temps réel des apprenants prêts pour le Live Coding Prototype (`READY_FOR_LIVE_CODING`).
     - Prise en charge 1-à-1 (*"Appeler pour Live Coding"* $\rightarrow$ bascule à `IN_LIVE_CODING`).
     - Prise en charge groupée (*"Lancer session mutualisée jusqu'à 4 apprenants"* pour désengorger la file).
     - Clôture d'épreuve : attribution de la note et décision binaire (`APPROVED` ou `NOT_VALIDATED`).
  2. **Surveillance Didactique Seuil $+10$ Minutes** :
     - Déclenchement automatique 10 min après le Live Coding des pairs.
     - Liste rouge des apprenants présents n'ayant pas soumis leur livrable.
     - Bouton d'action directe : *"Créer atelier de soutien / Trinôme d'entraide"*.
  3. **Monitoring de la Charge Logistique** :
     - Suivi du volume horaire hebdomadaire ($C_{\text{logistique}}$) avec ventilation (Kanban, File FIFO, Bordereaux).
  4. **Génération & Audit 65/35** :
     - Prévisualisation du tableau de conversion officielle (CC sur 20 / EFM sur 40).
     - Alerte sur les évaluations incomplètes avant soumission au secrétariat.

---

## 3. Tableau de Bord Responsable de Formation (Gouvernance & Intégrité Docimologique)

- **Acteur** : Responsable de formation / Directeur pédagogique.
- **Objectif** : Assurer la gouvernance académique, la conformité réglementaire au référentiel et l'intégrité face à l'usage de l'intelligence artificielle.
- **Fonctionnalités Clés** :
  1. **Indicateur de Couverture Pédagogique ($\text{ICP}$)** :
     - Suivi du taux d'acquisition des micro-compétences nationales sur chaque promotion.
     - Détection matricielle des impasses pédagogiques (micro-compétences avec taux de validation $< 50\%$).
  2. **Audit d'Intégrité face à l'IA ($r$ de Pearson)** :
     - Mesure statistique de la corrélation linéaire entre la maîtrise en direct ($N2$) et le projet final ($N3$).
     - Détection automatique et liste nominative des apprenants en surperformance suspecte ($\Delta_{\text{IA}} > +5$ pts).
  3. **Certification des Bordereaux 65/35 ($\tau_{\text{conformité}}$)** :
     - Contrôle de la complétude docimologique ($100\%$ sans omission ni divergence).
     - Exportation officielle en 1 clic des bordereaux CC et EFM scellés et prêts pour transmission administrative.
  4. **Benchmark Inter-Groupes** :
     - Comparaison des rythmes d'amorçage, délais d'attente et taux de révision entre filières et groupes.
