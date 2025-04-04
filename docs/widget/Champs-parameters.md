# **Documentation : Champ `parameters` d'un Widget**

Le champ **`parameters`** d'un widget est une **configuration JSON** qui permet de définir les critères de filtrage, la mise en page et le comportement d'affichage des données. Il permet d'adapter dynamiquement le widget en fonction des rôles des utilisateurs, des restrictions de données et des préférences d'affichage.

---

## **Structure Générale**
```json
{
  "limit": "10",
  "roles": {
    "apprenant": {
      "etatRealisationTache.nom": "En cours",
      "realisationProjet.apprenant.user_id": "#user_id"
    },
    "formateur": {
      "etatRealisationTache.nom": "En cours",
      "realisationProjet.affectationProjet.projet.formateur.user_id": "#user_id"
    }
  },
  "TableUI": {
    "Id": "id",
    "titre": "tache.titre"
  }
}
```

---

## **Détail des Propriétés**
| Clé | Type | Description |
|------|------|------------|
| `limit` | `int` | Définit le nombre maximum d'éléments à afficher dans le widget. |
| `roles` | `object` | Permet d'appliquer des filtres spécifiques en fonction du rôle de l'utilisateur. |
| `TableUI` | `object` | Détermine la structure d'affichage du tableau lorsque le widget est de type `table`. |

---

## **Explication des Paramètres**

### **1. Limite des résultats (`limit`)**
Définit le nombre maximal d'enregistrements retournés par la requête.

📌 **Exemple :**
```json
{
  "limit": "10"
}
```
➡️ Limite l'affichage du widget à **10 éléments**.

---

### **2. Gestion des rôles (`roles`)**
Permet d'adapter dynamiquement les filtres **en fonction du rôle** de l'utilisateur.  
Chaque rôle peut avoir des **conditions spécifiques** appliquées.

📌 **Exemple :**
```json
"roles": {
  "apprenant": {
    "etatRealisationTache.nom": "En cours",
    "realisationProjet.apprenant.user_id": "#user_id"
  },
  "formateur": {
    "etatRealisationTache.nom": "En cours",
    "realisationProjet.affectationProjet.projet.formateur.user_id": "#user_id"
  }
}
```
➡️ **Explication :**
- Un utilisateur **apprenant** voit uniquement les tâches où :
  - `etatRealisationTache.nom = "En cours"`
  - `realisationProjet.apprenant.user_id = #user_id` *(Filtrage des tâches liées à l'utilisateur connecté).*
- Un utilisateur **formateur** voit uniquement les tâches où :
  - `etatRealisationTache.nom = "En cours"`
  - `realisationProjet.affectationProjet.projet.formateur.user_id = #user_id` *(Filtrage des projets où il est affecté).*

🔹 **Le token `#user_id`** est remplacé dynamiquement par l'ID de l'utilisateur connecté.

---

### **3. Configuration de l'affichage (`TableUI`)**
Utilisé lorsque le widget est de type **table**. Il définit **les colonnes** qui doivent être affichées.

📌 **Exemple :**
```json
"TableUI": {
  "Id": "id",
  "titre": "tache.titre"
}
```
➡️ **Explication :**
- **Colonne "Id"** : Affiche la valeur du champ `id` de l'enregistrement.
- **Colonne "titre"** : Affiche la valeur `tache.titre` (titre de la tâche liée).

💡 **Utilisation possible :**
- Renommer des champs pour une meilleure lisibilité.
- Sélectionner les colonnes à afficher dans un tableau.

---

## **Exemple Complet**
Un widget configuré pour un **formateur**, affichant les tâches en cours avec une limite de 5 résultats.

```json
{
  "limit": "5",
  "roles": {
    "formateur": {
      "etatRealisationTache.nom": "En cours",
      "realisationProjet.affectationProjet.projet.formateur.user_id": "#user_id"
    }
  },
  "TableUI": {
    "ID": "id",
    "Tâche": "tache.titre",
    "Statut": "etatRealisationTache.nom"
  }
}
```

---

## **Gestion dans `WidgetService`**
Lorsqu'un widget est exécuté via `executeWidget()`, le système :
1. **Charge les paramètres JSON** du widget.
2. **Applique les conditions de filtrage** selon le rôle.
3. **Construit la requête** en appliquant `limit`, `group_by`, etc.
4. **Formate les données** si le type est `table` (via `TableUI`).

Extrait de code correspondant :
```php
$this->extractSpecialConditions($query);
$this->validateOperation($query, $widget->type->type);
$result = $this->execute($query, $widget);

if ($widget->type->type === "table" && isset($query['TableUI'])) {
    $result = $this->formatTableData($result, $query['TableUI']);
}
$widget->data = $result;
return $widget;
```

---

## **Conclusion**
- `parameters` est un champ **flexible** permettant de **personnaliser** les widgets.
- **`roles`** permet une gestion avancée des permissions et des restrictions d'accès.
- **`TableUI`** optimise l'affichage en mode tableau.
- Les valeurs dynamiques comme `#user_id` rendent les widgets **adaptatifs**.

✅ **Grâce à cette configuration, les widgets sont entièrement dynamiques et adaptés aux utilisateurs !** 🚀