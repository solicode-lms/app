## Annexe M — Indicateurs opérationnels de recherche et modélisation mathématique

**Introduction**

L'évaluation empirique des effets du Framework Méthodologique et de la plateforme Active-LMS repose sur la quantification d'indicateurs de terrain rigoureusement définis. La **Section 8.3 du Chapitre 8** a introduit ces variables pour opérationnaliser les trois hypothèses de recherche (H1 : axe apprenant, H2 : axe formateur, H3 : axe institutionnel).

Cette annexe constitue le répertoire méthodologique de référence de ces indicateurs. Pour chaque variable, elle formalise l'ancrage théorique, la définition conceptuelle, le mode de calcul depuis les données d'Active-LMS, la modélisation mathématique exacte, l'interprétation didactique et la finalité formative. Conformément aux exigences de rigueur méthodologique, cette annexe est consacrée à la spécification des instruments de mesure ; la présentation des données empiriques observées et leur discussion sont traitées dans le **Chapitre 9**.

---

**1. Indicateurs de l'axe apprenant (Hypothèse 1 — Allègement mental et progression)**

**A. Temps d'amorçage moyen ($T_{\text{amorçage}}$)**

* **Définition et rôle :** Mesure le temps écoulé entre l'ouverture d'un sprint et la première soumission de code fonctionnel. Il évalue la réduction du syndrome de la page blanche et la rapidité de mise au travail (Hypothèse 1 : axe apprenant).
* **Périmètre didactique (Tutoriel vs Projet) :** La mesure s'effectue sur le premier tutoriel (Niveau 1 — *Imiter*). L'apprenant y écrit et exécute du code réel dans son éditeur, mais sous guidage pas à pas. L'indicateur évalue cette mise en action immédiate sans attendre la phase de projet (*Transposer*), située en fin de sprint.
* **Calcul depuis les données d'Active-LMS :**
  Active-LMS enregistre l'horodatage d'activation du sprint par le formateur et l'horodatage où chaque apprenant soumet son premier livrable de tutoriel vers la colonne *En validation*. Le système calcule la différence temporelle individuelle en heures décimales, puis en extrait la moyenne arithmétique pour l'ensemble de la cohorte.
* **Formule mathématique :**
  $$T_{\text{amorçage}} = \frac{1}{N} \sum_{i=1}^{N} \left( t_{\text{première\_soumission}}(i) - t_{\text{ouverture\_sprint}} \right)$$
  - $N$ : effectif total des apprenants.
  - $t_{\text{ouverture\_sprint}}$ : horodatage d'ouverture du sprint.
  - $t_{\text{première\_soumission}}(i)$ : horodatage de la première tâche soumise au statut *En validation* par le stagiaire $i$.
  - Unité : heures décimales ($h$).
* **Interprétation didactique :**
  - **Délai court :** Témoigne d'une entrée fluide dans la tâche. L'apprenant s'approprie immédiatement les consignes, configure son environnement sans blocage et démarre la production de code sans inhibition.
  - **Délai prolongé :** Révèle une saturation cognitive initiale face au sujet ou à l'outillage. Il signale une hésitation structurelle qui risque de compromettre le rythme de l'ensemble du sprint.
* **Ancrage et justification théorique :** Face à un problème ouvert non découpé, l'apprenant novice sature sa mémoire de travail avant d'écrire la moindre ligne (Sweller, 1988). L'obligation de débuter par un tutoriel applique le principe de modélisation (*modeling*, Collins, Brown & Newman, 1989). Ce modèle immédiatement fonctionnel allège la charge extrinsèque, dissipe l'anxiété face à l'éditeur vide et déclenche une dynamique d'écriture rapide.

**B. Taux de validation au gating au premier essai ($R_{\text{gating}}$)**

* **Définition et rôle :** Mesure la proportion d'apprenants réussissant l'épreuve pratique individuelle du prototype dès leur première tentative. Il évalue l'assimilation robuste des compétences fondamentales avant le passage au projet (Hypothèse 1 : axe apprenant).
* **Modalité d'évaluation :** La décision est binaire (`validé` / `non validé`), souveraine et attribuée par le formateur lors d'une session de Live Coding individuel de 40 minutes sur le prototype (Niveau 2 — *Adapter*). Elle sanctionne l'autonomie d'exécution et de modification du code plutôt qu'une récitation théorique. En cas d'ajournement initial, le stagiaire bénéficie d'un étayage de remédiation ciblé (reprise guidée de tutoriels spécifiques) et se voit offrir une seconde tentative d'évaluation avant la clôture du sprint.
* **Calcul depuis les données d'Active-LMS :**
  À la fin de chaque session de Live Coding sur prototype, Active-LMS enregistre la décision souveraine du formateur (`validé` ou `non validé`) et le rang de tentative (`essai = 1`). Le système filtre les évaluations pour isoler les apprenants validés dès leur premier passage et calcule le ratio par rapport au nombre total de stagiaires présentés à l'épreuve.
