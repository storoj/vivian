<?php

// DB settings
define('DB_PREFIX', 'pipe_');
define('DB_NAME', 'pipe_db');
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('ADMIN_PREFIX', 'vivian_');

// path settings
define('PATH_ROOT', $_SERVER['DOCUMENT_ROOT'].'/admin/');
define('PATH_CLASSES', PATH_ROOT.'classes/');
define('PATH_TEMPLATES', PATH_ROOT . 'templates/');
define('PATH_TEMPLATES_SQL', PATH_ROOT . 'db_setup/');

define('PATH_SITE_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/');
define('PATH_SITE_CLASSES', PATH_SITE_ROOT.'classes/');

?>