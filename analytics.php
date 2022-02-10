<?php
include_once(__DIR__.'/config.php');
include 'mobile_detect.php';

if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
	$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
	$_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
}
$client  = @$_SERVER['HTTP_CLIENT_IP'];
$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
$remote  = $_SERVER['REMOTE_ADDR'];

if(filter_var($client, FILTER_VALIDATE_IP))
{
	$ip = $client;
}
elseif(filter_var($forward, FILTER_VALIDATE_IP))
{
	$ip = $forward;
}
else
{
	$ip = $remote;
}

$ip_info = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));

$country = $ip_info->geoplugin_countryName;
// echo 'Country Code = '.$ip_info->geoplugin_countryCode.'<br/>';
$city = $ip_info->geoplugin_city;
$region = $ip_info->geoplugin_region;
// echo 'Latitude = '.$ip_info->geoplugin_latitude.'<br/>';
// echo 'Longitude = '.$ip_info->geoplugin_longitude.'<br/>';
// echo 'Timezone = '.$ip_info->geoplugin_timezone.'<br/>';
// echo 'Continent Code = '.$ip_info->geoplugin_continentCode.'<br/>';
// echo 'Continent Name = '.$ip_info->geoplugin_continentName.'<br/>';
// echo 'Timezone = '.$ip_info->geoplugin_timezone.'<br/>';
// echo 'Currency Code = '.$ip_info->geoplugin_currencyCode;


$detect = new Mobile_Detect();
$device = 'desktop';
if ($detect->isMobile()){
	$device = 'mobile';
}

$current_page = basename($_SERVER["REQUEST_URI"], ".php");

if(strlen($ip) > 1 && strlen($city) > 1 && strlen($country) > 1 && strlen($device) > 1 && strlen($current_page) > 1){
	$data = VisitorsInfo::orderBy('created_at', 'desc')->firstOrCreate([
			'session_id' => session_id(),],[
			'ip' => $ip,
			'city' => $city,
			'region' => $region,
			'country' => $country,
			'device' => $device,
			'page' => $current_page
	]);
	if($data->leave_at) {
		$now = time(); // or your date as well
		$your_date = strtotime($data->leave_at);
		$datediff = $now - $your_date;
		if ($datediff < 120) {
			$data->update(['leave_at' => null]);
		}
		else {
			VisitorsInfo::create([
					'session_id' => session_id(),
					'ip' => $ip,
					'city' => $city,
					'region' => $region,
					'country' => $country,
					'device' => $device,
					'page' => $current_page
			]);
		}
	}
}
