# Capacité : Gestion des opérations longues (CRUD Jobs) et Polling Frontend

## 🎯 Objectif et Définition
Les opérations lourdes (cascades de calculs, imports massifs) en base de données risquent de bloquer l'interface utilisateur ou de déclencher un timeout. 
Cette capacité normalise l'architecture asynchrone :
1. Un **Backend** qui déporte l'opération dans un Job via le pattern Observer.
2. Un **Frontend** qui effectue un Polling (requêtes régulières) pour afficher une barre de progression en temps réel sans bloquer le navigateur.

---

## 🏗️ Architecture Backend : Pattern Observer (OBLIGATOIRE)

La **SEULE façon autorisée** de créer une opération CRUD asynchrone (Job) est d'utiliser un **Eloquent Observer**. Il est interdit d'appeler des méthodes asynchrones directement depuis les contrôleurs ou les hooks standards des services.

### 1. Séparation des Responsabilités (Exemple : `RealisationQcm`)
- **Le Service (`update`)** : Calcule instantanément et synchronement la note ou les données propres à l'entité parente.
- **L'Observer (`updated`)** : Génère un Token via `JobManager` et dispatche un job asynchrone si la requête vient d'un client AJAX.
- **Le Job Asynchrone (`evaluerUaPrototypes`)** : Calcule et met à jour les entités enfants (cascade). Il ne modifie pas l'entité source avec les méthodes de cycle de vie.

### 2. Implémentation Backend (Exemple Pratique)

**A. Surcharge dans le Service (Synchrone) :**
```php
public function update($id, array $data) {
    // Calcul direct des attributs de l'entité avant la base
    $data['note_obtenu'] = $note; 
    return parent::update($id, $data);
}
```

**B. Dispatch depuis l'Observer :**
```php
public function updated(RealisationQcm $item): void {
    $jobManager = JobManager::initJob("updatedObserverJob", "realisationQcm", "PkgQcm", $item->id, []);
    if (request()->ajax() || request()->wantsJson()) {
        $jobManager->dispatchTraitementCrudJob(); // Asynchrone
    } else {
        app(RealisationQcmService::class)->updatedObserverJob($item->id, $jobManager->getToken()); // Synchrone
    }
}
```

**C. Exécution du Job dans le Service :**
La méthode appelée par l'Observer (ici `updatedObserverJob`) DOIT être implémentée dans le Service concerné.
```php
public function updatedObserverJob(int $id, string $token): void {
    $jobManager = new \Modules\Core\App\Manager\JobManager($token);
    $item = $this->find($id);

    // On délègue à la méthode lourde en passant le jobManager
    if ($item) {
        $this->evaluerUaPrototypes($item, $jobManager); 
    } else {
        $jobManager->initProgress(1);
    }
    
    $jobManager->finish(); // 🚨 Toujours clôturer le job
}
```

**D. Implémentation du calcul lourd (Progression) :**
```php
public function evaluerUaPrototypes($item, $jobManager = null) {
    if ($jobManager) $jobManager->initProgress(count($enfants) + 1); // Initialise le diviseur

    foreach($enfants as $enfant) {
        // ... calcul ...
        if ($jobManager) {
            $jobManager->setLabel("Calcul de l'enfant : " . $enfant->nom); // Texte affiché au front
            $jobManager->tick(); // Incrémente le pourcentage
        }
    }
    
    if ($jobManager) {
        $jobManager->setLabel("Finalisation");
        $jobManager->tick();
    }
}
```

### 🚨 Interdiction de Traitement Circulaire
**Règle Stricte** : Un Job asynchrone (déclenché par un Observer) ne doit **JAMAIS** modifier le modèle source avec `$item->update()` ou `$item->save()`. Cela déclencherait à nouveau l'Observer et créerait une **boucle infinie**.
- **Solution** : Utilisez `withoutEvents(function() { ... })` si une mise à jour post-calcul de l'entité parente est strictement nécessaire.

---

## 💻 Architecture Frontend : Polling et Interface Utilisateur (SweetAlert)

Le front-end (Blade, Alpine.js ou Vanilla JS) doit impérativement exploiter le token renvoyé par le backend pour afficher l'évolution de la progression.

### 1. Le Flux de Données du Polling
1. **Initialisation (Action de l'utilisateur)** : Une requête AJAX (`fetch` / `axios`) modifie ou crée une ressource. Le backend retourne `{ "traitement_token": "token123" }`.
2. **Démarrage Worker Front** : Le front fait un appel asynchrone à `/admin/traitement/start?token=token123` pour réveiller le traitement.
3. **Boucle de Polling** : Le front interroge toutes les 1.5s l'URL `/admin/traitement/status/token123`.
4. **Mise à jour UI** : Le retour JSON de l'URL `/status` contient `progress` (0 à 100) et `label` (le texte). Le front met à jour le visuel.
5. **Finalisation** : Lorsque `status` === "done", le front arrête le polling et rafraîchit ou redirige la page.

### 2. Implémentation Front-end Standard (Vanilla JS & SweetAlert2)

Voici le code standard à implémenter dans les fichiers JS ou vues Blade (Skill: `app-blade` ou `app2-front-end`) :

```javascript
// 1. Appel AJAX initial
const response = await fetch('/api/realisation/1', { method: 'PUT', body: data });
const result = await response.json();

if (result.traitement_token) {
    const token = result.traitement_token;
    
    // 2. Réveiller le job
    fetch(`/admin/traitement/start?token=${token}`);
    
    // 3. Lancer la boucle de polling
    const checkStatus = async () => {
        const res = await fetch(`/admin/traitement/status/${token}`);
        const statusData = await res.json();
        
        // La variable `progress` va de 0 à 100 (calculée par le backend)
        const percent = statusData.progress || 0;
        const label = statusData.label || 'Traitement en cours...';
        
        // 4. Mise à jour de l'interface (Modal SweetAlert)
        if (typeof Swal !== 'undefined') {
            if (!Swal.isVisible()) {
                Swal.fire({
                    title: 'Évaluation en cours...',
                    html: `<b>${percent}%</b><br><small>${label}</small>`,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
            } else {
                Swal.update({ html: `<b>${percent}%</b><br><small>${label}</small>` });
            }
        }
        
        // 5. Conditions d'arrêt
        if (statusData.status === 'done') {
            Swal.fire({ title: 'Terminé', icon: 'success', timer: 1500 });
            window.location.reload(); // Ou redirection
        } else if (statusData.status === 'error') {
            Swal.fire('Erreur', statusData.messageError || 'Erreur inconnue', 'error');
        } else {
            // Continuer le polling
            setTimeout(checkStatus, 1500);
        }
    };
    
    checkStatus();
}
```

### 3. Règles d'intégration UI
- L'interface **doit toujours** se bloquer (modal `allowOutsideClick: false`) pour empêcher l'utilisateur de cliquer ailleurs pendant un traitement lourd.
- Affichez toujours le pourcentage (`statusData.progress`) ET le label métier (`statusData.label`). Le backend se charge de calculer le `progress` à partir du nombre de ticks, le front n'a pas à faire de division.
