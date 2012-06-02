CREATE TABLE IF NOT EXISTS `{db_prefix}vivian_settings`(
  `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `param` VARCHAR(32),
  `value` VARCHAR(64),
  `last_change` INT(10)
) ENGINE {db_engine}, DEFAULT CHARSET UTF8;