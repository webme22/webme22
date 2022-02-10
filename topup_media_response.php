<?php
include_once(__DIR__."/config.php");
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
$user_id = $_SESSION['waiting_for_topupmedia_payment'];
//$user = $con->query("SELECT * from users WHERE user_id={$user_id}");
$user = User::find($user_id);
//$family = $con->query("SELECT * from family WHERE user_id={$user_id}");
$family = Family::where(['user_id'=>$user_id])->first();
$family_id = $family['id'];
$transaction_id = $_REQUEST['resultIndicator'];
//$payment_sql = "INSERT INTO payment (user_id, operation_order_id, payment_id, result_indicator, value, result, payment_type, confirmed, date)" .
//    "VALUES ($user_id, ' ', '$transaction_id', ' ', 175, ' ', 'credit', true, '".date('Y-m-d' )."')";
//$con->query($payment_sql);
$payment = Payment::create([
		'user_id'=>$user_id, 'family_id'=>$family_id,'payment_id'=>$transaction_id,'value'=>175,'payment_type'=>'credit', 'confirmed'=>true,
		'date' => date('Y-m-d' ), 'purpose' => 'topup media'
]);
$payment_id = $payment->id;
$added_on = date('Y-m-d' );
$created_at = date('Y-m-d H:i:s');
$updated_at = $created_at;
//$sql = "INSERT INTO topups (user_id, family_id, payment_id, added_on, `type`, `value`, created_at, updated_at) VALUES" .
//    "($user_id, $family_id, $payment_id, '$added_on', 'media', '2.5', '$created_at', '$updated_at')";
//$con->query($sql);
Topup::create([
		'user_id'=>$user_id,'family_id'=>$family_id,'payment_id'=>$payment_id,'added_on'=>$added_on,'type'=>'media',
		'value'=>'2.5'
]);
$family_plan = new Plan($family_id);
$plan_name = $family_plan->plan_name();

$mailer = new Mailer();
$mailer->setVars(['user_name'=>$user['name'], 'plan_name'=>$plan_name]);
$mailer->sendMail([$user['email']], "Upgrade Successful",
    'upgrade_successful.html', 'upgrade_successful.txt');
unset($_SESSION['waiting_for_topupmedia_payment']);
unset($_SESSION['plan_id']);
unset($_SESSION['_POST']);
$_SESSION['upgrade_successful'] = trans('topup_media_successful');
header("Location: profile.php");
