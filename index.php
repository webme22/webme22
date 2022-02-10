<?php
function getCurrentUri()
{
	$basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
	$uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));
	if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));
	$uri = '/' . trim($uri, '/');
	return $uri;
}
$base_url = getCurrentUri();
$routes = array();$pages= array();
$routes = explode('/', $base_url);//var_dump($routes);
foreach($routes as $route)
{
	if(trim($route) != '')
		array_push($pages, trim($route));
}
//print $_GET['optn'];exit;
//print $pages[0];exit;

$TargetPage=(isset($pages[0]) && !empty($pages[0]))?$pages[0]:"";
$TargetPage=strtolower($TargetPage);
switch($TargetPage)
{
	case ""		        :
	case "index"        :
	case "home"         :include 'home.php'; break;
	case "about"         :include 'about.php'; break;
	case "services"         :include 'services.php'; break;
	case "login"         :include 'login.php'; break;
	case "contactus"         :include 'contact.php'; break;
	case "joinus"         :include 'signup.php'; break;
	case "family-tree"         :include 'tree.php'; break;
	case "set-lang"         :include 'setlang.php'; break;
	case "node"         :include 'node.php'; break;
	case "logout"         :include 'logout.php'; break;
	case "activation"         :include 'active.php'; break;
	case "search"         :include 'search.php'; break;
	case "tree"         :include 'ptree.php'; break;
	case "edit-node"         :include 'edit-node.php'; break;
	case "edit-tree"         :include 'edit-tree.php'; break;
	case "album"         :include 'album.php'; break;
	case "mailer"         :include 'mailer.php'; break;
	default:
	case'error':
		include "home.php";  break;
}
?>
