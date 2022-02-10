<?php
include_once ('lib/Mailer.php');
$old_email = '';
if(isset($_POST['email']) && isset($_POST['password']) && isset($_POST['email_html'])){
    if($_POST['password'] == 'hema' && !empty($_POST['email']) && ! empty($_POST['email_html'])){
	file_put_contents(__DIR__."/resources/views/mail/test_email.html", $_POST['email_html']);
	file_put_contents(__DIR__."/resources/views/mail/test_email.txt", strip_tags($_POST['email_html']));
	$mailer = new Mailer();
	$mails = ['eibrahim95@gmail.com'];
	if (! in_array($_POST['email'], $mails)){
	    array_push($mails, $_POST['email']);
    }
	$mailer->sendMail($mails, "Test Mail",
	    'test_email.html', 'test_email.txt');
	$error = false;

	$old_email = $_POST['email'];
    }
else {
    $error = true;
    }
}
else if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $error = true;
}
if($error){
    $message = "Are you kidding me !! , something is wrong check again";
    echo "<p style='color: red'>$message</p>";
}
else if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $message = "All good, mail sent";
    echo "<p style='color: green'>$message</p>";
}
?>
<style>
    .container {
	padding: 50px;
    }
    input, textarea {
	width: 100%
    }
</style>
<div class="container">
    <h1>Howdy</h1>
<form action="" method="POST">
    <label>Email</label><br>
    <input type="email" name="email" required value="<?=$old_email?>"><br>
    <label>Password</label><br>
    <input type="password" name="password" required><br>
    <label>HTML</label><br>
    <textarea rows="20" name="email_html" required></textarea><br><br>

    <input type="submit" value="send">
</form>
</div>
