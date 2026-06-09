# Capacité : Surcharge des Hooks CRUD

## 🎯 Rôle
Permet d'exécuter des validations de règles métier, d'injecter des données par défaut ou de déclencher des effets de bord (synchronisations, logs, créations en cascade) à différents moments du cycle de vie CRUD d'une entité.

## ⚙️ Mécanisme Standard
Les actions d'écriture dans `BaseService` appellent automatiquement des méthodes de règles (hooks) à différentes étapes. Si elles ne sont pas surchargées, elles ne font rien.

## 🛠️ Liste des Hooks et Signatures

Ces hooks doivent être définis/surchargés dans le Trait CRUD associé au service (ex: `[Model]CrudTrait.php`).

- **`beforeCreateRules(array &$data)`**
  - *Moment d'exécution* : Avant l'insertion en base de données.
  - *Rôle* : Validation métier, injection de valeurs par défaut (ex: auteur, formateur). Les données sont passées par référence (`&$data`).
- **`afterCreateRules($item)`**
  - *Moment d'exécution* : Après l'insertion réussie en base de données.
  - *Rôle* : Création automatique d'entités enfants, envoi de notifications, initialisations diverses.
- **`beforeUpdateRules($item, array $data)`** *
  - *Moment d'exécution* : Avant la mise à jour en base de données.
  - *Rôle* : Vérification des droits de transition d'état, blocage de modifications de champs figés.
- **`afterUpdateRules($item, array $data)`** *
  - *Moment d'exécution* : Après la mise à jour en base de données.
  - *Rôle* : Synchronisation des tables de liaison ManyToMany, recalculs de scores ou de statistiques.
- **`beforeDeleteRules($item)`**
  - *Moment d'exécution* : Avant la suppression physique ou logique de l'entité.
  - *Rôle* : Vérification des contraintes d'intégrité métier (ex: empêcher de supprimer si des liaisons actives existent).

*\* Note : Vérifiez toujours la signature de la méthode dans le parent (`BaseService` ou `Base[Model]Service`) car selon les entités, le deuxième argument `$data` peut ou non être requis.*

### Exemple d'implémentation (dans `[Model]CrudTrait`)
```php
trait ProjetCrudTrait
{
    // Empêcher la modification d'un champ sensible après création
    public function beforeUpdateRules($projet)
    {
        if (isset($projet['session_formation_id'])) {
            $original = $this->model->find($projet['id']);
            if ($original && $original->session_formation_id != $projet['session_formation_id']) {
                throw new BlException('La session de formation ne peut pas être modifiée après création.');
            }
        }
    }
}
```
