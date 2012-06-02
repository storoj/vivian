<?php

// DB settings
define('DB_PREFIX', 'pipe_');
define('DB_NAME', 'pipe_db');
define('DB_HOST', 'localhost');
define('DB_USER', 'pipe');
define('DB_PASS', 'pipe');

// path settings
define('PATH_ROOT', $_SERVER['DOCUMENT_ROOT'].'/');
define('PATH_CLASSES', PATH_ROOT.'classes/');
define('PATH_TEMPLATES', PATH_ROOT . 'templates/');
define('PATH_ENTITIES',  PATH_ROOT . 'entities/');
define('PATH_FILES', 'files/');
define('PATH_FILES_ABS',    PATH_ROOT . PATH_FILES);
define('PATH_FILES_TMP', PATH_FILES. 'tmp/');
define('PATH_FILES_TMP_ABS',    PATH_ROOT . PATH_FILES_TMP);

define('PROJECT_PERSONAL_LIST_PER_PAGE', 16);
define('PROJECT_MAIN_LIST_PER_PAGE', 12);
define('PROJECT_SINGLE_RAND_NUM', 2);
define('MESSAGES_LIST_PER_PAGE', 8);
define('COMMENTS_LIST_PER_PAGE', 10);
define('FRIENDS_LIST_PER_PAGE', 10);
define('ANSWERS_LIST_PER_PAGE', 4);
define('GROUPS_LIST_PER_PAGE', 4);

?>