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