# Capacité : Levée de Messages Métier (BlException)

## 🎯 Rôle
Permet d'interrompre le flux d'exécution lorsqu'une règle métier n'est pas respectée (lors d'une création, mise à jour ou suppression) et de retourner un message d'erreur clair et convivial destiné à l'utilisateur final.

## ⚙️ Mécanisme de l'Exception
Le projet Solicode LMS utilise une exception dédiée à la logique métier (Business Logic Layer) nommée `BlException` (`Modules\Core\App\Exceptions\BlException`).
Lorsqu'elle est levée, le gestionnaire d'exceptions global (Exception Handler) de Laravel la capture et affiche son message de manière propre sur l'interface (ex: pop-up SweetAlert2 ou message d'erreur sur le formulaire) avec un code HTTP 400.

## 🛠️ Utilisation de `BlException`

### 1. Importer l'Exception
```php
use Modules\Core\App\Exceptions\BlException;
```

### 2. Lever l'Exception dans un Hook ou une Action
```php
public function beforeDeleteRules($projet)
{
    // Vérification de la présence de réalisations associées
    if ($projet->affectationProjets()->count() > 0) {
        throw new BlException("Impossible de supprimer ce projet : </br> il est encore affecté à un ou plusieurs groupes. </br> Supprimez d'abord les affectations.");
    }
}
```

### ⚠️ Règles de Rédaction des Messages
- **Clarté** : Le message doit expliquer clairement *pourquoi* l'action a échoué et *comment* l'utilisateur peut résoudre le problème.
- **Formatage HTML** : Le composant d'affichage supporte le HTML de base. Vous pouvez utiliser des balises comme `</br>` ou `<b>` pour aérer et formater le message d'erreur.
- **Langue** : Les messages d'erreur doivent impérativement être rédigés en **Français** (langue du client).
