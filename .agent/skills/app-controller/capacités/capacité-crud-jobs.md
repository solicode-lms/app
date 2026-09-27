# Capacité : Gestion des opérations longues (CRUD Jobs)

## 📌 Contexte
Certaines opérations de modification en base de données (création complexe, mises à jour en cascade, imports massifs) peuvent prendre du temps. Pour éviter de bloquer l'interface utilisateur ou de provoquer un "timeout" de la requête HTTP, l'architecture Solicode utilise un système de **Jobs asynchrones**.

## ⚙️ Mécanisme `getCrudJobToken()`

### 1. Côté Service (`app-service`)
Lorsqu'une opération métier est déclenchée (ex: `create()`, `update()`), le service peut décider de déléguer la partie lourde du traitement à un `Job` Laravel exécuté en arrière-plan.
Lorsqu'il fait cela, le service enregistre un **token de suivi** accessible via `$this->service->getCrudJobToken()`.

### 2. Côté Contrôleur (`app-controller`)
Le contrôleur, juste après avoir appelé la méthode du service, doit vérifier si un Job asynchrone a été déclenché.
Si c'est le cas, il doit **obligatoirement** renvoyer ce token au frontend dans sa réponse JSON.

**Exemple d'implémentation standard générée par Gapp :**
```php
public function store(QuestionRequest $request) {
    $validatedData = $request->validated();
    
    // Appel au service (qui peut déclencher un Job)
    $question = $this->questionService->create($validatedData);

    if ($request->ajax()) {
        $message = __('Core::msg.addSuccess', [ /* ... */ ]);
        
        return JsonResponseHelper::success(
            $message,
            array_merge(
                ['entity_id' => $question->id],
                // On vérifie si un token a été généré et on l'ajoute à la réponse
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            )
        );
    }
    // ...
}
```

### 3. Côté Frontend (`app-crud-js` / UI)
Lorsque le JavaScript intercepte la réponse AJAX, s'il détecte la présence de `traitement_token`, il comprend que l'opération n'est pas encore terminée.
Il va alors :
- Afficher un indicateur de chargement (Spinner / Barre de progression).
- Effectuer un *polling* (interroger le serveur toutes les X secondes) avec ce token pour vérifier l'état du traitement.
- Recharger la vue (ou fermer la modale) uniquement lorsque le serveur confirme que le Job est `terminé`.

## 🚨 Règle d'Or (Contrôleurs Personnalisés)
Si vous surchargez ou créez une méthode `store` ou `update` personnalisée qui répond en AJAX, **vous devez toujours intégrer la vérification `$this->service->getCrudJobToken()`** dans votre `JsonResponseHelper::success()`. Sans cela, le frontend croira que l'opération est terminée instantanément, ce qui provoquera des erreurs d'affichage ou des données manquantes.
