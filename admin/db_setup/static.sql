CREATE TABLE IF NOT EXISTS `{db_prefix}static`(
  `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `date` INT(10),
  `creator_id` INT(11),
  `last_change` INT(10),
  `editor_id` INT(11),
  `locked` TINYINT(1),
  `url` VARCHAR(256),
  `page_title` VARCHAR(256),
  `title` VARCHAR(256),
  `text` MEDIUMTEXT,
  `meta_keys` TEXT,
  `meta_description` TEXT,
  `settings` TEXT
) ENGINE {db_engine}, DEFAULT CHARSET UTF8;