# Règle de Gestion : Répertoire des 9 Indicateurs de Recherche (Annexe M)

Ce document centralise les formules mathématiques exactes, les seuils d'alerte et les actions directes associées aux 9 indicateurs opérationnels du module `PkgTableauBord`.

---

## Matrice Récapitulative Complète

| N° | Symbole | Nom de l'Indicateur | Axe / Hypothèse | Unité | Seuil Vert (Optimal) | Seuil Rouge (Alerte) | Action Dashboard Immédiate |
| :---: | :---: | :--- | :---: | :---: | :---: | :---: | :--- |
| **01** | $T_{\text{amorçage}}$ | Temps d'amorçage moyen | H1 (Apprenant) | Heures ($h$) | $\le 1{,}5\,h$ | $> 3{,}0\,h$ | Assistance ciblée sur l'environnement de dev. |
| **02** | $R_{\text{gating}}$ | Validation gating au 1er essai | H1 (Apprenant) | % | $\ge 80\%$ | $< 65\%$ | Verrouillage du projet & ateliers de remédiation. |
| **03** | $F_{\text{révision}}$ | Boucles de révision par tâche | H1 (Apprenant) | Itérations | $< 0{,}5$ | $> 1{,}2$ | Clarification collective des critères d'acceptation. |
| **04** | $C_{\text{logistique}}$ | Charge logistique formateur | H2 (Formateur) | $h/\text{semaine}$ | $\le 2{,}5\,h$ | $> 5{,}0\,h$ | Allègement du micro-suivi via automatisation. |
| **05** | $T_{\text{file}}$ | Délai moyen en file d'attente | H2 (Formateur) | Minutes | $\le 30\,\text{min}$ | $> 60\,\text{min}$ | Basculer en session groupée (4 apprenants 4x4). |
| **06** | $T_{\text{intervention}}$ | Taux de remédiation post-Live Coding | H2 (Formateur) | % | $\ge 85\%$ | $< 60\%$ | Notification d'urgence des apprenants à $+10$ min. |
| **07** | $r$ | Corrélation intégrité IA (Pearson) | H3 (Institution) | $[-1, 1]$ | $\ge 0{,}70$ | $< 0{,}45$ | Audit des soutenances et ciblage des cas $\Delta_{\text{IA}} > 5$. |
| **08** | $\text{ICP}$ | Couverture Pédagogique Référentiel | H3 (Institution) | % | $\ge 90\%$ | $< 75\%$ | Sessions de rattrapage sur micro-compétences délaissées. |
| **09** | $\tau_{\text{conformité}}$ | Conformité conversion 65/35 | H3 (Institution) | % | $100\%$ | $< 100\%$ | Verrouillage export officiel si notes incomplètes. |

---

## Détail des Formules de Calcul

1. **Temps d'Amorçage ($T_{\text{amorçage}}$)** :
   $$T_{\text{amorçage}} = \frac{1}{N} \sum_{i=1}^{N} \left( \frac{t_{\text{première\_soumission}}(i) - t_{\text{ouverture\_sprint}}}{3600} \right)$$
2. **Taux de Gating au 1er Essai ($R_{\text{gating}}$)** :
   $$R_{\text{gating}} = \frac{N_{\text{validés\_essai\_1}}}{N_{\text{évalués}}} \times 100$$
3. **Fréquence des Révisions ($F_{\text{révision}}$)** :
   $$F_{\text{révision}} = \frac{1}{K} \sum_{k=1}^{K} C_{\text{rejets}}(k)$$
4. **Charge Logistique ($C_{\text{logistique}}$)** :
   $$C_{\text{logistique}} = \frac{1}{F \times W} \sum_{f=1}^{F} \sum_{w=1}^{W} \left( T_{\text{pilotage}}(f, w) + T_{\text{file}}(f, w) + T_{\text{bordereaux}}(f, w) \right)$$
5. **Délai de File d'Attente ($T_{\text{file}}$)** :
   $$T_{\text{file}} = \frac{1}{M} \sum_{j=1}^{M} \left( \frac{t_{\text{début\_live\_coding}}(j) - t_{\text{soumission\_prototype}}(j)}{60} \right)$$
6. **Taux de Remédiation ($T_{\text{intervention}}$)** :
   $$T_{\text{intervention}} = \frac{N_{\text{remédiations\_effectives}}}{N_{\text{besoin\_révision}(+10\,\text{min})}} \times 100$$
7. **Corrélation d'Intégrité IA (Pearson $r$)** :
   $$r = \frac{\sum_{i=1}^{N} (x_i - \bar{x})(y_i - \bar{y})}{\sqrt{\sum_{i=1}^{N} (x_i - \bar{x})^2 \sum_{i=1}^{N} (y_i - \bar{y})^2}}$$
8. **Couverture Pédagogique ($\text{ICP}$)** :
   $$\text{ICP} = \frac{\sum_{i=1}^{N} C_{\text{validées}}(i)}{N \times C_{\text{référentiel}}} \times 100$$
9. **Règle 65/35 & Conformité ($\tau_{\text{conformité}}$)** :
   $$\text{Note CC} = \frac{1}{P} \sum_{p=1}^{P} \text{Note}(N2_p) \in [0, 20]$$
   $$\text{Note EFM} = 2 \times \left( 0{,}65 \times \text{Note CC} + 0{,}35 \times \text{Note}(N3) \right) \in [0, 40]$$
   $$\tau_{\text{conformité}} = \frac{N_{\text{bordereaux sans anomalie}}}{N_{\text{total bordereaux}}} \times 100$$
