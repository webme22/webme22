<?php
	@session_start();
	$_SESSION = [];
    session_destroy();
    die("<meta http-equiv='Refresh' Content='0;URL=home.php'>");
?>
