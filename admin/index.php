<?
define('USE_DEBUG', false);
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

#echo($action . ' - ' . implode(",", $query) . ' - ' . implode('/', $url) . '<br/>');

$obj = FactoryAdmin::createObject($action, $query);

// get result
$_content = $obj->templatify();

if ($obj->empty_template) {
    // show raw template (header and body only)
    include(PATH_TEMPLATES.'main_empty.php');
} else {
    // show main template here
    include(PATH_TEMPLATES.'main.php');
}

ob_end_flush();
?>