* **Formule mathématique :**
  $$R_{\text{gating}} = \frac{N_{\text{validés\_essai\_1}}}{N_{\text{évalués}}} \times 100$$
  - $N_{\text{validés\_essai\_1}}$ : nombre d'apprenants validés dès la première tentative.
  - $N_{\text{évalués}}$ : effectif total des apprenants s'étant présentés à l'épreuve.
  - Unité : pourcentage ($0 \le R_{\text{gating}} \le 100\,\%$).
* **Interprétation didactique :**
  - **Taux élevé :** Atteste que l'étayage préalable (tutoriels Niveau 1) a été assimilé avec succès. Les stagiaires maîtrisent les fondations techniques et peuvent affronter la complexité fonctionnelle du projet (*Transposer*) sans lacunes bloquantes.
  - **Taux faible :** Révèle une illusion d'acquis lors de la phase d'imitation. Il signale au formateur que le guidage initial n'a pas suffi à construire l'autonomie, imposant d'enclencher les ateliers de remédiation par les pairs (trinômes de soutien) et la reprise ciblée de tutoriels avant d'autoriser l'accès au projet lors d'une seconde tentative.
* **Ancrage et justification théorique :** Dans un projet global sans étanchéité, les lacunes techniques s'accumulent silencieusement. Le gating sur prototype sépare la validation technique de la complexité métier du projet global (Roques & Vallée, 2004). Selon la pédagogie de maîtrise (Bloom, 1968) et le modèle Stage-Gate (Cooper, 1990), la validation conditionnelle des prérequis évite les échecs tardifs, élimine les illusions d'acquis et offre une seconde chance structurée aux apprenants fragiles.

**C. Fréquence moyenne des boucles de révision ($F_{\text{révision}}$)**

* **Définition et rôle :** Mesure le nombre moyen d'itérations d'ajustement nécessaires pour qu'un livrable atteigne les exigences attendues. Il évalue la clarté des consignes et la qualité initiale du code soumis (Hypothèse 1 : axe apprenant).
* **Modalité de suivi :** L'indicateur comptabilise chaque renvoi au statut *Révision nécessaire* par le formateur sur la fiche Kanban avant la validation définitive.
* **Calcul depuis les données d'Active-LMS :**
  Chaque transition d'une tâche vers l'état *Révision nécessaire* génère un événement horodaté dans le journal d'activité d'Active-LMS. En fin de module, le système agrège l'ensemble de ces rejets formateurs pour chaque Unité d'Apprentissage et divise ce volume total par le nombre de tâches évaluées.
* **Formule mathématique :**
  $$F_{\text{révision}} = \frac{1}{K} \sum_{k=1}^{K} C_{\text{rejets}}(k)$$
  - $K$ : nombre total de tâches évaluées sur le module.
  - $C_{\text{rejets}}(k)$ : nombre de transitions vers l'état *Révision nécessaire* pour la tâche $k$.
  - Unité : nombre moyen d'itérations ($F_{\text{révision}} \ge 0$).
* **Interprétation didactique :**
  - **Fréquence faible (proche de 1) :** Indique une compréhension claire des critères d'acceptation dès la première soumission. L'apprenant produit un code conforme et structuré sans dispersion d'attention.
  - **Fréquence élevée :** Traduit une démarche tâtonnante par essais et erreurs. Elle signale un déficit de guidage en amont, des consignes ambiguës ou une tendance à soumettre des livrables inachevés.
* **Ancrage et justification théorique :** Des révisions excessives traduisent des consignes imprécises qui forcent l'apprenant à tâtonner, saturant sa mémoire de travail (Sweller, 1988). Selon l'alignement constructif (Biggs, 1996), des critères d'acceptation explicites associés à un prototypage préalable ciblent l'effort de production et réduisent les cycles de reprise.

---

**2. Indicateurs de l'axe formateur (Hypothèse 2 — Gain de temps et remédiation ciblée)**

