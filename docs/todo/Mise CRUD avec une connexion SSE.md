# Mise à jour automatique des pages index CRUD avec une connexion SSE
Mise CRUD avec une connexion SSE

# 0) Objectif et principe

* **Une seule connexion SSE** (Server‑Sent Events) est ouverte au chargement du layout (ex. `layouts/app.blade.php`).
* Le serveur **pousse** des événements (ex. `crud.update`, `notif`).
* Chaque page **index** s’abonne à ces événements et **rafraîchit** soit toute la table, soit juste la ligne impactée.
* Aucun service payant, aucune lib externe.

---

# 1) Backend Laravel — flux SSE unique

## 1.1 Route SSE

Crée une route dédiée au stream, protégée par l’auth web (session) :

```php
// routes/web.php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Notification;

Route::get('/stream/app', function (Request $request) {
    abort_unless(auth()->check(), 401);
    $userId = auth()->id();
    $lastId = (int) $request->query('last_id', 0);

    return response()->stream(function () use ($userId, $lastId) {
        // Reconnexion auto côté client si coupure
        echo "retry: 5000\n";

        $cursorId = $lastId;

        while (!connection_aborted()) {
            /**
             * Ici on pousse :
             * - les nouvelles notifications PkgNotification
             * - les événements CRUD que tu publieras (voir §2)
             */

            // (A) Nouvelles notifications pour l’utilisateur
            $news = Notification::where('user_id', $userId)
                ->when($cursorId > 0, fn($q) => $q->where('id', '>', $cursorId))
                ->orderBy('id', 'asc')
                ->limit(50)
                ->get();

            if ($news->isNotEmpty()) {
                $cursorId = (int) $news->max('id');

                // Événement nommé "notif"
                echo "event: notif\n";
                echo "data: " . json_encode([
                    'items'   => $news,
                    'last_id' => $cursorId,
                ]) . "\n\n";
                @ob_flush(); @flush();
            }

            // (B) Bus CRUD : msgs temporisés en cache/DB/queue (voir §2)
            // Exemple : lire une file légère (cache) d’événements à pousser
            if (function_exists('cache')) {
                $busKey = "crud_bus_user_{$userId}";
                $events = cache()->pull($busKey, []);
                if (!empty($events)) {
                    foreach ($events as $evt) {
                        echo "event: {$evt['event']}\n"; // ex: "crud.update"
                        echo "data: " . json_encode($evt['payload']) . "\n\n";
                    }
                    @ob_flush(); @flush();
                }
            }

            sleep(3); // cadence (3s) – ajuste selon ta charge
        }
    }, 200, [
        'Content-Type'       => 'text/event-stream',
        'Cache-Control'      => 'no-cache',
        'X-Accel-Buffering'  => 'no',   // utile derrière Nginx
    ]);
})->middleware('auth');
```

> Notes :
>
> * On **réutilise `notifications`** de `PkgNotification`.
> * On prévoit un **bus CRUD** (voir §2) poussé dans le même flux.
> * `retry: 5000` → le navigateur réessaie après 5s si ça coupe.

---

# 2) Publier des événements CRUD côté serveur (réutilise ton existant)

Ajoute un **helper** pour publier un message dans le **bus CRUD** (par utilisateur ou par rôle) sans dépendance externe. Ici on utilise `cache()` pour faire simple :

```php
// app/Support/CrudBus.php
namespace App\Support;

class CrudBus
{
    /**
     * Publie un evt CRUD pour un utilisateur (ou un groupe d’ids)
     * $event: "crud.update" | "crud.delete" | "crud.create" ...
     * $payload: ['model' => 'RealisationTache', 'id' => 123, 'action' => 'updated', ...]
     */
    public static function publishToUsers(array $userIds, string $event, array $payload): void
    {
        foreach ($userIds as $uid) {
            $key = "crud_bus_user_{$uid}";
            $events = cache()->get($key, []);
            $events[] = ['event' => $event, 'payload' => $payload];
            cache()->put($key, $events, now()->addMinutes(2));
        }
    }
}
```

Utilisation dans tes **Services/Observers/Controllers** existants (ex. après update d’une entité) :

