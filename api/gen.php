<?php session_start();
include_once (__DIR__."/../config.php");
include_once(__DIR__."/../functions/global.php");
include_once(__DIR__."/../lib/Mailer.php");

$errors = [];
$required_fields = ['username', 'password', 'cpass', 'country', 'key', 'phone', 'nationality', 'email', 'confirmEmail'];
$errors = validate($required_fields);
$userName = trim($_POST["username"]);
$password = $_POST["password"];
$confirmPassword = $_POST["cpass"];
$country = trim($_POST["country"]);
$key = trim($_POST["key"]);
$phone = trim($_POST["phone"]);
$nationality = trim($_POST["nationality"]);
$email = trim($_POST["email"]);
$confirmEmail = trim($_POST["confirmEmail"]);
$role = "creator";
$plan_id = trim($_POST["plan_id"]?:1);
$payment_type = trim($_POST["payment_type"]?:'credit');
$mostpopular = isset($_POST['mostpopular']) ? 1 : 0;
if(checkUserNameExists($userName, 0)){
	$errors['username'] = trans("usernameExists");
}
if($password != $confirmPassword){
	$errors['password'] = trans("passwordMatch");
}
if($email != $confirmEmail){
	$errors['email'] = trans("emailMatch");
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	$errors['email'] = trans("invalidEmail");
}
if(checkEmailExists($email, 0)){
	$errors['email'] = trans("emailExists");
}
if(empty($errors)) {
    $profile_image = "images/default-user.png";
    $user_id = add_user($userName, $password, null, $country, $phone, $email, $profile_image, $role, null, null,
        null, null, $nationality, null, null, null, null, null, null, null);
    $family_id = add_family(" ", " ", " ", " ", 0, $country, $plan_id, $mostpopular, $user_id, 0);
	$actual_plan = DBPlan::find($plan_id);
	$plan_price = $actual_plan->price;
	if($plan_price > 0){
		$total_price = $plan_price;
		$purpose = 'sign up';
		if($mostpopular !== 0){
			$total_price =  $total_price + 1;
			$purpose = 'sign up + mostpopular';
		}
		if ($_POST['payment_type'] == 'wire'){
			$transaction_id = $_POST['wire_transfer'];
			$payment = Payment::create([
					'user_id'=>$user_id, 'family_id'=>$family_id,'payment_id'=>$transaction_id,'value'=>$total_price,'payment_type'=>'wire', 'confirmed'=>false,
					'date' => date('Y-m-d' ), 'purpose' => $purpose
			]);
			$payment_id = $payment->id;
			$start_date = date('Y-m-d' );
			$end_date = date('Y-m-d', strtotime('+1 years'));
			$capsule->table('family_plans')->insert([
					'plan_id' => $plan_id,'family_id'=>$family_id,'payment_id'=>$payment_id,
					'start_date'=>$start_date,'end_date'=>$end_date
			]);
			$url = $siteUrl.$RELATIVE_PATH."admin/payments_view.php";
//            mail('admin@alhamayel.com' ,"AlHamayel Family Tree",$htmlMsg,implode("\r\n", $headers));
			$mailer = new Mailer();
			$mailer->setVars(['user_name'=>$userName, 'url'=>$url]);
			$mailer->sendMail(['admin@alhamayel.com'], "New Bank Transfer",
					'bank_transfer.html', 'bank_transfer.txt');
			sendActivationEmail($user_id);
			$_SESSION['registration_successful'] = trans('register_success');
			echo json_encode([
					'status' => true,
					'done' => true
			]);
			die();
		} else {
			$_SESSION['waiting_for_payment'] = $user_id;
			$_SESSION['mostpopular'] = $mostpopular;
			$_SESSION['total_price'] = $total_price;
			if(!isset($_POST['total']) || empty($_POST['total'])){
				echo json_encode([
						'status' => false,
						'error' => 1,
						'message' => trans('payment_gateway_error')
				]);
				die();
			}
			$_SESSION['total']=$_POST['total'];
			$_SESSION['plan_id']=$_POST['plan_id'];
			$_SESSION['_POST']=$_POST;
			$rand="hama-".mt_rand(1000000,9999999999);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,"https://credimax.gateway.mastercard.com/api/nvp/version/57");
			curl_setopt($ch, CURLOPT_POST, 8);
			curl_setopt($ch, CURLOPT_POSTFIELDS,"apiOperation=CREATE_CHECKOUT_SESSION&interaction.operation=PURCHASE&apiPassword=c64c09161e4fba0ad70b46b77b5bc4e2&interaction.returnUrl=".$siteUrl.$RELATIVE_PATH."/response.php&apiUsername=merchant.E15701950&merchant=E15701950&order.id=$rand&order.currency=USD&order.amount=$total_price");
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
	}
	else {
		$start_date = date('Y-m-d' );
		$end_date = date('Y-m-d', strtotime('+3 months'));
//		$sql = "INSERT INTO family_plans (plan_id, family_id, payment_id, start_date, end_date) VALUES" .
//				"($plan_id, $family_id, '', '$start_date', '$end_date')";
		$capsule->table('family_plans')->insert([
				'plan_id' => $plan_id,'family_id'=>$family_id,'payment_id'=>"0",
				'start_date'=>$start_date,'end_date'=>$end_date
		]);
		sendActivationEmail($user_id);
//		$con->query($sql);
		$_SESSION['registration_successful'] = trans('register_success');
		echo json_encode([
				'status' => true,
				'done' => true
		]);
		die();
	}
	//sendActivationEmail($user_id);
} else {
	echo json_encode([
			'status' => false,
			'error' => 0,
			'errors' => $errors
	]);
	die();
}