**A. Charge logistique hebdomadaire du formateur ($C_{\text{logistique}}$)**

* **Définition et rôle :** Mesure le volume horaire hebdomadaire accaparé par les tâches administratives et le micro-suivi individuel. Il évalue le temps libéré pour le tutorat direct et la médiation technique (Hypothèse 2 : axe formateur).
* **Périmètre des activités logistiques :** Comprend la consultation du statut des tâches, la gestion de la file d'attente, le traitement des alertes de blocage et l'édition des bordereaux de notes.
* **Calcul depuis les données d'Active-LMS :**
  Active-LMS mesure ce temps de façon continue via les traces d'activité des comptes formateurs. Le système cumule la durée des interactions actives sur les interfaces de pilotage (tableau de bord de classe, console de régulation des passages et module d'export docimologique). Les sessions de Live Coding de 40 minutes sont automatiquement exclues de ce calcul, car elles relèvent de l'évaluation formative directe. Un verrou d'inactivité met en pause le décompte après cinq minutes sans action pour éviter toute surévaluation.
* **Formule mathématique :**
  $$C_{\text{logistique}} = \frac{1}{F \times W} \sum_{f=1}^{F} \sum_{w=1}^{W} \left( T_{\text{pilotage}}(f, w) + T_{\text{file}}(f, w) + T_{\text{bordereaux}}(f, w) \right)$$
  - $F$ : nombre de formateurs suivis.
  - $W$ : nombre total de semaines de formation.
  - $T_{\text{pilotage}}(f, w)$ : temps hebdomadaire passé sur le tableau de bord Kanban de suivi.
  - $T_{\text{file}}(f, w)$ : temps hebdomadaire dédié à l'ordonnancement de la file de passage.
  - $T_{\text{bordereaux}}(f, w)$ : temps hebdomadaire de vérification et d'export des notes.
  - Unité : heures par semaine ($h/\text{semaine}$).
* **Interprétation didactique :**
  - **Charge allégée :** Démontre que l'automatisation logicielle prend efficacement en charge le pointage, le tri des tâches et les calculs administratifs. Le formateur consacre l'essentiel de son temps au tutorat direct, à la revue de code et à la remédiation.
  - **Charge élevée :** Signale une dépendance excessive aux relances manuelles et à la saisie de bordereaux, réduisant le temps disponible pour l'accompagnement pédagogique des stagiaires fragiles.
* **Ancrage et justification théorique :** En gestion manuelle, le suivi dispersé d'une cohorte autonome sature le formateur de micro-tâches répétitives. L'exploitation des traces numériques (Siemens & Long, 2011) automatise la détection des retards et le reporting. Ce temps administratif épargné permet au tuteur de se recentrer sur sa mission pédagogique d'étayage cognitif et de modélisation de solutions (*modeling*, Collins, Brown & Newman, 1989).

**B. Délai moyen de traitement de la file d'attente ($T_{\text{file}}$)**

* **Définition et rôle :** Mesure le temps d'attente moyen d'un apprenant entre la soumission de son prototype technique et le début effectif de son évaluation en Live Coding par le formateur. Cet indicateur s'applique **exclusivement au Live Coding de Prototype (gating synchrone)** et non aux tâches de tutoriels (Hypothèse 2 : axe formateur).
* **Périmètre didactique et distinction des tâches :**
  - *Tutoriels (Niveau 1 — Imiter, validation asynchrone) :* L'apprenant dépose ses exercices sur le Kanban. Le formateur valide ces livrables au fil de l'eau de manière asynchrone, sans passage oral bloquant. Dès que 50 % de la promotion valide ses tutoriels, le système déclenche une session plénière de *Peer-Learning* au tableau pour l'ensemble du groupe.
  - *Prototype (Niveau 2 — Adapter, file synchrone bloquante) :* L'apprenant soumet son prototype au statut *En validation*, ce qui l'inscrit dans la file d'attente synchrone du Live Coding. Cette validation conditionne l'accès au projet final (*Transposition*).
* **Modalités d'évaluation et capacité de traitement :**
  - Le formateur évalue les prototypes en direct, soit individuellement (1 par 1), soit par micro-groupes (jusqu'à 4 apprenants simultanés via un outil de partage d'écran).
  - Chaque créneau mobilisant entre 40 et 60 minutes, une journée dédiée permet de réaliser environ 5 créneaux d'évaluation (soit 5 heures d'évaluation directe par jour). L'indicateur $T_{\text{file}}$ mesure la capacité du dispositif à absorber ce flux sans créer d'attente préjudiciable.