```php
use App\Support\CrudBus;

// Exemple: après update d'une RealisationTache
CrudBus::publishToUsers([$apprenantId, $formateurId], 'crud.update', [
    'model'  => 'RealisationTache',
    'id'     => $realisationTache->id,
    'action' => 'updated',
    // Optionnel: snapshot minimal pour update local
    'fields' => [
        'etat' => $realisationTache->etat_realisation_tache_id,
        // ... ce que tu veux pousser
    ]
]);
```

> Tu peux aussi pousser un fragment HTML (rendu Blade) si tu veux faire un remplacement de ligne sans re‑fetch, mais le JSON minimal est souvent plus robuste.

---

# 3) Frontend — **UNE** connexion SSE globale + “EventBus”

## 3.1 Initialiser la connexion dans le layout (unique)

Dans `resources/views/layouts/app.blade.php` (ou ton layout principal), ajoute :

```blade
@push('scripts')
<script>
window.AppStream = (function(){
  let lastId = Number(localStorage.getItem('notif_last_id') || 0);
  let es = null;

  // Petit EventBus natif
  const topicHandlers = {};

  function on(topic, handler) {
    (topicHandlers[topic] = topicHandlers[topic] || []).push(handler);
  }
  function emit(topic, data) {
    (topicHandlers[topic] || []).forEach(h => { try { h(data); } catch(e) {} });
  }

  function connect() {
    if (es) es.close();
    es = new EventSource(`/stream/app?last_id=${lastId}`);

    // Notifications PkgNotification
    es.addEventListener('notif', (e) => {
      const data = JSON.parse(e.data);
      if (data.last_id) {
        lastId = data.last_id;
        localStorage.setItem('notif_last_id', String(lastId));
      }
      emit('notif', data);
    });

    // Bus CRUD (ex: crud.update, crud.create, crud.delete)
    ['crud.update','crud.create','crud.delete'].forEach(evt => {
      es.addEventListener(evt, (e) => {
        emit(evt, JSON.parse(e.data));
      });
    });

    es.onerror = () => {
      // laisse le navigateur gérer la reconnexion (retry côté serveur)
    };
  }

  document.addEventListener('visibilitychange', () => {
    // reconnecte si l’onglet revient actif et que la connexion a sauté
    if (document.visibilityState === 'visible' && (es?.readyState === 2 || !es)) {
      connect();
    }
  });

  connect();
  return { on };
})();
</script>
@endpush
```

> Important : **UNE** seule connexion SSE (dans le layout).
> Les pages n’ouvrent pas de flux — elles **s’abonnent** via `AppStream.on(...)`.

## 3.2 Sur chaque page index : s’abonner et rafraîchir

### Option A — Recharger seulement le tableau (simple et robuste)

Dans la page `index.blade.php` du modèle (ex. `RealisationTache`), ajoute :

```blade
<div id="crud-table">
  {{-- ... ta table HTML Blade existante --}}
</div>

@push('scripts')
<script>
(function(){
  const modelName = "RealisationTache"; // adapte au modèle courant

  function refreshIndex() {
    // Recharge le rendu HTML du tableau depuis la même URL (AJAX)
    fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
      .then(r => r.text())
      .then(html => {
        const doc = new DOMParser().parseFromString(html, 'text/html');
        const newTable = doc.querySelector('#crud-table');
        if (newTable) {
          document.querySelector('#crud-table').innerHTML = newTable.innerHTML;
        }
      })
      .catch(console.error);
  }

  // CRUD bus
  AppStream.on('crud.update', (payload) => {
    if (payload.model === modelName) refreshIndex();
  });
  AppStream.on('crud.create', (payload) => {
    if (payload.model === modelName) refreshIndex();
  });
  AppStream.on('crud.delete', (payload) => {
    if (payload.model === modelName) refreshIndex();
  });

  // Optionnel : notifications
  AppStream.on('notif', (data) => {
    // mettre à jour ta cloche, badge, toasts, etc.
    // ex: window.dispatchEvent(new CustomEvent('ui:notif', {detail:data}));
  });
})();
</script>
@endpush
```

### Option B — Mise à jour “fine” d’une ligne (optimisé)

Si tu pousses un `payload.fields`, tu peux mettre à jour la ligne sans recharger :

