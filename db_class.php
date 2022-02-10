<?php
include_once("settings.php");
$con = mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
mysqli_query($con, "set character_set_server='utf8'");
mysqli_query($con, "set names 'utf8'");
if(! $con){
    echo "There was an error with the database connection<br>";
    die(mysqli_connect_error());
}
function loggedin() {
    if (isset($_SESSION['user_id'])) {
        $loggedin = TRUE;
        return $loggedin;
    }
}
function get_success($the_success) {
    $get_success = '<div style="top: 0px; left: 0px;" class="notifyjs-corner"><div class="notifyjs-wrapper notifyjs-hidable">
      <div class="notifyjs-arrow" style=""></div>
      <div class="notifyjs-container" style=""><div class="notifyjs-metro-base notifyjs-metro-success">
      <div data-notify-html="image" class="image"><i class="fa fa-check"></i></div><div class="text-wrapper">
      <div data-notify-html="title" class="title">' . $the_success . '</div>
      <div data-notify-html="text" class="text">' . $the_success . '</div></div></div></div></div></div>';
    return $get_success;
}
function get_error($the_error) {
    $get_error = '<div style="top: 0px; left: 0px;" class="notifyjs-corner"><div class="notifyjs-wrapper notifyjs-hidable">
    	  <div class="notifyjs-arrow" style=""></div>
    	  <div class="notifyjs-container" style=""><div class="notifyjs-metro-base notifyjs-metro-error">
    	  <div data-notify-html="image" class="image"><i class="fa fa-exclamation"></i></div><div class="text-wrapper">
    	  <div data-notify-html="title" class="title"> ' . $the_error . ' </div>
    	  <div data-notify-html="text" class="text"> ' . $the_error . ' </div></div></div></div>
    	</div></div>';
    return $get_error;
}



?>
