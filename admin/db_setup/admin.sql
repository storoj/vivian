CREATE TABLE IF NOT EXISTS `{db_prefix}vivian_admin`(
  `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `date` INT(10),
  `email` VARCHAR(128),
  `login` VARCHAR(64),
  `password` VARCHAR(32),
  `role` TINYINT(1),
  `last_login` INT(10),
  `right` TEXT
) ENGINE {db_engine}, DEFAULT CHARSET UTF8;