# 🎯 Objectif

Documenter l’usage de la mécanique **after\*Job** (ex. `afterCreateJob`) pour exécuter des traitements lourds **asynchrones** après une action CRUD, avec **token** de suivi, **progression** et **polling** côté UI.

---

## 🧠 Principe général

1. **Déclenchement auto** : Lors d’un `create()`, le trait `CrudCreateTrait` appelle `executeJob('after', 'create', $id)` (voir `CrudTrait`).
2. **Découverte de méthode** : `executeJob()` cherche une méthode **`afterCreateJob($id, $token)`** sur le service courant. Si présente, un **Job** générique est dispatché (`TraitementAsync`).
3. **Token** : un **UUID** est généré et stocké dans `Cache` sous `traitement.{token}.status = 'pending'` et dans `{$service}->job_token`.
4. **Progression** : votre méthode `afterCreateJob()` met à jour `traitement.{token}.progress` (0–100) pendant l’exécution.
5. **Fin** : la méthode doit **retourner** `"done"` en succès, ou `"error"` en cas d’échec, et renseigner les clés Cache associées.

> **Pourquoi ?** Pour ne pas bloquer la requête HTTP principale, tout en donnant un **feedback temps réel** côté interface (loader + pourcentage).

---

## 🧩 Prérequis

* `QUEUE_CONNECTION=database` (ou Redis) et table `jobs` migrée.
* Un worker disponible :

  * **Dév / sans Supervisor** : endpoint utilitaire `queue:work --once` (voir plus bas),
  * **Prod recommandé** : `php artisan queue:work` sous **Supervisor** (pas d’endpoint utilitaire).
* Le **service** qui gère l’entité implémente `afterCreateJob($id, $token): string`.

---

## 🔁 Cycle de vie

```
HTTP POST /entity → Service::create() → executeJob(…)
    ↳ crée token + Cache(pending) → dispatch(Job TraitementAsync)
        ↳ appelle Service::afterCreateJob($id, $token)
            ↳ met à jour Cache(progress, status, messages)

UI → /traitement/start  (optionnel en dev) → démarre queue:work --once
UI → /traitement/status/{token} → lit {status, progress}
```

---

## 🛠️ Implémentation côté Service (extrait prêt à l’emploi)

```php
/**
 * S’exécute en asynchrone après la création.
 * Doit retourner 'done' ou 'error'.
 */
public function afterCreateJob($id, $token): string
{
    $entity = $this->find($id);

    if (!$entity) {
        Cache::put("traitement.$token.status", 'error', 3600);
        Cache::put("traitement.$token.messageError", "L'entité n'existe pas", 3600);
        return 'error';
    }

    // Exemple de progression
    $total = 10; // 👉 adaptez : somme des étapes prévues
    $done  = 0;
    $tick  = function () use (&$done, $total, $token) {
        $done++;
        $progress = max(0, min(100, (int) round(($done / $total) * 100)));
        Cache::put("traitement.$token.progress", $progress, 3600);
    };

    Cache::put("traitement.$token.status", 'running', 3600);

    try {
        // … vos traitements (créations, sync, etc.)
        $tick();
        // $tick() à chaque étape significative

        Cache::put("traitement.$token.status", 'done', 3600);
        return 'done';
    } catch (\Throwable $e) {
        Cache::put("traitement.$token.status", 'error', 3600);
        Cache::put("traitement.$token.messageError", $e->getMessage(), 3600);
        return 'error';
    }
}
```

> 📌 Dans votre code fourni (`AffectationProjetService`), la progression est mise à jour après chaque création de `TacheAffectation` et de `RealisationProjet`, puis après `SyncEvaluation…`.

---

## 🔓 Récupérer le token côté contrôleur (réponse JSON)

`executeJob()` place le token dans `{$service}->job_token`. Pour l’exposer dans la réponse :

```php
public function store(Request $request)
{
    $service = app(\Modules\…\AffectationProjetService::class);
    $entity  = $service->create($request->all());

    // Idéalement, ajoutez un getter dans le service pour éviter les propriétés dynamiques
    $token = method_exists($service, 'getJobToken')
        ? $service->getJobToken()
        : ($service->job_token ?? null);

    return response()->json([
        'message' => 'Création lancée',
        'entity_id' => $entity->id,
        'traitement_token' => $token,
    ]);
}
```

👉 **Recommandé** : dans votre `CrudTrait`, ajoutez un **getter** sûr :

```php
public function getJobToken(): ?string { return $this->job_token ?? null; }
```

> **Note PHP 8.2** : les **propriétés dynamiques** sont dépréciées. Déclarez `protected ?string $job_token = null;` dans le service parent, ou ajoutez l’attribut `#[\AllowDynamicProperties]` si nécessaire.

---

## 🚦 Démarrer le worker & endpoints utilitaires (développement)

En environnement sans worker permanent, exposez deux endpoints (déjà présents dans votre code) :

