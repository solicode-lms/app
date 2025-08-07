/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `affectation_projet_evaluateur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `affectation_projet_evaluateur` (
  `affectation_projet_id` bigint unsigned NOT NULL,
  `evaluateur_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `affectation_projet_evaluateur_affectation_projet_id_foreign` (`affectation_projet_id`),
  KEY `affectation_projet_evaluateur_evaluateur_id_foreign` (`evaluateur_id`),
  CONSTRAINT `affectation_projet_evaluateur_affectation_projet_id_foreign` FOREIGN KEY (`affectation_projet_id`) REFERENCES `affectation_projets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `affectation_projet_evaluateur_evaluateur_id_foreign` FOREIGN KEY (`evaluateur_id`) REFERENCES `evaluateurs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `affectation_projets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `affectation_projets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime DEFAULT NULL,
  `annee_formation_id` bigint unsigned NOT NULL,
  `groupe_id` bigint unsigned DEFAULT NULL,
  `sous_groupe_id` bigint unsigned DEFAULT NULL,
  `projet_id` bigint unsigned NOT NULL,
  `is_formateur_evaluateur` tinyint(1) NOT NULL DEFAULT '1',
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `echelle_note_cible` int unsigned DEFAULT NULL COMMENT 'Échelle cible (ex: 50) pour recalculer la note brute',
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `affectation_projets_reference_unique` (`reference`),
  KEY `affectation_projets_annee_formation_id_foreign` (`annee_formation_id`),
  KEY `affectation_projets_groupe_id_foreign` (`groupe_id`),
  KEY `affectation_projets_projet_id_foreign` (`projet_id`),
  KEY `affectation_projets_sous_groupe_id_foreign` (`sous_groupe_id`),
  CONSTRAINT `affectation_projets_annee_formation_id_foreign` FOREIGN KEY (`annee_formation_id`) REFERENCES `annee_formations` (`id`),
  CONSTRAINT `affectation_projets_groupe_id_foreign` FOREIGN KEY (`groupe_id`) REFERENCES `groupes` (`id`),
  CONSTRAINT `affectation_projets_projet_id_foreign` FOREIGN KEY (`projet_id`) REFERENCES `projets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `affectation_projets_sous_groupe_id_foreign` FOREIGN KEY (`sous_groupe_id`) REFERENCES `sous_groupes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `alignement_uas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `alignement_uas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ordre` int NOT NULL DEFAULT '0',
  `unite_apprentissage_id` bigint unsigned NOT NULL,
  `session_formation_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alignement_uas_reference_unique` (`reference`),
  KEY `alignement_uas_unite_apprentissage_id_foreign` (`unite_apprentissage_id`),
  KEY `alignement_uas_session_formation_id_foreign` (`session_formation_id`),
  CONSTRAINT `alignement_uas_session_formation_id_foreign` FOREIGN KEY (`session_formation_id`) REFERENCES `session_formations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `alignement_uas_unite_apprentissage_id_foreign` FOREIGN KEY (`unite_apprentissage_id`) REFERENCES `unite_apprentissages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `annee_formations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `annee_formations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `annee_formations_reference_unique` (`reference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `apprenant_groupe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `apprenant_groupe` (
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `groupe_id` bigint unsigned NOT NULL,
  `apprenant_id` bigint unsigned NOT NULL,
  KEY `apprenant_groupe_groupe_id_foreign` (`groupe_id`),
  KEY `apprenant_groupe_apprenant_id_foreign` (`apprenant_id`),
  CONSTRAINT `apprenant_groupe_apprenant_id_foreign` FOREIGN KEY (`apprenant_id`) REFERENCES `apprenants` (`id`),
  CONSTRAINT `apprenant_groupe_groupe_id_foreign` FOREIGN KEY (`groupe_id`) REFERENCES `groupes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `apprenant_konosies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `apprenant_konosies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `MatriculeEtudiant` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Nom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Prenom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Sexe` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `EtudiantActif` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Diplome` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Principale` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `LibelleLong` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `CodeDiplome` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `DateNaissance` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `DateInscription` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `LieuNaissance` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `CIN` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `NTelephone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Adresse` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `Nationalite` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Nom_Arabe` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Prenom_Arabe` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `NiveauScolaire` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `apprenant_konosies_matriculeetudiant_unique` (`MatriculeEtudiant`),
  UNIQUE KEY `apprenant_konosies_reference_unique` (`reference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `apprenant_sous_groupe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `apprenant_sous_groupe` (
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `apprenant_id` bigint unsigned NOT NULL,
  `sous_groupe_id` bigint unsigned NOT NULL,
  KEY `apprenant_sous_groupe_apprenant_id_foreign` (`apprenant_id`),
  KEY `apprenant_sous_groupe_sous_groupe_id_foreign` (`sous_groupe_id`),
  CONSTRAINT `apprenant_sous_groupe_apprenant_id_foreign` FOREIGN KEY (`apprenant_id`) REFERENCES `apprenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `apprenant_sous_groupe_sous_groupe_id_foreign` FOREIGN KEY (`sous_groupe_id`) REFERENCES `sous_groupes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `apprenants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `apprenants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom_arab` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom_arab` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tele_num` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `matricule` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sexe` char(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `diplome` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `date_inscription` date DEFAULT NULL,
  `lieu_naissance` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cin` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `niveaux_scolaire_id` bigint unsigned DEFAULT NULL,
  `nationalite_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `apprenants_matricule_unique` (`matricule`),
  UNIQUE KEY `apprenants_reference_unique` (`reference`),
  KEY `apprenants_niveaux_scolaire_id_foreign` (`niveaux_scolaire_id`),
  KEY `apprenants_nationalite_id_foreign` (`nationalite_id`),
  KEY `apprenants_user_id_foreign` (`user_id`),
  CONSTRAINT `apprenants_nationalite_id_foreign` FOREIGN KEY (`nationalite_id`) REFERENCES `nationalites` (`id`),
  CONSTRAINT `apprenants_niveaux_scolaire_id_foreign` FOREIGN KEY (`niveaux_scolaire_id`) REFERENCES `niveaux_scolaires` (`id`),
  CONSTRAINT `apprenants_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `chapitres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chapitres` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lien` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `duree_en_heure` double NOT NULL DEFAULT '0',
  `ordre` int unsigned NOT NULL DEFAULT '0',
  `isOfficiel` tinyint(1) NOT NULL DEFAULT '1',
  `unite_apprentissage_id` bigint unsigned NOT NULL,
  `formateur_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chapitres_reference_unique` (`reference`),
  UNIQUE KEY `chapitres_code_unique` (`code`),
  KEY `chapitres_unite_apprentissage_id_foreign` (`unite_apprentissage_id`),
  KEY `chapitres_formateur_id_foreign` (`formateur_id`),
  CONSTRAINT `chapitres_formateur_id_foreign` FOREIGN KEY (`formateur_id`) REFERENCES `formateurs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `chapitres_unite_apprentissage_id_foreign` FOREIGN KEY (`unite_apprentissage_id`) REFERENCES `unite_apprentissages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `commentaire_realisation_taches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `commentaire_realisation_taches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `commentaire` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dateCommentaire` datetime NOT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `realisation_tache_id` bigint unsigned NOT NULL,
  `formateur_id` bigint unsigned DEFAULT NULL,
  `apprenant_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `commentaire_realisation_taches_reference_unique` (`reference`),
  KEY `commentaire_realisation_taches_realisation_tache_id_foreign` (`realisation_tache_id`),
  KEY `commentaire_realisation_taches_formateur_id_foreign` (`formateur_id`),
  KEY `commentaire_realisation_taches_apprenant_id_foreign` (`apprenant_id`),
  CONSTRAINT `commentaire_realisation_taches_apprenant_id_foreign` FOREIGN KEY (`apprenant_id`) REFERENCES `apprenants` (`id`) ON DELETE SET NULL,
  CONSTRAINT `commentaire_realisation_taches_formateur_id_foreign` FOREIGN KEY (`formateur_id`) REFERENCES `formateurs` (`id`) ON DELETE SET NULL,
  CONSTRAINT `commentaire_realisation_taches_realisation_tache_id_foreign` FOREIGN KEY (`realisation_tache_id`) REFERENCES `realisation_taches` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `competences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `competences` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mini_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `module_id` bigint unsigned NOT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `competences_reference_unique` (`reference`),
  KEY `competences_module_id_foreign` (`module_id`),
  CONSTRAINT `competences_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `critere_evaluations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `critere_evaluations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `intitule` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bareme` double NOT NULL DEFAULT '1',
  `ordre` int NOT NULL DEFAULT '0',
  `phase_evaluation_id` bigint unsigned NOT NULL,
  `unite_apprentissage_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `critere_evaluations_reference_unique` (`reference`),
  KEY `critere_evaluations_phase_evaluation_id_foreign` (`phase_evaluation_id`),
  KEY `critere_evaluations_unite_apprentissage_id_foreign` (`unite_apprentissage_id`),
  CONSTRAINT `critere_evaluations_phase_evaluation_id_foreign` FOREIGN KEY (`phase_evaluation_id`) REFERENCES `phase_evaluations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `critere_evaluations_unite_apprentissage_id_foreign` FOREIGN KEY (`unite_apprentissage_id`) REFERENCES `unite_apprentissages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `e_data_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `e_data_fields` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `column_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_order` int NOT NULL,
  `db_nullable` tinyint(1) NOT NULL,
  `db_primaryKey` tinyint(1) NOT NULL,
  `db_unique` tinyint(1) NOT NULL,
  `calculable` tinyint(1) NOT NULL DEFAULT '0',
  `calculable_sql` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `default_value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `e_model_id` bigint unsigned NOT NULL,
  `e_relationship_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `e_data_fields_reference_unique` (`reference`),
  KEY `e_data_fields_e_model_id_foreign` (`e_model_id`),
  KEY `e_data_fields_e_relationship_id_foreign` (`e_relationship_id`),
  CONSTRAINT `e_data_fields_e_model_id_foreign` FOREIGN KEY (`e_model_id`) REFERENCES `e_models` (`id`) ON DELETE CASCADE,
  CONSTRAINT `e_data_fields_e_relationship_id_foreign` FOREIGN KEY (`e_relationship_id`) REFERENCES `e_relationships` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `e_metadata`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `e_metadata` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value_boolean` tinyint(1) DEFAULT NULL,
  `value_string` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value_integer` int DEFAULT NULL,
  `value_float` double DEFAULT NULL,
  `value_date` date DEFAULT NULL,
  `value_datetime` datetime DEFAULT NULL,
  `value_enum` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value_json` json DEFAULT NULL,
  `value_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `e_model_id` bigint unsigned DEFAULT NULL,
  `e_data_field_id` bigint unsigned DEFAULT NULL,
  `e_metadata_definition_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `e_metadata_reference_unique` (`reference`),
  KEY `e_metadata_e_model_id_foreign` (`e_model_id`),
  KEY `e_metadata_e_data_field_id_foreign` (`e_data_field_id`),
  KEY `e_metadata_e_metadata_definition_id_foreign` (`e_metadata_definition_id`),
  CONSTRAINT `e_metadata_e_data_field_id_foreign` FOREIGN KEY (`e_data_field_id`) REFERENCES `e_data_fields` (`id`) ON DELETE CASCADE,
  CONSTRAINT `e_metadata_e_metadata_definition_id_foreign` FOREIGN KEY (`e_metadata_definition_id`) REFERENCES `e_metadata_definitions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `e_metadata_e_model_id_foreign` FOREIGN KEY (`e_model_id`) REFERENCES `e_models` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `e_metadata_definitions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `e_metadata_definitions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `groupe` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `scope` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `default_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `e_metadata_definitions_reference_unique` (`reference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `e_models`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `e_models` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_pivot_table` tinyint(1) NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `e_package_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `e_models_reference_unique` (`reference`),
  KEY `e_models_e_package_id_foreign` (`e_package_id`),
  CONSTRAINT `e_models_e_package_id_foreign` FOREIGN KEY (`e_package_id`) REFERENCES `e_packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `e_packages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `e_packages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `e_packages_reference_unique` (`reference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `e_relationships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `e_relationships` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `source_e_model_id` bigint unsigned NOT NULL,
  `target_e_model_id` bigint unsigned NOT NULL,
  `cascade_on_delete` tinyint(1) NOT NULL DEFAULT '0',
  `is_cascade` tinyint(1) NOT NULL DEFAULT '0',
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `column_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referenced_table` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referenced_column` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `through` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `with_column` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `morph_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `e_relationships_reference_unique` (`reference`),
  KEY `e_relationships_source_e_model_id_foreign` (`source_e_model_id`),
  KEY `e_relationships_target_e_model_id_foreign` (`target_e_model_id`),
  CONSTRAINT `e_relationships_source_e_model_id_foreign` FOREIGN KEY (`source_e_model_id`) REFERENCES `e_models` (`id`) ON DELETE CASCADE,
  CONSTRAINT `e_relationships_target_e_model_id_foreign` FOREIGN KEY (`target_e_model_id`) REFERENCES `e_models` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `etat_evaluation_projets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `etat_evaluation_projets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `titre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordre` int NOT NULL DEFAULT '0',
  `sys_color_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `etat_evaluation_projets_reference_unique` (`reference`),
  KEY `etat_evaluation_projets_sys_color_id_foreign` (`sys_color_id`),
  CONSTRAINT `etat_evaluation_projets_sys_color_id_foreign` FOREIGN KEY (`sys_color_id`) REFERENCES `sys_colors` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `etat_realisation_chapitres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `etat_realisation_chapitres` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ordre` int NOT NULL DEFAULT '0',
  `is_editable_only_by_formateur` tinyint(1) NOT NULL DEFAULT '0',
  `sys_color_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `etat_realisation_chapitres_reference_unique` (`reference`),
  UNIQUE KEY `etat_realisation_chapitres_code_unique` (`code`),
  KEY `etat_realisation_chapitres_sys_color_id_foreign` (`sys_color_id`),
  CONSTRAINT `etat_realisation_chapitres_sys_color_id_foreign` FOREIGN KEY (`sys_color_id`) REFERENCES `sys_colors` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `etat_realisation_micro_competences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `etat_realisation_micro_competences` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ordre` int NOT NULL DEFAULT '0',
  `is_editable_only_by_formateur` tinyint(1) NOT NULL DEFAULT '0',
  `sys_color_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `etat_realisation_micro_competences_reference_unique` (`reference`),
  UNIQUE KEY `etat_realisation_micro_competences_code_unique` (`code`),
  KEY `etat_realisation_micro_competences_sys_color_id_foreign` (`sys_color_id`),
  CONSTRAINT `etat_realisation_micro_competences_sys_color_id_foreign` FOREIGN KEY (`sys_color_id`) REFERENCES `sys_colors` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `etat_realisation_taches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `etat_realisation_taches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_editable_only_by_formateur` tinyint(1) DEFAULT '0',
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `formateur_id` bigint unsigned NOT NULL,
  `sys_color_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `workflow_tache_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `etat_realisation_taches_reference_unique` (`reference`),
  KEY `etat_realisation_taches_formateur_id_foreign` (`formateur_id`),
  KEY `etat_realisation_taches_sys_color_id_foreign` (`sys_color_id`),
  KEY `etat_realisation_taches_workflow_tache_id_foreign` (`workflow_tache_id`),
  CONSTRAINT `etat_realisation_taches_formateur_id_foreign` FOREIGN KEY (`formateur_id`) REFERENCES `formateurs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `etat_realisation_taches_sys_color_id_foreign` FOREIGN KEY (`sys_color_id`) REFERENCES `sys_colors` (`id`),
  CONSTRAINT `etat_realisation_taches_workflow_tache_id_foreign` FOREIGN KEY (`workflow_tache_id`) REFERENCES `workflow_taches` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `etat_realisation_uas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `etat_realisation_uas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ordre` int NOT NULL DEFAULT '0',
  `is_editable_only_by_formateur` tinyint(1) NOT NULL DEFAULT '0',
  `sys_color_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `etat_realisation_uas_reference_unique` (`reference`),
  UNIQUE KEY `etat_realisation_uas_code_unique` (`code`),
  KEY `etat_realisation_uas_sys_color_id_foreign` (`sys_color_id`),
  CONSTRAINT `etat_realisation_uas_sys_color_id_foreign` FOREIGN KEY (`sys_color_id`) REFERENCES `sys_colors` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `etats_realisation_projets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `etats_realisation_projets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ordre` int NOT NULL DEFAULT '0',
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `titre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sys_color_id` bigint unsigned DEFAULT NULL,
  `is_editable_by_formateur` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `etats_realisation_projets_reference_unique` (`reference`),
  UNIQUE KEY `etats_realisation_projets_code_unique` (`code`),
  KEY `etats_realisation_projets_sys_color_id_foreign` (`sys_color_id`),
  CONSTRAINT `etats_realisation_projets_sys_color_id_foreign` FOREIGN KEY (`sys_color_id`) REFERENCES `sys_colors` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `evaluateurs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `evaluateurs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organism` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `evaluateurs_email_unique` (`email`),
  UNIQUE KEY `evaluateurs_reference_unique` (`reference`),
  KEY `evaluateurs_user_id_foreign` (`user_id`),
  CONSTRAINT `evaluateurs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `evaluation_realisation_projets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `evaluation_realisation_projets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `date_evaluation` date NOT NULL,
  `remarques` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `realisation_projet_id` bigint unsigned NOT NULL,
  `evaluateur_id` bigint unsigned NOT NULL,
  `etat_evaluation_projet_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `evaluation_realisation_projets_reference_unique` (`reference`),
  KEY `evaluation_realisation_projets_realisation_projet_id_foreign` (`realisation_projet_id`),
  KEY `evaluation_realisation_projets_evaluateur_id_foreign` (`evaluateur_id`),
  KEY `evaluation_realisation_projets_etat_evaluation_projet_id_foreign` (`etat_evaluation_projet_id`),
  CONSTRAINT `evaluation_realisation_projets_etat_evaluation_projet_id_foreign` FOREIGN KEY (`etat_evaluation_projet_id`) REFERENCES `etat_evaluation_projets` (`id`) ON DELETE SET NULL,
  CONSTRAINT `evaluation_realisation_projets_evaluateur_id_foreign` FOREIGN KEY (`evaluateur_id`) REFERENCES `evaluateurs` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `evaluation_realisation_projets_realisation_projet_id_foreign` FOREIGN KEY (`realisation_projet_id`) REFERENCES `realisation_projets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `evaluation_realisation_taches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `evaluation_realisation_taches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `evaluation_realisation_projet_id` bigint unsigned DEFAULT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` float DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `evaluateur_id` bigint unsigned NOT NULL,
  `realisation_tache_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `evaluation_realisation_taches_reference_unique` (`reference`),
  KEY `evaluation_realisation_taches_evaluateur_id_foreign` (`evaluateur_id`),
  KEY `evaluation_realisation_taches_realisation_tache_id_foreign` (`realisation_tache_id`),
  KEY `fk_evaltache_evalprojet` (`evaluation_realisation_projet_id`),
  CONSTRAINT `evaluation_realisation_taches_evaluateur_id_foreign` FOREIGN KEY (`evaluateur_id`) REFERENCES `evaluateurs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `evaluation_realisation_taches_realisation_tache_id_foreign` FOREIGN KEY (`realisation_tache_id`) REFERENCES `realisation_taches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_evaltache_evalprojet` FOREIGN KEY (`evaluation_realisation_projet_id`) REFERENCES `evaluation_realisation_projets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `feature_domains`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `feature_domains` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sys_module_id` bigint unsigned NOT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `feature_domains_name_unique` (`name`),
  UNIQUE KEY `feature_domains_slug_unique` (`slug`),
  UNIQUE KEY `feature_domains_reference_unique` (`reference`),
  KEY `feature_domains_sys_module_id_foreign` (`sys_module_id`),
  CONSTRAINT `feature_domains_sys_module_id_foreign` FOREIGN KEY (`sys_module_id`) REFERENCES `sys_modules` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `feature_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `feature_permission` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `feature_id` bigint unsigned NOT NULL,
  `permission_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `feature_permission_feature_id_foreign` (`feature_id`),
  KEY `feature_permission_permission_id_foreign` (`permission_id`),
  CONSTRAINT `feature_permission_feature_id_foreign` FOREIGN KEY (`feature_id`) REFERENCES `features` (`id`) ON DELETE CASCADE,
  CONSTRAINT `feature_permission_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `features` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `feature_domain_id` bigint unsigned NOT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `features_name_unique` (`name`),
  UNIQUE KEY `features_reference_unique` (`reference`),
  KEY `features_feature_domain_id_foreign` (`feature_domain_id`),
  CONSTRAINT `features_feature_domain_id_foreign` FOREIGN KEY (`feature_domain_id`) REFERENCES `feature_domains` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `filieres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `filieres` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `filieres_reference_unique` (`reference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `formateur_groupe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `formateur_groupe` (
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `groupe_id` bigint unsigned NOT NULL,
  `formateur_id` bigint unsigned NOT NULL,
  KEY `formateur_groupe_groupe_id_foreign` (`groupe_id`),
  KEY `formateur_groupe_formateur_id_foreign` (`formateur_id`),
  CONSTRAINT `formateur_groupe_formateur_id_foreign` FOREIGN KEY (`formateur_id`) REFERENCES `formateurs` (`id`),
  CONSTRAINT `formateur_groupe_groupe_id_foreign` FOREIGN KEY (`groupe_id`) REFERENCES `groupes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `formateur_specialite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `formateur_specialite` (
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `specialite_id` bigint unsigned NOT NULL,
  `formateur_id` bigint unsigned NOT NULL,
  KEY `formateur_specialite_specialite_id_foreign` (`specialite_id`),
  KEY `formateur_specialite_formateur_id_foreign` (`formateur_id`),
  CONSTRAINT `formateur_specialite_formateur_id_foreign` FOREIGN KEY (`formateur_id`) REFERENCES `formateurs` (`id`),
  CONSTRAINT `formateur_specialite_specialite_id_foreign` FOREIGN KEY (`specialite_id`) REFERENCES `specialites` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `formateurs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `formateurs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `matricule` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom_arab` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nom_arab` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tele_num` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `diplome` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `echelle` int DEFAULT NULL,
  `echelon` int DEFAULT NULL,
  `profile_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `formateurs_matricule_unique` (`matricule`),
  UNIQUE KEY `formateurs_reference_unique` (`reference`),
  KEY `formateurs_user_id_foreign` (`user_id`),
  CONSTRAINT `formateurs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `groupes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `groupes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `filiere_id` bigint unsigned DEFAULT NULL,
  `annee_formation_id` bigint unsigned DEFAULT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `groupes_reference_unique` (`reference`),
  KEY `groupes_filiere_id_foreign` (`filiere_id`),
  KEY `groupes_annee_formation_id_foreign` (`annee_formation_id`),
  CONSTRAINT `groupes_annee_formation_id_foreign` FOREIGN KEY (`annee_formation_id`) REFERENCES `annee_formations` (`id`),
  CONSTRAINT `groupes_filiere_id_foreign` FOREIGN KEY (`filiere_id`) REFERENCES `filieres` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `historique_realisation_taches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `historique_realisation_taches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `dateModification` datetime NOT NULL,
  `changement` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `isFeedback` tinyint(1) NOT NULL DEFAULT '0',
  `realisation_tache_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `historique_realisation_taches_reference_unique` (`reference`),
  KEY `historique_realisation_taches_realisation_tache_id_foreign` (`realisation_tache_id`),
  KEY `historique_realisation_taches_user_id_foreign` (`user_id`),
  CONSTRAINT `historique_realisation_taches_realisation_tache_id_foreign` FOREIGN KEY (`realisation_tache_id`) REFERENCES `realisation_taches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `historique_realisation_taches_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `livrable_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `livrable_sessions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `titre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ordre` int NOT NULL DEFAULT '0',
  `session_formation_id` bigint unsigned NOT NULL,
  `nature_livrable_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `livrable_sessions_reference_unique` (`reference`),
  KEY `livrable_sessions_session_formation_id_foreign` (`session_formation_id`),
  KEY `livrable_sessions_nature_livrable_id_foreign` (`nature_livrable_id`),
  CONSTRAINT `livrable_sessions_nature_livrable_id_foreign` FOREIGN KEY (`nature_livrable_id`) REFERENCES `nature_livrables` (`id`) ON DELETE SET NULL,
  CONSTRAINT `livrable_sessions_session_formation_id_foreign` FOREIGN KEY (`session_formation_id`) REFERENCES `session_formations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `livrable_tache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `livrable_tache` (
  `tache_id` bigint unsigned NOT NULL,
  `livrable_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `livrable_tache_tache_id_foreign` (`tache_id`),
  KEY `livrable_tache_livrable_id_foreign` (`livrable_id`),
  CONSTRAINT `livrable_tache_livrable_id_foreign` FOREIGN KEY (`livrable_id`) REFERENCES `livrables` (`id`) ON DELETE CASCADE,
  CONSTRAINT `livrable_tache_tache_id_foreign` FOREIGN KEY (`tache_id`) REFERENCES `taches` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `livrables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `livrables` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nature_livrable_id` bigint unsigned NOT NULL,
  `projet_id` bigint unsigned NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_affichable_seulement_par_formateur` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `livrables_reference_unique` (`reference`),
  KEY `livrables_nature_livrable_id_foreign` (`nature_livrable_id`),
  KEY `livrables_projet_id_foreign` (`projet_id`),
  CONSTRAINT `livrables_nature_livrable_id_foreign` FOREIGN KEY (`nature_livrable_id`) REFERENCES `nature_livrables` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `livrables_projet_id_foreign` FOREIGN KEY (`projet_id`) REFERENCES `projets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `livrables_realisations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `livrables_realisations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `lien` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `livrable_id` bigint unsigned NOT NULL,
  `realisation_projet_id` bigint unsigned NOT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `livrables_realisations_reference_unique` (`reference`),
  KEY `livrables_realisations_livrable_id_foreign` (`livrable_id`),
  KEY `livrables_realisations_realisation_projet_id_foreign` (`realisation_projet_id`),
  CONSTRAINT `livrables_realisations_livrable_id_foreign` FOREIGN KEY (`livrable_id`) REFERENCES `livrables` (`id`),
  CONSTRAINT `livrables_realisations_realisation_projet_id_foreign` FOREIGN KEY (`realisation_projet_id`) REFERENCES `realisation_projets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `micro_competences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `micro_competences` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `titre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sous_titre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lien` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ordre` int unsigned NOT NULL DEFAULT '0',
  `competence_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `micro_competences_reference_unique` (`reference`),
  UNIQUE KEY `micro_competences_code_unique` (`code`),
  KEY `micro_competences_competence_id_foreign` (`competence_id`),
  CONSTRAINT `micro_competences_competence_id_foreign` FOREIGN KEY (`competence_id`) REFERENCES `competences` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `mobilisation_uas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mobilisation_uas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `criteres_evaluation_prototype` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `criteres_evaluation_projet` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `bareme_evaluation_prototype` double NOT NULL DEFAULT '0',
  `bareme_evaluation_projet` double NOT NULL DEFAULT '0',
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `projet_id` bigint unsigned NOT NULL,
  `unite_apprentissage_id` bigint unsigned NOT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mobilisation_uas_reference_unique` (`reference`),
  KEY `mobilisation_uas_projet_id_foreign` (`projet_id`),
  KEY `mobilisation_uas_unite_apprentissage_id_foreign` (`unite_apprentissage_id`),
  CONSTRAINT `mobilisation_uas_projet_id_foreign` FOREIGN KEY (`projet_id`) REFERENCES `projets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mobilisation_uas_unite_apprentissage_id_foreign` FOREIGN KEY (`unite_apprentissage_id`) REFERENCES `unite_apprentissages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `modules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `masse_horaire` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `filiere_id` bigint unsigned NOT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `modules_reference_unique` (`reference`),
  KEY `modules_filiere_id_foreign` (`filiere_id`),
  CONSTRAINT `modules_filiere_id_foreign` FOREIGN KEY (`filiere_id`) REFERENCES `filieres` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `nationalites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nationalites` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nationalites_reference_unique` (`reference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `nature_livrables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nature_livrables` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nature_livrables_reference_unique` (`reference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `niveaux_scolaires`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `niveaux_scolaires` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `niveaux_scolaires_reference_unique` (`reference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` json DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_foreign` (`user_id`),
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `controller_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`),
  UNIQUE KEY `permissions_reference_unique` (`reference`),
  KEY `permissions_controller_id_foreign` (`controller_id`),
  CONSTRAINT `permissions_controller_id_foreign` FOREIGN KEY (`controller_id`) REFERENCES `sys_controllers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `phase_evaluations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `phase_evaluations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `libelle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `coefficient` double NOT NULL DEFAULT '1',
  `ordre` int NOT NULL DEFAULT '0',
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phase_evaluations_reference_unique` (`reference`),
  UNIQUE KEY `phase_evaluations_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `priorite_taches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `priorite_taches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordre` int NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `formateur_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `priorite_taches_reference_unique` (`reference`),
  KEY `priorite_taches_formateur_id_foreign` (`formateur_id`),
  CONSTRAINT `priorite_taches_formateur_id_foreign` FOREIGN KEY (`formateur_id`) REFERENCES `formateurs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `profiles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_picture` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `profiles_user_id_foreign` (`user_id`),
  CONSTRAINT `profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `projets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `projets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `travail_a_faire` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `critere_de_travail` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `formateur_id` bigint unsigned NOT NULL,
  `filiere_id` bigint unsigned NOT NULL,
  `session_formation_id` bigint unsigned DEFAULT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `projets_reference_unique` (`reference`),
  KEY `projets_formateur_id_foreign` (`formateur_id`),
  KEY `projets_filiere_id_foreign` (`filiere_id`),
  KEY `projets_session_formation_id_foreign` (`session_formation_id`),
  CONSTRAINT `projets_filiere_id_foreign` FOREIGN KEY (`filiere_id`) REFERENCES `filieres` (`id`) ON DELETE CASCADE,
  CONSTRAINT `projets_formateur_id_foreign` FOREIGN KEY (`formateur_id`) REFERENCES `formateurs` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `projets_session_formation_id_foreign` FOREIGN KEY (`session_formation_id`) REFERENCES `session_formations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `realisation_chapitres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `realisation_chapitres` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_debut` datetime DEFAULT NULL,
  `date_fin` datetime DEFAULT NULL,
  `commentaire_formateur` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `realisation_ua_id` bigint unsigned NOT NULL,
  `realisation_tache_id` bigint unsigned DEFAULT NULL,
  `chapitre_id` bigint unsigned NOT NULL,
  `etat_realisation_chapitre_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `realisation_chapitres_reference_unique` (`reference`),
  KEY `realisation_chapitres_realisation_ua_id_foreign` (`realisation_ua_id`),
  KEY `realisation_chapitres_realisation_tache_id_foreign` (`realisation_tache_id`),
  KEY `realisation_chapitres_chapitre_id_foreign` (`chapitre_id`),
  KEY `fk_rchap_etat` (`etat_realisation_chapitre_id`),
  CONSTRAINT `fk_rchap_etat` FOREIGN KEY (`etat_realisation_chapitre_id`) REFERENCES `etat_realisation_chapitres` (`id`) ON DELETE SET NULL,
  CONSTRAINT `realisation_chapitres_chapitre_id_foreign` FOREIGN KEY (`chapitre_id`) REFERENCES `chapitres` (`id`) ON DELETE CASCADE,
  CONSTRAINT `realisation_chapitres_realisation_tache_id_foreign` FOREIGN KEY (`realisation_tache_id`) REFERENCES `realisation_taches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `realisation_chapitres_realisation_ua_id_foreign` FOREIGN KEY (`realisation_ua_id`) REFERENCES `realisation_uas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `realisation_micro_competences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `realisation_micro_competences` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_debut` datetime DEFAULT NULL,
  `date_fin` datetime DEFAULT NULL,
  `progression_cache` double NOT NULL DEFAULT '0',
  `note_cache` double DEFAULT NULL,
  `bareme_cache` double DEFAULT NULL,
  `commentaire_formateur` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `dernier_update` datetime DEFAULT NULL,
  `apprenant_id` bigint unsigned NOT NULL,
  `micro_competence_id` bigint unsigned NOT NULL,
  `etat_realisation_micro_competence_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `realisation_micro_competences_reference_unique` (`reference`),
  KEY `realisation_micro_competences_apprenant_id_foreign` (`apprenant_id`),
  KEY `realisation_micro_competences_micro_competence_id_foreign` (`micro_competence_id`),
  KEY `fk_rmcomp_etat` (`etat_realisation_micro_competence_id`),
  CONSTRAINT `fk_rmcomp_etat` FOREIGN KEY (`etat_realisation_micro_competence_id`) REFERENCES `etat_realisation_micro_competences` (`id`) ON DELETE SET NULL,
  CONSTRAINT `realisation_micro_competences_apprenant_id_foreign` FOREIGN KEY (`apprenant_id`) REFERENCES `apprenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `realisation_micro_competences_micro_competence_id_foreign` FOREIGN KEY (`micro_competence_id`) REFERENCES `micro_competences` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `realisation_projets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `realisation_projets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `date_debut` date NOT NULL,
  `date_fin` date DEFAULT NULL,
  `rapport` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `etats_realisation_projet_id` bigint unsigned DEFAULT NULL,
  `apprenant_id` bigint unsigned NOT NULL,
  `affectation_projet_id` bigint unsigned NOT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `realisation_projets_reference_unique` (`reference`),
  KEY `realisation_projets_etats_realisation_projet_id_foreign` (`etats_realisation_projet_id`),
  KEY `realisation_projets_apprenant_id_foreign` (`apprenant_id`),
  KEY `realisation_projets_affectation_projet_id_foreign` (`affectation_projet_id`),
  CONSTRAINT `realisation_projets_affectation_projet_id_foreign` FOREIGN KEY (`affectation_projet_id`) REFERENCES `affectation_projets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `realisation_projets_apprenant_id_foreign` FOREIGN KEY (`apprenant_id`) REFERENCES `apprenants` (`id`),
  CONSTRAINT `realisation_projets_etats_realisation_projet_id_foreign` FOREIGN KEY (`etats_realisation_projet_id`) REFERENCES `etats_realisation_projets` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `realisation_taches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `realisation_taches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `dateDebut` datetime DEFAULT NULL,
  `dateFin` datetime DEFAULT NULL,
  `is_live_coding` tinyint(1) NOT NULL DEFAULT '0',
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tache_id` bigint unsigned NOT NULL,
  `note` double DEFAULT NULL,
  `remarque_evaluateur` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `realisation_projet_id` bigint unsigned NOT NULL,
  `etat_realisation_tache_id` bigint unsigned DEFAULT NULL,
  `remarques_formateur` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `remarques_apprenant` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tache_affectation_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `realisation_taches_reference_unique` (`reference`),
  KEY `realisation_taches_tache_id_foreign` (`tache_id`),
  KEY `realisation_taches_realisation_projet_id_foreign` (`realisation_projet_id`),
  KEY `realisation_taches_etat_realisation_tache_id_foreign` (`etat_realisation_tache_id`),
  KEY `realisation_taches_tache_affectation_id_foreign` (`tache_affectation_id`),
  CONSTRAINT `realisation_taches_etat_realisation_tache_id_foreign` FOREIGN KEY (`etat_realisation_tache_id`) REFERENCES `etat_realisation_taches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `realisation_taches_realisation_projet_id_foreign` FOREIGN KEY (`realisation_projet_id`) REFERENCES `realisation_projets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `realisation_taches_tache_affectation_id_foreign` FOREIGN KEY (`tache_affectation_id`) REFERENCES `tache_affectations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `realisation_taches_tache_id_foreign` FOREIGN KEY (`tache_id`) REFERENCES `taches` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `realisation_ua_projets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `realisation_ua_projets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` double DEFAULT NULL,
  `bareme` double NOT NULL DEFAULT '0',
  `remarque_formateur` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `date_debut` datetime DEFAULT NULL,
  `date_fin` datetime DEFAULT NULL,
  `realisation_ua_id` bigint unsigned NOT NULL,
  `realisation_tache_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `realisation_ua_projets_reference_unique` (`reference`),
  KEY `realisation_ua_projets_realisation_ua_id_foreign` (`realisation_ua_id`),
  KEY `realisation_ua_projets_realisation_tache_id_foreign` (`realisation_tache_id`),
  CONSTRAINT `realisation_ua_projets_realisation_tache_id_foreign` FOREIGN KEY (`realisation_tache_id`) REFERENCES `realisation_taches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `realisation_ua_projets_realisation_ua_id_foreign` FOREIGN KEY (`realisation_ua_id`) REFERENCES `realisation_uas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `realisation_ua_prototypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `realisation_ua_prototypes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` double DEFAULT NULL,
  `bareme` double NOT NULL DEFAULT '0',
  `remarque_formateur` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `date_debut` datetime DEFAULT NULL,
  `date_fin` datetime DEFAULT NULL,
  `realisation_ua_id` bigint unsigned NOT NULL,
  `realisation_tache_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `realisation_ua_prototypes_reference_unique` (`reference`),
  KEY `realisation_ua_prototypes_realisation_ua_id_foreign` (`realisation_ua_id`),
  KEY `realisation_ua_prototypes_realisation_tache_id_foreign` (`realisation_tache_id`),
  CONSTRAINT `realisation_ua_prototypes_realisation_tache_id_foreign` FOREIGN KEY (`realisation_tache_id`) REFERENCES `realisation_taches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `realisation_ua_prototypes_realisation_ua_id_foreign` FOREIGN KEY (`realisation_ua_id`) REFERENCES `realisation_uas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `realisation_uas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `realisation_uas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_debut` datetime DEFAULT NULL,
  `date_fin` datetime DEFAULT NULL,
  `progression_cache` double NOT NULL DEFAULT '0',
  `note_cache` double DEFAULT NULL,
  `bareme_cache` double DEFAULT NULL,
  `commentaire_formateur` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `realisation_micro_competence_id` bigint unsigned NOT NULL,
  `unite_apprentissage_id` bigint unsigned NOT NULL,
  `etat_realisation_ua_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `realisation_uas_reference_unique` (`reference`),
  KEY `realisation_uas_realisation_micro_competence_id_foreign` (`realisation_micro_competence_id`),
  KEY `realisation_uas_unite_apprentissage_id_foreign` (`unite_apprentissage_id`),
  KEY `fk_rua_etat` (`etat_realisation_ua_id`),
  CONSTRAINT `fk_rua_etat` FOREIGN KEY (`etat_realisation_ua_id`) REFERENCES `etat_realisation_uas` (`id`) ON DELETE SET NULL,
  CONSTRAINT `realisation_uas_realisation_micro_competence_id_foreign` FOREIGN KEY (`realisation_micro_competence_id`) REFERENCES `realisation_micro_competences` (`id`) ON DELETE CASCADE,
  CONSTRAINT `realisation_uas_unite_apprentissage_id_foreign` FOREIGN KEY (`unite_apprentissage_id`) REFERENCES `unite_apprentissages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `resources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `resources` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lien` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `projet_id` bigint unsigned NOT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `resources_reference_unique` (`reference`),
  KEY `resources_projet_id_foreign` (`projet_id`),
  CONSTRAINT `resources_projet_id_foreign` FOREIGN KEY (`projet_id`) REFERENCES `projets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `role_widget`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_widget` (
  `role_id` bigint unsigned NOT NULL,
  `widget_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `role_widget_role_id_foreign` (`role_id`),
  KEY `role_widget_widget_id_foreign` (`widget_id`),
  CONSTRAINT `role_widget_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_widget_widget_id_foreign` FOREIGN KEY (`widget_id`) REFERENCES `widgets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`),
  UNIQUE KEY `roles_reference_unique` (`reference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `section_widgets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `section_widgets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sous_titre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ordre` int NOT NULL DEFAULT '0',
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sys_color_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `section_widgets_reference_unique` (`reference`),
  KEY `section_widgets_sys_color_id_foreign` (`sys_color_id`),
  CONSTRAINT `section_widgets_sys_color_id_foreign` FOREIGN KEY (`sys_color_id`) REFERENCES `sys_colors` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `session_formations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `session_formations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `titre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordre` int NOT NULL DEFAULT '0',
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `jour_feries_vacances` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `thematique` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `objectifs_pedagogique` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remarques` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `titre_prototype` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_prototype` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contraintes_prototype` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `titre_projet` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_projet` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contraintes_projet` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `filiere_id` bigint unsigned DEFAULT NULL,
  `annee_formation_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `session_formations_reference_unique` (`reference`),
  KEY `session_formations_filiere_id_foreign` (`filiere_id`),
  KEY `session_formations_annee_formation_id_foreign` (`annee_formation_id`),
  CONSTRAINT `session_formations_annee_formation_id_foreign` FOREIGN KEY (`annee_formation_id`) REFERENCES `annee_formations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `session_formations_filiere_id_foreign` FOREIGN KEY (`filiere_id`) REFERENCES `filieres` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sous_groupes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sous_groupes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `groupe_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sous_groupes_reference_unique` (`reference`),
  KEY `sous_groupes_groupe_id_foreign` (`groupe_id`),
  CONSTRAINT `sous_groupes_groupe_id_foreign` FOREIGN KEY (`groupe_id`) REFERENCES `groupes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `specialites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `specialites` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `specialites_reference_unique` (`reference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sys_colors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_colors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hex` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sys_colors_reference_unique` (`reference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sys_controllers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_controllers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sys_module_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sys_controllers_name_unique` (`name`),
  UNIQUE KEY `sys_controllers_slug_unique` (`slug`),
  UNIQUE KEY `sys_controllers_reference_unique` (`reference`),
  KEY `sys_controllers_sys_module_id_foreign` (`sys_module_id`),
  CONSTRAINT `sys_controllers_sys_module_id_foreign` FOREIGN KEY (`sys_module_id`) REFERENCES `sys_modules` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sys_models`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_models` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sys_module_id` bigint unsigned NOT NULL,
  `sys_color_id` bigint unsigned DEFAULT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sys_models_reference_unique` (`reference`),
  KEY `sys_models_sys_module_id_foreign` (`sys_module_id`),
  KEY `sys_models_sys_color_id_foreign` (`sys_color_id`),
  CONSTRAINT `sys_models_sys_color_id_foreign` FOREIGN KEY (`sys_color_id`) REFERENCES `sys_colors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sys_models_sys_module_id_foreign` FOREIGN KEY (`sys_module_id`) REFERENCES `sys_modules` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sys_modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_modules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` int NOT NULL,
  `ordre` int NOT NULL,
  `version` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sys_color_id` bigint unsigned NOT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sys_modules_reference_unique` (`reference`),
  KEY `sys_modules_sys_color_id_foreign` (`sys_color_id`),
  CONSTRAINT `sys_modules_sys_color_id_foreign` FOREIGN KEY (`sys_color_id`) REFERENCES `sys_colors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tache_affectations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tache_affectations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tache_id` bigint unsigned NOT NULL,
  `affectation_projet_id` bigint unsigned NOT NULL,
  `pourcentage_realisation_cache` double NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tache_affectations_tache_id_affectation_projet_id_unique` (`tache_id`,`affectation_projet_id`),
  UNIQUE KEY `tache_affectations_reference_unique` (`reference`),
  KEY `tache_affectations_affectation_projet_id_foreign` (`affectation_projet_id`),
  CONSTRAINT `tache_affectations_affectation_projet_id_foreign` FOREIGN KEY (`affectation_projet_id`) REFERENCES `affectation_projets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tache_affectations_tache_id_foreign` FOREIGN KEY (`tache_id`) REFERENCES `taches` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `taches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `taches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `dateDebut` datetime DEFAULT NULL,
  `dateFin` datetime DEFAULT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordre` int DEFAULT NULL,
  `priorite` int DEFAULT NULL COMMENT 'Niveau de priorité de la tâche, peut être NULL',
  `projet_id` bigint unsigned NOT NULL,
  `note` double DEFAULT NULL,
  `priorite_tache_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `phase_evaluation_id` bigint unsigned DEFAULT NULL,
  `chapitre_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `taches_reference_unique` (`reference`),
  KEY `taches_projet_id_foreign` (`projet_id`),
  KEY `taches_priorite_tache_id_foreign` (`priorite_tache_id`),
  KEY `taches_phase_evaluation_id_foreign` (`phase_evaluation_id`),
  KEY `taches_chapitre_id_foreign` (`chapitre_id`),
  CONSTRAINT `taches_chapitre_id_foreign` FOREIGN KEY (`chapitre_id`) REFERENCES `chapitres` (`id`) ON DELETE SET NULL,
  CONSTRAINT `taches_phase_evaluation_id_foreign` FOREIGN KEY (`phase_evaluation_id`) REFERENCES `phase_evaluations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `taches_priorite_tache_id_foreign` FOREIGN KEY (`priorite_tache_id`) REFERENCES `priorite_taches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `taches_projet_id_foreign` FOREIGN KEY (`projet_id`) REFERENCES `projets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `unite_apprentissages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `unite_apprentissages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lien` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ordre` int unsigned NOT NULL DEFAULT '0',
  `micro_competence_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unite_apprentissages_reference_unique` (`reference`),
  UNIQUE KEY `unite_apprentissages_code_unique` (`code`),
  KEY `unite_apprentissages_micro_competence_id_foreign` (`micro_competence_id`),
  CONSTRAINT `unite_apprentissages_micro_competence_id_foreign` FOREIGN KEY (`micro_competence_id`) REFERENCES `micro_competences` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_model_filters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_model_filters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `model_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `context_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `filters` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_model_filters_user_id_model_name_context_key_unique` (`user_id`,`model_name`,`context_key`),
  CONSTRAINT `user_model_filters_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `must_change_password` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_reference_unique` (`reference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `widget_operations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `widget_operations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `operation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `widget_operations_reference_unique` (`reference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `widget_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `widget_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `widget_types_reference_unique` (`reference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `widget_utilisateurs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `widget_utilisateurs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `widget_id` bigint unsigned NOT NULL,
  `ordre` int NOT NULL DEFAULT '0',
  `titre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sous_titre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `widget_utilisateurs_user_id_foreign` (`user_id`),
  KEY `widget_utilisateurs_widget_id_foreign` (`widget_id`),
  CONSTRAINT `widget_utilisateurs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `widget_utilisateurs_widget_id_foreign` FOREIGN KEY (`widget_id`) REFERENCES `widgets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `widgets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `widgets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_id` bigint unsigned NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  `operation_id` bigint unsigned NOT NULL,
  `color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parameters` json DEFAULT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `section_widget_id` bigint unsigned DEFAULT NULL,
  `ordre` int DEFAULT NULL,
  `sys_color_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `widgets_reference_unique` (`reference`),
  KEY `widgets_type_id_foreign` (`type_id`),
  KEY `widgets_model_id_foreign` (`model_id`),
  KEY `widgets_operation_id_foreign` (`operation_id`),
  KEY `widgets_sys_color_id_foreign` (`sys_color_id`),
  KEY `widgets_section_widget_id_foreign` (`section_widget_id`),
  CONSTRAINT `widgets_model_id_foreign` FOREIGN KEY (`model_id`) REFERENCES `sys_models` (`id`) ON DELETE CASCADE,
  CONSTRAINT `widgets_operation_id_foreign` FOREIGN KEY (`operation_id`) REFERENCES `widget_operations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `widgets_section_widget_id_foreign` FOREIGN KEY (`section_widget_id`) REFERENCES `section_widgets` (`id`) ON DELETE SET NULL,
  CONSTRAINT `widgets_sys_color_id_foreign` FOREIGN KEY (`sys_color_id`) REFERENCES `sys_colors` (`id`),
  CONSTRAINT `widgets_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `widget_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `workflow_taches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `workflow_taches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `titre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_editable_only_by_formateur` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Indique si cet état est modifiable uniquement par le formateur',
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordre` int DEFAULT NULL,
  `sys_color_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `workflow_taches_code_unique` (`code`),
  UNIQUE KEY `workflow_taches_titre_unique` (`titre`),
  UNIQUE KEY `workflow_taches_reference_unique` (`reference`),
  KEY `workflow_taches_sys_color_id_foreign` (`sys_color_id`),
  CONSTRAINT `workflow_taches_sys_color_id_foreign` FOREIGN KEY (`sys_color_id`) REFERENCES `sys_colors` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'0001_01_01_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'0001_01_02_000000_create_roles_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'0001_01_03_000000_create_permissions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'0001_01_05_000000_create_role_user_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'0001_01_06_000000_create_permission_role_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'0002_01_01_000000_create_sys_colors_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'0002_01_01_000002_create_sys_modules_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'0002_01_10_000000_create_sys_controllers_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'0002_01_20_105344_create_feature_domains_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'0002_01_30_105641_create_features_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'0002_01_31_000000_create_feature_permission_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'0002_01_32_000000_create_sys_models_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'0003_01_03_000003_create_feature_permission_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'0003_90_90_000000_create_apprenant_konosies_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'0004_01_01_000000_create_filieres_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'0004_01_02_000000_create_modules_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'0004_01_04_000000_create_competences_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (25,'0005_01_01_000000_create_niveaux_scolaires_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (27,'0005_01_03_000000_create_nationalites_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (28,'0005_01_03_000010_create_annee_formations_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (29,'0005_01_04_000000_create_groupes_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (30,'0005_01_05_000000_create_apprenants_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (31,'0005_01_07_000000_create_specialites_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (32,'0005_01_08_000000_create_formateurs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (33,'0005_01_09_000000_create_formateur_groupe_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (34,'0005_01_10_000000_create_apprenant_groupe_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (35,'0005_01_10_000000_create_formateur_specialite_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (36,'0010_01_01_000000_create_widget_types_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (37,'0010_01_02_000000_create_widget_operations_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (38,'0010_01_03_000000_create_widgets_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (40,'2024_12_26_122739_create_nature_livrables_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (41,'2024_12_26_123348_create_projets_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (42,'2024_12_26_123533_create_resources_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (43,'2024_12_26_123819_create_livrables_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (47,'2025_01_09_174213_create_e_metadata_definitions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (48,'2025_01_09_194546_create_e_packages_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (49,'2025_01_09_195045_create_e_models_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (50,'2025_01_09_195912_create_e_relationships_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (51,'2025_01_09_195913_create_e_data_fields_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (52,'2025_01_09_195914_create_e_metadata_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (53,'2025_01_29_185601_create_etats_realisation_projets_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (54,'2025_01_29_185602_create_affectation_projets_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (55,'2025_01_30_150714_create_formateur_groupe_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (56,'2025_01_30_185358_create_realisation_projets_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (57,'2025_01_30_191645_create_livrables_realisations_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (59,'2025_02_16_085233_create_profiles_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (65,'2025_03_01_111510_create_etat_realisation_taches_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (67,'2025_03_01_112813_create_priorite_taches_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (68,'2025_03_01_112941_create_taches_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (71,'2025_03_01_114121_create_realisation_taches_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (72,'2025_03_01_114437_create_historique_realisation_taches_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (73,'2025_03_01_114542_create_commentaire_realisation_taches_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (74,'2025_03_03_124351_update_realisation_taches_date_fields',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (75,'2025_03_03_130424_create_livrable_tache_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (76,'2025_03_03_132140_create_livrable_tache_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (77,'2025_03_04_171210_create_widget_utilisateurs_table',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (78,'2025_03_06_081943_create_workflow_taches_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (79,'2025_03_06_082519_add_workflow_tache_to_etat_realisation_taches_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (80,'2025_03_07_080906_add_remarques_to_realisation_taches_table',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (81,'2025_03_09_104844_add_calculable_to_e_data_fields_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (82,'2025_03_10_090419_add_calculable_sql_to_e_data_field',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (93,'2025_04_03_203624_create_role_widget_table',12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (94,'2025_04_03_204114_create_role_widget_table',12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (95,'2025_04_04_164020_add_sys_color_to_widgets_table',12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (96,'2025_04_04_164246_add_icone_to_sys_models_table',12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (97,'2025_04_04_184236_add_ordre_to_widgets_table',12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (98,'2025_04_09_204251_add_is_affichable_column_to_livrables_table',13);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (99,'2025_04_12_114212_alter_default_value_column_in_e_metadata_definitions',14);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (100,'2025_04_13_074238_create_section_widgets_table',14);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (101,'2025_04_13_074404_add_section_widget_id_to_widgets_table',14);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (105,'2025_04_13_200545_add_sys_color_to_workflow_taches_table',14);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (106,'2025_04_16_170325_create_user_model_filters_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (107,'2025_04_19_115140_add_ordre_to_workflow_taches_table',16);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (108,'2025_04_19_115422_add_ordre_to_workflow_projets_table',16);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (109,'2025_04_19_115621_add_ordre_to_taches_table',16);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (110,'2025_04_26_191545_alter_affectation_projets_change_date_columns',17);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (111,'2025_04_29_080818_alter_realisation_taches_change_date_to_datetime',18);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (112,'2025_05_01_094340_add_user_id_is_feedback_to_historique_realisation_taches_table',19);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (113,'2025_05_01_103554_create_notifications_table',19);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (114,'2025_05_17_105706_add_note_to_taches_table',20);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (115,'2025_05_17_105814_add_note_to_realisation_taches_table',20);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (116,'2025_05_17_105946_add_is_formateur_evaluateur_to_affectation_projets_table',20);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (117,'2025_05_17_110438_create_evaluateurs_table',20);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (118,'2025_05_17_111522_create_evaluation_realisation_taches_table',20);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (119,'2025_05_17_111726_create_affectation_projet_evaluateur_table',20);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (120,'2025_05_17_112841_add_unique_formateur_id_to_evaluateurs_table',20);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (121,'2025_05_17_123338_create_affectationProjet_evaluateur_table',20);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (122,'2025_05_17_123340_create_affectationProjet_evaluateur_table',20);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (123,'2025_05_17_141618_add_reference_to_evaluateurs_table',20);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (124,'2025_05_17_141708_add_reference_to_evaluation_realisation_taches_table',20);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (125,'2025_05_17_143457_add_user_id_to_evaluateurs_table',20);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (126,'2025_05_17_205019_make_evaluateurs_user_id_nullable',20);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (127,'2025_05_18_162324_add_remarque_evaluateur_to_realisation_taches_table',20);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (128,'2025_06_03_111601_create_etat_evaluation_projets_table',21);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (129,'2025_06_03_111845_create_evaluation_realisation_projets_table',21);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (130,'2025_06_03_175648_add_evaluation_projet_foreign_to_realisation_taches_table',21);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (131,'2025_06_03_185702_add_note_to_evaluation_realisation_taches_table',21);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (132,'2025_06_13_095006_delete_evaluation_realisation_taches_without_projet_id',22);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (133,'2025_06_18_081751_create_sous_groupes_table',23);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (134,'2025_06_18_082224_create_apprenant_sous_groupe_table',23);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (135,'2025_06_18_083412_update_affectation_projets_add_sous_groupe_id',23);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (136,'2025_06_18_094700_create_apprenant_sousGroupe_table',23);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (137,'2025_06_21_120321_add_echelle_note_cible_to_affectation_projets_table',24);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (139,'2025_07_12_125922_create_livrable_tache_table',25);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (140,'2025_07_14_101307_create_micro_competences_table',26);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (141,'2025_07_14_101815_create_unite_apprentissages_table',27);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (142,'2025_07_14_102022_create_chapitres_table',28);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (143,'2025_07_19_144700_add_code_to_unite_apprentissages_table',29);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (144,'2025_07_19_150339_add_code_and_duree_to_chapitres_table',30);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (145,'2025_07_23_183609_create_phase_evaluations_table',31);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (146,'2025_07_23_183911_create_critere_evaluations_table',32);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (147,'2025_07_23_192910_add_unite_apprentissage_id_to_critere_evaluations_table',33);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (148,'2025_07_24_085112_create_etat_realisation_micro_competences_table',34);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (149,'2025_07_24_085304_create_etat_realisation_uas_table',35);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (151,'2025_07_24_085404_create_etat_realisation_chapitres_table',36);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (152,'2025_07_24_085625_create_realisation_micro_competences_table',37);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (153,'2025_07_24_090114_create_realisation_uas_table',38);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (155,'2025_07_24_090212_create_realisation_chapitres_table',39);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (156,'2025_07_24_090256_create_realisation_ua_prototypes_table',39);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (157,'2025_07_24_091203_create_realisation_ua_projets_table',40);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (158,'2025_07_24_095527_add_ordre_to_etat_realisation_micro_competences_table',41);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (159,'2025_07_24_095605_add_ordre_to_etat_realisation_chapitres_table',41);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (160,'2025_07_24_095656_add_ordre_to_etat_realisation_uas_table',41);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (162,'2025_07_25_130046_create_session_formations_table',42);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (163,'2025_07_25_131005_create_alignement_uas_table',42);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (164,'2025_07_25_131313_create_livrable_sessions_table',43);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (165,'2025_07_26_100252_update_projets_table',44);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (167,'2025_07_26_100429_create_mobilisation_uas_table',45);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (168,'2025_07_26_125311_add_phase_evaluation_and_chapitre_to_taches_table',46);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (169,'2025_07_26_130806_add_priorite_to_taches_table',47);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (170,'2025_07_28_173221_add_is_editable_only_by_formateur_to_workflow_taches_table',48);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (171,'2025_07_30_102459_update_etats_realisation_projets_table',49);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (172,'2025_07_30_123128_rename_order_to_ordre_in_sys_modules_table',50);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (173,'2025_07_30_161840_add_context_key_to_user_model_filter_table',51);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (174,'2025_07_30_162549_update_unique_key_on_user_model_filters_table',52);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (175,'2025_08_03_205207_alter_note_nullable_in_realisation_ua_tables',53);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (176,'2025_08_04_105012_update_realisation_uas_and_micro_competences_nullable_note',54);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (177,'2025_08_07_081245_create_tache_affectations_table',55);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (178,'2025_08_07_082723_add_is_live_coding_to_realisation_taches_table',56);
