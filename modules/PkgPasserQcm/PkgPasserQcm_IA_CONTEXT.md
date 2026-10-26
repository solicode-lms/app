# Contexte IA du Module : PkgPasserQcm

## 1. Responsabilité Métier
Le package **PkgPasserQcm** gère l'interface et l'expérience utilisateur côté apprenant pour le déroulement des examens QCM en ligne :
1. **Démarrage & Timer :** Contrôle du compte à rebours, enregistrement du timestamp de début et calcul du temps restant.
2. **Navigation entre Questions :** Affichage pas-à-pas des questions, gestion des brouillons et soumission temporaire.
3. **Clôture & Soumission Finale :** Calcul de la note finale et transition d'état vers "Terminé".

---

## 2. Particularités Frontend
- Interface interactive sans rafraîchissement global avec gestion de compte à rebours JavaScript.
- Enregistrement AJAX des choix en temps réel pour prévenir les pertes en cas de coupure réseau.