* **Calcul depuis les données d'Active-LMS :**
  Active-LMS enregistre l'horodatage où la tâche Prototype bascule au statut *En validation* dans la file d'attente FIFO ($t_{\text{soumission\_prototype}}$). Dès que le formateur démarre l'évaluation en direct, le système consigne l'horodatage exact de prise en charge ($t_{\text{début\_évaluation\_live\_coding}}$). Il calcule l'écart individuel en minutes, puis en extrait la moyenne arithmétique pour l'ensemble des passages de la cohorte.
* **Formule mathématique :**
  $$T_{\text{file}} = \frac{1}{M} \sum_{j=1}^{M} \left( t_{\text{début\_évaluation\_live\_coding}}(j) - t_{\text{soumission\_prototype}}(j) \right)$$
  - $M$ : nombre total de sessions de Live Coding de Prototype conduites.
  - $t_{\text{soumission\_prototype}}(j)$ : horodatage d'arrivée du prototype $j$ au statut *En validation* dans la file d'attente FIFO.
  - $t_{\text{début\_évaluation\_live\_coding}}(j)$ : horodatage de début effectif de l'évaluation en Live Coding par le formateur.
  - Unité : minutes ($\text{min}$).
* **Interprétation didactique :**
  - **Délai maîtrisé :** Démontre un dimensionnement adapté de la cadence d'évaluation. L'ordonnancement FIFO et le recours aux sessions mutualisées (4 par 4 avec partage d'écran) résorbent la file d'attente sans générer d'inactivité chez les apprenants.
  - **Délai excessif :** Révèle un engorgement face à la contrainte des 5 créneaux journaliers. Il indique une saturation ponctuelle du formateur imposant d'augmenter la fréquence des sessions groupées ou d'intensifier le soutien préparatoire par les pairs.
* **Ancrage et justification théorique :** Sans file d'attente régulée, les sollicitations directes désorganisent le formateur et pénalisent les profils réservés. L'ordonnancement d'un flux FIFO instrumenté par les traces numériques (Siemens & Long, 2011) garantit une stricte équité de traitement. L'apprenant poursuit ses activités en autonomie et le formateur assure un étayage serein au sein de la Zone de Développement Proximal (Wood, Bruner & Ross, 1976 ; Vygotski, 1978).

**C. Taux d'intervention et de remédiation ciblée ($T_{\text{intervention}}$)**

* **Définition et rôle :** Mesure la proportion d'apprenants en difficulté persistante après le Live Coding collaboratif qui bénéficient d'un étayage direct ou d'une remédiation par le formateur. Il évalue l'efficacité du relais entre l'entraide par les pairs et l'intervention experte du formateur (Hypothèse 2 : axe formateur).
* **Déclencheur didactique (Le seuil des 10 minutes post-Live Coding) :**
  - *Filtrage rigoureux de l'assiduité :* Le calcul s'applique exclusivement aux apprenants physiquement présents ($N_{\text{présents}}$), le pointage quotidien sur Active-LMS écartant tout faux positif lié aux absences.
  - *Séance collective de Peer-Learning (Seuil des 50 %) :* Dès que la moitié des stagiaires présents valide ses tutoriels, un pair anime une session de Live Coding au tableau pour expliciter le code et débloquer ses camarades.
  - *Fenêtre d'application et seuil de révision (10 minutes) :* À l'issue de cette présentation, une fenêtre d'autonomie de 10 minutes ($t_{\text{fin\_live\_coding}} + 10\,\text{min}$) est accordée pour finaliser le livrable. Tout apprenant présent n'ayant toujours pas soumis sa tâche à l'expiration de ce délai prouve que l'explication collective n'a pas suffi : sa tâche bascule automatiquement au statut *Besoin de révision*.
* **Modalités d'action du formateur :**
  - Le formateur intervient immédiatement sur cette minorité ciblée pour diagnostiquer la cause du blocage (environnement technique, incompréhension algorithmique) et dispenser une remédiation individualisée ou en trinôme de soutien.
  - En aval, ce dispositif englobe également l'accompagnement des apprenants ajournés lors du Live Coding Prototype (Niveau 2) pour leur permettre de se représenter avant la clôture du sprint.
* **Calcul depuis les données d'Active-LMS :**
  Active-LMS horodate la fin de la session collective de Live Coding ($t_{\text{fin\_live\_coding}}$). À l'échéance de la fenêtre de 10 minutes ($t_{\text{seuil}} = t_{\text{fin\_live\_coding}} + 10\,\text{min}$), le système recense les apprenants présents dont la tâche de tutoriel n'est pas encore soumise ($N_{\text{besoin\_révision}}$). Le système vérifie ensuite combien parmi eux ont fait l'objet d'une fiche de remédiation consignée dans le journal d'activité et en extrait le ratio de conversion.
* **Formule mathématique :**
  $$T_{\text{intervention}} = \frac{N_{\text{remédiations\_effectives}}}{N_{\text{besoin\_révision}(+10\,\text{min})}} \times 100$$
  - $N_{\text{remédiations\_effectives}}$ : nombre d'actions de déblocage ou de remédiation effectivement tracées par le formateur.
  - $N_{\text{besoin\_révision}(+10\,\text{min})}$ : nombre total d'apprenants présents n'ayant pas soumis leur tâche 10 minutes après la fin du Live Coding collaboratif.
  - Unité : pourcentage ($0 \le T_{\text{intervention}} \le 100\,\%$).
* **Interprétation didactique :**
  - **Taux élevé :** Confirme la parfaite réactivité du formateur qui prend immédiatement le relais dès que l'étayage horizontal des pairs atteint ses limites. Les blocages sont résolus avant le passage au prototype, empêchant le décrochage.
  - **Taux faible :** Révèle une rupture d'étayage après la phase collective, laissant les stagiaires bloqués à l'issue des 10 minutes sans prise en charge personnalisée.
* **Ancrage et justification théorique :** L'apprentissage entre pairs (*peer-learning*) résout les difficultés courantes mais ne peut traiter les blocages structurels. L'instrumentation par les Learning Analytics (Siemens & Long, 2011) permet de repérer objectivement à $+10$ minutes les apprenants en situation de saturation. Le formateur intervient alors au sein de leur Zone de Développement Proximal (Vygotski, 1978) pour apporter un étayage expert sur-mesure (*scaffolding*, Wood, Bruner & Ross, 1976) indispensable à la pédagogie de maîtrise (Bloom, 1968).

---

**3. Indicateurs de l'axe institutionnel (Hypothèse 3 — Fiabilité des notes et intégrité face à l'IA)**

