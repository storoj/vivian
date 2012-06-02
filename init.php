<?php

// configuration file
include('config.php');

// loading class files automatically
function __autoload($class_name) {
    $class = strtolower(preg_replace('#([^A-Z])([A-Z])#', '$1.$2', $class_name));

    $class = array_reverse(explode('.', $class));

    $dir = $class[0];
    $fileName = strtolower(implode('.', $class)).'.php';

    if(is_dir(PATH_CLASSES.$dir)){
        $fileName = $dir.'/'.$fileName;
    }

    $includeFile = PATH_CLASSES . $fileName;
    
    if (file_exists($includeFile)){

        if (USE_DEBUG) {
            global $debugger;
            $debugger->addClass($class_name);
        }

        include $includeFile;
    } else {
        #throw new Exception("no file to include", 1);
        #echo 'no file <strong>'.$includeFile.'</strong>';
    }
}

// common functions (global)
include_once(PATH_ROOT . 'helper.php');

// initiate repository list
// comment this when repositories are set and stable
//include('repositories.php');

// connecting db
if (!DB::getInstance()->connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PREFIX)) {
    die ('DB connection error!');
}

// override default session handler

/*$handler = new SessionHelper();
session_set_save_handler(
    array($handler, 'open'),
    array($handler, 'close'),
    array($handler, 'read'),
    array($handler, 'write'),
    array($handler, 'destroy'),
    array($handler, 'gc')
);

ini_set('session.gc_maxlifetime', 10);*/

/*
 * hack for plupload flash runtime
 * setting session id from multipart params to get user object
 */
if (isset($_POST['TC_SESSID'])) {
    session_id($_POST['TC_SESSID']);
}

session_start();
