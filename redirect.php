<?php

include_once("config.php");

$id = substr($_GET['id'], 2);
$id = substr($id, 0, -2);

//$result = $con->query("select link from invitation_links where id='$id'");
//$row = mysqli_fetch_assoc($result);
$row = InvitationLink::find($id);
header('location: ' . $row['link']);