**A. Écart d'authenticité évaluative / Corrélation d'intégrité face à l'IA ($r$)**

* **Définition et rôle :** Mesure la concordance linéaire entre la maîtrise individuelle démontrée sous surveillance (Live Coding Prototype $N2$) et la note globale de projet final ($N3$). Il garantit que l'usage légitime de l'intelligence artificielle dans le projet s'appuie sur une réelle compréhension conceptuelle et non sur une illusion de compétence (Hypothèse 3 : axe institutionnel).
* **Articulation didactique du Prototype et du Projet Fil Rouge :**
  - *Le Prototype comme socle générique :* Le prototype technique ($N2$) constitue la matrice architecturale du projet Fil Rouge ($N3$). La réussite du Live Coding individuel de 40 minutes atteste que l'apprenant dispose des compétences nécessaires pour mener son projet.
  - *L'IA comme outil professionnel légitime :* Dans la réalisation du projet ($N3$), l'usage des assistants d'IA générative est pleinement encouragé, car il reflète les compétences professionnelles attendues du développeur moderne.
  - *Sécurisation docimologique par la règle 65/35 :* Le dispositif immunise l'institution contre la complaisance en accordant 65 % de la note certificative à l'autonomie démontrée en direct sur le prototype et 35 % au projet assisté par IA.
* **Calcul depuis les données d'Active-LMS :**
  Active-LMS extrait les séries appariées des notes chiffrées de Live Coding individuel ($N2$) et des notes de soutenance de projet ($N3$) pour l'ensemble de la cohorte et calcule automatiquement le coefficient de corrélation linéaire de Pearson ($r$).
* **Formule mathématique :**
  $$r = \frac{\sum_{i=1}^{N} (x_i - \bar{x})(y_i - \bar{y})}{\sqrt{\sum_{i=1}^{N} (x_i - \bar{x})^2 \sum_{i=1}^{N} (y_i - \bar{y})^2}}$$
  - $N$ : effectif des apprenants évalués.
  - $x_i$ : note individuelle de Live Coding Prototype ($N2$) du stagiaire $i$.
  - $y_i$ : note de soutenance finale du projet Fil Rouge ($N3$) du stagiaire $i$.
  - $\bar{x}, \bar{y}$ : moyennes arithmétiques respectives des notes $N2$ et $N3$.
  - Unité : coefficient de corrélation ($-1 \le r \le +1$).