```js
AppStream.on('crud.update', (p) => {
  if (p.model !== 'RealisationTache') return;
  const row = document.querySelector(`#row-${p.id}`);
  if (!row) return;

  if (p.fields?.etat !== undefined) {
    row.querySelector('.cell-etat').textContent = p.fields.etat;
  }
  // autres champs ciblés...
});
```

> Les deux options peuvent coexister (update fin, sinon fallback refresh global).

---

# 4) Nginx — configuration pour SSE

Crée/édite ton vhost (ex. `/etc/nginx/sites-available/solilms.conf`) :

```nginx
server {
    listen 80;
    server_name solilms.local;
    root /var/www/solilms/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # 🔥 Flux SSE (tout sous /stream)
    location /stream {
        proxy_pass http://127.0.0.1:9000;   # ou :9000 Octane, ou php-fpm via app gateway
        proxy_http_version 1.1;
        proxy_set_header Host $host;
        proxy_set_header Connection '';
        proxy_buffering off;
        proxy_cache off;
        chunked_transfer_encoding off;
        proxy_read_timeout 3600;      # garder la connexion 1h
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;

        # 🔥 important pour ne pas bufferiser SSE
        fastcgi_buffering off;
    }
}
```

> Si tu es derrière Nginx → `X-Accel-Buffering: no` est déjà envoyé par la réponse Laravel (vu plus haut).
> Pense à `sudo nginx -t && sudo systemctl reload nginx`.

---

# 5) Apache — configuration pour SSE

Dans ton VirtualHost (ex. `/etc/apache2/sites-available/solilms.conf`) :

```apache
<VirtualHost *:80>
    ServerName solilms.local
    DocumentRoot /var/www/solilms/public

    <Directory /var/www/solilms/public>
        AllowOverride All
        Require all granted
    </Directory>

    # 🔥 Proxy de /stream vers ton backend app (Octane/Laravel)
    ProxyPass        /stream http://127.0.0.1:9000/stream retry=0
    ProxyPassReverse /stream http://127.0.0.1:9000/stream

    ProxyPreserveHost On
    ProxyTimeout 3600
</VirtualHost>
```

Et dans **php.ini** (module Apache ou FPM) :

```ini
output_buffering = Off
zlib.output_compression = Off
```

Active les modules si besoin :

```bash
a2enmod proxy proxy_http headers
systemctl reload apache2
```

---

# 6) Sécurité / Perf / Bonnes pratiques

* **Auth obligatoire** sur `/stream/app` (middleware `auth`).
* **Une seule connexion** par onglet via le layout (pas par page).
* **Indice de fraîcheur** : conserve `last_id` notifications dans `localStorage`.
* **Cadence** : `sleep(3)` côté stream suffit dans 99% des cas.
* **Scalabilité** : si tu as 1 000+ connexions, envisage PHP‑FPM + Nginx bien tuné, ou Laravel Octane (Swoole/RoadRunner).
* **Fallback** : si `EventSource` indisponible (vieux IE), tomber sur un **polling** toutes 30–60s.

---

# 7) Débogage rapide

* Ouvre `chrome://net-internals/#events` (ou l’onglet « Réseau ») → vérifie que la réponse `text/event-stream` **arrive par petits paquets** au fil du temps.
* Si tout arrive en **bloc** → buffering actif (vérifie `proxy_buffering off`, `fastcgi_buffering off`, `output_buffering=Off`).
* Si la connexion **coupe à 60s** → augmente `proxy_read_timeout` (Nginx) / `ProxyTimeout` (Apache).

---

# 8) Intégration Soli‑LMS (raccourcis)

* Tu as déjà `PkgNotification` → tu reçois gratuitement l’événement `notif` pour ta cloche, compteurs « non lus », toasts.
* Ajoute `CrudBus::publishToUsers([...])` au **moment clé** de tes Services/Observers (create/update/delete) pour notifier **uniquement** les utilisateurs impactés (apprenant, formateur, admin du groupe, etc.).
* Sur chaque page index, **3 lignes** suffisent : s’abonner à `crud.update | crud.create | crud.delete` et appeler `refreshIndex()`.

---

Besoin que je te génère un **starter kit** (fichiers exacts + fragments Blade) prêt à coller dans `Modules/PkgNotification` et `resources/views/layouts` de Soli‑LMS ? Je peux te le fournir avec les noms d’espaces conformes à ta structure.
