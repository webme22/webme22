<?php
ini_set('session.gc_maxlifetime', 3600);
session_start();
define('_DEFVAR', 1);
include_once(__DIR__."/settings.php");
include_once(__DIR__."/functions/translation.php");
include_once(__DIR__."/functions/helpers.php");
$redirected_url = currentUrl($_SERVER);
$lang_param = "?lang=";
if (strpos($redirected_url, '?') !== false) {
    $lang_param = "&lang=";
}
if(isset($_GET['lang']) && $_SERVER['REQUEST_METHOD'] != 'POST'){
    $lang = $_GET['lang'];
    $_SESSION['lang'] = $lang;
    header('location: ' . strip_param_from_url((isset($_SERVER["HTTPS"]) ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", "lang"));
}else if(isset($_GET['lang']) && $_SERVER['REQUEST_METHOD'] != 'POST'){
	$lang = $_GET['lang'];
	$_SESSION['lang'] = $lang;
}
else {
    $lang = isset($_SESSION['lang'])? $_SESSION['lang'] : 'en';
    $_SESSION['lang'] = $lang;
}
$align = ($lang == "en")? "left" : "right";
$align_2 = ($lang == "en")? "right" : "left";
date_default_timezone_set('Asia/Bahrain');
include_once(__DIR__."/functions/languages.php");
//include_once(__DIR__."/db_class.php");
include_once(__DIR__."/bootstrap.php");
include_once(__DIR__."/functions/global.php");