* **Interprétation didactique :**
  - **Forte corrélation ($r$ élevé) :** Confirme que les compétences fondamentales validées lors du prototype sont efficacement réinvesties dans le projet. L'apprenant mobilise l'IA comme un levier de productivité tout en restant pleinement maître de son architecture.
  - **Divergence par note de projet inférieure ($N3 < N2$) :** Traduit généralement une réalisation partielle du périmètre fonctionnel du projet (contrainte de temps ou sprint inachevé) plutôt qu'un déficit de compétence technique de base.
  - **Divergence par note de projet supérieure ($N3 \gg N2$) :** Révèlerait une délégation passive à l'IA sans assimilation personnelle, aussitôt neutralisée dans la note officielle par le poids prépondérant des 65 % accordés au Live Coding.
* **Ancrage et justification théorique :** L'évaluation doit mesurer la compétence en situation d'action authentique (Biggs, 1996). Le Live Coding individuel en temps contraint vérifie la maîtrise réelle du code (Bloom, 1968). L'adossement du projet au socle du prototype assure la transition entre apprentissage guidé et autonomie professionnelle outillée par l'IA (Collins, Brown & Newman, 1989).

**B. Indicateur de Couverture Pédagogique (ICP)**

* **Définition et rôle :** Mesure la part globale des micro-compétences du référentiel validées sur l'ensemble de la cohorte. Il évalue l'exhaustivité des apprentissages et la conformité au programme national (Hypothèse 3 : axe institutionnel).
* **Modalité de suivi :** L'indicateur agrège dynamiquement l'ensemble des compétences validées lors des Unités d'Apprentissage (UA) successives.
* **Calcul depuis les données d'Active-LMS :**
  Chaque Unité d'Apprentissage (UA) est adossée dans la base de données aux micro-compétences du référentiel national. Lorsque le formateur valide une UA pour un stagiaire, Active-LMS enregistre automatiquement l'acquisition des compétences associées. Le système cumule ces validations pour l'ensemble de la cohorte et calcule le pourcentage par rapport au total prescrit par le module.
* **Formule mathématique :**
  $$\text{ICP} = \frac{\sum_{i=1}^{N} C_{\text{validées}}(i)}{N \times C_{\text{référentiel}}} \times 100$$
  - $N$ : effectif total des stagiaires inscrits.
  - $C_{\text{validées}}(i)$ : nombre de compétences validées pour le stagiaire $i$.
  - $C_{\text{référentiel}}$ : nombre de compétences prescrites dans le référentiel du module.
  - Unité : pourcentage ($0 \le \text{ICP} \le 100\,\%$).
* **Interprétation didactique :**
  - **Taux proche de 100 % :** Atteste que le parcours en pédagogie active couvre la totalité du référentiel officiel. Aucune compétence intermédiaire n'a été éludée ou sacrifiée au profit du seul produit final.
  - **Taux déficitaire :** Alerte sur des impasses techniques ou un parcours tronqué. Il permet au responsable pédagogique d'identifier les compétences non acquises et de programmer des séances de consolidation ciblées.
* **Ancrage et justification théorique :** Dans les projets ouverts, certaines compétences techniques secondaires sont régulièrement contournées ou sacrifiées par manque de temps. Conformément à la pédagogie de maîtrise (Bloom, 1968), la certification repose sur l'assimilation progressive de chaque unité. La traçabilité numérique par les Learning Analytics (Siemens & Long, 2011) garantit une couverture complète et offre à l'institution un indicateur de gouvernance objectif.

**C. Taux de conformité de conversion institutionnelle ($\tau_{\text{conformité}}$)**

