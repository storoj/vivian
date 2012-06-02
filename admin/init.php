<?php

// configuration file
include('config.php');

// loading class files automatically
function __autoload($class_name) {
    // change uppercase symbols to dot
    $class = strtolower(preg_replace('#([^A-Z])([A-Z])#', '$1.$2', $class_name));
    // set reverse name order
    $class = array_reverse(explode('.', $class));

    $dir = $class[0];
    $fileName = strtolower(implode('.', $class)).'.php';

    if(is_dir(PATH_CLASSES.$dir)){
        $filePath = $dir.'/'.$fileName;
    } else {
        $filePath = $fileName;
    }

    $includeFile = PATH_CLASSES . $filePath;

    //echo 'trying file <strong>'.$includeFile.'</strong><br/>';

    if (file_exists($includeFile)){
        include $includeFile;
    } else {
        if(is_dir(PATH_SITE_CLASSES.$dir)){
            $filePath = $dir.'/'.$fileName;
        }
        $includeFile = PATH_SITE_CLASSES . $filePath;
        if (file_exists($includeFile)){
            //echo 'trying file <strong>'.$includeFile.'</strong><br/>';
            include $includeFile;
        } else {
            #throw new Exception("no file to include", 1);
            //echo 'no file <strong>'.$includeFile.'</strong>';
        }
    }
}

// connecting db
if (!DB::getInstance()->connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PREFIX)) {
    die ('DB connection error!');
}

?>