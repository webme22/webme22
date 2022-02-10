<?php
include_once("config.php");
include_once(__DIR__."/functions/translation.php");
include_once(__DIR__."/lib/Mailer.php");
include_once(__DIR__."/lib/Plan.php");

if(!isset($_REQUEST['resultIndicator']) || empty($_REQUEST['resultIndicator'])) {
    $_SESSION['upgrade_fail'] = trans("upgrade_fail");
    header("Location: profile.php");
}
if($_SESSION['successIndicator'] != $_REQUEST['resultIndicator']){
    $_SESSION['upgrade_fail'] = trans("upgrade_fail");
    header("Location: profile.php");
    exit();
}
$user_id = $_SESSION['waiting_for_mostpopular'];
//$user = $con->query("SELECT * from users WHERE user_id={$user_id}");
$user = User::find($user_id);
//$family = $con->query("SELECT * from family WHERE user_id={$user_id}");
$family = Family::where(['user_id'=>$user_id])->first();
$family_id = $family['id'];
$family_plan = new Plan($family_id);
$remaining_days = $family_plan->remaining_days();
$per_day = 1 / 365;
$price = ceil($remaining_days * $per_day);
$payment_type = trim($_POST["payment_type"]?:'credit');
$transaction_id = $_REQUEST['resultIndicator'];
//$payment_sql = "INSERT INTO payment (user_id, family_id, operation_order_id, payment_id, result_indicator, value, result, purpose, payment_type, confirmed, date)" .
//    "VALUES ($user_id, $family_id, ' ', '$transaction_id', ' ', $price, ' ', 'mostpopular', 'credit', false, '".date('Y-m-d' )."')";
//$con->query($payment_sql);
$payment = Payment::create([
		'user_id'=>$user_id, 'family_id'=>$family_id,'payment_id'=>$transaction_id,'value'=>$price,'payment_type'=>'credit', 'confirmed'=>true,
		'date' => date('Y-m-d' ),'purpose'=>'mostpopular'
]);
//$con->query("UPDATE `family` SET `mostpopular`=1 WHERE `id`='$family_id'");
$family->update(['mostpopular'=>1]);
$mailer = new Mailer();
$mailer->setVars(['user_name'=>$user['name']]);
$mailer->sendMail([$user['email']], "Subscription Successful",
    'mostpopular_successful.html', 'mostpopular_successful.txt');
unset($_SESSION['waiting_for_mostpopular']);
unset($_SESSION['plan_id']);
unset($_SESSION['_POST']);
unset($_SESSION['plan_id']);
unset($_SESSION['total_price']);
unset($_SESSION['mostpopular']);
$_SESSION['upgrade_successful'] = trans('mostpopular_successful');
header("Location: profile.php");