* **Définition et rôle :** Mesure la fiabilité arithmétique et la conformité réglementaire de la génération automatisée des notes officielles de Contrôle Continu (CC sur 20) et d'Examen de Fin de Module (EFM sur 40) à partir des évaluations formatives continues, selon la règle de pondération 65/35. Il garantit l'équité docimologique et l'élimination de toute erreur de report administratif (Hypothèse 3 : axe institutionnel).
* **Règle algorithmique de conversion sous-jacente (Règle 65/35) :**
  Pour chaque apprenant, le calcul des notes officielles respecte la double pondération institutionnelle :
  $$\text{Note CC} = \frac{1}{P} \sum_{p=1}^{P} \text{Note}(N2_p) \quad \in [0, 20]$$
  $$\text{Note EFM} = 2 \times \left( 0{,}65 \times \overline{\text{Note}}(N2) + 0{,}35 \times \text{Note}(N3) \right) \quad \in [0, 40]$$
  - $P$ : nombre d'Unités d'Apprentissage évaluées sur le module.
  - $\text{Note}(N2_p)$ : note sur 20 attribuée au prototype de l'UA $p$ lors du Live Coding individuel.
  - $\overline{\text{Note}}(N2)$ : moyenne arithmétique des notes individuelles de prototypes.
  - $\text{Note}(N3)$ : note sur 20 obtenue lors de la soutenance finale du projet Fil Rouge.
* **Calcul de l'indicateur de recherche depuis les données d'Active-LMS :**
  Le service applicatif `ConversionEvaluationService` d'Active-LMS applique cet algorithme en fin de semestre. L'audit docimologique confronte l'ensemble des bordereaux officiels générés aux critères réglementaires de l'OFPPT afin de quantifier le taux de conformité sans divergence arithmétique ni ressaisie manuelle.
* **Formule mathématique de l'indicateur :**
  $$\tau_{\text{conformité}} = \frac{N_{\text{bordereaux conformes sans anomalie}}}{N_{\text{total bordereaux générés}}} \times 100$$
  - $N_{\text{bordereaux conformes sans anomalie}}$ : nombre de bordereaux officiels calculés et exportés sans aucune divergence arithmétique ni erreur de barème.
  - $N_{\text{total bordereaux générés}}$ : volume total de bordereaux administratifs émis pour l'ensemble des promotions.
  - Unité : pourcentage ($0 \le \tau_{\text{conformité}} \le 100\,\%$).
* **Interprétation didactique :**
  - **Taux de 100 % (zéro divergence) :** Atteste de la parfaite fiabilité du moteur de conversion. Le système concilie la richesse formative de la pédagogie active avec les contraintes sommatives de l'institution, en supprimant tout risque d'erreur humaine et toute double saisie manuelle.
  - **Taux inférieur à 100 % :** Révèlerait des failles d'arrondi, des incohérences de barème ou des reports erronés nécessitant une intervention manuelle corrective.
* **Ancrage et justification théorique :** L'évaluation doit préserver l'alignement constructif (Biggs, 1996) entre l'apprentissage par projet et les exigences de certification. En accordant 65 % à la maîtrise individuelle démontrée en direct ($N2$) et 35 % au produit d'intégration collective ($N3$), la formule garantit une note objective. L'automatisation logicielle via les traces numériques (Siemens & Long, 2011) certifie l'intégrité docimologique et soulage l'administration pédagogique.

---

**4. Matrice récapitulative des indicateurs de recherche**

Le tableau ci-dessous synthétise le cadre méthodologique des neuf indicateurs opérationnels, précisant pour chacun son symbole, son axe de recherche, son unité de mesure, son ancrage théorique issu du Chapitre 3 et son rôle dans l'expérimentation :

