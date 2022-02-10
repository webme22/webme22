<?ob_start(); ?>
<?php session_start();
define('_DEFVAR', 1);

if (file_exists(dirname(__FILE__)."/../settings.php")) {
    include_once(dirname(__FILE__)."/../settings.php");
}
if (file_exists(dirname(__FILE__)."/functions/helpers.php")) {
    include_once(dirname(__FILE__)."/functions/helpers.php");
}
$redirected_url = currentUrl($_SERVER);
$lang_param = "?lang=";
if (strpos($redirected_url, '?') !== false) {
    $lang_param = "&lang=";
}
$align = ($lang == "en")? "left" : "right";
if(isset($_GET['lang'])){
    $lang = $_GET['lang'];
    $_SESSION['lang'] = $lang;
    header('location: ' . strip_param_from_url((isset($_SERVER["HTTPS"]) ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", "lang"));
} else {
    $lang = isset($_SESSION['lang'])? $_SESSION['lang'] : 'en';
    $_SESSION['lang'] = $lang;
}


$sit_url = $siteUrl;
date_default_timezone_set('Asia/Bahrain');
ini_set('session.gc_maxlifetime', 3600);

include("functions/languages.php");
include("connection.php");
include("functions/users_functions.php");
include("functions/family_functions.php");
include("functions/countries_functions.php");
include("functions/plans_functions.php");
include("functions/messages_functions.php");
include("functions/reviews_functions.php");
include("functions/about_functions.php");
include("functions/questions_functions.php");
include("functions/groups_functions.php");

function strip_param_from_url($url, $param)
{
    $base_url = strtok($url, '?'); 
    $parsed_url = parse_url($url);          
    $query = $parsed_url['query'];           
    parse_str($query, $parameters);  
    // var_dump($parameters); die();        
    unset( $parameters[$param] );         
    $new_query = http_build_query($parameters); 
    return $base_url.((strlen($new_query) > 0)? '?'.$new_query : '');      
}

function currentUrl($server){
    $http = 'http';
    if(isset($server['HTTPS'])){
        $http = 'https';
    }
    $host = $server['HTTP_HOST'];
    $requestUri = $server['REQUEST_URI'];
    return $http . '://' . htmlentities($host) . htmlentities($requestUri);
}
?>
