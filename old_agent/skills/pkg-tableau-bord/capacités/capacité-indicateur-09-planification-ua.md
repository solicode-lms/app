# Capacité : Indicateur 09 — Planification des UA ($\text{TCP-UA}$)

## 1. Fiche Synthétique

| Paramètre | Spécification Didactique & Technique |
| :--- | :--- |
| **Numéro & Symbole** | **Indicateur 09** — $\text{TCP-UA}$ |
| **Nom Officiel** | Taux de Couverture de Planification Évaluative des UA par SessionFormation |
| **Axe Pédagogique** | H4 — Anticipation prévisionnelle de la production des notes N2 de fin d'année |
| **Sources de Données** | `unite_apprentissages`, `session_formations`, `alignement_uas` |
| **Règle d'Unicité** | **Chaque UA est comptabilisée une seule fois, même si multi-sessions** |
| **Unité & Bornes** | Pourcentage ($0 \le \text{TCP-UA} \le 100\%$) |
| **Objectif Institutionnel** | Cible $\ge 80\,\%$ des UA obligatoires planifiées dans les sessions |
| **Service / Trait** | `Modules\PkgTableauBord\Services\Traits\DashboardIndicateur09Trait` |
| **Route & Page** | `admin.PkgTableauBord.indicateurs.tcp-ua` (`indicateurs/tcp-ua.blade.php`) |
| **Composants KPI** | `PkgTableauBord::components.kpi-docimologie`, `PkgTableauBord::components.bordereau-officiel-panel` |

---

## 2. Modélisation Didactique & Anticipation des Notes

### A. Rôle Préventif en Amont
- Pour que les notes du Contrôle Continu ($N2$) soient disponibles en fin d'année, chaque Unité d'Apprentissage (UA) obligatoire du programme doit faire l'objet d'une planification au sein d'une `SessionFormation`.
- Cet indicateur intervient **en amont** : il repère les lacunes dans la planification avant qu'elles ne se transforment en blocages administratifs ou en notes manquantes.

### B. Périmètre et Règle d'Unicité (CRITIQUE)
- **$N_{\text{attendues}}$** : Total des Unités d'Apprentissage obligatoires du programme de la filière (ex : 53 UA pour DWB, 23 UA pour DMB, 76 UA établissement).
- **$N_{\text{couvertes}}$** : Nombre d'UA distinctes ayant au moins une association dans `alignement_uas` avec une `SessionFormation` active de la filière.
- **Unicité stricte** : Si une même UA est mobilisée sur plusieurs sessions de formation successives, elle n'est **comptabilisée qu'une seule fois** (`COUNT(DISTINCT unite_apprentissage_id)`).
- **$N_{\text{manquantes}}$** : $N_{\text{attendues}} - N_{\text{couvertes}}$ (Unités d'Apprentissage orphelines sans session planifiée à inscrire au calendrier).

---

## 3. Formules Mathématiques

### Taux de Couverture de Planification des UA :
$$\text{TCP-UA} = \frac{N_{\text{couvertes}}}{N_{\text{attendues}}} \times 100$$

### Écart à la Cible Institutionnelle :
$$\Delta_{\text{cible}} = \text{TCP-UA} - 80{,}0\%$$

---

## 4. Composants & Ergonomie 100% Inline

- **Carte KPI** (`PkgTableauBord::components.kpi-docimologie`) :
  - Apprenant : Couverture de son programme ($45.3\%$), ratio chiffré $24/53\text{ UA}$, sessions actives.
  - Formateur : Taux TCP-UA, nombre d'UA couvertes, volume d'UA non planifiées et lien direct vers le détail.
  - Responsable : Taux macroscopique établissement ($55.3\%$) et volume total de sessions.
- **Panneau Formateur** (`PkgTableauBord::components.bordereau-officiel-panel`) :
  - Synthèse des sessions déployées et aperçu des UA en attente de planification avec bouton d'accès direct à la matrice.
- **Page de Détail** (`admin.PkgTableauBord.indicateurs.tcp-ua`) :
  - 4 cartes KPI directes (Taux TCP-UA, UA Couvertes, UA Non Planifiées, Sessions Déployées).
  - Navigation par 4 onglets interactifs sous Alpine.js :
    1. **Matrice Complète des UA** : Table filtrable de l'ensemble des UA avec recherche textuelle instantanée, badge d'alignement et sessions associées.
    2. **Sessions & UA Mobilisées** : Fiches des sessions de formation avec projets liés et UA mobilisées.
    3. **Diagnostic par Module & Alertes** : Jauges de couverture par module et liste dépliable des UA manquantes avec leur compétence.
    4. **Benchmark Macro Filières** : Tableau comparatif inter-filières (DWB $45.3\%$ vs DMB $78.3\%$).
