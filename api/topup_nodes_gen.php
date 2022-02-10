<?php session_start();
include_once (__DIR__."/../config.php");
include_once(__DIR__."/../functions/global.php");
include_once(__DIR__."/../lib/Mailer.php");
include_once(__DIR__."/../lib/Plan.php");

$user_id = $_SESSION['user_id'];
//$user =  $con->query("SELECT * FROM users where id={$user_id}");
$user = User::find($user_id);
$price=175;
$family_id = $_SESSION['family_id'];
$family_plan = new Plan($family_id);

$payment_type = trim($_POST["payment_type"]?:'credit');

if ($_POST['payment_type'] == 'wire'){
    $transaction_id = $_POST['wire_transfer'];
//    $payment_sql = "INSERT INTO payment (user_id, operation_order_id, payment_id, result_indicator, value, result,
//                     payment_type, confirmed, date)" .
//			"VALUES ($user_id, ' ', '$transaction_id', ' ', 175, ' ', 'wire', false, '".date('Y-m-d' )."')";
//	$con->query($payment_sql);
	$payment = Payment::create([
					'user_id'=>$user_id, 'family_id'=>$family_id,'payment_id'=>$transaction_id,'value'=>$price,'payment_type'=>'wire', 'confirmed'=>false,
					'date' => date('Y-m-d' ), 'purpose' => 'topup nodes'
			]);
    $payment_id = $payment->id;
    $added_on = date('Y-m-d' );
    $created_at = date('Y-m-d H:i:s');
    $updated_at = $created_at;
//    $sql = "INSERT INTO topups (user_id, family_id, payment_id, added_on, `type`, `value`, created_at, updated_at) VALUES" .
//	"($user_id, $family_id, $payment_id, '$added_on', 'nodes', '50', '$created_at', '$updated_at')";
//    $con->query($sql);
	Topup::create([
			'user_id'=>$user_id,'family_id'=>$family_id,'payment_id'=>$payment_id,'added_on'=>$added_on,'type'=>'nodes',
			'value'=>'50'
	]);
    $url = $siteUrl.$RELATIVE_PATH."admin/payments_view.php";
    $mailer = new Mailer();
    $mailer->setVars(['user_name'=>$user['name'], 'url'=>$url]);
    $mailer->sendMail(['admin@alhamayel.com'], "New Bank Transfer",
	'bank_transfer.html', 'bank_transfer.txt');
    $mailer = new Mailer();
    $mailer->setVars(['user_name'=>$user['name']]);
    $mailer->sendMail([$user['email']], "Bank transfer waiting approval",
	'waiting_transfer_approve.html', 'waiting_transfer_approve.txt');
    $_SESSION['upgrade_successful'] = trans("upgrade_bank_transfer_success");
    echo json_encode([
	'status' => true,
	'done' => true
    ]);
    die();
}
else {
    $_SESSION['waiting_for_topupnodes_payment'] = $user_id;
    if(!isset($_POST['total']) || empty($_POST['total'])){
	echo json_encode([
	    'status' => false,
	    'error' => 1,
	    'message' => trans('payment_gateway_error')
	]);
	die();
    }
    $_SESSION['total']=$_POST['total'];
    $_SESSION['_POST']=$_POST;
    $rand="hama-".mt_rand(1000000,9999999999);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,"https://credimax.gateway.mastercard.com/api/nvp/version/57");
    curl_setopt($ch, CURLOPT_POST, 8);
    curl_setopt($ch, CURLOPT_POSTFIELDS,"apiOperation=CREATE_CHECKOUT_SESSION&interaction.operation=PURCHASE&apiPassword=c64c09161e4fba0ad70b46b77b5bc4e2&interaction.returnUrl=".$siteUrl.$RELATIVE_PATH."/topup_nodes_response.php&apiUsername=merchant.E15701950&merchant=E15701950&order.id=$rand&order.currency=USD&order.amount=$price");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch);
    curl_close ($ch);
    parse_str($server_output, $output);
    if($output['result']=="SUCCESS"){
	$data = [];
	$data['ref']=$rand;
	$data['successIndicator']=$output['successIndicator'];
	$_SESSION['successIndicator']=$output['successIndicator'];
	$data['session_ver']=$output['session_version'];
	$data['session_id']=$output['session_id'];
	$data['amount']=$output['tot'];
	$data['trackid']="track-".rand(10000000,99999999999);
	//redirect("pay.php");
	echo json_encode([
	    'status' => true,
	    'data' => $data,
	    'user_id' => $user_id
	]);
	die();
    }else{
	echo json_encode([
	    'status' => false,
	    'error' => 1,
	    'result' => $server_output,
	    'message' => trans('payment_gateway_error')
	]);
	die();
    }
}
