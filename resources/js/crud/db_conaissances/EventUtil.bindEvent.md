# 🧠 Base de Connaissance : EventUtil.bindEvent

## Problème

Avec `document.addEventListener`, les événements ne s’appliquent pas aux éléments ajoutés dynamiquement et, après plusieurs `init()`, on peut avoir plusieurs callbacks exécutés.

## Solution

Utiliser `EventUtil.bindEvent` avec délégation et suppression des doublons :

```js
export default class EventUtil {
  static bindEvent(eventType, selector, callback, namespace = 'default') {
    const namespaced = `${eventType}.${namespace}`;
    $(document).off(namespaced, selector).on(namespaced, selector, callback);
  }
}
```

✅ Gère les éléments dynamiques
✅ Évite la duplication après plusieurs `init()`

---

Veux-tu que je te donne aussi la **version ultra-compacte en JSON** (clé : problème / solution / exemple) pour l’injecter directement dans ta base IA ?
