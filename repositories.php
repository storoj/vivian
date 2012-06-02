<?php

// this file initializes all the repositories
// for the project
// need to be executed only once

define("USE_DEBUG", false);
// initialize
include('init.php');

$manager = FileManager::getInstance();

// avatars repository
$settings = array(
    'allowed_ext' => array('jpg', 'jpeg', 'png', 'gif'),
    'nesting' => 3,
    'min_img_size' => array('w' => 42, 'h' => 42),
    'max_file_size' => '500K',
    'resize' => array(
        'main' => array(
            'w' => 42,
            'h' => 42
        ),
        'tmb31' => array(
            'w' => 31,
            'h' => 31
        ),
        'tmb20' => array(
            'w' => 20,
            'h' => 20
        )
    )
);
$description = 'User avatar repository';
$manager->addRepository('avatar', PATH_FILES, $settings, $description);

// project files repository
$settings = array(
    'allowed_ext' => array('jpg', 'jpeg', 'png', 'gif'),
    'nesting' => 4,
    'min_file_size' => '50K',
    'min_img_size' => array('w' => '640'),
    'max_file_size' => '10M',
    'resize' => array(
        'common' => array(
            'w' => 618
        ),
        'tmb168' => array(
            'w' => 168,
            'h' => 112
        ),
        'tmb128' => array(
            'w' => 128,
            'h' => 85
        ),
        'tmb78' => array(
            'w' => 78,
            'h' => 52
        ),
        'tmb30' => array(
            'w' => 30,
            'h' => 20
        )
    )
);
$description = 'Repository for user project files';
$manager->addRepository('projects', PATH_FILES, $settings, $description);

$manager->showErrors();

// some more repositories to add later

?>