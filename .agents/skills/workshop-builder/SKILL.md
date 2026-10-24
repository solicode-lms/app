---
name: workshop-builder
description: Conçoit des ateliers pratiques guidés (TP), briefs de projet et grilles d'évaluation critériées selon l'Approche Par Compétences (APC) de Solicode.
version: 1.0.0
inspiration:
  source: "solicode-pedagogy"
  plugin: "pedagogy-referential"
  pattern: "competency-based practical workshop & assessment rubric"
---

# 1. Mission
Ce skill pilote la conception didactique d'ateliers pratiques (TP), de Briefs de projets guidés et de dispositifs d'évaluation dans Solicode LMS. S'appuyant rigoureusement sur le référentiel d'Approche Par Compétences (APC) de Solicode, il transforme un besoin d'apprentissage ou une fonctionnalité technique en mise en situation professionnelle réaliste, séquencée en jalons vérifiables et assortie d'une grille d'évaluation critériée observable.

# 2. Déclenchement & Limites
- **Quand l'activer :**
  - Demande de création d'un atelier pratique ou TP ("Crée un TP sur l'implémentation d'un filtre TableUI", "Rédige un brief projet pour la gestion des QCM").
  - Conception d'une grille d'évaluation par compétences avec critères d'acceptation observables.
  - Scénarisation pédagogique d'un livrable pour les apprenants.
- **Quand NE PAS l'activer :**
  - Rédaction d'un tutoriel pas-à-pas linéaire ➔ Utiliser `tutorial-writer`.
  - Développement direct de code applicatif ➔ Utiliser `feature-dev`.
  - Analyse d'architecture logicielle ➔ Utiliser `architecture-planner`.

# 3. Entrées & Ressources à Charger
- **Priorité 1 :** `.agents/standards/pedagogy-referential.md` (Principes de l'APC Solicode, niveaux d'autonomie).
- **Priorité 2 :** Code source réel et fonctionnel de la fonctionnalité cible dans `modules/Pkg[Nom]/`.
- **Priorité 3 :** `modules/PkgCompetences/` pour aligner les critères sur le référentiel officiel des compétences de l'OFPPT/Solicode.

# 4. Contraintes & Règles Inviolables
- **Ancrage Professionnel Réel :** Chaque atelier doit démarrer par une mise en situation d'entreprise réaliste (contexte client, persona, enjeu opérationnel).
- **Objectifs Opérables & Observables :** Formuler les compétences cibles avec des verbes d'action mesurables (ex: "Configurer", "Implémenter", "Valider") et non des intentions passives ("Comprendre", "Savoir").
- **Grille Critériée Obligatoire :** Tout atelier pratique DOIT comporter une grille d'évaluation explicite découpée en 4 niveaux d'acquisition : Non Acquis (NA), En Cours d'Acquisition (ECA), Acquis (A), et Maîtrisé (M).
- **Autonomie Évolutive :** Fournir le cadre et les contraintes sans donner le code complet de la solution finale (distinction clé avec un tutoriel pas-à-pas).

# 5. Procédure Pas-à-Pas (Workflow de Conception d'Atelier)
1. **Étape 1 : Cadrage de la Compétence & Contexte :**
   - Identifier la compétence cible du référentiel (ex: "Développer des composants frontaux dynamiques").
   - Définir le scénario métier (ex: "Moderniser le tableau de bord des formateurs").
2. **Étape 2 : Rédaction de la Mise en Situation :**
   - Décrire le contexte, le rôle de l'apprenant, le livrable attendu et la date/durée indicative.
3. **Étape 3 : Découpage en Jalons de Réalisation :**
   - Structurer le travail en 3 à 5 étapes progressives (ex: Étape 1 : Analyse du modèle, Étape 2 : Configuration du filtre, Étape 3 : Liaison TableUI).
   - Pour chaque étape, formuler des consignes précises et les conditions d'acceptation.
4. **Étape 4 : Définition des Ressources & Contraintes :**
   - Citer les fichiers du projet à consulter, les classes `Base/` à ne pas modifier, et la documentation locale (`.agents/local/`).
5. **Étape 5 : Construction de la Grille d'Évaluation :**
   - Établir le tableau critérié liant chaque critère observable à son niveau de conformité.

# 6. Gestion des Ambiguïtés & Décisions
- Si le niveau des apprenants n'est pas spécifié : calibrer par défaut pour des développeurs de 2ème année (autonomie intermédiaire, indications méthodologiques fournies sans code prêt à copier-coller).
- Si la durée n'est pas fournie : proposer un format standard d'atelier de 3h à 4h.

# 7. Format de Sortie & Livrables
Un document Markdown structuré comprenant :
- **En-tête :** Titre de l'atelier, durée estimée, prérequis, compétences ciblées.
- **Mise en situation :** Contexte opérationnel professionnel.
- **Consignes par jalon :** Séquence de réalisation avec critères de succès.
- **Grille critériée d'évaluation :** Tableau des compétences et niveaux d'acquisition.

# 8. Contrôle Qualité (Checklist Binaire)
- [ ] L'atelier est-il introduit par une mise en situation professionnelle contextualisée ?
- [ ] Les compétences sont-elles formulées avec des verbes d'action mesurables ?
- [ ] Le document préserve-t-il la posture de recherche en évitant le code "solution" prêt à coller ?
- [ ] La grille d'évaluation comporte-t-elle les 4 paliers d'évaluation critériée ?

# 9. Gestion des Erreurs Fréquentes
| Erreur Typique | Cause | Solution Immédiate |
| :--- | :--- | :--- |
| Donner la solution complète | Confusion avec un tutoriel | Ne fournir que les spécifications, contrats et indices |
| Critères vagues ("Le code est propre") | Manque d'objectivité | Remplacer par des indicateurs binaires mesurables |
| Consignes théoriques sans livrable | Déconnexion pratique | Exiger un livrable précis (vue personnalisée, méthode testée) |

# 10. Exemples Concrets (Few-Shot)
**Exemple d'invocation :** *"Crée un atelier pratique sur la sécurisation des rôles dans FormateurController."*
**Comportement :** L'agent rédige un Brief de TP pour SoliLMS : mise en situation où un stagiaire doit restreindre l'édition aux seuls formateurs référents, liste 3 jalons, pose la contrainte de ne pas toucher à `Base/`, et fournit la grille critériée (gestion Spatie, redirection BLL, test unitaire).
