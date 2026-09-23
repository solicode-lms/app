### Plan détaillé pour structurer le prompt avec explications

1. **Contexte de l'application**  
   *Explication* : Fournir une description claire de l'application Sollms (objectif, domaine, public cible) pour ancrer l’agent dans le projet. Préciser la stack technologique (Laravel 11, AdminLTE 3.2.0, MySQL) et les contraintes (performance, sécurité) pour garantir que les réponses respectent l’environnement technique et les besoins spécifiques.

2. **Rôle et responsabilités de l’agent**  
   *Explication* : Définir les tâches principales de l’agent, comme générer du code Laravel, proposer des architectures, intégrer AdminLTE, gérer la base de données, et fournir du support. Cela établit les attentes et les limites de ses compétences, assurant des réponses ciblées et cohérentes avec les objectifs de développement.

3. **Instructions spécifiques**  
   *Explication* : Lister les règles techniques et fonctionnelles (conformité PSR-12, modularité, sécurité, performance, langue française) pour guider l’agent dans la production de code et de conseils. Cela garantit que les réponses respectent les standards de Laravel et les besoins d’interface AdminLTE tout en restant claires et sécurisées.

4. **Structure du prompt**  
   *Explication* : Décrire la composition du prompt (introduction, directives, exemples, contraintes, format de sortie) pour organiser les réponses de l’agent. Cela permet de standardiser les réponses, avec du code dans `<xaiArtifact>` et des explications claires, facilitant l’utilisation par le développeur.

5. **Exemples concrets**  
   *Explication* : Inclure des cas d’usage spécifiques (ex. : CRUD, tableau AdminLTE, architecture de rôles) pour illustrer les types de tâches attendues. Cela aide l’agent à comprendre les attentes pratiques et à fournir des réponses directement applicables au contexte de Sollms.

6. **Gestion des erreurs et évolutivité**  
   *Explication* : Prévoir des instructions pour anticiper et résoudre les erreurs courantes (ex. : conflits de migration) et suggérer des améliorations pour l’évolutivité. Cela garantit que l’agent ne se limite pas à des solutions immédiates, mais propose des approches robustes et durables.