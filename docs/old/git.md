Pour que Git ignore les changements des fichiers si leur contenu ne change pas, mais seulement leurs métadonnées (comme les permissions ou les timestamps), vous pouvez configurer Git pour ignorer ces types de modifications. Voici les étapes à suivre :

### 1. **Configurer `core.fileMode` pour ignorer les changements de permissions**
Si vous travaillez sur un système où les permissions des fichiers changent fréquemment, configurez Git pour ne pas suivre ces changements :

```bash
git config core.fileMode false
```

Cette commande désactive la détection des changements liés aux permissions des fichiers dans le dépôt.

---

### 2. **Utiliser `.gitignore` pour ignorer certains fichiers ou dossiers**
Si vous voulez qu'un fichier ou un dossier spécifique soit totalement ignoré (même les modifications), ajoutez-les au fichier `.gitignore` :

1. Ouvrez ou créez un fichier `.gitignore` à la racine du dépôt.
2. Ajoutez les chemins des fichiers ou dossiers à ignorer.

Exemple :
```
# Ignorer un fichier spécifique
path/to/file

# Ignorer tous les fichiers d'un type particulier
*.log
```

---

### 3. **Nettoyer le cache Git des fichiers déjà suivis**
Si des fichiers sont déjà suivis mais ne doivent plus l'être, supprimez-les de l'index sans les effacer physiquement :

```bash
git rm --cached path/to/file
```

---

### 4. **Configurer `core.ignoreStat` pour ignorer les changements légers**
Git détecte automatiquement les changements dans les fichiers en vérifiant leurs timestamps. Pour désactiver ce comportement et limiter les vérifications aux différences de contenu :

```bash
git config core.ignoreStat true
```

---

### 5. **Utiliser des scripts pour une vérification avancée**
Si vous devez implémenter un mécanisme pour ignorer des changements basés sur une logique spécifique, vous pouvez automatiser cela avec un script avant chaque commit.

Exemple d'utilisation de `git diff` pour vérifier les changements réels :

```bash
git diff --name-only | xargs -I {} sh -c 'diff -q {} <(git show HEAD:{}) || true'
```

Ce script compare chaque fichier modifié avec sa version dans le dernier commit pour détecter les changements réels.

---

Avec ces configurations, Git ignorera efficacement les modifications qui ne concernent pas le contenu des fichiers. Vous pouvez tester et ajuster ces options selon vos besoins spécifiques.















Le problème que vous rencontrez provient du fait que des modifications ont été effectuées sur des fichiers sur le serveur, probablement via des commandes comme `sudo chmod`, qui ont modifié les permissions ou d'autres attributs des fichiers. Ces modifications ont provoqué des conflits dans Git lors du rebasage interactif, car Git considère ces changements comme des altérations des fichiers en cours de suivi.

### Pourquoi le problème se produit-il ?
1. **Changements involontaires des fichiers suivis par Git :** Lorsque vous utilisez des commandes comme `chmod`, même si vous ne modifiez pas directement le contenu des fichiers, Git détecte des changements si les permissions des fichiers sont différentes de celles enregistrées dans le référentiel.
2. **Conflits pendant le rebasage :** Pendant un rebasage, Git applique chaque commit de manière séquentielle. Si un fichier a été modifié localement ou a des différences non résolues, cela peut provoquer un conflit.

---

### Solutions possibles

#### 1. **Éviter que les permissions des fichiers soient modifiées**
   - Configurez Git pour ignorer les changements de permissions en ajoutant cette configuration :
     ```bash
     git config core.fileMode false
     ```
     Cela empêche Git de suivre les modifications de permissions, réduisant ainsi les risques de conflits dus à des changements effectués par `chmod`.

#### 2. **Résoudre les conflits existants**
   Pour résoudre les conflits actuels :
   - Identifiez les fichiers marqués comme "non fusionnés" dans la sortie de Git.
     Exemple : `supprimé par nous : docs/1.Installation_Ubuntu.md`
   - Si vous voulez conserver les modifications locales, ajoutez-les à l'index après avoir corrigé les conflits :
     ```bash
     git add <fichier>
     git rebase --continue
     ```
   - Si vous souhaitez annuler les modifications locales :
     ```bash
     git restore <fichier>
     git rebase --continue
     ```

#### 3. **Interdire les modifications non intentionnelles sur le serveur**
   Si les changements sur le serveur ne doivent pas se produire :
   - Vérifiez les permissions actuelles des fichiers avec :
     ```bash
     ls -l <fichiers_concernés>
     ```
   - Réinitialisez-les pour éviter que les commandes exécutées avec `sudo` les modifient inutilement.

#### 4. **Réinitialiser les fichiers modifiés par `chmod`**
   Si les modifications effectuées ne sont pas souhaitées, vous pouvez réinitialiser les fichiers concernés à leur état précédent :
   ```bash
   git checkout -- <fichier>
   ```

---

### Workflow pour un serveur propre
1. Configurez des hooks Git pour protéger les fichiers sensibles ou éviter des changements accidentels.
2. Si des permissions spécifiques sont nécessaires pour des scripts ou fichiers, utilisez des commandes spécifiques dans des fichiers `.sh` ou `.ps1` pour les appliquer localement sans les inclure dans le suivi Git.

Avec ces approches, vous pourrez résoudre le problème actuel et éviter qu'il ne se reproduise.