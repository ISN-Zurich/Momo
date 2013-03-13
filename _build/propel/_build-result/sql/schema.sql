
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- users
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users`
(
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`firstName` VARCHAR(100) NOT NULL,
	`lastName` VARCHAR(100) NOT NULL,
	`email` VARCHAR(100) NOT NULL,
	`birthdate` DATE NOT NULL,
	`login` VARCHAR(50) NOT NULL,
	`password` CHAR(32),
	`type` VARCHAR(50) NOT NULL,
	`workload` FLOAT NOT NULL,
	`offDays` TEXT NOT NULL,
	`entryDate` DATE NOT NULL,
	`exitDate` DATE NOT NULL,
	`role` VARCHAR(50) NOT NULL,
	`enabled` TINYINT(1) DEFAULT 1 NOT NULL,
	`archived` TINYINT(1) DEFAULT 0 NOT NULL,
	`lastLogin` DATETIME,
	`passwordResetToken` CHAR(36),
	PRIMARY KEY (`id`),
	UNIQUE INDEX `constr_login_unique` (`login`),
	UNIQUE INDEX `constr_email_unique` (`email`),
	UNIQUE INDEX `constr_password_reset_token_unique` (`passwordResetToken`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- teams
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `teams`;

CREATE TABLE `teams`
(
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`parent_id` INTEGER,
	`name` VARCHAR(50) NOT NULL,
	`archived` TINYINT(1) DEFAULT 0 NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `constr_name_unique` (`name`),
	INDEX `teams_FI_1` (`parent_id`),
	CONSTRAINT `teams_FK_1`
		FOREIGN KEY (`parent_id`)
		REFERENCES `teams` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- teams_users
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `teams_users`;

CREATE TABLE `teams_users`
(
	`team_id` INTEGER NOT NULL,
	`user_id` INTEGER NOT NULL,
	`primary` TINYINT(1) DEFAULT 0 NOT NULL,
	`secondary` TINYINT(1) DEFAULT 0 NOT NULL,
	`leader` TINYINT(1) DEFAULT 0 NOT NULL,
	PRIMARY KEY (`team_id`,`user_id`),
	INDEX `teams_users_FI_1` (`user_id`),
	CONSTRAINT `teams_users_FK_1`
		FOREIGN KEY (`user_id`)
		REFERENCES `users` (`id`),
	CONSTRAINT `teams_users_FK_2`
		FOREIGN KEY (`team_id`)
		REFERENCES `teams` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- projects
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `projects`;

CREATE TABLE `projects`
(
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(50) NOT NULL,
	`enabled` TINYINT(1) DEFAULT 1 NOT NULL,
	`archived` TINYINT(1) DEFAULT 0 NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `constr_name_unique` (`name`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- teams_projects
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `teams_projects`;

CREATE TABLE `teams_projects`
(
	`team_id` INTEGER NOT NULL,
	`project_id` INTEGER NOT NULL,
	PRIMARY KEY (`team_id`,`project_id`),
	INDEX `teams_projects_FI_1` (`project_id`),
	CONSTRAINT `teams_projects_FK_1`
		FOREIGN KEY (`project_id`)
		REFERENCES `projects` (`id`),
	CONSTRAINT `teams_projects_FK_2`
		FOREIGN KEY (`team_id`)
		REFERENCES `teams` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- users_projects
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `users_projects`;

CREATE TABLE `users_projects`
(
	`user_id` INTEGER NOT NULL,
	`project_id` INTEGER NOT NULL,
	PRIMARY KEY (`user_id`,`project_id`),
	INDEX `users_projects_FI_1` (`project_id`),
	CONSTRAINT `users_projects_FK_1`
		FOREIGN KEY (`project_id`)
		REFERENCES `projects` (`id`),
	CONSTRAINT `users_projects_FK_2`
		FOREIGN KEY (`user_id`)
		REFERENCES `users` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- workplans
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `workplans`;

CREATE TABLE `workplans`
(
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`year` INTEGER NOT NULL,
	`weeklyWorkHours` INTEGER NOT NULL,
	`annualVacationDaysUpTo19` INTEGER NOT NULL,
	`annualVacationDays20to49` INTEGER NOT NULL,
	`annualVacationDaysFrom50` INTEGER NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `constr_name_unique` (`year`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- holidays
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `holidays`;

CREATE TABLE `holidays`
(
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`workplan_id` INTEGER NOT NULL,
	`dateOfHoliday` DATE NOT NULL,
	`fullDay` TINYINT(1) NOT NULL,
	`halfDay` TINYINT(1) NOT NULL,
	`oneHour` TINYINT(1) NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `holidays_FI_1` (`workplan_id`),
	CONSTRAINT `holidays_FK_1`
		FOREIGN KEY (`workplan_id`)
		REFERENCES `workplans` (`id`)
		ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- days
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `days`;

CREATE TABLE `days`
(
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`workplan_id` INTEGER NOT NULL,
	`dateOfDay` DATE NOT NULL,
	`weekDayName` CHAR(3) NOT NULL,
	`iso8601Week` INTEGER NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `days_FI_1` (`workplan_id`),
	CONSTRAINT `days_FK_1`
		FOREIGN KEY (`workplan_id`)
		REFERENCES `workplans` (`id`)
		ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- tags
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `tags`;

CREATE TABLE `tags`
(
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`day_id` INTEGER NOT NULL,
	`user_id` INTEGER NOT NULL,
	`expiration_date` DATETIME,
	`type` VARCHAR(50) NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `tags_FI_1` (`day_id`),
	INDEX `tags_FI_2` (`user_id`),
	CONSTRAINT `tags_FK_1`
		FOREIGN KEY (`day_id`)
		REFERENCES `days` (`id`)
		ON DELETE CASCADE,
	CONSTRAINT `tags_FK_2`
		FOREIGN KEY (`user_id`)
		REFERENCES `users` (`id`)
		ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- entries
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `entries`;

CREATE TABLE `entries`
(
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`day_id` INTEGER NOT NULL,
	`user_id` INTEGER NOT NULL,
	`descendant_class` VARCHAR(100),
	PRIMARY KEY (`id`),
	INDEX `entries_FI_1` (`day_id`),
	INDEX `entries_FI_2` (`user_id`),
	CONSTRAINT `entries_FK_1`
		FOREIGN KEY (`day_id`)
		REFERENCES `days` (`id`)
		ON DELETE CASCADE,
	CONSTRAINT `entries_FK_2`
		FOREIGN KEY (`user_id`)
		REFERENCES `users` (`id`)
		ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- regularentries
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `regularentries`;

CREATE TABLE `regularentries`
(
	`regularentrytype_id` INTEGER NOT NULL,
	`from` TIME NOT NULL,
	`until` TIME NOT NULL,
	`comment` VARCHAR(255),
	`time_interval` INTEGER NOT NULL,
	`id` INTEGER NOT NULL,
	`day_id` INTEGER NOT NULL,
	`user_id` INTEGER NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `regularentries_FI_1` (`regularentrytype_id`),
	INDEX `regularentries_I_2` (`day_id`),
	INDEX `regularentries_I_3` (`user_id`),
	CONSTRAINT `regularentries_FK_1`
		FOREIGN KEY (`regularentrytype_id`)
		REFERENCES `regularentrytypes` (`id`),
	CONSTRAINT `regularentries_FK_2`
		FOREIGN KEY (`id`)
		REFERENCES `entries` (`id`)
		ON DELETE CASCADE,
	CONSTRAINT `regularentries_FK_3`
		FOREIGN KEY (`day_id`)
		REFERENCES `days` (`id`)
		ON DELETE CASCADE,
	CONSTRAINT `regularentries_FK_4`
		FOREIGN KEY (`user_id`)
		REFERENCES `users` (`id`)
		ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- projectentries
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `projectentries`;

CREATE TABLE `projectentries`
(
	`project_id` INTEGER NOT NULL,
	`team_id` INTEGER,
	`time_interval` INTEGER NOT NULL,
	`id` INTEGER NOT NULL,
	`day_id` INTEGER NOT NULL,
	`user_id` INTEGER NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `projectentries_FI_1` (`project_id`),
	INDEX `projectentries_FI_2` (`team_id`),
	INDEX `projectentries_I_3` (`day_id`),
	INDEX `projectentries_I_4` (`user_id`),
	CONSTRAINT `projectentries_FK_1`
		FOREIGN KEY (`project_id`)
		REFERENCES `projects` (`id`)
		ON DELETE CASCADE,
	CONSTRAINT `projectentries_FK_2`
		FOREIGN KEY (`team_id`)
		REFERENCES `teams` (`id`)
		ON DELETE CASCADE,
	CONSTRAINT `projectentries_FK_3`
		FOREIGN KEY (`id`)
		REFERENCES `entries` (`id`)
		ON DELETE CASCADE,
	CONSTRAINT `projectentries_FK_4`
		FOREIGN KEY (`day_id`)
		REFERENCES `days` (`id`)
		ON DELETE CASCADE,
	CONSTRAINT `projectentries_FK_5`
		FOREIGN KEY (`user_id`)
		REFERENCES `users` (`id`)
		ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- ooentries
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `ooentries`;

CREATE TABLE `ooentries`
(
	`oobooking_id` INTEGER NOT NULL,
	`type` VARCHAR(50) NOT NULL,
	`id` INTEGER NOT NULL,
	`day_id` INTEGER NOT NULL,
	`user_id` INTEGER NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `ooentries_FI_1` (`oobooking_id`),
	INDEX `ooentries_I_2` (`day_id`),
	INDEX `ooentries_I_3` (`user_id`),
	CONSTRAINT `ooentries_FK_1`
		FOREIGN KEY (`oobooking_id`)
		REFERENCES `oobookings` (`id`)
		ON DELETE CASCADE,
	CONSTRAINT `ooentries_FK_2`
		FOREIGN KEY (`id`)
		REFERENCES `entries` (`id`)
		ON DELETE CASCADE,
	CONSTRAINT `ooentries_FK_3`
		FOREIGN KEY (`day_id`)
		REFERENCES `days` (`id`)
		ON DELETE CASCADE,
	CONSTRAINT `ooentries_FK_4`
		FOREIGN KEY (`user_id`)
		REFERENCES `users` (`id`)
		ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- adjustmententries
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `adjustmententries`;

CREATE TABLE `adjustmententries`
(
	`type` VARCHAR(50) NOT NULL,
	`creator` VARCHAR(50) NOT NULL,
	`value` FLOAT NOT NULL,
	`reason` VARCHAR(255),
	`id` INTEGER NOT NULL,
	`day_id` INTEGER NOT NULL,
	`user_id` INTEGER NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `adjustmententries_I_1` (`day_id`),
	INDEX `adjustmententries_I_2` (`user_id`),
	CONSTRAINT `adjustmententries_FK_1`
		FOREIGN KEY (`id`)
		REFERENCES `entries` (`id`)
		ON DELETE CASCADE,
	CONSTRAINT `adjustmententries_FK_2`
		FOREIGN KEY (`day_id`)
		REFERENCES `days` (`id`)
		ON DELETE CASCADE,
	CONSTRAINT `adjustmententries_FK_3`
		FOREIGN KEY (`user_id`)
		REFERENCES `users` (`id`)
		ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- regularentrytypes
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `regularentrytypes`;

CREATE TABLE `regularentrytypes`
(
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`type` VARCHAR(50) NOT NULL,
	`creator` VARCHAR(50) NOT NULL,
	`worktimeCreditAwarded` TINYINT(1) NOT NULL,
	`enabled` TINYINT(1) NOT NULL,
	`defaultType` TINYINT(1) DEFAULT 0 NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `constr_name_unique` (`type`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- oobookingtypes
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `oobookingtypes`;

CREATE TABLE `oobookingtypes`
(
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`type` VARCHAR(50) NOT NULL,
	`paid` TINYINT(1) NOT NULL,
	`creator` VARCHAR(50) NOT NULL,
	`bookableInDays` TINYINT(1) NOT NULL,
	`bookableInHalfDays` TINYINT(1) NOT NULL,
	`rgbColorValue` CHAR(6),
	`enabled` TINYINT(1) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `constr_name_unique` (`type`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- auditevents
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `auditevents`;

CREATE TABLE `auditevents`
(
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`user_id` INTEGER NOT NULL,
	`timestamp` DATETIME NOT NULL,
	`sourcekey` VARCHAR(50) NOT NULL,
	`action` VARCHAR(50) NOT NULL,
	`details` TEXT NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `auditevents_FI_1` (`user_id`),
	CONSTRAINT `auditevents_FK_1`
		FOREIGN KEY (`user_id`)
		REFERENCES `users` (`id`)
		ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- oobookings
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `oobookings`;

CREATE TABLE `oobookings`
(
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`user_id` INTEGER NOT NULL,
	`oobookingtype_id` INTEGER NOT NULL,
	`autoAssignWorktimeCredit` TINYINT(1),
	PRIMARY KEY (`id`),
	INDEX `oobookings_FI_1` (`user_id`),
	INDEX `oobookings_FI_2` (`oobookingtype_id`),
	CONSTRAINT `oobookings_FK_1`
		FOREIGN KEY (`user_id`)
		REFERENCES `users` (`id`)
		ON DELETE CASCADE,
	CONSTRAINT `oobookings_FK_2`
		FOREIGN KEY (`oobookingtype_id`)
		REFERENCES `oobookingtypes` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- oorequests
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `oorequests`;

CREATE TABLE `oorequests`
(
	`id` INTEGER NOT NULL,
	`status` VARCHAR(50) NOT NULL,
	`originator_comment` TEXT,
	PRIMARY KEY (`id`),
	CONSTRAINT `oorequests_FK_1`
		FOREIGN KEY (`id`)
		REFERENCES `oobookings` (`id`)
		ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- settings
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `settings`;

CREATE TABLE `settings`
(
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`key` VARCHAR(50) NOT NULL,
	`value` VARCHAR(250) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `constr_name_unique` (`key`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- applicationscope
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `applicationscope`;

CREATE TABLE `applicationscope`
(
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`key` VARCHAR(100) NOT NULL,
	`value` TEXT NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `constr_name_unique` (`key`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
