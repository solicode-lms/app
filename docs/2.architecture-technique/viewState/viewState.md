### **📌 Explication de `viewState`**
Dans ton application Laravel, **`viewState`** est un **mécanisme de gestion de l'état des vues**. Il permet de stocker et de récupérer des variables spécifiques à une vue, sans affecter d’autres parties de l’application. C'est une amélioration du concept de `contextState`, qui était **global**.

---

## **🎯 Pourquoi `viewState` ?**
✅ **Chaque vue a son propre contexte** → Évite d’écraser des valeurs globales comme avec `sessionState` ou `contextState`.  
✅ **Gérer dynamiquement les valeurs nécessaires** → Ex: `livrable__scope__projet_id`, utilisé uniquement dans la vue d’édition de projet.  
✅ **Réutilisable et maintenable** → Peut être appliqué **dans plusieurs endroits sans conflit**.  

---

## **🛠️ Différence entre `viewState` et `contextState`**
| **Concept**      | **`viewState` (État spécifique à une vue)** | **`contextState` (État global)** |
|------------------|--------------------------------|--------------------------------|
| **Stockage**      | Spécifique à la vue en cours  | Global pour toute l'application |
| **Visibilité**    | Accessible uniquement dans une vue donnée | Partagé entre plusieurs vues et contrôleurs |
| **Utilisation**   | Gérer les variables temporaires (ex: `projet_id` dans `edit`) | Stocker des données globales (ex: `sessionState`) |
| **Impact**       | Affecte uniquement la vue active | Affecte plusieurs parties du système |

---

## **🚀 Comment fonctionne `viewState` en pratique ?**
### **🔹 Définition dans le contrôleur (`edit` d’un projet)**
Lorsqu'on charge une vue, on assigne une **valeur spécifique** à `viewState`.

```php
public function edit(string $id)
{
    $itemProjet = $this->projetService->find($id);
    
    // Stocker la variable uniquement pour cette vue
    $this->viewState->set('livrable__scope__projet_id', $id);

    return view('PkgCreationProjet::projet.edit', compact('itemProjet'));
}
```

---

### **🔹 Récupération dans la vue (`edit.blade.php`)**
Dans la vue, on récupère la variable **sans affecter d’autres parties du système**.

```blade
<input type="hidden" name="projet_id" value="{{ viewState()->get('livrable__scope__projet_id') }}">
```

---

### **🔹 Application dans `DynamicContextScope`**
Dans le scope dynamique, on applique les **filtres uniquement si la variable existe** dans `viewState`.

```php
$projetId = $this->viewState->get('livrable__scope__projet_id');

if ($projetId) {
    $builder->where('projet_id', $projetId);
}
```

---

## **🎯 Bénéfices de `viewState`**
✅ **Évite d’écraser des variables globales** → Contrairement à `contextState`, les valeurs restent **isolées** dans chaque vue.  
✅ **Améliore la maintenabilité** → Plus facile à gérer et debuguer, car chaque vue gère son propre état.  
✅ **Plus sécurisé** → Moins de risque de modifier des valeurs qui affecteraient d’autres fonctionnalités.  

---

## **🚀 Exemple d'application concrète**
Tu peux utiliser `viewState` pour :
1. **Filtrer des livrables en fonction d’un projet** (`livrable__scope__projet_id`).
2. **Masquer certains champs dans un formulaire** (`hide_projet_select`).
3. **Gérer dynamiquement des paramètres d'affichage** (`table_columns_visible`).

---

💡 **Besoin d’un exemple spécifique pour ton application ? Dis-moi ce que tu veux approfondir ! 🚀**