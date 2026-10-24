# Capacité : Affichage Blade — PkgQcm

## 1. Vues de Gestion (Formateur/Admin)

Les vues de gestion des QCMs (Création, Édition, Liste) sont générées par Gapp et utilisent l'architecture standard du projet (AdminLTE, Bootstrap).

### Points de Vigilance
- **Questions et Propositions dynamiques** : L'interface de création/édition d'un QCM doit souvent permettre d'ajouter dynamiquement des questions et leurs propositions de réponse.
- **FormRequests** : Assurez-vous que la validation des tableaux dynamiques (ex: `questions.*.enonce`, `questions.*.propositions.*.texte`) est correctement gérée dans les FormRequests.

## 2. Vues de Passage QCM (Apprenant)

L'interface de passage d'un QCM est critique pour l'expérience utilisateur de l'apprenant.

### Affichage des Questions
- **Type de Question** : Les questions à choix unique doivent utiliser des boutons `radio`, tandis que les questions à choix multiples utilisent des `checkbox`.
- **Pagination / Défilement** : Les questions peuvent être affichées toutes sur une page, ou paginées (une par une) selon le design choisi.
- **RÈGLE STRICTE** : Les vues de passage (côté apprenant) **NE DOIVENT JAMAIS exposer** quelles propositions sont correctes dans le code HTML/JS (ex: éviter des classes `is-correct` ou des data-attributes révélant la réponse). L'évaluation doit se faire uniquement côté serveur après la soumission.

### Chronomètre (Si `is_duree_limitee`)
- Utiliser du JavaScript pour afficher un compte à rebours basé sur `duree_minutes` et `date_debut`.
- Le chronomètre doit se baser sur le temps restant calculé côté serveur (passé à la vue) pour éviter les triches en modifiant l'horloge du client.
- À expiration du temps, le formulaire doit être soumis automatiquement.

## 3. Vues des Résultats

Une fois le QCM soumis et évalué, l'apprenant ou le formateur consulte les résultats.

### Affichage du Score
- Utiliser la `note_obtenu` de la `RealisationQcm` et calculer le score maximum possible (somme du `bareme` des questions du `Qcm`).
- Afficher un feedback visuel (ex: barre de progression colorée) selon que le score atteint le seuil de réussite (souvent défini par l'application, ex: 50% ou 70%).

### Détail des Réponses (Optionnel / Selon règles métier)
- Si l'application autorise de voir la correction : afficher l'énoncé, la réponse donnée par l'apprenant, et la correction avec `explication` éventuelle de la question.
- Utiliser des icônes explicites (✅ / ❌) pour indiquer si la réponse était juste.