| Indicateur                        |          Symbole          | Axe & Hypothèse  |          Unité           | Ancrage théorique (Chapitre 3)                                                                 | Instrument de recueil                        | Finalité didactique                                                  |
| :-------------------------------- | :-----------------------: | :--------------: | :----------------------: | :--------------------------------------------------------------------------------------------- | :------------------------------------------- | :------------------------------------------------------------------- |
| Temps d'amorçage moyen            |   $T_{\text{amorçage}}$   |  H1 (Apprenant)  |       Heures ($h$)       | Charge cognitive (Sweller, 1988) ; Apprentissage cognitif (Collins et al., 1989)               | Logs Kanban (Tutoriel Niveau 1)              | Mesurer la réduction du syndrome de la page blanche                  |
| Validation au gating au 1er essai |    $R_{\text{gating}}$    |  H1 (Apprenant)  |     Pourcentage (%)      | Pédagogie de maîtrise (Bloom, 1968) ; Stage-Gate (Cooper, 1990) ; 2TUP (Roques & Vallée, 2004) | Live Coding Prototype (Niveau 2)             | Quantifier l'assimilation préalable des fondamentaux                 |
| Boucles de révision par tâche     |   $F_{\text{révision}}$   |  H1 (Apprenant)  |        Itérations        | Charge extrinsèque (Sweller, 1988) ; Alignement constructif (Biggs, 1996)                      | Fiches de tâches Kanban (Transitions)        | Évaluer la clarté des critères et la qualité du travail initial      |
| Charge logistique hebdomadaire    |  $C_{\text{logistique}}$  |  H2 (Formateur)  |     Heures / semaine     | Learning Analytics (Siemens & Long, 2011) ; Apprentissage cognitif (Collins et al., 1989)      | Console de pilotage et relevés formateur     | Mesurer le temps administratif libéré au profit du tutorat           |
| Délai de file d'attente           |     $T_{\text{file}}$     |  H2 (Formateur)  |  Minutes ($\text{min}$)  | Learning Analytics (Siemens & Long, 2011) ; Étayage et ZDP (Bruner, 1976 ; Vygotski, 1978)     | File d'attente FIFO instrumentée             | Vérifier la régulation équitable et la suppression des interruptions |
| Taux d'intervention et remédiation | $T_{\text{intervention}}$ |  H2 (Formateur)  |     Pourcentage (%)      | Learning Analytics (Siemens & Long, 2011) ; ZDP et étayage (Vygotski, 1978 ; Bruner, 1976) ; Maîtrise (Bloom, 1968)     | Traces post-Live Coding (+10 min) et registres de remédiation     | Mesurer l'efficacité du relais formateur après l'entraide entre pairs |
| Authenticité et intégrité IA       |            $r$            | H3 (Institution) |    Pearson $[-1, 1]$     | Alignement constructif (Biggs, 1996) ; Pédagogie de maîtrise (Bloom, 1968) ; Collins et al. (1989) | Notes croisées N2 (Prototype) et N3 (Projet) | Mesurer l'alignement entre autonomie surveillée et projet assisté par IA |
| Couverture pédagogique            |       $\text{ICP}$        | H3 (Institution) |     Pourcentage (%)      | Pédagogie de maîtrise (Bloom, 1968) ; Learning Analytics (Siemens & Long, 2011)                | Registre des micro-compétences validées      | Vérifier l'achèvement exhaustif des compétences officielles          |
| Conformité conversion 65/35       | $\tau_{\text{conformité}}$ | H3 (Institution) |     Pourcentage (%)      | Alignement constructif (Biggs, 1996) ; Learning Analytics (Siemens & Long, 2011)               | Module de conversion Active-LMS              | Quantifier l'absence d'erreur et la conformité administrative        |

---

**Conclusion**

La formalisation mathématique et didactique de ces neuf indicateurs garantit la reproductibilité scientifique du protocole d'expérimentation. En associant un recueil automatisé via les traces d'Active-LMS à une interprétation ancrée dans le cadre théorique du Chapitre 3, ce répertoire méthodologique établit les fondements de la validation des hypothèses de recherche. Les mesures obtenues sur le terrain et leur analyse comparative sont présentées de manière détaillée dans le **Chapitre 9**.

---

**Références bibliographiques**

* Biggs, J. (1996). Enhancing teaching through constructive alignment. *Higher Education*, 32(3), 347-364.
* Bloom, B. S. (1968). Learning for mastery. *Evaluation Comment*, 1(2), 1-12.
* Collins, A., Brown, J. S., & Newman, S. E. (1989). Cognitive apprenticeship: Teaching the crafts of reading, writing, and mathematics. In L. B. Resnick (Ed.), *Knowing, learning, and instruction: Essays in honor of Robert Glaser* (pp. 453-494). Lawrence Erlbaum Associates.
* Cooper, R. G. (1990). Stage-gate systems: A new tool for managing new products. *Business Horizons*, 33(3), 44-54.
* Roques, P., & Vallée, F. (2004). *UML 2 en action : De l'analyse des besoins à la conception en Java (Méthode 2TUP)*. Eyrolles.
* Siemens, G., & Long, P. (2011). Penetrating the fog: Analytics in learning and education. *EDUCAUSE Review*, 46(5), 30-32.
* Sweller, J. (1988). Cognitive load during problem solving: Effects on learning. *Cognitive Science*, 12(2), 257-285.
* Vygotski, L. S. (1978). *Mind in society: The development of higher psychological processes*. Harvard University Press.
* Wood, D., Bruner, J. S., & Ross, G. (1976). The role of tutoring in problem solving. *Journal of Child Psychology and Psychiatry*, 17(2), 89-100.
