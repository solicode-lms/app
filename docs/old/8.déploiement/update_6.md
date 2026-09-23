# Mise à jour 6 : Barème par défaut (Live coding & Réalisation)

**Problème :** 
Les tâches "Prototype Live coding" et "Réalisation" prenaient un barème de `0` si l'Unité d'Apprentissage n'avait pas de critères définis.

**Solution :**
- Pré-remplissage de barèmes par défaut (`4` pour Live coding, `2` pour Réalisation) dès la mobilisation de l'UA (`MobilisationUaDataCalculTrait` et `MobilisationUaCrudTrait`).
- Le calcul de la note pour ces tâches se base désormais sur la mobilisation, et garantit un minimum de `4` et `2` même sans UA (`TacheCrudTrait`).

**Correction des anciennes données :**
Exécutez le script ci-dessous pour mettre à jour les tâches et mobilisations existantes.

*Windows (Local)* :
```bash
php artisan tinker storage\update_baremes.php
```

*Ubuntu (Serveur)* :
```bash
php artisan tinker storage/update_baremes.php
```