```php
// POST /admin/traitement/start
public function start() {
    \Artisan::call('queue:work --once');
    return response()->json();
}

// GET /admin/traitement/status/{token}
public function status($token) {
    \Artisan::call('queue:work --once'); // Optionnel si un worker tourne déjà
    $status   = Cache::get("traitement.$token.status", 'unknown');
    $progress = Cache::get("traitement.$token.progress", 0);
    $message  = Cache::get("traitement.$token.messageError");
    return response()->json(compact('status','progress','message'));
}
```

> **Prod** : ne pas appeler `queue:work --once` dans les endpoints. Laissez **Supervisor** gérer `queue:work`.

---

## 🧪 Polling côté front (jQuery/vanilla)

```js
function pollTraitementStatus(token, onDone) {
  const startedAt = Date.now();
  const loader = window.loader_traitement; // votre wrapper UI

  // Lancer le traitement SANS bloquer
  $.post('/admin/traitement/start').always(() => {});

  const tickUi = () => {
    const secs = Math.floor((Date.now() - startedAt) / 1000);
    loader?.showNomBloquante(`⏳ Traitement en cours… ${secs}s`);
  };
  tickUi();

  const intervalUi = setInterval(tickUi, 1000);

  const poll = () => {
    $.get(`/admin/traitement/status/${token}`)
      .done((res) => {
        const { status, progress, message } = res;
        loader?.setProgress?.(progress ?? 0); // si votre loader supporte une barre

        if (status === 'done') {
          clearInterval(intervalUi);
          loader?.hide();
          if (typeof onDone === 'function') onDone();
          return;
        }
        if (status === 'error') {
          clearInterval(intervalUi);
          loader?.hide();
          NotificationHandler.showError('❌ ' + (message || 'Erreur pendant le traitement.'));
          return;
        }

        setTimeout(poll, 800); // cadence de polling
      })
      .fail(() => {
        setTimeout(poll, 1200);
      });
  };

  poll();
}
```

> **Astuce UX** : limitez le polling à 0.5–1.5s; affichez un compteur **mm\:ss** si > 60s.

---

## 🗄️ Convention des clés Cache

* `traitement.{token}.status` → `pending` | `running` | `done` | `error` | `unknown`
* `traitement.{token}.progress` → `0–100` (int)
* `traitement.{token}.messageError` → message court (optionnel)

Durée recommandée : **3600s** (1h), ajustez selon vos besoins.

---

## ✅ Bonnes pratiques

* **Idempotence** : si le job peut être relancé, gérez les doublons (upsert, `updateOrCreate`).
* **Granularité** : appelez votre closure `tick()` à chaque étape métier **perceptible**.
* **Échecs** : interceptez les exceptions et **renseignez** `status=error` + `messageError`.
* **Nettoyage** : envisagez un job de **cleanup** périodique (tokens expirés).
* **Prod** : utilisez **Supervisor** → `queue:work --sleep=3 --tries=1`.
* **Logs** : loguez début/fin + token pour corrélation.

---

## 🐞 Erreurs courantes & solutions

* **Rien ne se passe** : `QUEUE_CONNECTION=sync` ➜ passez à `database`/Redis + lancez un worker.
* **Status reste "pending"** : votre `afterCreateJob()` n’écrit jamais `status` ni `progress`.
* **Propriété `$job_token` inaccessible** : ajoutez un **getter** ou déclarez la propriété dans la classe parente.
* **Timeout HTTP** : ne **bloquez jamais** la requête initiale; tout le lourd dans le job.

---

## 🧾 Exemple réel : `AffectationProjetService::afterCreateJob()` (extrait commenté)

* Récupère l’affectation ➜ sinon `status=error`.
* Collecte **tâches** du projet et **apprenants** du (sous)groupe.
* Crée `TacheAffectation` pour chaque tâche, puis `RealisationProjet` pour chaque apprenant.
* Appelle `SyncEvaluationRealisationProjet()`.
* Met à jour la **progression** à chaque étape et retourne `"done"`.

---

## 📚 FAQ

**Q. Où est dispatché le job ?**
Dans `CrudTrait::executeJob()` → `dispatch(new TraitementAsync(...))`.

**Q. Puis‑je l’utiliser pour update/delete ?**
Oui : implémentez `afterUpdateJob($id, $token)` ou `afterDeleteJob($id, $token)` et `executeJob('after', 'update', $id)` sera appelé si vous utilisez le flux standard du service.

**Q. Comment tester rapidement ?**

* Postman : créez l’entité → récupérez `traitement_token` → appelez `/admin/traitement/start` puis `/admin/traitement/status/{token}`.
* `php artisan tinker` : simulez l’appel à `afterCreateJob($id, $token)` et inspectez `Cache`.

---

## 🔚 Résumé

* Implémentez `afterCreateJob($id, $token)` dans votre service.
* Exposez le **token** dans la réponse HTTP.
* Démarrez un **worker** (endpoint utilitaire ou Supervisor).
* **Pollez** `status/progress` côté UI et affichez un loader avec pourcentage.

> Avec ce pattern, vos créations lourdes sont **instantanées** côté UX, tout en restant **fiables** et **observables** côté serveur.
