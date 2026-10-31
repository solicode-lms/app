# Mise à jour 17 - Normalisation des Codes de Tutoriels (Chapitres)

## 1. Contexte & Problème Résolu
Plusieurs tutoriels (chapitres) possédaient un code et une référence malformés à 4 parties avec des points superflus (ex. `T.152.21.1` au lieu de `T.152.211`).
Le script `deploy/imports/sessions/fix_tuto_codes.php` normalise l'ensemble de ces codes en format canonique strict à 3 parties (`T . {module} . {ua_et_ordre}`), tout en préservant 100 % des réalisations apprenants et des tâches rattachées via `chapitre_id`.

---

## 2. Procédure d'Exécution

### En local (Windows)
```bash
# 1. Simulation en lecture seule (optionnel)
php deploy/imports/sessions/fix_tuto_codes.php --dry-run

# 2. Application de la normalisation en BDD
php deploy/imports/sessions/fix_tuto_codes.php --apply
```

### En production (Serveur Linux)
```bash
# 1. Récupération des dernières modifications
git pull origin develop

# 2. Exécution du script de normalisation
sudo php deploy/imports/sessions/fix_tuto_codes.php --apply
```

---

## 3. Contrôle Qualité Post-Déploiement
Vérifier qu'aucun code malformé à plus de 3 parties ne subsiste en base de données :

```bash
# En local (Windows) :
php deploy/imports/sessions/fix_tuto_codes.php --dry-run

# En production (Serveur Linux) :
sudo php deploy/imports/sessions/fix_tuto_codes.php --dry-run
```

*Résultat attendu : « Aucun chapitre malformé trouvé en base de données. Tout est conforme ! »*
