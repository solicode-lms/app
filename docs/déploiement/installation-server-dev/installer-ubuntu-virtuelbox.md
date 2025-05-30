# Authentification 

login : solicode
password : admin



### **1. Télécharger les outils nécessaires**
- **VirtualBox** : Téléchargez-le et installez-le à partir de [VirtualBox](https://www.virtualbox.org/).
- **Ubuntu ISO** : Téléchargez la version serveur ou desktop à partir de [Ubuntu](https://ubuntu.com/download).

---

### **2. Créer une machine virtuelle**
1. **Ouvrir VirtualBox** et cliquer sur "Nouveau".
2. **Configurer la VM** :
   - **Nom** : `Ubuntu_Laravel_Server` (par exemple).
   - **Type** : Linux.
   - **Version** : Ubuntu (64-bit).
3. **Mémoire** : Allouez au moins 2 Go (2048 Mo) de RAM.
4. **Disque dur** :
   - Créez un disque dur virtuel (VDI).
   - Taille dynamique (minimum 20 Go).

5. **Démarrer la VM** et sélectionner l'ISO d'Ubuntu pour lancer l’installation.

---

### **3. Installer Ubuntu**
1. Suivez les étapes d'installation :
   - Sélectionnez le langage et le fuseau horaire.
   - Configurez le clavier.
   - Créez un utilisateur avec des droits sudo (ex. : `fouad`).
   - Configurez une connexion réseau (par câble ou NAT dans VirtualBox).
   - Installez les paquets recommandés, mais assurez-vous d'avoir **OpenSSH Server** pour permettre une gestion à distance.

2. Une fois terminé, redémarrez la VM.




Pour copier et coller du texte ou des fichiers entre **Windows** (hôte) et une machine virtuelle sur **VirtualBox**, voici comment configurer correctement la machine virtuelle :

---

### **1. Installer les Additions Invité (Guest Additions)**

Les Additions Invité permettent d’activer des fonctionnalités avancées comme le copier-coller, le glisser-déposer, et la synchronisation du presse-papiers entre la machine hôte et la VM.

#### Étapes :
1. **Démarrez votre machine virtuelle dans VirtualBox.**
2. Dans le menu en haut de VirtualBox, cliquez sur **Périphériques** > **Insérer l'image CD des Additions Invité**.
3. Si rien ne se passe :
   - Montez manuellement l'image CD dans la VM :
     ```bash
     sudo mount /dev/cdrom /mnt
     ```
   - Ensuite, exécutez le script d'installation des Additions Invité :
     ```bash
     sudo /mnt/VBoxLinuxAdditions.run
     ```
4. Redémarrez la machine virtuelle après l’installation.

---

### **2. Configurer le partage du presse-papiers**
Pour activer le copier-coller entre Windows et la VM :
1. Dans VirtualBox, sélectionnez votre machine virtuelle **éteinte**.
2. Cliquez sur **Configuration** > **Avancé**.
3. Activez les options suivantes :
   - **Presse-papiers partagé** : choisissez **Bidirectionnel**.
   - **Glisser-déposer** : choisissez également **Bidirectionnel** (facultatif pour les fichiers).

---

### **3. Copier-coller du texte**
1. **Depuis Windows vers la VM** :
   - Copiez le texte dans Windows (Ctrl + C).
   - Collez-le dans la VM (Ctrl + Shift + V dans un terminal ou Ctrl + V dans d’autres applications).
2. **Depuis la VM vers Windows** :
   - Copiez le texte dans la VM (Ctrl + Shift + C dans un terminal ou Ctrl + C dans d’autres applications).
   - Collez-le dans Windows (Ctrl + V).

---

### **4. Partage de fichiers entre Windows et la VM**

#### **Option 1 : Dossier partagé**
1. Dans VirtualBox, ouvrez les paramètres de votre VM.
2. Allez dans **Dossiers partagés** et cliquez sur l'icône **+** pour ajouter un dossier partagé.
3. Choisissez :
   - **Chemin du dossier** : sélectionnez un dossier sur votre machine Windows.
   - **Nom du dossier** : donnez-lui un nom (ex. : `shared_folder`).
   - Activez **Montage automatique** et **Rendre permanent**.
4. Démarrez votre VM.
5. Le dossier partagé sera accessible dans `/media/sf_shared_folder`.

   Si vous ne voyez pas le dossier, ajoutez votre utilisateur au groupe `vboxsf` :
   ```bash
   sudo usermod -aG vboxsf $USER
   ```

   Redémarrez la VM après cette commande.

#### **Option 2 : Glisser-déposer**
- Si le glisser-déposer est activé (voir étape 2), vous pouvez simplement glisser un fichier depuis Windows vers la VM ou vice versa.

---

### **5. Résolution des problèmes**
Si le copier-coller ou le partage de fichiers ne fonctionne pas :
1. Assurez-vous que les Additions Invité sont correctement installées.
2. Vérifiez que le **presse-papiers partagé** et/ou **glisser-déposer** sont activés dans les paramètres de la VM.
3. Redémarrez la machine virtuelle après chaque modification des paramètres.

---

Si tu rencontres un problème spécifique, Fouad, dis-le-moi et je t'aiderai à le résoudre ! 😊



