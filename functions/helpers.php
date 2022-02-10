<?php
defined('_DEFVAR') or exit('Restricted Access');

include_once(__DIR__."/../settings.php");
if (!function_exists('errExist')){
	function errExist(){
		if (empty($_SESSION['errors'])){
			return false;
		}
		return true;
	}
}
if (!function_exists('inputErr')) {
	function inputErr($name)
	{
		if (errExist()) {
			if (array_key_exists($name, $_SESSION['errors'])) {
				$return_val = $_SESSION['errors'][$name];
//            unset($_SESSION['errors'][$name]);
				return $return_val;
			}
		}
		return false;
	}
}
if (!function_exists('validate')) {

	function validate($required)
	{
		$errors = [];
		foreach ($required as $required_field) {
			if (!isset($_POST[$required_field]) || $_POST[$required_field] == '') {
				$errors[$required_field] = ucfirst($required_field) . " Is Required";
			}
		}
		return $errors;
	}
}
function asset($file){
	global $RELATIVE_PATH;
	if (filter_var($file, FILTER_VALIDATE_URL)) {
		return $file;
	}
	else {
		return preg_replace('~/+~', '/', '/'.trim($RELATIVE_PATH, '/').'/'.trim($file, '/'));
	}
}
function thumb($file){
	$thumb = dirname($file) . "/thumbnails/".basename($file);
	if (file_exists($thumb)){
		return asset($thumb);
	}
	else {
		return asset($file);
	}
}
function current_user(){
	return isset($_SESSION['user_id'] ) ? $_SESSION['user_id'] : null;
}
function logged_in(){
	return current_user()?true:false;
}
function has_access($family_id){
	if (! logged_in()) return false;
	if (! isset($_SESSION['family_id'])) return false;
	$logged_family = $_SESSION['family_id'];
	if ($logged_family == $family_id) return true;

}
function user_can_view($family_id){
	if (!has_access($family_id)){
		header('HTTP/1.0 403 Forbidden');
		die("unauthorized");;
	}
}
function middleware($name){
	include_once(__DIR__."/../functions/translation.php");
	include_once(__DIR__."/../lib/Plan.php");
	switch ($name){
		case 'guest':
			if (logged_in()){
				header('Location: home.php');
			}
			break;
		case 'user':
			$user_id = current_user();
			if($user_id){
//				$user = mysqli_fetch_assoc($user);
				$user = User::find($user_id);
				if(! $user->verified){
					header("Location: complete_registration.php");
					exit();
				}
				if ($user->role == 'creator' && $user->family_id == -1){
					header("Location: complete_registration.php");
					exit();
				}
				$family_id = $_SESSION['family_id'];
				$family_plan = new Plan($family_id);
				if($family_plan->remaining_days() < 0){
					$_SESSION['plan_expired'] = trans('plan_expired');
					header("Location: renew_plan.php");
					exit();
				}
			}
			else {
				header("Location: login.php");
				exit();
			}
			break;
		case 'valid_plan':

			break;
		case 'user_can_renew':
			$user_id = current_user();
			if($user_id) {
				$user = User::find($user_id);
				if(! $user['verified']){
					header("Location: complete_registration.php");
				}
				if ($user['role'] == 'creator' && $user['family_id'] == -1){
					header("Location: complete_registration.php");
					exit();
				}
				$family_id = $_SESSION['family_id'];
				$family_plan = new Plan($family_id);
				if(! $family_plan->renewable()){
					header("Location: profile.php");
				}
			}
			else {
				header("Location: login.php");
			}
			break;
		case 'user_not_complete':
			$user_id = current_user();
			if($user_id){
				$user = User::find($user_id);
				if($user['verified'] == 1 &&  $user['family_id'] != -1){
					header("Location: home.php");
				}
			}
			else {
				header("Location: home.php");
			}
			break;
	}
}
function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
	$output = NULL;
	if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
		$ip = $_SERVER["REMOTE_ADDR"];
		if ($deep_detect) {
			if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
				$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
	}
	$purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
	$support    = array("country", "countrycode", "state", "region", "city", "location", "address");
	$continents = array(
			"AF" => "Africa",
			"AN" => "Antarctica",
			"AS" => "Asia",
			"EU" => "Europe",
			"OC" => "Australia (Oceania)",
			"NA" => "North America",
			"SA" => "South America"
	);
	if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
		$ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
		if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
			switch ($purpose) {
				case "location":
					$output = array(
							"city"           => @$ipdat->geoplugin_city,
							"state"          => @$ipdat->geoplugin_regionName,
							"country"        => @$ipdat->geoplugin_countryName,
							"country_code"   => @$ipdat->geoplugin_countryCode,
							"continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
							"continent_code" => @$ipdat->geoplugin_continentCode
					);
					break;
				case "address":
					$address = array($ipdat->geoplugin_countryName);
					if (@strlen($ipdat->geoplugin_regionName) >= 1)
						$address[] = $ipdat->geoplugin_regionName;
					if (@strlen($ipdat->geoplugin_city) >= 1)
						$address[] = $ipdat->geoplugin_city;
					$output = implode(", ", array_reverse($address));
					break;
				case "city":
					$output = @$ipdat->geoplugin_city;
					break;
				case "state":
					$output = @$ipdat->geoplugin_regionName;
					break;
				case "region":
					$output = @$ipdat->geoplugin_regionName;
					break;
				case "country":
					$output = @$ipdat->geoplugin_countryName;
					break;
				case "countrycode":
					$output = @$ipdat->geoplugin_countryCode;
					break;
			}
		}
	}
	return $output;
}
function sendActivationEmail($user_id){
	include_once(__DIR__."/../lib/Mailer.php");
	global $siteUrl;
//	$user = $con->query("SELECT * from users WHERE user_id={$user_id}");
	$user = User::find($user_id);;
	$hashedPassword = password_hash($user['user_name'], PASSWORD_DEFAULT);
	$url = "$siteUrl/login.php?activation=" . $hashedPassword;

	$mailer = new Mailer();
	$mailer->setVars(['user_name'=>$user['user_name'], 'url'=>$url]);
	return $mailer->sendMail([$user['email']], "Activate Your Account", 'activation.html', 'activation.txt');
//    mail($user['email'] ,"AlHamayel Family Tree",$htmlMsg,implode("\r\n", $headers));
}
function strip_param_from_url($url, $param)
{
	$base_url = strtok($url, '?');
	$parsed_url = parse_url($url);
	$query = $parsed_url['query'];
	parse_str($query, $parameters);
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
