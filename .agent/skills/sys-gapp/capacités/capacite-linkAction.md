# ✅ Documenter une Metadata Gapp

## 🧾 Metadata `linksAction`

### 🎯 **Objectif**
Permet d’ajouter un bouton ou une icône dans chaque ligne de la `TableUI`, afin de rediriger l'utilisateur vers une autre page (souvent un index ou un détail) avec des paramètres dynamiques liés à l'entité de la ligne. Cela facilite la navigation vers des vues filtrées ou contextuelles (ex. : tâches d’un apprenant, livrables d’un projet).

---

### ⚙️ **Détails Techniques**
| Propriété         | Valeur                      |
|-------------------|-----------------------------|
| Nom               | `linkAction`                |
| Type              | `Json`                      |
| Scope             | `model`                     |
| Groupe            | `table`                     |
| Valeur par défaut | `null`                      |

---

### ✅ **Fonctionnement**

Lors de l'affichage d'une table générée par `Gapp`, la metadata `linkAction` permet de configurer dynamiquement un lien vers une autre page avec :
- **une route Laravel** (ex : `realisationTaches.index`)
- **des paramètres de filtre** injectés dynamiquement à partir des attributs de l’entité
- **une icône** (Font Awesome) et un `tooltip` pour l’accessibilité
- **des classes CSS** optionnelles (ex: `showIndex`) pour déclencher un comportement JS personnalisé (comme ouvrir un modal)

---

### 💡 **Exemples d’utilisation**

#### Exemple de configuration JSON

```json
[
  {
    "actionName": "livrablesRealisations",
    "ordre": 1,
    "icon": "fas fa-file-alt",
    "tooltip": "Livrables",
    "route": "livrablesRealisations.index",
    "params": {
      "showIndex": true,
      "contextKey": "'livrablesRealisation-index'",
      "scope.livrablesRealisation.realisation_projet_id": "$realisationTache->realisation_projet_id",
      "scope.livrable.projet_id": "$realisationTache->realisationProjet->affectationProjet->projet_id"
    },
    "class": "btn btn-default btn-sm context-state actionEntity showIndex",
    "permission": "index-livrablesRealisation"
  }
]
```

#### Exemple HTML / Blade généré

```blade
 @can('index-livrablesRealisation')
                    <a 
                        data-toggle="tooltip" 
                        title="Livrables" 
                        href="{{ route('livrablesRealisations.index', [
                            'showIndex' => true,
                            'contextKey' => 'livrablesRealisation-index',
                            'scope.livrablesRealisation.realisation_projet_id' => $realisationTache->realisation_projet_id,
                            'scope.livrable.projet_id' => $realisationTache->realisationProjet->affectationProjet->projet_id
                        ]) }}" 
                        data-id="{{$realisationTache->id}}" 
                        
                        class="btn btn-default btn-sm context-state actionEntity showIndex">
                            <i class="fas fa-file-alt"></i>
                    </a>
@endcan
```

---

### 🧩 **Remarques**
- **CRITIQUE (`contextKey` et chaîne de caractères)** : Pour transmettre une chaîne de caractères (comme le `contextKey`) via le générateur Gapp, vous **DEVEZ** l'entourer de guillemets simples à l'intérieur de la valeur JSON (ex: `"contextKey": "'ModelName-index'"`). Sinon, le générateur Blade l'écrira sans guillemets, causant une erreur PHP. N'oubliez JAMAIS d'ajouter `contextKey` ainsi que les `scope` de filtrage (ex: `"scope.[Model].[Property]"`) dans `"params"`.
- Peut être utilisée pour **naviguer vers des sous-ressources** (ex: toutes les tâches liées à un projet ou les livrables d’une tâche).
- Le champ `"params"` peut contenir des **valeurs dynamiques**, remplaçables par des `{{attribut}}` ou via `$entite->attribut`.
- Compatible avec les classes JS comme `.showIndex` pour affichage modal.
- **Combinez-la avec :**
  - `permission` : pour contrôler l'affichage du lien
  - `actionType: "modal"` (future extension possible)
