<?php
/**
 * TopCreator Core
 */

// turning on/off debugger
define('USE_DEBUG', true);
if (USE_DEBUG) {
    include('debugger/debugger.php');
    $debugger = new Debugger;
    $debugger->startDebug();

    // save debug panel state in cookies
    if (isset($_POST['debugger_state'])) {
        setcookie('gebug_panel_state', $_POST['debugger_state'], time() + 3600);
        exit;
    }
}

// initialize
include('init.php');

ob_start();

// Geting all the parameters from URL
// get action (generated in .htaccess)
$action = ucfirst(empty($_GET['action']) ? 'Index' : $_GET['action']);
// get query
$query = empty($_GET['query']) ? array() : explode('/', $_GET['query']);
if (empty($query[count($query)-1])) unset($query[count($query)-1]);
// save full url
$url = array_merge(array(strtolower($_GET['action'])), $query);

if ($action == 'Ajax') {
    // ajax class for simple actions
    $obj = new Ajax($query);

    // TODO make here exit point ?? (set for debug)
} else {
    $obj = Factory::createObject($action, $query);
}

// get result
$_content = $obj->templatify();

// check whether we use ajax or normal output
if ($obj->use_ajax) {
    // return json encoded content
    echo $_content;
} else {
    if ($obj->empty_template) {
        // show raw template (header and body only)
        include(PATH_TEMPLATES.'main_empty.php');
    } else {
        // show main template here
        include(PATH_TEMPLATES.'main.php');
    }
}

// showing content like this is only for debug
// TODO make it simple later :: need only ob_end_flush()
$_content = ob_get_contents();

ob_end_clean();

if (USE_DEBUG) {
    $debugger->endDebug();
    // looks like it slows down system a little
    $_content = str_replace('[{exec_time}]', $debugger->getExecTime(), $_content);
    if($debugger->enabled){
        $_content = str_replace('<!--debugger-->', $debugger->showDebugInfo(), $_content);
        //echo $debugger->showDebugInfo();
    }
}

echo $_content;
?>
