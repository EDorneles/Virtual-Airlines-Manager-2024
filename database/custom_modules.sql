-- Tables used by the additional courses, training, exam-candidate and legacy
-- FSACARS pages in this fork. Import after the VAM 2.6 schema and seed files.
SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS `candidatos` (
  `id_candidato` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(120) NOT NULL,
  `sobrenome` VARCHAR(120) NOT NULL,
  `email` VARCHAR(254) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_candidato`),
  KEY `idx_candidatos_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- The VAM 2.6 schema already creates these two tables with MyISAM storage.
-- Convert the courses key to unsigned InnoDB so course_enrollments can enforce
-- its foreign key, and add fields used by the custom training request pages.
ALTER TABLE `courses`
  ENGINE=InnoDB,
  ROW_FORMAT=DYNAMIC;

ALTER TABLE `courses`
  MODIFY `course_id` INT UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `trainings`
  ADD COLUMN `pilot_id` INT DEFAULT NULL,
  ADD COLUMN `training_date` DATE DEFAULT NULL,
  ADD COLUMN `training_time` TIME DEFAULT NULL,
  ADD COLUMN `observations` TEXT DEFAULT NULL,
  ADD COLUMN `completed` TINYINT(1) NOT NULL DEFAULT 0;

ALTER TABLE `trainings`
  ENGINE=InnoDB,
  ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `course_enrollments` (
  `course_id` INT UNSIGNED NOT NULL,
  `user_id` INT NOT NULL,
  `enrolled_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`course_id`, `user_id`),
  KEY `idx_course_enrollments_user` (`user_id`),
  CONSTRAINT `fk_course_enrollments_course`
    FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `positions` (
  `pilot_num` VARCHAR(32) NOT NULL,
  `last_update` DATETIME DEFAULT NULL,
  `lat` DECIMAL(10,7) DEFAULT NULL,
  `lon` DECIMAL(10,7) DEFAULT NULL,
  `gs` SMALLINT DEFAULT NULL,
  `alt` INT DEFAULT NULL,
  `IATA` VARCHAR(8) DEFAULT NULL,
  `depAptICAO` VARCHAR(8) DEFAULT NULL,
  `depApt` VARCHAR(255) DEFAULT NULL,
  `disDepApt` DECIMAL(10,2) DEFAULT NULL,
  `timeDepApt` VARCHAR(32) DEFAULT NULL,
  `destAptICAO` VARCHAR(8) DEFAULT NULL,
  `destApt` VARCHAR(255) DEFAULT NULL,
  `disDestApt` DECIMAL(10,2) DEFAULT NULL,
  `timeDestApt` VARCHAR(32) DEFAULT NULL,
  `phase` INT DEFAULT NULL,
  PRIMARY KEY (`pilot_num`),
  KEY `idx_positions_last_update` (`last_update`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
