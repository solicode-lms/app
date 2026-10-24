---
name: rediger-tutoriel
description: Redige des tutoriels techniques pas-a-pas progressifs, verifiables et ancres dans le code reel de Solicode LMS pour les apprenants developpeurs.
version: 1.0.0
---

# 1. Mission
Ce skill a pour mission de concevoir des guides d'apprentissage techniques pas-à-pas (tutoriels) clairs, didactiques et directement testables. Chaque tutoriel vise l'acquisition d'un savoir-faire précis par les apprenants développeurs de Solicode, en utilisant des exemples issus du codebase réel et en appliquant le principe de divulgation progressive.

# 2. Déclenchement & Limites
- **Quand l'activer :**
  - Demande de tutoriel guidé ou pas-à-pas ("Rédige un tutoriel pour créer un filtre TableUI", "Explique aux apprenants comment lever une BlException").
  - Documentation pédagogique d'un composant complexe (Services, Traits, FormUI).
  - Guide méthodologique pour débuter sur un module de SoliLMS.
- **Quand NE PAS l'activer :**
  - Conception d'un sujet de TP ou brief d'évaluation autonome -> Déclencher `creer-atelier-pratique`.
  - Implémentation directe de code applicatif -> Déclencher `implementer-fonctionnalite`.
  - Fiche de contexte brute pour l'IA -> Déclencher `documenter-package`.

# 3. Entrées & Ressources à Charger
1. **Priorité 1 (Contexte Pédagogique) :** `.agents/references/standards/pedagogy-referential.md` (Compétences cibles Solicode, niveaux d'autonomie).
2. **Priorité 2 (Références Techniques) :**
   - `.agents/references/architecture/layers-3tiers.md`
   - `.agents/references/frontend/tableui-framework.md`
   - `.agents/references/gapp/01-conventions-et-garde-fous.md`
3. **Priorité 3 (Code Source Réel) :** Extraire les exemples directement depuis les classes fonctionnelles du projet (ex: `FiliereService.php`, `TableUI.js`).

# 4. Contraintes & Règles Inviolables
- **Zéro Code Fictif :** Les exemples de code DOIVENT correspondre rigoureusement à l'architecture réelle de Solicode LMS (pas de faux code PHP ou de snippets génériques trouvés sur le web).
- **Divulgation Progressive :** Ne jamais noyer l'apprenant sous des notions avancées hors-sujet. Isoler une difficulté à la fois.
- **Expliciter les Pièges Réels :** Toujours mettre en garde contre les erreurs fréquentes observées chez les apprenants (ex: modifier par erreur `Base/` au lieu de la classe enfant, oublier `data-calcule`).

# 5. Procédure Pas-à-Pas (Workflow de Rédaction)
1. **Étape 1 : Cadrage Pédagogique :**
   - Définir l'objectif SMART du tutoriel (ex: "À la fin de ce guide, vous serez capable d'ajouter un filtre ManyToOne fonctionnel").
   - Préciser les prérequis techniques (notions PHP/Laravel, environnement local prêt).
2. **Étape 2 : Mise en Situation Métier :** Formuler un besoin client crédible (ex: "Le responsable pédagogique souhaite filtrer les projets par session de formation").
3. **Étape 3 : Découpage en Étapes Numérotées :**
   - Chaque étape doit comporter : *L'action à réaliser*, *Le fichier exact à ouvrir/modifier*, *Le bloc de code commenté*, et *Ce qui se passe sous le capot*.
4. **Étape 4 : Défi d'Application Autonome :** Proposer un exercice court de mise en pratique pour tester l'ancrage des connaissances.
5. **Étape 5 : Checklist d'Auto-Évaluation :** Fournir une liste de points observables pour que l'apprenant valide lui-même son travail.

# 6. Gestion des Ambiguïtés & Décisions
- Si le niveau des apprenants n'est pas spécifié : calibrer par défaut pour un développeur junior (Niveau 1/2 : expliquer le pourquoi de chaque annotation et relation).

# 7. Format de Sortie & Livrables
Un document Markdown structuré comprenant obligatoirement :
- `# [Titre Clair du Tutoriel]`
- `## 1. Contexte & Objectif d'Apprentissage`
- `## 2. Prérequis`
- `## 3. Étapes Pas-à-Pas (Guidées)`
- `## 4. Pièges Fréquents & Bonnes Pratiques`
- `## 5. Défi Pratique Autonome`
- `## 6. Checklist de Validation (Je valide que mon code marche)`

# 8. Contrôle Qualité (Checklist Binaire)
- [ ] Le code présenté est-il strictement conforme aux conventions réelles de SoliLMS ?
- [ ] Les étapes sont-elles reproductibles de bout en bout sans zone d'ombre ?
- [ ] Les pièges liés à Gapp (`Base/` vs classes enfants) sont-ils clairement signalés ?
- [ ] Le livrable inclut-il une checklist d'auto-évaluation ?

# 9. Gestion des Erreurs Fréquentes
| Erreur Typique | Cause | Solution Immédiate |
| :--- | :--- | :--- |
| Donner du code avec des variables non déclarées | Inattention lors de la rédaction | Vérifier la complétude de chaque extrait |
| Expliquer trop de concepts en même temps | Manque de cadrage pédagogique | Découper le tutoriel en sous-étapes plus digestes |

# 10. Exemples Concrets (Few-Shot)
**Exemple d'introduction d'étape :**
> **Étape 2 : Déclarer le filtre dans la couche Service**  
> Ouvrez le fichier enfant `modules/PkgFormation/Services/FiliereService.php`.  
> *Attention : Ne modifiez pas `BaseFiliereService.php` qui est écrasé par Gapp !*  
> Ajoutez la méthode `initFieldsFilterable()` pour injecter votre filtre...
