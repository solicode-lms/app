# Évolution : Gestion Dynamique des Notes QCM et Affectation (EVOL-001)

## Contexte
La conception initiale du module QCM mentionnait une entité intermédiaire `ResultatUAQCM` pour stocker les résultats. Afin de simplifier et de dynamiser le calcul, nous remplaçons cette logique par un transfert direct vers la réalisation du prototype.

## Modifications Demandées dans le Modèle (À réaliser dans le code)

1. **Renommage de l'Entité Cible** : 
   - L'entité cible pour le transfert des notes (qui s'appelait `RealisationUAProjet` dans le diagramme) doit être renommée en `RealisationUaPrototype`.
   
2. **Ajout de Colonnes (Entité `RealisationUaPrototype`)** :
   - Ajouter un champ `note_qcm` (type: float/décimal) qui stockera la note finale après transfert.

3. **Ajout de Colonnes (Entité `AffectationQcmProjet`)** :
   - Ajouter un champ de configuration `is_insertion_automatique` (type: booléen, par défaut à false).
   - *Règle métier* : Ce champ détermine si, lors de la validation par le formateur, la note du QCM s'insère automatiquement dans `RealisationUaPrototype.note_qcm` ou si elle sert seulement d'aide à la décision pour une saisie manuelle.

## Actions Post-Développement
Une fois ces modifications codées et intégrées dans la base de données de l'application :
1. Le diagramme de classe `pkg_qcm_classes.mmd` devra être mis à jour pour refléter ces nouvelles colonnes et le nom exact de la classe `RealisationUaPrototype`.
2. Ce ticket pourra être fermé.
