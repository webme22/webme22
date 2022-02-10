<?php
include_once(__DIR__."/../config.php");
include_once(__DIR__."/../functions/global.php");
include_once(__DIR__."/../lib/Mailer.php");
include_once(__DIR__."/../lib/Plan.php");
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;


if(isset($_POST['plan_id'])){
	$plan_id = trim($_POST['plan_id']);
	$plan = DBPlan::find($plan_id)->toArray();
	$family_plan = new Plan($_SESSION['family_id']);
	echo $family_plan->upgrade_price($plan);
}

if(isset($_POST['user_leave_event'])){
	VisitorsInfo::where('session_id', session_id())->orderBy('created_at', 'desc')->limit(1)->update([
		'leave_at' => date('Y-m-d H:i:s')
	]);
}

if(isset($_POST['family_to_access'])){
	echo Family::find($_POST['family_to_access'])->status;
}

if(isset($_GET['check_both_type_invitation']) && isset($_SESSION['user_id'])){
	echo $_SESSION['waiting_to_join'];
}

if(isset($_GET['get_user_data']) && isset($_SESSION['user_id'])){
	$user = getUserData($_SESSION['user_id']);
	$user['key'] = getCountry($user['country_id'])['countryKey'];
	echo json_encode($user);
}

if(isset($_POST['family_info'])){
	$family_id = $_POST['family_info'];
	$response = [];

	$response['access'] = FamilyAccess::where(['family_id'=>$family_id])->where(['accept'=>2])->count();
	$response['join']	= FamilyJoinRequest::where(['family_id'=>$family_id])->where(['status'=>2])->count();

	echo json_encode($response);
}
if(isset($_POST['check_user'])){
	$user_id = $_POST['check_user'];
	$data = getUserData($user_id);

	echo (empty($data['user_name']) && empty($data['user_password']))? 1 : 0;
}
if(isset($_POST['get_wife_possible_husbands'])){
	$wife_id = $_POST['get_wife_possible_husbands'];

	$users = User::male()->whereRaw("find_in_set($wife_id, wife)")->get(['user_id', 'wife', 'name']);

	echo json_encode($users);

}
// if(isset($_POST['check_for_wife'])){
// 	$husband_id = $_POST['check_for_wife'];
// 	$wifes_ids = User::find($husband_id)->wife;

