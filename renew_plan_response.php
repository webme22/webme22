<?php
include_once("config.php");
include_once(__DIR__."/functions/translation.php");
include_once(__DIR__."/lib/Mailer.php");
include_once(__DIR__."/lib/Plan.php");

if(!isset($_REQUEST['resultIndicator']) || empty($_REQUEST['resultIndicator'])) {
    $_SESSION['upgrade_fail'] = trans("renew_fail");
    header("Location: profile.php");
}
if($_SESSION['successIndicator'] != $_REQUEST['resultIndicator']){
    $_SESSION['upgrade_fail'] = trans("renew_fail");
    header("Location: profile.php");
    exit();
}
$user_id = $_SESSION['waiting_for_renew_payment'];
$plan_id = $_SESSION['plan_id'];
$mostpopular = $_SESSION['mostpopular'];

//$user = $con->query("SELECT * from users WHERE user_id={$user_id}");
$user = User::find($user_id);
//$plan = $con->query("SELECT * from plans WHERE id={$plan_id}");
$plan = DBPlan::find($plan_id);

//$family = $con->query("SELECT * from family WHERE user_id={$user_id}");
$family = Family::where(['user_id'=>$user_id])->first();
$family_id = $family['id'];
$family_plan = new Plan($family_id);
//$update_user_family = "UPDATE users SET family_id=-1 where user_id={$user_id}";
//$con->query($update_user_family);
$plan_price = $plan['price'];
$total_price = $plan_price;
if($mostpopular !== 0){
    $total_price =  $total_price + 1;
//    $con->query("UPDATE family SET mostpopular=1 where id={$family_id}");
    $family->update(['mostpopular'=>1]);
    $purpose = 'renew plan + mostpopular';
}
else {
//    $con->query("UPDATE family SET mostpopular=0 where id={$family_id}");
	$family->update(['mostpopular'=>2]);
    $purpose = 'renew plan';
}
$transaction_id = $_REQUEST['resultIndicator'];
//$payment_sql = "INSERT INTO payment (user_id, operation_order_id, payment_id, result_indicator, value, result, payment_type, confirmed, date)" .
//    "VALUES ($user_id, ' ', '$transaction_id', ' ', $total_price, ' ', 'credit', true, '".date('Y-m-d' )."')";
//$con->query($payment_sql);
$payment = Payment::create([
		'user_id'=>$user_id, 'family_id'=>$family_id,'payment_id'=>$transaction_id,'value'=>$total_price,'payment_type'=>'credit', 'confirmed'=>true,
		'date' => date('Y-m-d' ), 'purpose' => $purpose
]);
$payment_id = $payment->id;
$remaining_days = $family_plan->remaining_days()>-1?$family_plan->remaining_days()+1:0;
$start_date = date('Y-m-d', strtotime('+'.($remaining_days).' days'));
$end_date = date('Y-m-d', strtotime('+1 years', strtotime($start_date)));
//$sql = "INSERT INTO family_plans (plan_id, family_id, payment_id, start_date, end_date) VALUES" .
//    "($plan_id, $family_id, $payment_id, '$start_date', '$end_date')";
//$con->query($sql);
$capsule->table('family_plans')->insert([
		'plan_id' => $plan_id,'family_id'=>$family_id,'payment_id'=>$payment_id,
		'start_date'=>$start_date,'end_date'=>$end_date
]);
$family_plan = new Plan($family_id);
$plan_name = $family_plan->plan_name();

$mailer = new Mailer();
$mailer->setVars(['user_name'=>$user['name'], 'plan_name'=>$plan_name]);
$mailer->sendMail([$user['email']], "Renew Successful",
    'upgrade_successful.html', 'upgrade_successful.txt');
unset($_SESSION['waiting_for_renew_payment']);
unset($_SESSION['plan_id']);
unset($_SESSION['total_price']);
unset($_SESSION['mostpopular']);
unset($_SESSION['_POST']);
$_SESSION['upgrade_successful'] = trans('renew_successful');
header("Location: profile.php");
