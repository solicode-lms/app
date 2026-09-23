### 1. Installer et Configurer SSH sur Ubuntu
#### Installer OpenSSH Server
1. Connectez-vous à votre serveur Ubuntu (localement ou via une autre méthode, comme une console cloud).
2. Installez le serveur SSH :
   ```bash
   sudo apt update
   sudo apt install openssh-server -y
   ```

#### Vérifier le Statut du Service SSH
1. Assurez-vous que le service SSH est actif :
   ```bash
   sudo systemctl status ssh
   ```
   Vous devriez voir un message indiquant que le service est **active (running)**.

#### (Optionnel) Configurer SSH
Pour renforcer la sécurité ou ajuster les paramètres :
1. Ouvrez le fichier de configuration SSH :
   ```bash
   sudo nano /etc/ssh/sshd_config
   ```
2. Modifiez les options selon vos besoins, par exemple :
   - **Changer le port par défaut (facultatif)** :
     ```text
     Port 22
     ```
   - **Autoriser la connexion root (désactivée par défaut pour des raisons de sécurité)** :
     ```text
     PermitRootLogin yes
     ```
   - **Restreindre les utilisateurs autorisés (recommandé)** :
     ```text
     AllowUsers utilisateur1
     ```

3. Sauvegardez les modifications et redémarrez SSH :
   ```bash
   sudo systemctl restart ssh
   ```

---

### 2. Configurer le Pare-feu (UFW)
Si votre pare-feu (UFW) est activé, vous devez autoriser le trafic SSH.

1. Vérifiez l’état du pare-feu :
   ```bash
   sudo ufw status
   ```

2. Autorisez le port SSH (par défaut 22) :
   ```bash
   sudo ufw allow OpenSSH
   ```

3. Rechargez le pare-feu pour appliquer les modifications :
   ```bash
   sudo ufw reload
   ```

---

### 3. Assurer la Connectivité Réseau
1. Assurez-vous que votre serveur est accessible via son adresse IP publique ou privée.
2. Testez la connectivité avec la commande :
   ```bash
   ping <adresse-IP-du-serveur>
   ```

---

### 4. Configurer les Clés SSH (Optionnel mais recommandé)
Pour améliorer la sécurité, utilisez des clés SSH au lieu d’un mot de passe.
1. **Générer une clé SSH** sur votre machine locale (Windows, macOS, Linux) :
   ```bash
   ssh-keygen
   ```
2. **Copier la clé publique sur le serveur Ubuntu** :
   Utilisez une commande comme :
   ```bash
   ssh-copy-id utilisateur@adresse-IP
   ```
   Si `ssh-copy-id` n'est pas disponible, ajoutez manuellement la clé au fichier `/home/utilisateur/.ssh/authorized_keys`.

---

### 5. Tester la Connexion avec PuTTY
1. Lancez PuTTY et entrez l'adresse IP du serveur Ubuntu.
2. Vérifiez que le port SSH (22) est ouvert.
3. Connectez-vous en utilisant les informations d'identification (ou clé SSH).

---

Si SSH est bien configuré et que le port est ouvert, Ubuntu acceptera la connexion via PuTTY sans problème.