// 	echo ($wifes_ids == 0)? $wifes_ids : check_wifes($wifes_ids);
// }
if(isset($_POST['check_for_wife'])){
	echo check_first_father_children($_POST['check_for_wife']);
}
if(isset($_POST['check_wife_exist'])){
	$husband_id = $_POST['check_wife_exist'];
	$user = new User();
	$wifes_ids = explode(",", $user->find($husband_id)->wife);
	echo $user->whereIn('user_id', $wifes_ids)->count();
}
if (isset($_POST['strangerName']) && isset($_POST['strangerEmail']) && isset($_POST['family']) && ! isset($_POST['strangerPhone'])) {
	$name = trim($_POST['strangerName']);
	$email = trim($_POST['strangerEmail']);
	$type = $_POST['role'];
	$family = $_SESSION['family_id'];
	if (checkPlan($family, "Members") && $type == 'Both') {
		$error = "Sorry, Your family already reached its permitted number of members, You can upgrade your plan .";
		echo json_encode([
				'success' => 0,
				'message' => $error
		]);
		exit();
	}
	$invitation = FamilyInvitation::create([
			'family_id' => $family,
			'user_id' => $_SESSION['user_id'],
			'type' => $type,
			'name'=>$name,
			'date'=>date('Y-m-d h:i:s a')
	]);
	$invitation_id = $invitation->id;
	$invitation_id = base64_encode(($invitation_id + 4548) . "bla&@bla");

	$url = $siteUrl.$RELATIVE_PATH."profile.php?type={$invitation_id}&flag={$family}";
	$faq_url = $siteUrl.$RELATIVE_PATH."/faq.php";
//	$con->query("insert into invitation_links values (null, '$family','$url')") or die(mysqli_error($con));
	$link = InvitationLink::create([
			'family_id' => $family,
			'link'=>$url
	]);
	$link_id = mt_rand(10,99) . $link->id . mt_rand(10,99);
	
	$mailer = new Mailer();
	$mailer->setVars(['user_name'=>$name, 'url'=>$url, 'type'=>$type, 'faq_url'=>$faq_url]);
	if ($type == 'Assistant') {
		$subject = "Invitation to be a Family Assistant";
		$message = "&nbsp; &nbsp;  &nbsp; &nbsp;   &nbsp;          We Invite you to be our assistant to manage our family tree, Please click on the below button to enter your information . ";
	}
	else {
		$subject = "Invitation to join our family";
		$message = "&nbsp; &nbsp;  &nbsp; &nbsp;   &nbsp;          We are delighted to invite you to join our family tree,  Please click on the below button and choose your immediate / nearest family member. <br> If your Grand Parents or Parents is not shown, start adding them from top until you reach to yourself, then add others ... <a href='".$siteUrl.$RELATIVE_PATH."faq.php'   style='color: blue !important;' target='_blank'>Learn More .</a> ";
	}
	$emailSent = $mailer->sendMail([$email], $subject, 'invite.html', 'invite.txt');
	$sent = 0;
	if ($emailSent) {
		$sent = 1;
		$success = trans('invitation_sent');
	}
//	$con->query("insert into siteMails values (null, '$family','$name', '$email', 'Invitation', '$sent', '" . date('Y-m-d h:i:s a') . "')");
	SiteMail::create([
			'family_id' => $family,
			'name' => $name,
			'email' => $email,
			'title' => 'Invitation',
			'sent' => $sent,
			'date' => date('Y-m-d h:i:s a'),
	]);
	$url = $siteUrl . "/redirect.php?id=" . $link_id;
	$message = str_replace("the below button", "this link : <br> <a href='{$url}' style='color: blue !important;' target='_blank'>{$url}</a><br>", $message);
	$message = str_replace("<a href='".$siteUrl.$RELATIVE_PATH."faq.php'   style='color: blue !important;' target='_blank'>Learn More .</a>", "To Learn More, Please go to this link <br> <a href='".$siteUrl.$RELATIVE_PATH."/faq.php' style='color: blue !important;' target='_blank'>".$siteUrl.$RELATIVE_PATH."faq.php</a>", $message);

	$htmlMsg  = $message . "<br>";
	$htmlMsg .= " Regards.";
    $plain_message= strip_tags($htmlMsg);
	$plain_message = str_replace("&nbsp;", '', $plain_message);
	echo json_encode([
			'success' => 1,
			'message' => $htmlMsg,
			'plain_message' => $plain_message
	]);

}
if(isset($_POST['fileId']) && isset($_SESSION['user_id'])){
	$family_id = FamilyMedia::find($_POST['fileId'])->family_id;
	if($family_id == $_SESSION['family_id']){
		if(FamilyMedia::destroy($_POST['fileId'])){
			echo trans('deleteMessage');
		}
	}
}
if(isset($_POST['service_type']) && isset($_POST['session_family'])){
	$service = $_POST['service_type'];
	$family_id = $_POST['session_family'];
	$media = [];
	$media['data'] = [];
	$familyMedia = new FamilyMedia;
	$media['count'] = $familyMedia->where(['family_type' => $service, 'file_type'=>'Image', 'family_id' => $family_id])->count();
	$media['data'] = $familyMedia->where(['family_type' => $service, 'file_type'=>'Image', 'family_id' => $family_id])->limit(4)->get();
	echo json_encode($media);
}
if(isset($_POST['type']) && isset($_POST['logged_family']) && isset($_POST['file_type'])){
	$service = $_POST['type'];
	$family_id = $_POST['logged_family'];
	$file_type = $_POST['file_type'];
	$media = [];
	$media['data'] = [];
	$familyMedia = new FamilyMedia;
	$media['count'] = $familyMedia->where(['family_type' => $service, 'file_type'=> $file_type, 'family_id' => $family_id])->count();
	if($file_type == "PDF"){
		$media['data'] = $familyMedia->where(['family_type' => $service, 'file_type'=> $file_type, 'family_id' => $family_id])->limit(6)->get();
	} else {
		$media['data'] = $familyMedia->where(['family_type' => $service, 'file_type'=> $file_type, 'family_id' => $family_id])->limit(4)->get();
	}
	echo json_encode($media);
}
if(isset($_POST['page']) && isset($_POST['pagination_service']) && isset($_POST['pagination_type']) && isset($_POST['logged_family_id'])){
	$service = $_POST['pagination_service'];
	$file_type = $_POST['pagination_type'];
	$family_id = $_POST['logged_family_id'];
	$page = $_POST['page'];
	$start = ($page-1) * 4;
	$media = [];
	if($file_type == "PDF"){
		$media = $familyMedia->where(['family_type' => $service, 'file_type'=> $file_type, 'family_id' => $family_id])->limit(6)->get();
	} else {
		$media = $familyMedia->where(['family_type' => $service, 'file_type'=> $file_type, 'family_id' => $family_id])->limit(4)->get();
	}
	echo json_encode($media);
}
if (isset($_POST['usersType'])) {
	$type = $_POST['usersType'];
	$id = $_SESSION['family_id'];
	$response = [];
	$response['users'] = [];
	$response['role'] = $_SESSION['role'];
//	$users = [];
//	$sql = "select * from users where family_id='$id'";
	$users = Family::find($id)->users();
	if($type == 'assistant'){
//		$sql .= " and role='assistant' and member='0'";
		$users = $users->where(['role'=>'assistant'])->where(['member'=>0]);
	} elseif($type == 'both'){
//		$sql .= " and role='assistant' and member='1'";
		$users = $users->where(['role'=>'assistant'])->where(['member'=>1]);
	} elseif($type == 'member'){
//		$sql .= " and role='user'";
		$users = $users->where(['role'=>'user']);
	}

//	$result = $con->query($sql . " order by name asc");
	$users= $users->orderBy('name', 'asc')->get();
//	if(mysqli_num_rows($result) > 0){
//		while($row = mysqli_fetch_assoc($result)){
//			array_push($users, $row);
//		}
//	}
	$response['users'] = $users;
	echo json_encode($response);
}
if (isset($_POST['family']) && isset($_POST['ajax'])) {
	$family = $_SESSION['family_id'];
	echo json_encode(Family::find($family));
}
if (isset($_POST['username']) && isset($_POST['lang']) && isset($_POST['x'])) {
	$username = $_POST['username'];
	$lang = $_POST['lang'];

	if (User::where(['user_name' => $username])->count() > 0) {
		echo trans('usernameExists');
	}
}
if (isset($_POST['req'])) {
	if ($_POST['req'] == 'pronunciation') {
		$family_id = $_POST['familyId'];
		echo Family::find($family_id)->pronunciation;
	}
}
if (isset($_POST['email']) && isset($_POST['ajax']) && $_POST['ajax'] == 0) {
	$email = $_POST['email'];
	if (User::where(['email' => $email])->count() > 0) {
		echo trans('emailExists');
	}
}
if (isset($_POST['check_invitation'])) {
	$invitation_id = $_POST['invitation'];
	$row = get_family_invitation($invitation_id);
	if($row['responded'] == '0' && empty($row['responded_at'])){
		echo json_encode($row);
	}
}
if (isset($_POST['user']) && isset($_POST['lang'])) {
	$user_id = $_POST['user'];
	$lang = $_POST['lang'];
	$row = User::find($user_id);
	$userId = $row['user_id'];
	if($row['parent_id'] == 'alpha'){
		$row['name'] = $row['name'] . " (First Father)";
	}
	$row['member_id'] = $row['family_id'] . "00" . get_member_arrangement($row['family_id'], $userId);
	if ($row['parent_id'] != 0) {
		$row['parent_name'] = getUseName($row['parent_id']);
	}
	if (empty($row['outside_family'])) {
		$row['family_name'] = getFamilyName($row['family_id']);
	} else {
		if(is_numeric($row['outside_family'])){
			$row['family_name'] = getFamilyName($row['outside_family']);
			$row['family_id'] = $row['outside_family'];
		} else {
			$row['family_name'] = $row['outside_family'];
			$row['family_id'] = 0;
		}
	}
	$row['FamilyStatus'] = checkFamilyStatus($row['family_id']);
	if ($row['wife'] != 0) {
		$row['wife_name'] = [];
		$row['wife_name'] = getwifesNames($row['wife']);
	}
	if ($row['gender'] == 'Female') {
		if(! empty($row['husband'])){
			$row['husband_name'] = $row['husband'];
		} else {
			$resHus = User::whereRaw("find_in_set('$userId', wife)")->where(['display' => 1])->first();
			if($resHus){
				$row['husband_id'] = $resHus['user_id'];
				$row['husband_name'] = $resHus['name'];
			}
		}
		$children = getUserChildren($userId, 'mother');
		if (!empty($children)) {
			$row['children'] = [];
			$row['children'] = $children;
		}
		if(checkFemaleMaritalStatus($row['user_id'])){
			$row['gender'] = 'Woman';
		} else {
			$row['gender'] = 'Girl';
		}
		$row['memberGender'] = 'Female';
	} elseif ($row['gender'] == 'Male') {
		$children = getUserChildren($userId, 'parent');
		if (!empty($children)) {
			$row['children'] = [];
			$row['children'] = $children;
		}
		$row['memberGender'] = 'Male';
	}
	$row['siblings'] = getSiblings($row['user_id'], $row['family_id'], $row['parent_id'], $row['mother_id']);
	if ($row['mother_id'] != 0) {
		$row['mother'] = getUserName($row['mother_id']);
	}
	$row['country'] = getCountryName($row['country_id']);
	$row['nationality_name'] = getNationalityName($row['nationality']);
	$row['image'] = $row['image']? $row['image'] : asset('images/default-user.png');
	$row['club_logo'] = $row['club_logo']? $row['club_logo'] : asset('images/default-club.png');

	$rowCountry = Country::where(['id'=>$row['country_id']])->first();
	$row['country_row'] = $rowCountry;
	echo json_encode($row);
}
if(isset($_POST['fatherChildren'])){
	$father_id = $_POST['fatherChildren'];
	$children_ids = User::where('parent_id', $father_id)->where('mother_id', 0)->get(['user_id', 'name']);
	if(count($children_ids) > 0){
		$children = "";
		foreach($children_ids as $child){
			$children .= "<option value='{$child['user_id']}'>{$child['name']}</option>";
		}
		echo $children;
	}
}
if (isset($_FILES['file']) and!$_FILES['file']['error']) {
	$name = $_FILES['file']['name'];
	$tmp = $_FILES['file']['tmp_name'];
	$family_id = $_SESSION['family_id'];
	if (!file_exists("../uploads/familyPronunciation/{$family_id}")) {
		mkdir("../uploads/familyPronunciation/{$family_id}", 0775, true);
	}
	$targetPath = "../uploads/familyPronunciation/{$family_id}/" . round(microtime(true)) . ".mp3";
	$fileDB = "uploads/familyPronunciation/{$family_id}/" . round(microtime(true)) . ".mp3";
	$upload = move_uploaded_file($tmp, $targetPath);
	Family::find($family_id)->update([
		'pronunciation' =>  $fileDB
	]);

	if ($upload) {
		echo json_encode([
			"message" => trans('recorded_successfully'),
			"record" => $fileDB
		]);
	} else {
		echo json_encode([
			"message" => "Error !"
		]);
	}
}
if(isset($_POST['showUser'])){
	$user = $_POST['showUser'];
	$row = User::where(['user_id'=>$user])->where(['family_id' => $_SESSION['family_id']])->first();
	if($row['gender'] == 'Male'){
		if($row['wife'] != '0' && $row['outer_husband'] != '1'){
			showWifes($row['wife']);
		}
		showChildren($row['user_id'], $row['gender']);
	} elseif($row['gender'] == 'Female'){
		$hasOuterHusband = hasOuterHusband($row['family_id'], $row['user_id'], 0);
		if($hasOuterHusband > 0){
			showOuterHusbands($row['family_id'], $row['user_id']);
		} else {
			showChildren($row['user_id'], $row['gender']);
		}
	}
	$row->update(['display'=>1]);
	echo trans("member's_tree_displayed");
}
if(isset($_POST['hiddenMember'])){
	$member = $_POST['hiddenMember'];
	$display = $_POST['display'];
	User::where(['user_id'=>$member])->where(['family_id'=>$_SESSION['family_id']])->update(['display'=>$display]);
	if($display == 1){
		echo trans('member_shown');
	} else if($display == 0) {
		echo trans('member_hidden');
	}
}
if(isset($_POST['hideUser'])){
	$user = $_POST['hideUser'];
	$row = User::where(['user_id'=>$user])->where(['family_id'=>$_SESSION['family_id']])->first();
	if($row['parent_id'] == 'alpha' || $row['role'] == 'creator' || $row['role'] == 'admin'){
		echo json_encode([
			"success" => 0,
			"message" => trans("can't_hide_member")
		]);
		exit();
	}
	if($row['gender'] == 'Male'){
		if($row['wife'] != '0' && $row['outer_husband'] != '1'){
			hideWifes($row['wife']);
		}
		hideChildren($row['user_id'], $row['gender']);

	} elseif($row['gender'] == 'Female'){
		$hasOuterHusband = hasOuterHusband($row['family_id'], $row['user_id'], 0);
		if($hasOuterHusband > 0){
			hideOuterHusbands($row['family_id'], $row['user_id']);
		} else {
			hideChildren($row['user_id'], $row['gender']);
		}
	}

	$row->update(['display'=>0]);
	echo json_encode([
			"success" => 1,
			"message" => trans("member's_tree_hidden")
	]);
}
if(isset($_POST['deleteTree'])){
	$user = $_POST['deleteTree'];
	$lang = $_POST['lang'];
	$role = $_SESSION['role'];

	$row = User::where(['user_id'=>$user])->where(['family_id'=>$_SESSION['family_id']])->first();
	if(($row['role'] == 'admin' || $row['role'] == 'creator') && ($role != 'admin' && $role != 'creator')){
		echo json_encode([
				'success' => 0,
				'message' => trans("can't_delete_member")
		]);
		exit();
	}
	if($row['parent_id'] == 'alpha'){

		// $row2 = Family::find($row['family_id'])->users()->where(['role'=>'creator'])->orWhere(['role'=>'admin'])->first();
		$row2 = Family::find($row['family_id'])->users()->where(function($query){
			$query->where(['role'=>'creator'])->orWhere(['role'=>'admin']);
		})->first();
		$now = date("Y-m-d");
		$expiry_date = date("Y-m-d", strtotime('+2 days'));

		$request = FamilyDelete::create([
			'family_id'=>$row2['family_id'],
			'date' => $now,
			'expire_date' => $expiry_date
		]);
		$request_id = $request->id;
		$salt = "delete!@#$%family";
		$family = base64_encode($row['family_id'] + 2772 . $salt);

		$salt = "delete!@#$%famil#request";
		$request_id = base64_encode($request_id + 2392 . $salt);

		$url = $siteUrl.$RELATIVE_PATH."/profile.php?lang={$lang}&deleteFamily={$family}&request={$request_id}";
		$mailer = new Mailer();
		$mailer->setVars(['user_name'=>$row2['name'], 'url'=>$url]);
		$emailSent = $mailer->sendMail([$row2['email']], "Delete your family", 'delete_family.html', 'delete_family.txt');
		$sent = 0;
		if ($emailSent) {
			$sent = 1;
		}

		SiteMail::create([
				'family_id' => $row2['family_id'],
				'name' => $row2['name'],
				'email' => $row2['email'],
				'title' => 'Delete Family',
				'sent' => $sent,
				'date' => date('Y-m-d h:i:s a'),
		]);
		echo json_encode([
				'success' => 1,
				'message' => trans("email_sent_to_delete_family")
		]);
		exit();
	}
	if($row['gender'] == 'Male'){
		if($row['wife'] != '0' && $row['outer_husband'] != '1'){
			deleteWifes($row['wife']);
		}
		deleteChildren($row['user_id'], $row['gender']);
	} elseif($row['gender'] == 'Female'){
		$hasOuterHusband = hasOuterHusband($row['family_id'], $row['user_id'], 0);
		if($hasOuterHusband > 0){
			deleteOuterHusbands($row['family_id'], $row['user_id']);
		} else {
			deleteChildren($row['user_id'], $row['gender']);
		}
	}
	$delete = User::destroy($user);
	if($delete){
		echo json_encode([
				'success' => 1,
				'message' => trans("member's_tree_deleted")
		]);
	}
}
if(isset($_POST['treeUser']) && isset($_POST['tree'])){
	$user = $_POST['treeUser'];

	$row = User::where(['user_id'=>$user])->where(['family_id'=>$_SESSION['family_id']])->first();
	?>
    <li>
        <a href="<?php echo $row['user_id']; ?>" class="cell">
            <img src="<?=asset($row['image'])?>" height="90" width="90">
            <figcaption><?php echo $row['name']; ?></figcaption>
        </a>
		<?php
		if ($row['gender'] == 'Male') {
			if($row['wife'] != '0'){
				echo getUserWifes($row['user_id'], $row['family_id'], $row['wife'], 0);
			} else {
				echo getChildren($row['user_id'], $row['family_id'], $row['wife'], 0);
			}
		} elseif($row['gender'] == 'Female') {
			$outerHusband = hasOuterHusband($row['family_id'], $row['user_id'], 0);
			if($outerHusband > 0){
				echo getOuterHusband($row['family_id'], $row['user_id'], 0);
			} else {
				$father = getFatherFromMother($row['user_id']);
				echo getChildren($father, $row['family_id'], $row['user_id'], 0);
			}
		}
		?>
    </li>
	<?php
}
if (isset($_POST['plan']) && isset($_POST['lang'])) {
	$plan = $_POST['plan'];
	$lang = $_POST['lang'];

	$row = DBPlan::find($plan);
	$row['members_prompt'] = trans('members') . ": ";
	$row['media_prompt'] = trans('media') . ": ";
	echo json_encode($row);
}
if (isset($_POST['ajax']) && $_POST['ajax'] == 'key') {
	$country = $_POST['country'];

	$row = Country::find($country);
	$key = $row['countryKey'];
	echo $key;
}
if (isset($_POST['refuse_request_to_add_members'])) {
	$id = $_POST['refuse_request_to_add_members'];
	$user_id = $_POST['user_id'];

	$query = FamilyJoinRequest::where(['id'=>$id])->where(['family_id'=>$_SESSION['family_id']])->update([
			'status'=> 0,
			'accepted_by'=>$user_id
	]);
	if($query){
		echo trans("request_refused");
	}
}
if (isset($_POST['id']) && isset($_POST['ajax'])) {
	$id = $_POST['id'];
    FamilyAccess::where(['id'=>$id])->where(['family_id'=>$_SESSION['family_id']])->update([
       'accept'=>0
    ]);

	echo trans("request_refused");
}
if (isset($_POST['nameAr']) && isset($_POST['lang'])) {
	$name = trim($_POST['nameAr']);
	$lang = $_POST['lang'];
	if (Family::where(['name_ar'=>$name])->first()) {
		echo trans("familyExists");
	}
}
if (isset($_POST['nameEn']) && isset($_POST['lang'])) {
	$name = trim($_POST['nameEn']);
	$lang = $_POST['lang'];
	if (Family::where(['name_en'=>$name])->first()) {
		echo trans("familyExists");
	}
}
if (isset($_POST['email']) && isset($_POST['lang'])) {
	$email = trim($_POST['email']);
	// $username = trim($_POST['name']);
	$family = trim($_POST['memberFamily']);
	$lang = $_POST['lang'];
//	$sql = "select * from users where `email`='$email' and family_id='$family'";
	$result = User::where(['email'=>$email]);
// 	if (!empty($username)) {
// //		$sql .= " and user_name='$username'";
// 		$result = $result->where(['user_name'=>$username]);
// 	}

//	$result = $con->query($sql);
	$result = $result->first();
	if (! $result) {
		echo trans("passwordSent");
		exit();
	} else {
		$row = $result;
		$password = $row['user_password'];
		$name = $row['name'];
		$family = $row['family_id'];
		$config = Configuration::forSymmetricSigner(
				new Sha256(),
				InMemory::base64Encoded($APP_KEY));
		$now   = new DateTimeImmutable();
		$token = $config->builder()->identifiedBy($row['user_name'])->issuedAt($now)
				->expiresAt($now->modify('+1 day'))
				->getToken($config->signer(), $config->signingKey())->toString();
		$url = $siteUrl.$RELATIVE_PATH."reset_password.php?username=".$row['user_name']."&token=".$token;
		$mailer = new Mailer();
		$mailer->setVars(['user_name'=>$row['user_name'], 'url'=>$url, 'name' => $name]);
		$emailSent = $mailer->sendMail([$email], "Reset Your Password Or User Name On alhamayel", 'password_reset.html', 'password_reset.txt');
		$sent = 0;
		if ($emailSent) {
			$sent = 1;
		}

		SiteMail::create([
				'family_id' => $result->family_id,
				'name' => $name,
				'email' => $email,
				'title' => 'Forget Password Or User Name',
				'sent' => $sent,
				'date' => date('Y-m-d h:i:s a'),
		]);
		echo trans("passwordSent");
	}
}
if (isset($_POST['family']) && isset($_POST['lang'])) {
	$family = $_POST['family'];
	$lang = $_POST['lang'];
	$usersNum = countFamilyUsers($family)['count'];
	if (checkFamilyPlanForMembers($family, $usersNum)) {
		echo json_encode(['status' => 'error', 'message' => trans('checkPlanMembers')]);
		exit();
	}
	$users = countFamilyUsers($family)['users'];
	$options = "<option value='0'>" . trans("chooseParent") . "</option>";
	foreach ($users as $user) {
		$options .= "<option value=" . $user["user_id"] . ">" . $user["name"] . "</option>";
	}
	echo json_encode(['status' => 'success', 'data' => $options]);
}
if(isset($_POST['userFather'])){
	$father = $_POST['userFather'];
	$options = "<option value=''>".trans("choose_mother")."</option>";
//	$result1 = $con->query("select * from users where user_id='$father'") or die(mysqli_error($con));
//	$row1 = mysqli_fetch_assoc($result1);
	$row1 = User::find($father);
	if($row1['wife'] != 0){
		$wifes = explode(',' ,$row1['wife']);
		foreach($wifes as $wife){
//			$result2 = $con->query("select * from users where user_id='$wife'") or die(mysqli_error($con));
			$row2 = User::where(['user_id'=>$wife])->first();
			if($row2){
				$options .= "<option value=".$row2['user_id'].">" . $row2['user_id'] . "- " . $row2['name'] . "</option>";
			}
		}

	}
	echo $options;
}
