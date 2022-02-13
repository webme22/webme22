<?php
include("config.php");
include_once("functions/translation.php");
include_once("lib/Mailer.php");
if (isset($_SESSION['user_id']) && $_SESSION['family_id'] == 0) {
	header("location: home.php");
	exit();
}
else if (isset($_SESSION['user_id']) && isset($_SESSION['family_id']) &&  $_SESSION['family_id'] != 0){
	middleware('user');
}

// || isset($_GET['f']) && $_GET['f'] != '' && isset($_GET['id']) && strtotime(date('Y-m-d')) > checkUserAccessAbility($_GET['id'])
if (isset($_GET['family'])) {
	$familyId = $_GET['family'];
	if (checkFamilyStatus($familyId) == 0) {
		header("location: home.php");
		exit();
	}
} elseif (isset($_GET['flag']) && (! isset($_GET['type']) || get_family_invitation($_GET['type'])['family_id'] != $_GET['flag'])) {
	header("location: home.php");
	exit();
} elseif (isset($_GET['f']) && $_GET['f']) {
	$familyId = $_GET['f'];
	$id = $_GET['id'];
	$expireDate = checkUserAccessAbility($id);
	$expireDate = strtotime($expireDate);
	$now = strtotime(date('Y-m-d'));
	if ($now > $expireDate) {
//		$con->query("delete from familyAccess where family_id='$familyId' and id='$id'");
		FamilyAccess::destroy($id);
		header("location: home.php");
		exit();
	}
} elseif(isset($_GET['deleteFamily']) && isset($_GET['request']) && isset($_SESSION['user_id']) && in_array($_SESSION['role'], ['admin', 'creator'])){
	$creator = $_SESSION['user_id'];
	$family = base64_decode($_GET['deleteFamily']);
	$salt = "delete!@#$%family";
	$family_id = ((int) preg_replace(sprintf('/%s/', $salt), '', $family)) - 2772;
	$request_id = ((int) base64_decode($_GET['request'])) - 2392;
	$request = get_delete_request($request_id, $family_id);
	$now = date('Y-m-d');
	if($now > $request['expire_date']){
		$error = $languages[$lang]["link_availability"];
	} elseif($_SESSION['family_id'] != $family_id){
		$error = $languages[$lang]["not_your_family"];
	} else {
		// $delete = User::where(['family_id'=>$family_id])->where('user_id', '!=', 'creator')->delete();
		// FamilyAccess::where(['family_id'=>$family_id])->delete();
		// FamilyHistory::where(['family_id'=>$family_id])->delete();
		// FamilyInvitation::where(['family_id'=>$family_id])->delete();
		// FamilyMedia::where(['family_id'=>$family_id])->delete();
		// FamilyJoinRequest::where(['family_id'=>$family_id])->delete();
		// SiteMail::where(['family_id'=>$family_id])->delete();
		// $update = User::find($creator)->update([
		// 		'parent_id'=>'alpha'
		// ]);
		Family::where(['id'=> $family_id])->update([
				'deleted' => 1,
				'deleted_at' => date('Y-m-d')
		]);
		FamilyHistory::create([
				'family_id'=>$family_id,
				'user_id'=>$creator,
				'action'=> "delete family",
				'date'=> date('Y-m-d h:i:s a')
		]);
		@session_start();
		$_SESSION = [];
		session_destroy();
		header("location: login.php?status=delete");
		exit();
	}
} elseif (!isset($_GET['flag']) && !isset($_GET['type']) && !isset($_GET['family']) && !isset($_GET['f']) && !isset($_SESSION['user_id']) && (! isset($_GET['deleteFamily']) && ! isset($_GET['request']))) {
	header("location: home.php");
	exit();
}
if (isset($_GET['flag']) && isset($_GET['type'])) {
	$id = get_family_invitation($_GET['type'])['id'];
//	$con->query("update familyInvitations set `seen`='1' where id='$id'");
	FamilyInvitation::find($id)->update(['seen'=>1]);
}
include_once("header.php");


if(isset($_POST['request_view_family_submit'])){
	$family_id = $_POST['requested_family'];
	$name = trim($_POST['strangerName']);
	$email = trim($_POST['strangerEmail']);
	if(! filter_var($email, FILTER_VALIDATE_EMAIL)){
		$error = trans('failed') . " . ". trans('invalidEmail');
	} else {
		FamilyAccess::create([
				'family_id'=>  $family_id ,
				'name'=>  $name ,
				'email'=> $email  ,
				'accept'=> '2'  ,
				'acceptedBy'=> null  ,
				'expire_date'=>  null ,
				'date' => date('Y-m-d h:i:s a')
		]);
		$emails  = Family::find($family_id)->users()->responsible()->pluck('email')->toArray();
        $account_url = $siteUrl.$RELATIVE_PATH."/account.php";
		$login_url = $siteUrl.$RELATIVE_PATH."/login.php";
		$mailer = new Mailer();
		$mailer->setVars(['user_name'=>'Creator/Assistant', 'name'=>$name, 'name'=>$name, 'account_url'=>$account_url, 'login_url'=>$login_url]);
		$mailer->sendMail($emails, "New access request", 'access_request.html', 'access_request.txt');
		$success = trans('request_sent');
	}
}

if(isset($_POST['addMembersSubmit'])){
	$name = trim($_POST['strangerName']);
	$email = trim($_POST['strangerEmail']);
	$phone = trim($_POST['strangerPhone']);
	$familyId = $_POST['familyId'];
	if(! empty($name) && ! empty($email) && ! empty($phone) && ! empty($familyId)){
//		$query_1 = $con->query("insert into join_family_requests (family_id, name, email, phone, date) values
//                            ('$familyId', '$name', '$email', '$phone', '".date('Y-m-d H:i:s')."')") or die(mysqli_error($con));
		FamilyJoinRequest::create([
				'family_id'=>$familyId,
				'name'=>$name,
				'email'=>$email,
				'phone'=>$phone,
				'date'=>date('Y-m-d H:i:s'),
		]);
		$emails = Family::find($familyId)->users()->responsible()->orderBy('role', 'desc')->pluck('email');
		$emails = is_array($emails) ? $emails : $emails->toArray();
        $login_url = $siteUrl.$RELATIVE_PATH."/login.php";
		$mailer = new Mailer();
		$mailer->setVars(['user_name'=>'Creator/Assistant', 'name'=>$name, 'login_url'=>$login_url]);
		$mailer->sendMail($emails, "Request to join family",
				'join_request.html', 'join_request.txt');
		$success = $languages[$lang]["join_request_sent"];
	}
}
if (isset($_POST['deleteNodeSubmit'])) {
	$user = $_POST['deleteNode'];
//	$result = $con->query("select * from users where user_id='$user'");
//	$row = mysqli_fetch_assoc($result);
	$row = User::find($user);
	$name = $row['name'];
	$member = $row['member'];
	$userId = $_SESSION['user_id'];
	$action = "Delete member : " . $name;
	$family_id = $_SESSION['family_id'];
//	$con->query("insert into familyHistory values (null, '$family_id', '$userId' , '$action', '" . date('Y-m-d h:i:s a') . "')") or die(mysqli_error($con));
	FamilyHistory::create([
			'family_id'=>$family_id,
			'user_id'=>$userId,
			'action'=>$action,
			'date'=>   date('Y-m-d h:i:s a')
	]);
//	$query3 = $con->query("update users set display='0' where user_id='$user'");
	$query3 = User::find($user)->update(['display'=>0]);
	if ($query3) {
		$success = $languages[$lang]["deleteMessage"];
	}
}
if (isset($_POST['deleteSubmit'])) {
	$id = $_POST['deleteFile'];
//	$delete = $con->query("delete from familyMedia where `id`='$id'");
	$delete = FamilyMedia::destroy($id);
	if ($delete) {
		$deleteMessage = $languages[$lang]["deleteMessage"];
	}
}
if(isset($_POST['requestSubmit'])){
	$family_id = $_POST['familyId'];
	$name = trim($_POST['strangerName']);
	$email = trim($_POST['strangerEmail']);
	if(! filter_var($email, FILTER_VALIDATE_EMAIL)){
		$error = trans("failed_sent_request") . " , " . trans("invalidEmail");
	} else {
//		$con->query("insert into familyAccess values(null, '$family_id', '$name', '$email', '2', null, null, '".date('Y-m-d h:i:s a')."')") or die(mysqli_error($con));
		FamilyAccess::create([
				'family_id'=>$family_id,
				'name'=>$name,
				'email'=>$email,
				'accept'=>2,
				'date'=>date('Y-m-d h:i:s a')
		]);
//		$result = $con->query("select name, email from users where family_id='$family_id' and role!='user'");
		$result = Family::find($family_id)->users()->where('role', '!=', 'user')->get();
		foreach($result as $row){
			$familyEmail = $row['email'];
			$userName = $row['name'];
            $account_url = $siteUrl.$RELATIVE_PATH."/account.php";
		    $login_url = $siteUrl.$RELATIVE_PATH."/login.php";
			$mailer = new Mailer();
			$mailer->setVars(['user_name'=>$userName, 'name'=>$name, 'account_url'=>$account_url, 'login_url'=>$login_url]);
			$mailer->sendMail([$familyEmail], "New access request",
					'access_request.html', 'access_request.txt');
		}
		$success = $languages[$lang]["request_sent"];
	}
}
if (isset($_POST['addNodeSubmit'])) {
	$name = trim($_POST["name"]);
	$phone = ($_POST["phone"] != '') ? trim($_POST["phone"]) : null;
	$email = ($_POST["email"] != '') ? trim($_POST["email"]) : null;
	$confirmEmail = ($_POST["joinConfirmEmail"] != '') ? trim($_POST["joinConfirmEmail"]) : null;
	$gender = trim($_POST["gender"]);
	$role = 'assistant';
	$wait_to_join = 0;
	if(isset($_SESSION['user_id'])){
		$family_id = $_SESSION['family_id'];
	} else if(! isset($_SESSION['user_id']) && isset($_GET['type'])){
		$invitation = get_family_invitation($_GET['type']);
		$family_id = $invitation['family_id'];
		if($invitation['type'] == 'Both'){
			$wait_to_join = 1;
		}
	}
	$country_id = $_POST['country'];
	$errors = [];
	if ($email != null && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
		array_push($errors, $languages[$lang]["invalidEmail"]);
	}
    if ($email != $confirmEmail) {
		array_push($errors, trans("email_mismatch"));
	}
	if (empty($errors)) {
		$user = User::create([
				'parent_id' => 0,
				'name' => $name,
				'email' => $email,
				'phone' => $phone,
				'image' => null,
				'family_id' => $family_id,
				'country_id' => $country_id,
				'role' => $role,
				'verified' => true,
				'users' => 0,'families' => 0,'countries' => 0,'services' => 0,'setting' => 0,'clients' => 0,'messages' => 0,
				'date' => date('Y-m-d'),
				'wife' => '0',
				'member' => '0',
				'mother_id' => '0',
				'outside_family' => '0',
				'outer_husband' => '0',
				'display' => '1',
				'waiting_to_join' => $wait_to_join
		]);
		$add_user = $user->user_id;


		if(isset($_FILES['photo']['name']) && $_FILES['photo']['name'] != ''){
			$imageName = $_FILES['photo']['name'];
			$imageTmpName = $_FILES['photo']['tmp_name'];
			if (!file_exists("uploads/users/" . $add_user)) {
				mkdir("uploads/users/" . $add_user, 0775, true);
			}
			$target_path = "uploads/users/" . $add_user . "/" . round(microtime(true)) . ".jpg";
			$imagedatabase = "uploads/users/" . $add_user . "/" . round(microtime(true)) . ".jpg";
			$user->image = $imagedatabase;
			$user->save();
			move_uploaded_file($imageTmpName, $target_path);
		}

		if(isset($_SESSION['user_id'])){
			$userId = $_SESSION['user_id'];
			$action = "Add assistant : " . $name;
			$title = "addNewAssistant";
		} else {
			$userId = $invitation['user_id'];
			$invitation_id = $invitation['id'];
//			$con->query("update familyInvitations set `responded`='1', `responded_at`='".date('Y-m-d H:i:s').
//                    "' where id='$invitation_id' and type='Assistant' and family_id='$family_id'") or die(mysqli_error($con));
			FamilyInvitation::find($invitation_id)->update([
					'responded'=>1,
					'responded_at'=> date('Y-m-d H:i:s')
			]);
			if($invitation['type'] == 'Both'){
				$action = "Invitation to assistant and member , " . $name . " , has been accepted .";
				$title = "addNewAssistantAndMember";
			} else {
				$action = "Invitation to assistant , " . $name . " , has been accepted .";
				$title = "addNewAssistant";
			}
		}

		FamilyHistory::create([
				'family_id' => $family_id,
				'user_id' => $userId,
				'action' => $action,
				'date' => date('Y-m-d h:i:s a'),
		]);
		$successMessage = trans("registered_successfully");
		$registeredMessage = trans("email_sent_with_link");
		$flag = $add_user + 10;
		$belong = $family_id + 20;
		$url = $siteUrl.$RELATIVE_PATH."/login.php?status=complete&flag={$flag}&belong={$belong}";
		$familyName = getFamilyName($family_id);
		$mailer = new Mailer();
		$mailer->setVars(['user_name'=>$name, 'url'=>$url, 'family_name'=>$familyName]);
		$emailSent = $mailer->sendMail([$email], "You are now a family assistant on alhamayel",
				'assistant_inform.html', 'assistant_inform.txt');
		$sent = 0;
		if ($emailSent) {
			$sent = 1;
		}
		SiteMail::create([
				'family_id' => $family_id,
				'name' => $name,
				'email' => $email,
				'title' => $title,
				'sent' => $sent,
				'date' => date('Y-m-d h:i:s a'),
		]);
	} else {
		$successMessage = $languages[$lang]["registration_failed"];
	}
}
if (isset($_POST['addRelatedMemberSubmit'])) {
	$name = trim($_POST["name"]);
	$kunya = isset($_POST["kunya"]) ? trim($_POST["kunya"]) : null;
	$phone = ($_POST["phone"] != '') ? trim($_POST["phone"]) : null;
	$country = ($_POST['country'] != '') ? trim($_POST["country"]) : 0;
	$email = ($_POST["email"] != '') ? trim($_POST["email"]) : null;
	$confirmEmail = ($_POST["confirmEmail"] != '') ? trim($_POST["confirmEmail"]) : null;
	$occupation = ($_POST["occupation"] != '') ? trim($_POST["occupation"]) : null;
	$memberRole = $_POST['memberRole'];
	$member = 1;
	$DOB = $_POST['DOB'];
	$DOD = $_POST['DOD'];
	$nationality = ($_POST['nationality'] != '') ? trim($_POST["nationality"]) : 0;
	$facebook = ($_POST["facebook"] != '') ? trim($_POST["facebook"]) : null;
	$twitter = ($_POST["twitter"] != '') ? trim($_POST["twitter"]) : null;
	$instagram = ($_POST["instagram"] != '') ? trim($_POST["instagram"]) : null;
	$snapchat = ($_POST["snapchat"] != '') ? trim($_POST["snapchat"]) : null;
	$interests = trim($_POST["interests"]);
	$about = trim($_POST["about"]);
	$club_name = trim($_POST["club_name"]);
	$family_id = isset($_SESSION['family_id'])? $_SESSION['family_id'] : (int) $_GET['flag'];
	$relatedMember = $_POST['relatedMember'];
	$relation = $_POST['relationType'];
	$father_data = getUserData($relatedMember);
	$display = 1;
	$is_that_you = (isset($_POST['is_that_you']))? $_POST['is_that_you'] : FALSE;
	$errors = [];
	// if(($father_data['wife'] == 0 || check_wifes($father_data['wife']) == 0) && $relation == "father"){
	// 	array_push($errors, trans("failed !") . " , " . trans("add_wife_first"));
	// }
	if(check_first_father_children($father_data['user_id']) != 0 && $relation == "father"){
		array_push($errors, trans("failed !") . " , " . trans("add_wife_first"));
	}
	if ($email != null && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
		array_push($errors, trans("invalidEmail"));
	}
    if ($email != $confirmEmail) {
		array_push($errors, trans("email_mismatch"));
	}
	if (checkPlan($family_id, "Members")) {
		array_push($errors, trans("members_plan_error"));
	}
	if (empty($errors)) {
//		$con->query("insert into users values (null, '0', '$name', null, null, '$email', '$phone', null, null,
//                          '$family_id', '$country', '$memberRole', '1', '0', '0', '0', '0', '0', '0', '0',
//                          '" . date('Y-m-d') . "', null, '0', '$member', '0', null, '$occupation', '$kunya',
//                           '$DOB', '$nationality', '$facebook', '$twitter', '$instagram', '$snapchat', '$interests',
//                           '$club_name', null, null, '0', '$display', '$DOD', '$about')") or die(mysqli_error($con));

		if($is_that_you){

			$user = User::find($_SESSION['user_id']);
			$user->update([
					'name' => $name,'email'=>$email,'phone'=>$phone,'family_id'=>$family_id,'country_id'=>$country,
					'role'=> 'assistant','verified'=>true,'date'=>date('Y-m-d'),'member'=>$member,'occupation'=>$occupation,
					'kunya'=>$kunya,'date_of_birth'=>$DOB,'nationality'=>$nationality,'facebook'=>$facebook,'twitter'=>$twitter,
					'instagram'=>$instagram,'snapchat'=>$snapchat,'interests'=>$interests,'club_name'=>$club_name,'display'=>$display,
					'date_of_death'=>$DOD,'about'=>$about, 'waiting_to_join' => 2
			]);

			$_SESSION['waiting_to_join'] = 2;

		} else {

			$user = User::create([
					'name' => $name,'email'=>$email,'phone'=>$phone,'family_id'=>$family_id,'country_id'=>$country,
					'role'=>$memberRole,'verified'=>true,'date'=>date('Y-m-d'),'member'=>$member,'occupation'=>$occupation,
					'kunya'=>$kunya,'date_of_birth'=>$DOB,'nationality'=>$nationality,'facebook'=>$facebook,'twitter'=>$twitter,
					'instagram'=>$instagram,'snapchat'=>$snapchat,'interests'=>$interests,'club_name'=>$club_name,'display'=>$display,
					'date_of_death'=>$DOD,'about'=>$about
			]);

		}

		$add_user =$user->user_id;
		$gender = $_POST['relatedMemberGender'];
		$alpha = $_POST['checkAlpha'];
		if($relation === 'father'){
//			$con->query("update users set parent_id='$add_user' where  (parent_id='alpha' or parent_id='alpha_2') and family_id='$family_id'");
			$alpha = Family::find($family_id)->users()->where(['parent_id'=>'alpha'])->orWhere(['parent_id'=>'alpha_2'])->update(['parent_id'=>$add_user]);
//			$con->query("update users set parent_id='alpha', `gender`='Male' where user_id='$add_user'");
			$user->update(['parent_id'=>'alpha', 'gender'=>'Male']);
		} elseif ($relation === 'wife') {
//			$res = $con->query("select wife from users where `user_id`='$relatedMember'") or die(mysqli_error($con));
//			$row = mysqli_fetch_assoc($res);
			$row = User::find($relatedMember);
			$old = $row['wife'];
			if ($old != 0) {
				$new = $old . ',' . $add_user;
			} else {
				$new = $add_user;
			}
			if($_POST['wifeFamily'] != 0){
				$wifeFamily = $_POST['wifeFamily'];
			} else {
				$wifeFamily = trim($_POST["memberFamily"]);
			}
//			$con->query("update users set `wife`='$new' where `user_id`='$relatedMember'") or die(mysqli_error($con));
			$row->update(['wife'=>$new]);
//			$con->query("update users set `outside_family`='$wifeFamily', `gender`='Female' where user_id='$add_user'");
			$user->update(['outside_family'=>$wifeFamily, 'gender'=>'Female']);
			if($alpha == 1 && isset($_POST['children']) && ! empty($_POST['children'])){
				foreach($_POST['children'] as $child){
//					$con->query("update users set mother_id='$add_user' where user_id='$child'");
					User::find($child)->update(['mother_id'=>$add_user]);
				}
			}
		} elseif($relation === 'daughter'){
			if($gender === 'Male'){
				$mother = $_POST['memberMom'];
//				$con->query("update users set `mother_id`='$mother', `parent_id`='$relatedMember', `gender`='Female' where `user_id`='$add_user'") or die(mysqli_error($con));
				$user->update(['mother_id'=>$mother, 'parent_id'=>$relatedMember, 'gender'=>'Female']);
			} else {
				$father = $_POST['choose_father'];
//				$con->query("update users set `mother_id`='$relatedMember', `parent_id`='$father', `gender`='Female' where `user_id`='$add_user'") or die(mysqli_error($con));
				$user->update(['mother_id'=>$relatedMember, 'parent_id'=>$father, 'gender'=>'Female']);
			}
		} elseif($relation === 'son'){
			if($gender === 'Male'){
				$mother = $_POST['memberMom'];
//				$con->query("update users set `mother_id`='$mother', `parent_id`='$relatedMember', `gender`='Male' where `user_id`='$add_user'") or die(mysqli_error($con));
				$user->update(['mother_id'=>$mother, 'parent_id'=>$relatedMember, 'gender'=>'Male']);
			} else {
				$father = $_POST['choose_father'];
//				$con->query("update users set `mother_id`='$relatedMember', `parent_id`='$father', `gender`='Male' where `user_id`='$add_user'") or die(mysqli_error($con));
				$user->update(['mother_id'=>$relatedMember, 'parent_id'=>$father, 'gender'=>'Male']);
			}
		} elseif($relation === 'husband'){
			if($_POST['wifeFamily'] != 0){
				$wifeFamily = $_POST['wifeFamily'];
			} else {
				$wifeFamily = trim($_POST["memberFamily"]);
			}
//			$con->query("update users set `display`='0' where `wife`='$relatedMember' and `gender`='Male' and `outer_husband`='1'") or die(mysqli_error($con));
			User::where(['wife'=>$relatedMember])->where(['gender'=>'Male'])->where(['outer_husband'=>1])->update(['display'=>0]);
//			$con->query("update users set `wife`='$relatedMember', `gender`='Male', `outer_husband`='1', `outside_family`='$wifeFamily' where `user_id`='$add_user'") or die(mysqli_error($con));
			$user->update(['wife'=>$relatedMember, 'gender'=>'Male', 'outer_husband'=>1, 'outside_family'=>$wifeFamily]);
		} elseif($relation === 'sister'){
			$mother = getMemberParents($relatedMember)['mother_id'];
			$father = getMemberParents($relatedMember)['parent_id'];
			if($father == 'alpha' || $father == 'alpha_2'){
				$father = "alpha_2";
				$mother = 0;
			}
//			$con->query("update users set `mother_id`='$mother', `parent_id`='$father', `gender`='Female' where `user_id`='$add_user'") or die(mysqli_error($con));
			$user->update(['mother_id'=>$mother, 'parent_id'=>$father, 'gender'=>'Female']);
		} elseif($relation === 'brother'){
			$mother = getMemberParents($relatedMember)['mother_id'];
			$father = getMemberParents($relatedMember)['parent_id'];
			if($father == 'alpha' || $father == 'alpha_2'){
				$father = "alpha_2";
				$mother = 0;
			}
//			$con->query("update users set `mother_id`='$mother', `parent_id`='$father', `gender`='Male' where `user_id`='$add_user'") or die(mysqli_error($con));
			$user->update(['mother_id'=>$mother,'parent_id'=>$father, 'gender'=>'Male']);
		}
		if (!file_exists("uploads/users/" . $add_user)) {
			mkdir("uploads/users/" . $add_user, 0775, true);
		}
		if (!file_exists("uploads/users/" . $add_user.'/thumbnails')) {
			mkdir("uploads/users/" . $add_user.'/thumbnails', 0775, true);
		}

		if (isset($_FILES['photo']['name']) && !empty($_FILES['photo']['name'])) {
			$imageName = $_FILES['photo']['name'];
			$imageTmpName = $_FILES['photo']['tmp_name'];
			$full_image_name = round(microtime(true)) . ".jpg";
			$target_path = "uploads/users/" . $add_user . "/" . $full_image_name;
			$imagedatabase = "uploads/users/" . $add_user . "/" . $full_image_name;
			$thumb_path = "uploads/users/" . $add_user . "/thumbnails/" . $full_image_name;
			$user->update(['image'=>$imagedatabase]);
			move_uploaded_file($imageTmpName, $target_path);
			copy($target_path, $thumb_path);
			$imagick = new \Imagick(realpath($target_path));
			$imagick->resizeImage(90,90,\Imagick::FILTER_CATROM, 1);
			$imagick->writeImage(realpath( $thumb_path));
		}
		if (isset($_FILES['logo']['name']) && !empty($_FILES['logo']['name'])) {
			$logoName = $_FILES['logo']['name'];
			$logoTmpName = $_FILES['logo']['tmp_name'];
			$logo_path = "uploads/users/" . $add_user . "/logo_" . round(microtime(true)) . ".jpg";
			$logodatabase = "uploads/users/" . $add_user . "/logo_" . round(microtime(true)) . ".jpg";
//			$con->query("update users set `club_logo` = '$logodatabase' where `user_id`='$add_user'");
			$user->update(['club_logo'=>$logodatabase]);
			move_uploaded_file($logoTmpName, $logo_path);
		}
		if(isset($_SESSION['user_id'])){
			$userId = $_SESSION['user_id'];
			$action = "Add member : " . $name;
		} else {
			$invitation = get_family_invitation($_GET['type']);
			$userId = $invitation['user_id'];
			$action = "Family member and assistant , " . $name . " , accepted his invitation .";
		}

//		$con->query("insert into familyHistory values (null, '$family_id', '$userId' ,
//                                  '$action', '" . date('Y-m-d h:i:s a') . "')") or die(mysqli_error($con));
		FamilyHistory::create([
				'family_id'=>$family_id, 'user_id'=>$userId,'action'=>$action,'date'=>date('Y-m-d h:i:s a')
		]);
		if ($memberRole == 'user' || $is_that_you) {
			$successMessage = trans("registered_successfully");
			$registeredMessage = trans("registered_successfully");
		} else {
			$successMessage = trans("registered_successfully");
			$registeredMessage = trans("email_sent_with_link");
			$flag = $add_user + 10;
			$belong = $family_id + 20;
			$url = $siteUrl.$RELATIVE_PATH."/login.php?status=complete&flag={$flag}&belong={$belong}";
			$familyName = getFamilyName($family_id);
			$mailer = new Mailer();
			$mailer->setVars(['user_name'=>$name, 'url'=>$url, 'family_name'=>$familyName]);
			$emailSent = $mailer->sendMail([$email], "You are now a family assistant on alhamayel",
					'assistant_inform.html', 'assistant_inform.txt');
			$sent = 0;
			if ($emailSent) {
				$sent = 1;
			}
//			$con->query("insert into siteMails values (null, '$family_id','$name', '$email', 'addNewMember', '$sent', '" . date('Y-m-d h:i:s a') . "')");
			SiteMail::create([
					'family_id' => $family_id,
					'name' => $name,
					'email' => $email,
					'title' => 'addNewMember',
					'sent' => $sent,
					'date' => date('Y-m-d h:i:s a'),
			]);
		}
		if($invitation['responded'] == 0 && ! isset($_SESSION['user_id'])){
//			$con->query("update familyInvitations set responded='1', `responded_at`='".date('Y-m-d H:i:s')."' 
//			where id='".$invitation['id']."' and family_id='$family_id' and type='Both'") or die(mysqli_error($con));
			$invitation->update(['responded'=>1, 'responded_at'=>date('Y-m-d H:i:s')]);
			// $family_creator = getFamilyData($family_id)['user_id'];
			// $creator_email = getUserData($family_creator)['email'];
			// $creator_name = getUserData($family_creator)['name'];
			// $url = $siteUrl.$RELATIVE_PATH."/profile.php?lang={$lang}&tree={$add_user}#treeDiv";
			// $mailer = new Mailer();
			// $mailer->setVars(['user_name'=>$creator_name, 'url'=>$url]);
			// $mailer->sendMail([$creator_email], "Assistant started adding data",
			// 		'assistant_adding.html', 'assistant_adding.txt');
		}
	} else {
		$successMessage = trans("registration_failed");
	}
}
if (isset($_POST['joinFamily'])) {
	$userName = $password = $confirmPassword = '';
	if (isset($_POST['userName'])) {
		$userName = trim($_POST["userName"]);
	}
	if (isset($_POST['password'])) {
		$password = $_POST["password"];
	}
	if (isset($_POST['confirmPassword'])) {
		$confirmPassword = $_POST["confirmPassword"];
	}
	$name = trim($_POST["name"]);
	$kunya = trim($_POST["kunya"]);
	$gender = trim($_POST["gender"]);
	$husband = ($_POST['husband'] != '') ? trim($_POST["husband"]) : 0;
	$parent_id = ($_POST['parent_id'] != '') ? trim($_POST["parent_id"]) : 0;
	$phone = trim($_POST["phone"]);
	$country = trim($_POST["country"]);
	$email = trim($_POST["email"]);
	$role = trim($_POST["role"]);
	$family_id = $_POST['flag'];
	$member = $_POST['member'];
	$occupation = trim($_POST["occupation"]);
	$DOB = $_POST['DOB'];
	$nationality = trim($_POST["nationality"]);
	$facebook = trim($_POST["facebook"]);
	$twitter = trim($_POST["twitter"]);
	$instagram = trim($_POST["instagram"]);
	$snapchat = trim($_POST["snapchat"]);
	$interests = trim($_POST["interests"]);
	$club_name = trim($_POST["club_name"]);
	if($_POST['wifeFamily'] != 0){
		$wifeFamily = $_POST['wifeFamily'];
	} else {
		$wifeFamily = trim($_POST["memberFamily"]);
	}
	$invitedUserHusband = ($_POST['invitedUserHusband'] != '') ? $_POST['invitedUserHusband'] : null;
	$mother_id = ($_POST['mother_id'] != '') ? trim($_POST["mother_id"]) : 0;
	$errors = [];
	if (checkUserNameExists($userName, 0)) {
		array_push($errors, trans("usernameExists"));
	}
	if ($password != $confirmPassword) {
		array_push($errors, trans("passwordMatch"));
	}
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		array_push($errors, trans("invalidEmail"));
	}
	if (empty($errors)) {
		$user = User::create(['parent_id'=>$parent_id,'user_name'=>$userName,'password'=>$password,'image'=>$imageName,
				'name' => $name,'email'=>$email,'phone'=>$phone,'family_id'=>$family_id,'country_id'=>$country,
				'role'=>$role,'verified'=>true,'date'=>date('Y-m-d'),'member'=>$member,'occupation'=>$occupation,
				'kunya'=>$kunya,'date_of_birth'=>$DOB,'nationality'=>$nationality,'facebook'=>$facebook,'twitter'=>$twitter,
				'instagram'=>$instagram,'snapchat'=>$snapchat,'interests'=>$interests,'club_name'=>$club_name,'display'=>1,
				'gender'=>$gender,'mother_id'=>$mother_id,'outside_family'=>$wifeFamily
		]);
		$add_user = $user->user_id;
		if ($gender == 'Female' && $husband != 0 && $member == 1) {
//			$res = $con->query("select wife from users where `user_id`='$husband'") or die(mysqli_error($con));
//			$row = mysqli_fetch_assoc($res);
			$row = User::find($husband);
			$old = $row['wife'];
			if ($old != 0) {
				$new = $old . ',' . $add_user;
			} else {
				$new = $add_user;
			}
//			$con->query("update users set `wife`='$new' where `user_id`='$husband'") or die(mysqli_error($con));
			$row->update(['wife'=>$new]);
		} elseif($gender == 'Female' && strlen($invitedUserHusband) > 2 && $member == 1){
//			$con->query("update users set `husband`='$invitedUserHusband' where `user_id`='$add_user'") or die(mysqli_error($con));
			$user->update(['husband'=>$invitedUserHusband]);
		}
		if (!file_exists("uploads/users/" . $add_user)) {
			mkdir("uploads/users/" . $add_user, 0775, true);
		}
		if (isset($_FILES['photo']['name']) && !empty($_FILES['photo']['name'])) {
			$imageName = $_FILES['photo']['name'];
			$imageTmpName = $_FILES['photo']['tmp_name'];
			$target_path = "uploads/users/" . $add_user . "/" . round(microtime(true)) . ".jpg";
			$imagedatabase = "uploads/users/" . $add_user . "/" . round(microtime(true)) . ".jpg";
//			$con->query("update users set `image` = '$imagedatabase' where `user_id`='$add_user'");
			$user->update(['image'=>$imagedatabase]);
			move_uploaded_file($imageTmpName, $target_path);
		}
		if (isset($_FILES['logo']['name']) && !empty($_FILES['logo']['name'])) {
			$logoName = $_FILES['logo']['name'];
			$logoTmpName = $_FILES['logo']['tmp_name'];
			$logo_path = "uploads/users/" . $add_user . "/logo_" . round(microtime(true)) . ".jpg";
			$logodatabase = "uploads/users/" . $add_user . "/logo_" . round(microtime(true)) . ".jpg";
//			$con->query("update users set `club_logo` = '$logodatabase' where `user_id`='$add_user'");
			$user->update(['club_logo'=>$logodatabase]);
			move_uploaded_file($logoTmpName, $logo_path);
		}
		$type = 'J';
		if ($role == 'assistant' && $member == 0) {
			$type = 'H';
		} elseif ($role == 'assistant' && $member == 1) {
			$type = 'JA';
		}
		$url = $siteUrl.$RELATIVE_PATH."/profile.php?type={$type}&flag={$family_id}&lang={$lang}";
		$successMessage = trans("registered_successfully");
		if ($member == 1) {
			$registeredMessage = "Please Check Your Email To Verify Your Account, And If You Want To Add Another Member   " . "<a href='{$url}' style='color: #556575 !important; font-weight: 1000 !important;'>Click Here .</a>";
		} elseif ($member == 0) {
			$url = $siteUrl.$RELATIVE_PATH."/login.php?lang={$lang}";
			$registeredMessage = "Please Check Your Email To Verify Your Account, then    " . "<a href='{$url}' style='color: #556575 !important; font-weight: 1000 !important;'>Login .</a>";
		}
		$emailSent = sendActivationEmail($add_user);
		$sent = 0;
		if ($emailSent) {
			$sent = 1;
		}
//		$con->query("insert into siteMails values (null, '$family_id','$name', '$email', 'Answer To Invitation', '$sent', '" . date('Y-m-d h:i:s a') . "')");
		SiteMail::create([
				'family_id' => $family_id,
				'name' => $name,
				'email' => $email,
				'title' => 'Answer To Invitation',
				'sent' => $sent,
				'date' => date('Y-m-d h:i:s a'),
		]);
	} else {
		$successMessage = trans('registration_failed');
		$type = 'J';
		if ($role == 'assistant' && $member == 0) {
			$type = 'H';
		} elseif ($role == 'assistant' && $member == 1) {
			$type = 'JA';
		}
		$tryAgain = $siteUrl.$RELATIVE_PATH."/profile.php?type={$type}&flag={$family_id}";
	}
}
if (isset($_POST['submitNewFile'])) {
	$family = $_SESSION['family_id'];
	$user = $_SESSION['user_id'];
	$fileType = trim($_POST['fileType']);
	$description= trim($_POST['description']);
	$description_ar= trim($_POST['description_ar']);
	$fileTitle = trim($_POST['fileTitle']);
	$fileArTitle = trim($_POST['fileArTitle']);
	$fileArTitle = $fileArTitle ?: $fileTitle;
	$fileName = $_FILES['familyFile']['name'];
	$fileTmp = $_FILES['familyFile']['tmp_name'];
	$size = $_FILES['familyFile']['size'] / 1000;
	if ($fileType == "Image") {
		$extentions = ['jpg', 'JPG', 'png', 'PNG', 'jpeg', 'JPEG', 'tiff', 'bmp', 'gif', 'eps', ''];
		$ext = pathinfo($fileName, PATHINFO_EXTENSION);
		if (!in_array($ext, $extentions)) {
			$error = trans("invalidFile");
		}
	} elseif ($fileType == "Video") {
		$extentions = ['mp4', 'mov', 'mpg', 'flv', 'WMV', 'FLV', 'AVI', 'AVCHD', 'MKV', 'WebM'];
		$ext = pathinfo($fileName, PATHINFO_EXTENSION);
		if (!in_array($ext, $extentions)) {
			$error = trans("invalidFile");
		}
	} elseif ($fileType == "Audio") {
		$extentions = ['wav', "mp3", "Wave64", "m4a", "ogg", "wma", "aac"];
		$ext = pathinfo($fileName, PATHINFO_EXTENSION);
		if (!in_array($ext, $extentions)) {
			$error = trans("invalidFile");
		}
	} elseif ($fileType == "PDF") {
		$extentions = ['pdf', 'docx', 'doc', 'odt', 'ods', 'ppt', 'pptx', 'txt'];
		$ext = pathinfo($fileName, PATHINFO_EXTENSION);
		if (!in_array($ext, $extentions)) {
			$error = trans("invalidFile");
		}
	}
	$info = checkPlan($family, "Media");
	$media = $info['media'] * 1000000;
	$sum = $info['sum'];
	if (($sum + $size) > $media) {
		$error = trans("media_plan_error");
	}
	if (!empty($fileName) && empty($error)) {
		$media = FamilyMedia::create([
				'user_id'=>$user,
				'family_id'=>$family,
				'name_en'=>$fileTitle,
				'name_ar'=>$fileArTitle,
				'description_en'=>$description,
				'description_ar'=>$description_ar,
				'file'=>$fileName,
				'family_type' => 'Gallery',
				'file_type'=>$fileType,
				'size'=>$size,
				'date'=> date('Y-m-d H:i:s')
		]);
		$id = $media->id;
		if (!file_exists("./uploads/media/{$id}")) {
			mkdir("./uploads/media/{$id}", 0775, true);
		}
		$target_path = "./uploads/media/{$id}/" . round(microtime(true)) . "_{$fileName}";
		$file_db =  "uploads/media/{$id}/" . round(microtime(true)) . "_{$fileName}";
		$media->update(['file'=>$file_db]);
		$check = move_uploaded_file($fileTmp, $target_path);
		if($check){
			$success = trans("addMessage");
		}
	}
}
if (isset($_POST['submitEditFile'])) {
	$family = $_SESSION['family_id'];
	$user = $_SESSION['user_id'];
	$fileId = trim($_POST['file_id']);
	$fileType = trim($_POST['fileType']);
	$description= trim($_POST['description']);
	$description_ar= trim($_POST['description_ar']);
	$fileTitle = trim($_POST['fileTitle']);
	$fileArTitle = trim($_POST['fileArTitle']);
	$fileArTitle = $fileArTitle ?: $fileTitle;
	$media = Family::find($family)->media()->where(['id'=>$fileId])->first();
	if (! $media){
		$error = trans("no_file");
	}
	else {
        if(isset($_FILES['familyFile']['name']) && ! empty($_FILES['familyFile']['name'])){
            $file_db = $media->file;
            $size = $media->size;
            $fileName = $_FILES['familyFile']['name'];
            $fileTmp = $_FILES['familyFile']['tmp_name'];
            $size = $_FILES['familyFile']['size'] / 1000;
            if ($fileType == "Image") {
                $extentions = ['jpg', 'JPG', 'png', 'PNG', 'jpeg', 'JPEG', 'tiff', 'bmp', 'gif', 'eps', ''];
                $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                if (!in_array($ext, $extentions)) {
                    $error = trans("invalidFile");
                }
            } elseif ($fileType == "Video") {
                $extentions = ['mp4', 'mov', 'mpg', 'flv', 'WMV', 'FLV', 'AVI', 'AVCHD', 'MKV', 'WebM'];
                $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                if (!in_array($ext, $extentions)) {
                    $error = trans("invalidFile");
                }
            } elseif ($fileType == "Audio") {
                $extentions = ['wav', "mp3", "Wave64", "m4a", "ogg", "wma", "aac"];
                $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                if (!in_array($ext, $extentions)) {
                    $error = trans("invalidFile");
                }
            } elseif ($fileType == "PDF") {
                $extentions = ['pdf', 'docx', 'doc', 'odt', 'ods', 'ppt', 'pptx', 'txt'];
                $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                if (!in_array($ext, $extentions)) {
                    $error = trans("invalidFile");
                }
            }

            $info = checkPlan($family, "Media");
            $media_size = $info['media'] * 1000000;
            $sum = $info['sum'];
            if (($sum + $size) > $media_size) {
                $error = trans("media_plan_error");
            }
            else {
                $id = $media->id;
                if (!file_exists("./uploads/media/{$id}")) {
                    mkdir("./uploads/media/{$id}", 0775, true);
                }
                $target_path = "./uploads/media/{$id}/" . round(microtime(true)) . "_{$fileName}";
                $file_db =  "uploads/media/{$id}/" . round(microtime(true)) . "_{$fileName}";
                $check = move_uploaded_file($fileTmp, $target_path);
            }
        }
	}
	if (empty($error) && isset($_FILES['familyFile']['name']) && ! empty($_FILES['familyFile']['name'])) {
		$update = $media->update([
			'user_id'=>$user,
			'family_id'=>$family,
			'name_en'=>$fileTitle,
			'name_ar'=>$fileArTitle,
			'description_en'=>$description,
			'description_ar'=>$description_ar,
			'file'=>$file_db,
			'family_type' => 'Gallery',
			'file_type'=>$fileType,
			'size'=>$size,
		]);
	} else if (empty($error) && empty($_FILES['familyFile']['name'])){
        $update = $media->update([
            'user_id'=>$user,
            'family_id'=>$family,
            'name_en'=>$fileTitle,
            'name_ar'=>$fileArTitle,
            'description_en'=>$description,
            'description_ar'=>$description_ar,
        ]);
    }

    if($update){
        $success = trans("addMessage");
    }
}
if (isset($_POST['editSubmit'])) {
	$family = $_SESSION['family_id'];
	$required_fields = ['desc_ar', 'desc_en', 'fnameEn', 'fnameAr', 'fstatus'];
	$errors = validate($required_fields);
	$desc_ar = trim($_POST['desc_ar']);
	$desc_en = trim($_POST['desc_en']);
	$name_en = trim($_POST['fnameEn']);
	$name_ar = trim($_POST['fnameAr']);
	$status = trim($_POST['fstatus']);
//	$en_name_exists = $con->query("SELECT * FROM family where id != '$family' and name_en='$name_en'");
	$en_name_exists = Family::where('id', '!='. $family)->where(['name_en'=>$name_en])->first();
	if($en_name_exists){
		$errors['en_name'] .= "\nEnglish Family name already exists, please choose a different name";
	}
//	$ar_name_exists = $con->query("SELECT * FROM family where id != '$family' and name_ar='$name_ar'");
	$ar_name_exists =Family::where('id', '!='. $family)->where(['name_ar'=>$name_ar])->first();
	if($ar_name_exists){
		$errors['ar_name'] .= "\nArabic Name already exists, please choose a different name";
	}
	if(empty($errors)){
		if($family == $_SESSION['family_id']){
//			$query = $con->query("update family set `desc_ar`='$desc_ar', `desc_en`='$desc_en',
//                  `name_en`='$name_en', `name_ar`='$name_ar', `status`='$status' where id='$family'");
            $name_ar = str_replace('عائله', '', $name_ar);
            $name_ar = str_replace('عائلة', '', $name_ar);
            $name_en = str_replace('family', '', $name_en);
            $name_en = str_replace('Family', '', $name_en);
			$query = Family::find($family)->update(['desc_ar'=>$desc_ar, 'desc_en'=>$desc_en,
					'name_en'=>$name_en,'name_ar'=>$name_ar, 'status'=>$status
			]);
			$success = trans("updateMessage");
		}

	}
	else {
		$fail = $errors;
	}
}
if (isset($_GET['family'])) {
	$familyId = $_GET['family'];
} elseif (isset($_GET['flag'])) {
	$familyId = $_GET['flag'];
} elseif (isset($_GET['f']) && $_GET['f']) {
	$familyId = $_GET['f'];
} else {
	$familyId = $_SESSION['family_id'];
}

$logged_in_user = User::where(['user_id'=>$_SESSION['user_id']])->where(['family_id'=> $familyId])->where('role', '!=', 'user')->first();

?>

<!--<link rel='stylesheet' href='//cdn.jsdelivr.net/npm/lightgallery@2.0.0-beta.3/css/lightgallery-bundle.css'>-->
<!--<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/lightgallery/2.2.0-beta.0/css/lg-thumbnail.min.css"/>-->
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/lightgallery/1.2.21/css/lightgallery.min.css'>
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" />
<link rel="stylesheet" href="css/gallery.css">
<link rel="stylesheet" href="css/likely.css">
<link href="//vjs.zencdn.net/4.12/video-js.css" rel="stylesheet">
<link rel="stylesheet" href="css/profile.css">

<input type="hidden" id="loggedUserRole" value="<?php echo $_SESSION['role']; ?>">
<input type="hidden" id="profileLang" value="<?php echo $lang; ?>">
<input type="hidden" id="deleteMedia" value="<?php if (isset($deleteMessage)) {echo $deleteMessage;} ?>">
<input type='hidden' id='newUser' value="<?php if (isset($_SESSION['newUser'])) {echo $_SESSION['newUser'];} ?>">
<input type="hidden" id="newly_added_member" value="<?= isset($add_user) ? $add_user : '' ?>">
<input type='hidden' id='submitStatus' value="<?php echo isset($_SESSION['submitStatus']) ? $_SESSION['submitStatus'] : ''; ?>">
<input type='hidden' id='loggedFamily' value="<?php
if (isset($_GET['family'])) {
	$familyId = $_GET['family'];
} elseif (isset($_GET['flag'])) {
	$familyId = $_GET['flag'];
} elseif (isset($_GET['f']) && $_GET['f']) {
	$familyId = $_GET['f'];
} else {
	$familyId = $_SESSION['family_id'];
}
echo $familyId;
?>">
<div class="modal fade" id="user-bio-see" style="z-index: 1080 !important;">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ml-auto mt-3"><?=trans('about_member')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=trans('close')?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="bio-container">

                </div>
            </div>
            <div class="modal-footer justify-content-center" style="margin: auto;">
                <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal"><?= trans('close') ?></button>
            </div>
        </div>

    </div>
</div>
<div class="modal fade" id="modal30" tabindex="-1" role="dialog">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ml-auto mt-3"><?= trans('join_request') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=trans('close')?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" value="<?= $familyId ?>" name="familyId">
                    <div class="form-group">
                        <label><?= trans('name') ?></label>
                        <input type="text" class="form-control" required name="strangerName">
                    </div>

                    <div class="form-group">
                        <label><?= trans('email') ?></label>
                        <input type="email" class="form-control" required name="strangerEmail">
                    </div>

                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" class="form-control" required name="strangerPhone">
                    </div>
                    <div class="form-group h4">
                        <input type="checkbox" required> <span>I agree to <a href="terms.php?lang=<?php echo $lang; ?>" style="color: blue !important;" target="_blank"><?= trans('terms_conditions') ?></a></span>
                    </div>

                </div>
                <div class="modal-footer justify-content-center" style="margin: auto;">
                    <button type="submit" class="btn hbtn btn-hred" name="addMembersSubmit">Send</button>
                    <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="close30"><?= trans('close') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modal31" tabindex="-1" role="dialog">
    <div class="modal-dialog " role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ml-auto mt-3"><?=trans('request_private')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=trans('close')?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" value="" name="familyId" id="accessedFamilyId">
                    <div class="form-group">
                        <label><?=trans('name')?></label>
                        <input type="text" class="form-control" required name="strangerName">
                    </div>

                    <div class="form-group">
                        <label><?=trans('email')?></label>
                        <input type="email" class="form-control" required name="strangerEmail">
                    </div>

                </div>
                <div class="modal-footer justify-content-center" style="margin: auto;">
                    <button type="submit" class="btn hbtn btn-hred" name="requestSubmit"><?=trans('send')?></button>
                    <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="close31"><?=trans('close')?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modal1" tabindex="-1" role="dialog">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ml-auto mt-3" ><?=trans('add_media_item')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=trans('close')?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="fileUser" value="<?php echo $_SESSION['user_id']; ?>">
                    <input type="hidden" name="fileFamily" value="<?php echo $_SESSION['family_id']; ?>">
                    <div class="form-group">
                        <label for="fileType"><?=trans('item_type')?> *</label>
                        <select name="fileType" class="form-control" id="fileType" required>
                            <option value=''><?=trans('choose')?></option>
                            <option value="Image"><?=trans('image')?></option>
                            <option value="Video"><?=trans('video')?></option>
                            <option value="Audio"><?=trans('audio')?></option>
                            <option value="PDF"><?=trans('document')?></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fileTitle"><?=trans('item_name')?> *</label>
                        <input type="text" class="form-control" required name="fileTitle" id="fileTitle">
                    </div>
                    <div class="form-group">
                        <label for="fileArTitle"><?=trans('item_name_ar')?></label>
                        <input type="text" class="form-control" name="fileArTitle" id="fileArTitle">
                    </div>

                    <div class="form-group">
                        <label for="fileDesc"><?=trans('desc')?></label>
                        <input type="text" class="form-control" name="description" id="fileDesc">
                    </div>
                    <div class="form-group">
                        <label for="fileArDesc"><?=trans('desc_ar')?></label>
                        <input type="text" class="form-control" name="description_ar" id="fileArDesc">
                    </div>

                    <div class="form-group">
                        <label for="familyFile"><?=trans('file')?> *</label>
                        <input type="file" class="form-control" required name="familyFile" id="familyFile">
                    </div>

                </div>
                <div class="modal-footer justify-content-center" style="margin: auto;">
                    <button type="submit" class="btn hbtn btn-hred" name="submitNewFile"><?=trans('submit')?></button>
                    <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="close1"><?=trans('close')?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modal2" tabindex="-1" role="dialog">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <form method="POST" class="form">
                <div class="modal-header">
                    <h5 class="modal-title ml-auto mt-3"><?=trans('edit_family_details')?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="<?=trans('close')?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" value="" name="edit_family_id" id="edit_family_id">
                    <div class="form-group">
                        <label for="fnameEn"><?=trans('familyNameEn')?></label>
                        <input name="fnameEn" id="fnameEn" class="form-control validate-text validate-en">
                    </div>
                    <div class="form-group">
                        <label><?=trans('english_desc')?></label>
                        <textarea name="desc_en" id="desc_en" class="form-control validate-text validate-en"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="fnameAr"><?=trans('familyNameAr')?></label>
                        <input name="fnameAr" id="fnameAr" class="form-control validate-text validate-ar">
                    </div>

                    <div class="form-group">
                        <label><?=trans('arabic_desc')?></label>
                        <textarea name="desc_ar" id="desc_ar" class="form-control validate-text validate-ar"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="fstatus">
							<?=trans('fStatus')?>
                        </label>
                        <select id="fstatus"  required name="fstatus" class="form-control" >
                            <option value=""><?=trans('fStatus')?></option>
                            <option value="0" <?=isset($_POST['fstatus'])?($_POST['fstatus'] == "0" ?'selected':''):""?>><?=trans('private')?></option>
                            <option value="1" <?=isset($_POST['fstatus'])?($_POST['fstatus'] == "1" ?'selected':''):""?>><?=trans('public')?></option>
                            <option value="2" <?=isset($_POST['fstatus'])?($_POST['fstatus'] == "2" ?'selected':''):""?>><?=trans('show_tree_only')?></option>
                            <option value="3" <?=isset($_POST['fstatus'])?($_POST['fstatus'] == "3" ?'selected':''):""?>><?=trans('show_gallery_only')?></option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center" style="margin: auto;">
                    <button type="submit" class="btn hbtn btn-hred" name="editSubmit"><?=trans('submit')?></button>
                    <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="close2"><?=trans('close')?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modal51" tabindex="-1" role="dialog">
    <div class="modal-dialog " role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" ><?=trans('permanent_delete')?></h5>
            </div>
            <div class="modal-body">

                <input type="hidden" value="" name="deleteNode" id="deleteNode">
            </div>
            <div class="modal-footer justify-content-center" style="margin: auto;">
                <button type="button" class="btn hbtn btn-hred" name="deleteNodeSubmit" id="deleteNodeSubmit"><?=trans('delete')?></button>
                <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="close51"><?=trans('close')?></button>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="modal11" tabindex="-1" role="dialog">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ml-auto mt-3"><?=trans('show_member_tree')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=trans('close')?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" value="" id="changeStatusOn">
            </div>
            <div class="modal-footer justify-content-center" style="margin: auto;">
                <button type="button" class="btn hbtn btn-hred" id="showUser"><?=trans('show_tree')?></button>
                <button type="button" class="btn hbtn btn-hmutedy" data-dismiss="modal" id="close11"><?=trans('close')?></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal111" tabindex="-1" role="dialog">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center"><?=trans('hide_member_tree')?></h5>
            </div>
            <div class="modal-body">
                <input type="hidden" value="" id="changeStatusOff">
            </div>
            <div class="modal-footer justify-content-center" style="margin: auto;">
                <button type="button" class="btn hbtn btn-hred" id="hideUser"><?=trans('hide_tree')?></button>
                <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="close111"><?=trans('close')?></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal10" tabindex="-1" role="dialog">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title m-auto mt-3" ><span id="spanText"><?=trans('hide')?></span> <?=trans('this_member')?> ?</h5>
            </div>
            <div class="modal-body">
                <input type="hidden" value="" id="hiddenMember">
            </div>
            <div class="modal-footer justify-content-center" style="margin: auto;">
                <button type="button" class="btn hbtn btn-hred" id="hideThisMember"><?=trans('hide')?> </button>
                <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="close10"><?=trans('close')?></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal3" tabindex="-1" role="dialog">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ml-auto mt-3" ><?=trans('delete_file')?></h5>
            </div>
            <div class="modal-body">
                <p style="text-align: center;"><?=trans('want_delete_file')?> ?</p>
                <input type="hidden" value="" name="deleteFile" id="deleteFile">
            </div>
            <div class="modal-footer justify-content-center" style="margin: auto;">
                <button type="button" class="btn hbtn btn-hred" id="deleteSubmit"><?=trans('delete')?></button>
                <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="close3"><?=trans('close')?></button>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="request_view_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ml-auto mt-3 request_view_modal_title" style="text-align: center;"><?=trans('request_to_view_family_profile')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <input type="hidden" value="" name="requested_family" id="requested_family">
                    <input type="hidden" value="" name="requested_type" id="requested_type">
                    <div class="form-group">
                        <label><?=trans('name')?>* :</label>
                        <input type="text" class="form-control" required name="strangerName" placeholder="<?=trans('enter')?><?=trans('name')?>" value="<?= $_SESSION['name'] ?>">
                    </div>
                    <div class="form-group">
                        <label><?=trans('email')?>* :</label>
                        <input type="email" class="form-control" required name="strangerEmail" placeholder="<?=trans('enter')?><?=trans('email')?>" value="<?= $_SESSION['email'] ?>">
                    </div>
            </div>
            <div class="modal-footer" style="margin: auto;">
                <button type="submit" class="btn hbtn btn-hred" name="request_view_family_submit"><?=trans('submit')?></button>
                <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="request_view_modal_close"><?=trans('close')?></button>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modal17" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog  " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ml-auto mt-3 ml-auto mt-3" ><?=trans('record_pron')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=trans('close')?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p style="text-align: center;"><?=trans('note_max_record')?> .
                    <span class="badge" style="text-align: right !important;" id="counter">10</span>
                </p>
                <p id="recordStatus" style="text-align: center; color: red;"></p>
            </div>
            <div class="modal-footer justify-content-center" style="margin: auto;">
                <div class="slider w-100" id="encodingRecord">
                    <div class="spinner-border d-block m-auto" style="width: 80px;height:80px;" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <button type="button" class="btn btn-primary" id="record">Start</button>
                <button type="button" class="btn btn-success" id="stop" style="display: none;"><?=trans('stop')?></button>
                <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="close17"><?=trans('cancel')?></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal4" tabindex="-1" role="dialog">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ml-auto mt-3">
                    - <?=trans('invite_members_join')?>. <br>
                    - <?=trans('invite_assistants_join')?> .
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=trans('close')?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <input type="hidden" value="<?php echo $_SESSION['family_id']; ?>" name="familyId" id="familyId">
                    <div class="form-group">
                        <label><?=trans('name')?> *</label>
                        <input type="text" class="form-control strangerName" required name="strangerName" required>
                    </div>
                    <div class="form-group">
                        <label><?=trans('email')?> *</label>
                        <input type="email" class="form-control strangerEmail" name="strangerEmail" required>

                    </div>
                    <div class="form-group">
                        <input type="radio" name="type" value="Assistant" checked> <?=trans('assistant')?><br>
                        <input type="radio" name="type" value="Both"><?=trans('assistant_and_member')?>
                    </div>
                    <div class="form-group d-none" id="mailMessage">
                        <textarea id="copiedMessage" class="form-control" rows="3" readonly></textarea>
                        <div class="w-100 text-center p-0 pt-1">
                            <!--                            <a href="#" class="btn btn-sm btn-primary copy-btn"><i class="fa fa-clipboard"></i> --><?//=trans('copy')?><!--</a>-->
                            <a id="invite-send-whatsapp" href="https://wa.me/?text="
                               class="btn btn-success btn-sm" target="_blank"><i class="fa fa-whatsapp"></i></a>
                        </div>
                        <!-- <hr> -->
                        <!-- <small><?=trans('copy_to_social')?></small> -->
                    </div>

            </div>
            <div class="modal-footer justify-content-center" style="margin: auto;">
                <button type="button" class="btn hbtn btn-hred" id="inviteSubmit"><?=trans('send')?></button>
                <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="close4"><?=trans('close')?></button>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modal5" tabindex="-1" role="dialog">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ml-auto mt-3">
					<?php
					if (isset($_GET['type']) && $_GET['type'] == 'J') {
						echo "Add Family Member";
					} elseif ($_GET['type'] == 'H' || $_GET['type'] == 'JA') {
						echo "Join Us";
					}
					?>
                </h5>
            </div>
            <form method="POST" enctype="multipart/form-data">

                <div class="modal-body" style="height: 250px;
                 overflow-y: auto;">
                    <input type="hidden" value="" id="flag" name="flag">
                    <input type="hidden" value="" id="role" name="role">
                    <input type="hidden" value="" id="member" name="member">
                    <div class="form-group">
                        <input type="text" class="form-control" required name="name" placeholder="<?=trans('first_name')?>"  maxlength="12">
                    </div>

                    <div class="form-group">
                        <label><?=trans('DOB')?></label>
                        <input type="date" class="form-control" required placeholder="<?=trans('DOB')?>" name="DOB" max="<?=date('Y-m-d')?>">
                    </div>
					<?php if ($_GET['type'] == 'H' || $_GET['type'] == 'JA') { ?>
                        <div class="form-group">
                            <input type="text" class="form-control" required name="userName" placeholder="<?=trans('username')?>" id="username">
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <input type="password" class="form-control" required name="password" placeholder="<?=trans('password')?>" id="password">
                                <div class="input-group-append">
                                    <a type="buttton" class="input-group-text show-password" style="background: #fff; border-left: 0; cursor: pointer;"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <input type="password" class="form-control" required name="confirmPassword" placeholder="<?=trans('confirmPassword')?>" id="cpass">
                                <div class="input-group-append">
                                    <a type="buttton" class="input-group-text show-password" style="background: #fff; border-left: 0; cursor: pointer;"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
					<?php } ?>
                    <div class="form-group">
                        <input type="radio" name="gender" value="Male" checked class="gender"> <?=trans('male')?><br>
                        <input type="radio" name="gender" value="Female" class="gender"> <?=trans('female')?>
                    </div>
					<?php if ($_GET['type'] != 'H') { ?>
                        <div class="form-group">
                            <select class="form-control" style="display: none;" id="joinStatus">
                                <option value=''>Marital Status</option>
                                <option value='0'>Not Married</option>
                                <option value='1'>Married</option>

                            </select>
                        </div>
                        <div class="form-group">
                            <select class="form-control" name="husband" style="display: none;" id="husband">
                                <option value=''>Wife Of</option>
                                <option value='0'>Not In The List</option>
								<?php
								$users = User::where(['family_id'=>$_GET['flag']])->male()->where(['member'=>1])->get();
								foreach ($users as $user) {

									echo "<option value='{$user["user_id"]}' style='font-size: 1vw !important;'
                                        data-imagesrc='{$user['image']}'>{$user['user_id']}- {$user['name']}</option>";
								}
								?>
                            </select>
                        </div>

                        <div class="form-group">
                            <input type="text" name="invitedUserHusband" id="invitedUserHusband" style="display: none;" placeholder="<?=trans('husband_name')?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <select class="form-control" name="parent_id" id="joinParentSelect" required>
                                <option value=''><?=trans('choose_father')?></option>

								<?php
								foreach ($users as  $user) {
									echo "<option value='{$user["user_id"]}'>{$user['user_id']}- {$user['name']}</option>";
								}
								?>
                            </select>
                        </div>
                        <div class="form-group">
                            <select class="form-control" name="mother_id" style="display: none;" id="joinMother">
                                <option value=''><?=trans('choose_mother')?></option>

                            </select>
                        </div>
                        <div class="form-group">

                            <select class="form-control" name="wifeFamily" style="width: 100%; display: none;" id="wifeFamily">
                                <option value=''><?=trans('choose_new_family')?></option>
                                <option value='0'>Not In The List</option>
								<?php
								$families = Family::valid()->active()->get();
								foreach ($families as $family) {

									echo "<option value='{$family['id']}'>".db_trans($family, 'name')."</option>";
								}
								?>
                            </select>
                        </div>
                        <div class="form-group d-none memberFamily">
                            <input type="text" name="memberFamily" class="form-control" placeholder="<?=trans('familyName')?>">
                        </div>
					<?php } ?>
                    <div class="form-group">
                        <input type="text" class="form-control" name="kunya" placeholder="<?=trans('kunya')?>">
                    </div>
                    <div class="form-group">
                        <select class="form-control country_id" name="country" required>
                            <option value=''><?=trans('country_residence')?></option>
							<?php
							$countries = Country::active()->get();
							foreach ($countries as $country) {
								echo "<option value='{$country["id"]}'>".db_trans($country, 'name')."</option>";
							}
							?>
                        </select>
                    </div>

                    <div class="form-group">
                        <select class="form-control" name="nationality" required>
                            <option value=''><?=trans('chooseNationality')?></option>
							<?php
							$nationalities = Nationality::all();
							foreach ($nationalities as $nationality) {
								echo "<option value='{$nationality["id"]}'>{$nationality['name']}</option>";
							}
							?>
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom: 5px !important;">
                        <span style="color: red;">*</span>
                        <input id='' placeholder="<?=trans('key')?>" class="form-control col-sm-2 key"><input type="text" class="form-control col-md-10" name="phone" placeholder="<?=trans('phone')?>">
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" required name="email" placeholder="<?=trans('email')?>" id="invjoinMemberEmail">
                    </div>

                    <div class="form-group">
                        <input type="email" class="form-control" required placeholder="<?=trans('confirm_email')?>" id="invConfirmEmail">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="occupation" placeholder="<?=trans('occupation')?>">
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control facebook" name="facebook" placeholder="<?=trans('enter_facebook')?>">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control twitter" name="twitter" placeholder="<?=trans('enter_twitter')?>">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control instagram" name="instagram" placeholder="<?=trans('enter_instagram')?>">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control snapchat" name="snapchat" placeholder="<?=trans('enter_snapchat')?>">
                    </div>

                    <div class="form-group">
                        <span style="text-align: left;"><?=trans('profile_pics')?></span>
                        <input type="file" class="form-control" name="photo">
                    </div>

					<?php if ($_GET['type'] != 'H') { ?>
                        <div class="form-group">
                            <span style="text-align: left;"><?=trans('preferred_club')?></span>
                            <input type="file" class="form-control" name="logo">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="club_name" placeholder="<?=trans('clubName')?>">
                        </div>
					<?php } ?>
                    <div class="form-group">
                        <textarea class="form-control" name="interests" placeholder="<?=trans('hobbies_interests')?>"></textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-center" style="margin: auto;">
                    <button type="submit" class="btn hbtn btn-hred" name="joinFamily"><?=trans('submit')?></button>
                    <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="close5"><?=trans('close')?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modal55" tabindex="-1" role="dialog" >
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data" id="addNodeForm">
                <div class="modal-header">
					<?php if(isset($_SESSION['user_id'])){
						?>
                        <h5 class="modal-title ml-auto mt-3" style="text-align: center;">
							<?=trans('add_member')?></h5>
					<?php } else if(! isset($_SESSION['user_id']) && isset($_GET['type'])) { ?>
                        <h5 class="modal-title ml-auto mt-3" style="text-align: center;">
							<?=trans('add_info')?></h5>
					<?php } ?>
                    <button type="button" class="close" data-dismiss="modal" aria-label="<?=trans('close')?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="
                 overflow-y: auto;">
					<?php if(isset($_SESSION['user_id'])){
						$display = "display: none";
						?>
                        <div class="form-group">
                            <select class="form-control" name="role" required id="userRole">
                                <option value=''><?=trans('choose_role')?></option>
                                <option value='member'><?=trans('justUser')?></option>
                                <option value='assistant'><?=trans('assistant')?></option>
                                <option value='both'><?=trans('assistant_and_member')?></option>
                            </select>
                        </div>
					<?php } else if(! isset($_SESSION['user_id']) && isset($_GET['type'])) {
						$display = "";
					} ?>
                    <div class="form-group" id="memberName" style="<?= $display; ?>">
                        <span>*</span>
                        <input type="text" class="form-control" required name="name" placeholder="<?=trans('first_name')?>" maxlength="12">
                    </div>

                    <div class="form-group" id="memberEmail" style="<?= $display; ?>">
                        <span>*</span>
                        <input type="email" class="form-control" name="email" placeholder="<?=trans('email')?>" required id="joinMemberEmail">
                    </div>
                    <div class="form-group" id="memberConfirmation" style="<?= $display; ?>">
                        <span>*</span>
                        <input type="email" class="form-control" placeholder="<?=trans('confirm_email')?>" required id="joinConfirmEmail" name="joinConfirmEmail">
                        <span class="d-none email_confirmation_error"><?=trans('email_mismatch')?> .</span>
                    </div>

                    <div class="form-group" style="<?= $display; ?>" id="memberPhone">
                        <span>*</span>
                        <input type="text" class="form-control" name="phone" placeholder="<?=trans('phone')?>" required>
                    </div>

                    <div class="form-group" id="profileDiv" style="<?= $display; ?>">
                        <span style="text-align: left;"><?=trans('profile_picture')?></span>
                        <input type="file" class="form-control" name="photo">
                    </div>
                    <div class="form-group" style="<?= $display; ?>" id="country_residence">
                        <span>*</span>
                        <select class="form-control country_id" name="country" required>
                            <option value=''><?=trans('country_residence')?></option>
							<?php
							$countries = Country::active()->get();
							foreach ($countries as $country) {
								echo "<option value='{$country["id"]}'>".db_trans($country, 'name')."</option>";
							}
							?>
                        </select>
                    </div>
					<?php if(! isset($_SESSION['user_id']) && isset($_GET['type']) && isset($_GET['flag'])){ ?>
                        <div class="form-group h4">
                            <input type="checkbox" required> <span><?=trans('agree_to')?> <a href="terms.php?lang=<?php echo $lang; ?>" style="color: blue !important;" target="_blank"><?=trans('terms_conditions')?></a></span>
                        </div>
					<?php } ?>

                </div>
                <div class="modal-footer justify-content-center" style="margin: auto;">
                    <button type="submit" class="btn hbtn btn-hred" name="addNodeSubmit" id=
                    "addNodeSubmit" style="<?= $display; ?>"><?=trans('submit')?></button>
                    <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="close55"><?=trans('close')?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modal555" tabindex="-1" role="dialog">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
				<?php if(isset($_SESSION['user_id'])){ ?>
                    <h5 class="modal-title ml-auto mt-3">
						<?=trans('add_member_or_assistant')?></h5>
				<?php } else { ?>
                    <h5 class="modal-title ml-auto mt-3">
						<?=trans('add_info')?></h5>
				<?php } ?>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=trans('close')?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" enctype="multipart/form-data" id="addRelatedMemberForm">
                <div class="modal-body" style="height: 45vh !important;
                 overflow-y: auto;">
					<?php if(isset($_SESSION['user_id'])){

						?>
                        <input type="hidden" value="<?php echo $_SESSION['family_id']; ?>" name="flag">
					<?php } else {

						?>
                        <input type="hidden" value="<?php echo $_GET['flag']; ?>" name="flag">
					<?php } ?>
                    <input type="hidden" name="relatedMember" id="relatedMember" value="">
                    <input type="hidden" name="relatedMemberGender" id="relatedMemberGender" value="">

                    <input type="hidden" name="checkAlpha" id="checkAlpha" value="">

                    <div class="form-group">
                        <span>*</span>
                        <select class="form-control" name="relationType" required id="relationType">

                        </select>
                    </div>
					<?php if($_SESSION['waiting_to_join'] == 1){ ?>
                        <div class="h6">
                            <input type="checkbox" class="is_that_you" name="is_that_you"> <span><?=trans('is_that_you?')?></span>
                        </div>
					<?php } ?>
                    <div class="form-group d-none" id="memberMomDiv">
                        <span>*</span>
                        <select class="form-control" name="memberMom" id="memberMom" style="display: none;">

                        </select>
                    </div>
                    <div class="form-group d-none" id="familyDiv">
                        <span>*</span>
                        <select class="form-control" name="wifeFamily" style="width: 100%; display: none;" id="family">
                            <option value=''><?=trans('choose_new_family')?></option>
                            <option value='0'><?=trans('not_in_the_list')?></option>
							<?php
							foreach ($families as $family) {

								echo "<option value='{$family['id']}'>".db_trans($family, 'name')."</option>";
							}
							?>
                        </select>
                    </div>
                    <div class="form-group d-none member_family">
                        <span>*</span>
                        <input type="text" name="memberFamily" class="form-control" placeholder="<?=trans('familyName')?>">
                    </div>
                    <div class="form-group" id="childrenDiv" style="display: none;">
                        <label for="children"><?=trans('choose_children')?> *</label>
                        <select class="form-control selection" name="children[]" style="width: 100%;" multiple="multiple" id="children">
                        </select>
                    </div>

                    <div class="form-group d-none" id="choose_father">
                        <span>*</span>
                        <select class="form-control" style="width: 100%;" name="choose_father"></select>
                    </div>

                    <div class="form-group">
                        <span>*</span>
                        <input type="text" class="form-control" required name="name" placeholder="<?=trans('first_name')?>" maxlength="12" id="first_name">
                    </div>
                    <div class="form-group" id="DOBDiv">
                        <label><?=trans('DOB')?> *</label>
                        <input type="date" class="form-control" required placeholder="<?=trans('DOB')?>" name="DOB" max="<?php echo date('Y-m-d'); ?>" id="nodeDOB">
                    </div>
                    <div class="form-group" id="DODDiv">
                        <label for="nodeDOD"><?=trans('dod')?></label>
                        <input type="date" class="form-control" placeholder="<?=trans('dod')?>" name="DOD" id="nodeDOD" max="<?=date('Y-m-d')?>">
                    </div>

                    <div class="form-group">
                        <span>*</span>
                        <select class="form-control" name="memberRole" required id="memberRole">
                            <option value=''><?=trans('chooseRole')?></option>
                            <option value='user'><?=trans('justUser')?></option>
                            <option value='assistant'><?=trans('assistant_and_member')?></option>
                        </select>
                    </div>

                    <div class="form-group" id="kunya">
                        <input type="text" class="form-control" placeholder="<?=trans('kunya')?>" name="kunya">
                    </div>

                    <div class="form-group" id="countryDiv">
                        <span>*</span>
                        <select class="form-control country_id" name="country" required id="country_of_residence">
                            <option value=''><?=trans('chooseCountry')?></option>
							<?php
							foreach ($countries  as $country) {
								echo "<option value='{$country["id"]}'>".db_trans($country, 'name')."</option>";
							}
							?>
                        </select>
                    </div>

                    <div class="form-group">
                        <span>*</span>
                        <select class="form-control" name="nationality" required>
                            <option value=''><?=trans('chooseNationality')?></option>
							<?php
							foreach ($nationalities  as $nationality) {
								echo "<option value='{$nationality["id"]}'>{$nationality['name']}</option>";
							}
							?>
                        </select>
                    </div>
                    <div class="form-group">
                        <span class="star d-none">*</span>
                        <input type="email" class="form-control" name="email" placeholder="<?=trans('email')?>" id="relatedMemberEmail">
                    </div>
                    <div class="form-group" id="memberConfirmation">
                        <span class="star d-none">*</span>
                        <input type="email" class="form-control" placeholder="<?=trans('confirm_email')?>" id="relatedMemberEmailConfirmation" name="confirmEmail">
                        <span class="d-none confirm_email_error"><?=trans('email_mismatch')?> .</span>
                    </div>
                    <!-- <span>*</span> -->
                    <div class="form-group">

                        <input placeholder="<?=trans('key')?>" class="form-control col-sm-2 key related_country_key"><input type="text" class="form-control col-md-10" name="phone" placeholder="<?=trans('phone')?>" id="nodePhone">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="occupation" placeholder="<?=trans('occupation')?>">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control facebook"  name="facebook" placeholder="<?=trans('enter_facebook')?>">
                        <span class="fb_error d-none"><?=trans('invalid_facebook')?> .</span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control twitter" name="twitter" placeholder="<?=trans('enter_twitter')?>">
                        <span class="twitter_error d-none"><?=trans('invalid_twitter')?>.</span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control instagram" name="instagram" placeholder="<?=trans('enter_instagram')?>">
                        <span class="instagram_error d-none"><?=trans('invalid_instagram')?> .</span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control snapchat" name="snapchat" placeholder="<?=trans('enter_snapchat')?>">
                        <span class="snapchat_error d-none"><?=trans('invalid_snapchat')?> .</span>
                    </div>
                    <div class="form-group">
                        <span style="text-align: left;"><?=trans('profile_picture')?> </span>
                        <input type="file" class="form-control" name="photo">
                    </div>

                    <div class="form-group">
                        <span style="text-align: left;"><?=trans('preferred_club')?></span>
                        <input type="file" class="form-control" name="logo">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="club_name" placeholder="<?=trans('clubName')?>">
                    </div>
                    <div class="form-group" id="interests">
                        <textarea class="form-control" name="interests" placeholder="<?=trans('hobbies_interests')?>"></textarea>
                    </div>
                    <div class="form-group" id="about">
                        <textarea class="form-control" name="about" placeholder="<?=trans('about_member')?>"></textarea>
                    </div>
					<?php if(! isset($_SESSION['user_id']) && isset($_GET['type']) && isset($_GET['flag'])){ ?>
                        <div class="form-group h4">
                            <input type="checkbox" required> <span><?=trans('acceptTerms')?> <a href="terms.php?lang=<?php echo $lang; ?>" style="color: blue !important;" target="_blank"><?=trans('terms_conditions')?></a></span>
                        </div>
					<?php } ?>

                </div>
                <div class="modal-footer justify-content-center" style="margin: auto;">
                    <button type="submit" class="btn hbtn btn-hred" name="addRelatedMemberSubmit" id=
                    "addRelatedMemberSubmit"><?=trans('submit')?></button>
                    <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="close555"><?=trans('close')?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modal6" >
    <div class="modal-dialog " role="document" >
        <!-- Modal content -->
        <div class="modal-content" >
            <div class="modal-header">
                <h4 class="modal-title ml-auto mt-3"><?php
					if (isset($successMessage)){
						echo $successMessage;
					}
					?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=trans('close')?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

				<?php
				if (!empty($errors)) {

					echo "<ul style='list-style: none;'>";

					foreach ($errors as $error) {

						echo "<li>{$error}</li>";
					}


					echo "</ul>";
				} else {
					?>

                    <p><?php if (isset($registeredMessage)) {echo $registeredMessage;} ?></p>

				<?php } ?>
            </div>
            <div class="modal-footer justify-content-center">
                <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
				<?php if (isset($tryAgain)) { ?>
                    <button class="btn btn-primary" style="letter-spacing: 0;margin: 0 auto;" onclick="window.open('<?php echo $tryAgain; ?>', '_self');"><?=trans('tryAgain')?></button>
				<?php } else { ?>
                    <button id="close6" class="btn hbtn btn-hmuted" style="letter-spacing: 0;margin: 0 auto;"><?=trans('close')?></button>
				<?php } ?>

            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="modal7" tabindex="-1" role="dialog" style="z-index: 1070 !important;">
    <div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content" >
            <div class="modal-body p-0">
                <div class="container-fluid p-0">
                    <div class="profile-head" id="coverImage"
                        style="background-image: url('images/map-min.webp'); background-image: -webkit-image-set(url('images/map-min.webp')); background-image: image-set(url('images/map-min.png')); background-color: #96948d; background-position: center; background-size: cover !important; background-repeat: no-repeat; width: 100% !important;">
                        <picture class="profile-country-img">
                            <source srcset="<?=asset('images/logo-ar.webp')?>" type="image/webp">
                            <source srcset="<?=asset('images/logo-ar.png')?>" type="image/png">
                            <img src="<?=asset('images/logo-ar.png')?>" class="pt-5 pb-5">
                        </picture>
                        <button type="button" class="close custom-modal-close" data-dismiss="modal" aria-label="<?=trans('close')?>">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="row profile-info-row">
                        <div class="col-12 profile-image position-relative text-center">
                            <div class="d-inline-block  position-relative profile-image-container">
                                <img src="" width="200" alt="" id="profileImage" class="w-100 h-100">
                                <img class="modal-profile-country" src="" alt="" title="">
                            </div>
                        </div>
                        <div class="col-12 caption position-relative text-dark text-center">
                            <h4 class="text-center  user-name"></h4>
                            <div class="caption-social text-center">
                                <a href="#" class="fa fa-facebook user-facebook" title="FaceBook" target="_blank"></a>
                                <a href="#" class="fa fa-twitter user-twitter" title="twitter" target="_blank"></a>
                                <a href="#" class="fa fa-instagram user-instagram" title="Instagram" target="_blank"></a>
                                <a href="#" class="fa fa-snapchat-ghost user-snapchat" title="Snapchat" target="_blank"></a>
                                <a href="#" data-lightbox="user-club" class="user-club-anchor" style="display: none;">
                                    <img class="user-club" title="<?=trans('preferred_club')?>"></a>
                            </div>
                        </div>
                    </div>
                </div>
                <table id="userInfo" style="margin: 3% auto 0 auto; width: 90%;" class="table table-light border-0 border-separate">
                    <tr class="border-0">
                        <th class="border-0 pr-4 user-left-col"><?=trans('id')?>: </th>
                        <td id="userId" class="user-right-col"></td>
                    </tr>
                    <tr class="spacer border-0"></tr>
                    <tr>
                        <th class="border-0 pr-4 user-left-col"><?=trans('name')?>: </th>
                        <td class="user-name user-right-col"  id="userName"></td>
                    </tr>
                    <tr class="spacer border-0"></tr>
                    <tr id="kunyaTab">
                        <th class="border-0 pr-4 user-left-col"><?=trans('kunya')?>: </th>
                        <td class="user-right-col" id="userKunya"></td>
                    </tr>
                    <tr class="spacer border-0"></tr>
                    <tr id="bioTab">
                        <th class="border-0 pr-4 user-left-col"><?=trans('about_member')?>: </th>
                        <td class="user-right-col" id="UserBio"></td>
                    </tr>

                    <tr class="spacer border-0"></tr>
                    <tr id="DOBTab">
                        <th class="border-0 pr-4 user-left-col"><?=trans('DOB')?>: </th>
                        <td class="user-right-col" id="DOB"></td>
                    </tr>
                    <tr class="spacer border-0"></tr>
                    <tr id="GenderTab">
                        <th class="border-0 pr-4 user-left-col"><?=trans('gender')?>: </th>
                        <td class="user-right-col" id="memberGender"></td>
                    </tr>
                    <tr class="spacer border-0"></tr>
                    <tr>
                        <th class="border-0 pr-4 user-left-col"><?=trans('role')?>: </th>
                        <td class="user-right-col" id="member_role"></td>
                    </tr>
                    <tr class="spacer border-0"></tr>
                    <tr>
                        <th class="border-0 pr-4 user-left-col"><?=trans('the_family')?>: </th>
                        <td class="user-right-col" id="userFamily"></td>
                    </tr>
                    <tr class="spacer border-0"></tr>

                    <tr id="emailTab">
                        <th class="border-0 pr-4 user-left-col"><?=trans('email')?>: </th>
                        <td class="user-right-col" id="userEmail"></td>
                    </tr>
                    <tr class="spacer border-0"></tr>
                    <tr id="phoneTab">
                        <th class="border-0 pr-4 user-left-col"><?=trans('phone')?>: </th>
                        <td class="user-right-col" id="userPhone"></td>
                    </tr>
                    <!--                    <tr class="spacer border-0"></tr>-->
                    <!--                    <tr id="phoneTab" style="display: none;">-->
                    <!--                        <th class="border-0 pr-4 user-left-col">Phone: </th>-->
                    <!--                        <td class="user-right-col" id="userPhone"></td>-->
                    <!--                    </tr>-->
                    <tr class="spacer border-0"></tr>
                    <tr>
                        <th class="border-0 pr-4 user-left-col"><?=trans('country')?>: </th>
                        <td class="user-right-col" id="userCountry"></td>
                    </tr>
                    <tr class="spacer border-0"></tr>
                    <tr>
                        <th class="border-0 pr-4 user-left-col"><?=trans('chooseNationality')?>: </th>
                        <td class="user-right-col" id="userNationality"></td>
                    </tr>
                    <tr class="spacer border-0"></tr>
                    <tr id="jobTab">
                        <th class="border-0 pr-4 user-left-col"><?=trans('occupation')?>: </th>
                        <td class="user-right-col" id="userJob"></td>
                    </tr>
                    <tr class="spacer border-0"></tr>
                    <tr id="fatherTab">
                        <th class="border-0 pr-4 user-left-col"><?=trans('father')?>: </th>
                        <td class="user-right-col" id="userFather"></td>
                    </tr>
                    <tr class="spacer border-0"></tr>
                    <tr id="motherTab">
                        <th class="border-0 pr-4 user-left-col"><?=trans('mother')?>: </th>
                        <td class="user-right-col" id="userMother"></td>
                    </tr>
                    <tr class="spacer border-0"></tr>
                    <tr id="siblingsTab">
                        <th class="border-0 pr-4 user-left-col"><?=trans('siblings')?>: </th>
                        <td class="user-right-col" id="userSiblings"></td>
                    </tr>
                    <tr class="spacer border-0"></tr>
                    <tr id="wifesTab">
                        <th class="border-0 pr-4 user-left-col"><?=trans('the_wife')?>: </th>
                        <td class="user-right-col" id="userWifes"></td>
                    </tr>
                    <tr class="spacer border-0"></tr>
                    <tr id="childrenTab">
                        <th class="border-0 pr-4 user-left-col"><?=trans('children')?>: </th>
                        <td class="user-right-col" id="userChildren"></td>
                    </tr>
                    <tr class="spacer border-0"></tr>
                    <tr id="husbandTab">
                        <th class="border-0 pr-4 user-left-col"><?=trans('husband')?>: </th>
                        <td class="user-right-col" id="userHusband"></td>
                    </tr>
                    <tr class="spacer border-0"></tr>
                    <tr id="clubTab">
                        <th class="border-0 pr-4 user-left-col"><?=trans('club_name')?>: </th>
                        <td class="user-right-col" id="userClub"></td>
                    </tr>
                    <tr class="spacer border-0"></tr>
                    <tr id="interestsTab">
                        <th class="border-0 pr-4 user-left-col"><?=trans('just_interests')?>: </th>
                        <td class="user-right-col" id="userInterests"></td>
                    </tr>
                    <!--<tr id="logoTab" style="display: none;">-->
                    <!--    <th>Club Logo: </th>-->
                    <!--    <td><img src="" width="100" height="100" id="clubLogo" style="margin: auto;"></td>-->
                    <!--</tr>-->
                </table>
                <br>
                <div style="text-align: center !important; padding: 3px !important;">

                    <a href="" class="fa fa-facebook" title="FaceBook" style="display: none;padding: 15px;
                       border-radius: 50%;width: 70px;text-align: center;text-decoration: none;margin: 5px 2px; background: #3B5998;color: white;" id="fbLink"></a>
                    <a href="" class="fa fa-twitter" id="twitterLink" title="Twitter" style="display: none;padding: 15px;
                       border-radius: 50%;width: 70px;text-align: center;text-decoration: none;margin: 5px 2px; background: #55ACEE;color: white;"></a>
                    <a href="" id="logoTab" style="display: none;"><img src="" title="preferredClubLogo" style="border-radius: 50%;" width="70" id="clubLogo"></a>
                    <a href="" class="fa fa-instagram" title="Instagram" id="instagramLink" style="display: none;padding: 15px;
                       border-radius: 50%;width: 70px;text-align: center;text-decoration: none;margin: 5px 2px; background: #125688;color: white;"></a>
                    <a href="" class="fa fa-snapchat-ghost" title="Snapchat" id="snapchatLink" style="display: none;padding: 15px;
                       border-radius: 50%;width: 70px;text-align: center;text-decoration: none;margin: 5px 2px; background: #fffc00;color: white;"></a>
                </div>

            </div>
            <div class="modal-footer justify-content-center" style="margin: auto;">
                <div class="text-center">
					<?php if (isset($_SESSION['user_id']) && $logged_in_user) {

						if(checkAdmin($_SESSION['user_id'], $_SESSION['family_id'])){
							?>
                            <button id="toggleMember" class="btn btn-danger mt-1" style="letter-spacing: 0;"><?=trans('hide')?></button>
						<?php } ?>
                        <button id="EditUser" class="btn btn-basic mt-1" style="letter-spacing: 0;"><?=trans('edit')?></button>
                        <button id="addMember" class="btn btn-primary mt-1" style="letter-spacing: 0;"><?=trans('add_related')?></button>

					<?php } ?>
                    <button id="close7" class="btn hbtn btn-hmuted mt-1" style="letter-spacing: 0;"><?=trans('close')?></button>

                </div>

            </div>

        </div>
    </div>

</div>
<div class="modal fade" id="modal8" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <!-- Modal content -->
        <div class="modal-content">

            <div class="modal-header" >
                <h5 class="modal-title ml-auto mt-3"><?=trans('members_assistants')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=trans('close')?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <button type="button" class="btn btn-light info active" data="all" family="<?php echo $_SESSION['family_id']; ?>">
						<?=trans('all')?>
                    </button>
                    <button type="button" class="btn btn-light info" data="assistant" family="<?php echo $_SESSION['family_id']; ?>">
						<?=trans('assistants')?>
                    </button>
                    <button type="button" class="btn btn-light info" data="member" family="<?php echo $_SESSION['family_id']; ?>">
						<?=trans('just_members')?>
                    </button>
                    <button type="button" class="btn btn-light info" data="both" family="<?php echo $_SESSION['family_id']; ?>">
						<?=trans('members_assistants')?>
                    </button>
                </div>
                <hr>
                <div>
                    <table style="width: 100%;" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th style="text-align: center !important;"><?=trans('just_member')?></th>
                            <th style="text-align: center !important;"><?=trans('actions')?></th>
                        </tr>
                        </thead>
                        <tbody id="usersList">
						<?php

						$users = Family::find($familyId)->users()->where(function($query) {
							$query->where('users.role', '!=', 'creator')->orWhere('users.role', '!=', 'admin');
						});
						$users = $users->orderBy('name', 'asc')->get();

						$x = 1;
						foreach($users as $user){
							?>

                            <tr>
                                <td>
                                    <a href="<?php echo $user['user_id']; ?>" class="cell" style='
									<?php
									$toggleDisplay = '';
									if($user['display'] == '1'){
										echo "color: brown !important;";
									} else if($user['display'] == '0'){
										echo "color: #202020 !important;";
									} else if($user['display'] == '2'){
										echo "color: gray !important";
										$toggleDisplay = "display: none !important;";
									}
									?>
                                            '>
										<?php echo $x. "- " .$user['name']; ?>
                                    </a>
                                </td>
                                <td style="text-align: center !important;">
									<?php if($user['member'] == '1'){ ?>
                                        <a style="font-size: 100%; color: black !important; margin: auto 1vw !important; cursor: pointer;" data-title="<?=trans('view_tree')?>" user="<?php echo $user['user_id']; ?>" class="viewTree"><i class="fa fa-eye"></i></a>
										<?php if($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'creator'){ ?>
                                            <a style="font-size: 100%; color: black !important; cursor: pointer; margin: auto 1vw !important;" data-title="<?=trans('delete_tree')?>" user="<?php echo $user['user_id']; ?>" class="deleteTree" parent="<?php echo $user['parent_id']; ?>"><i class="fa fa-trash"></i></a>
										<?php } if($user['display'] == '1'){ ?>
                                            <a style="font-size: 100%; color: black !important; cursor: pointer; margin: auto 1vw !important;" data-title="<?=trans('hide_tree')?>" user="<?php echo $user['user_id']; ?>" id="toggleOff"><i class="fa fa-toggle-on"></i></a>
										<?php } else { ?>
                                            <a style="font-size: 100%; color: black !important; cursor: pointer; margin: auto 1vw !important; <?php echo $toggleDisplay; ?>" data-title="<?=trans('show_tree')?>" user="<?php echo $user['user_id']; ?>" id="toggleOn"><i class="fa fa-toggle-off"></i></a>
										<?php } ?>
									<?php } ?>
                                </td>

                            </tr>

							<?php
							$x++;
						} ?>
                        </tbody>
                    </table>
                </div>
                <hr>
                <div class="row">
                    <div class="col-12 col-md-4 text-center">
                        <div class='colorBoxes brown'></div>
						<?=trans('visible_members')?>
                    </div>
                    <div class="col-12 col-md-4 text-center">
                        <div class='colorBoxes black'></div>
						<?=trans('hidden_tree_head')?></div>
                    <div class="col-12 col-md-4 text-center">
                        <div class='colorBoxes gray'></div>
						<?=trans('hidden_tree_member')?>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center">

                <button id="close8" class="btn hbtn btn-hmuted" style="letter-spacing: 0; margin: auto;"><?=trans('close')?></button>

            </div>

        </div>
    </div>

</div>
<div class="modal fade" id="modal88" tabindex="-1" role="dialog" style="z-index: 1060 !important;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content w-100"  id="treeContent">
            <div class="modal-header">
				<?php if ($lang == 'ar') { ?>
                    <p id="firstMember"><?=trans('tree')?><span style="font-size: 100% !important; color: brown !important;"></span></p>
				<?php } else { ?>
                    <p id="firstMember"><span style="font-size: 100% !important; color: brown !important;"></span> <?=trans('tree')?></p>
				<?php }?>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=trans('close')?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="familyTree d-block position-relative" id="treeBody" style="overflow: auto;width: 100% !important;height: 70vh;">
                    <ul style="width: max-content;margin: auto;">
                    </ul>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button id="close88" class="btn hbtn btn-hmuted" style="letter-spacing: 0;"><?=trans('close')?></button>
            </div>

        </div>
    </div>

</div>
<div class="modal fade" id="top-up-media" tabindex="-1" role="dialog" style="z-index: 1060">
    <div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ml-auto mt-3"><?=trans('top_up_media')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=trans('close')?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mt-3">
                    <div class="c-shadowtext-pop d-none d-md-block">   <?=trans('top_up')?>  </div>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-6 offset-lg-3 col-md-10 offset-md-1">
                                <div class="plans">
                                    <div class="plan col-md-12">
                                        <div class="head_popup">
                                            <h3 class="plan-title_1"><?=trans('top_up_media')?></h3></div>
                                        <div class=" main_npl">
                                            <p class="plan-price-pop"> 175<?=trans('usd')?> <span class="plan-unit-pop"><?=trans('valid_until')?></span></p>
                                            <ul class="plan-features">
                                                <li class=" plan-feature pop-fea">2.5 <?=trans('gb')?><span class="plan-feature-name">&nbsp;  <?=trans('media_storage')?></span></li>
                                            </ul>
                                            <a href="topup_media.php" class="plan-button_up" style=""><?=trans('confirm')?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 alignself-center col-md-12">
                                <div class="w-richtext">
                                    <p style="font-size: 16px; color: #556575;"> </p>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12 order-first md-text-align-center"> </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button class="btn hbtn btn-hmuted" data-dismiss="modal" aria-label="<?=trans('close')?>" style="letter-spacing: 0; margin: auto;"><?=trans('close')?></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="top-up-nodes" tabindex="-1" role="dialog" style="z-index: 1060">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ml-auto mt-3"><?=trans('top_up_nodes')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=trans('close')?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mt-3">
                    <div class="c-shadowtext-pop d-none d-md-block">   <?=trans('top_up')?> </div>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-6 offset-lg-3 col-md-10 offset-md-1">
                                <div class="plans">
                                    <div class="plan col-md-12">
                                        <div class="head_popup">
                                            <h3 class="plan-title_1"><?=trans('top_up_nodes')?></h3></div>
                                        <div class=" main_npl">
                                            <p class="plan-price-pop"> 175<?=trans('usd')?> <span class="plan-unit-pop"><?=trans('valid_until')?></span></p>
                                            <ul class="plan-features">
                                                <li class=" plan-feature pop-fea">50<span class="plan-feature-name">&nbsp;  <?=trans('nodes')?></span></li>
                                            </ul>
                                            <a href="topup_node.php" class="plan-button_up" style=""><?=trans('confirm')?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 alignself-center col-md-12">
                                <div class="w-richtext">
                                    <p style="font-size: 16px; color: #556575;"> </p>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12 order-first md-text-align-center"> </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button class="btn hbtn btn-hmuted" data-dismiss="modal" aria-label="<?=trans('close')?>" style="letter-spacing: 0; margin: auto;"><?=trans('close')?></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="plan-details" tabindex="-1" role="dialog">
	<?php
	include_once(__DIR__."/lib/Plan.php");
	$family_plan = new Plan($familyId);
	?>
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ml-auto mt-3"><?=trans('plan_details')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=trans('close')?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-hover table-bordered">
                    <tbody>
                    <tr>
                        <th><?=trans('my_plan')?></th>
                        <td>
							<?=$family_plan->plan_name()?> ( <?=$family_plan->remaining_days()?> <?=trans('days_remaining')?>)<br>
							<?php
							$next_plans = $family_plan->upgradeable();
							if($next_plans) {?>
                                <a href="upgrade_plan.php?lang=<?=$lang?>" class="btn btn-link"><?=trans('upgrade_plan')?></a>
							<?php }
							if ($family_plan->renewable()){
								?>
                                <a href="renew_plan.php?lang=<?=$lang?>" class="btn btn-link"><?=trans('renew_plan')?></a>
							<?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <th><?=trans('media_left')?></th>
                        <td><?=number_format((float) ($family_plan->availableMedia() / 1000000), 3, '.', '')?><?=trans('gb')?>
                            <a href="#" data-toggle="modal" data-target="#top-up-media" class="btn btn-link"><?=trans('top_up')?></a>
                            <div class="progress custom-progress">
                                <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: <?=$family_plan->usedMediaPercentage()?>%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><?=$family_plan->usedMediaPercentage()?>%</div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th><?=trans('nodes_left')?></th>
                        <td><?=$family_plan->availableMembers(); ?>
                            <a href="#" data-toggle="modal" data-target="#top-up-nodes" class="btn btn-link"><?=trans('top_up')?></a>
                            <div class="progress custom-progress">
                                <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: <?=$family_plan->usedMembersPercentage()?>%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><?=$family_plan->usedMembersPercentage()?>%</div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th><?=trans('most_popular')?></th>
                        <td>
							<?php
							$plan = $family_plan->plan();
							$mostpopular = $family_plan->getFamily()['mostpopular'];
							if(!$plan['popular']) {?>
                                <p><?=trans('require_premium')?>
									<?php if($next_plans) {?>
                                        <a href="upgrade_plan.php?lang=<?=$lang?>" class="btn btn-link"><?=trans('upgrade_plan')?></a>
									<?php }?></p>
								<?php
							}else{
								if ($mostpopular == 1){?>
                                    <p><?=trans('subscribed')?></p>
								<?php } else { ?>
                                    <a href='mostpopular.php' class='btn btn-link'><?=trans('subscribe')?></a>
								<?php }} ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer justify-content-center">
                <button class="btn hbtn btn-hmuted" data-dismiss="modal" aria-label="<?=trans('close')?>" style="letter-spacing: 0; margin: auto;"><?=trans('close')?></button>
            </div>
        </div>
    </div>
</div>

<!-- Page Start -->
<div class="section is-hero has-gradient position-relative overflow-hidden is-subpage" style="padding-bottom: 3vh;">
    <div class="container position-relative">
        <div class="block-centered text-align-center lg-7 md-12">
            <h2 style="color:#fff;" data-title="<?= trans('click_to_pronunciation') ?>">
				<?php
				if (isset($_GET['family'])) {
					$familyId = $_GET['family'];
				} elseif (isset($_GET['flag'])) {
					$familyId = $_GET['flag'];
				} elseif (isset($_GET['f']) && $_GET['f']) {
					$familyId = $_GET['f'];
				} else {
					$familyId = $_SESSION['family_id'];
				}
				$row = Family::find($familyId);
				echo "    <img src='".asset('images/audio-recording.png')."' width='80' height='80' id='speaker' family='{$familyId}'>";
				?>
<!--                 <audio controls id="family_pronunciation" class="hidden" src="<?= $row->pronunciation ?>" > -->
		    <audio controls="controls" controls id="family_pronunciation" class="hidden">
			    <source src="<?= $row->pronunciation ?>" type="audio/mpeg" />
		    </audio>
            </h2>
            <p class="padding-left padding-right margin-bottom is-heading-color" style="color:#fff;text-align:center; font-size: 2vw !important; margin-top: -3% !important; margin-bottom: 4px !important;">
				<?php echo $lang != 'ar' ? ucfirst($row['name_' . $lang]) . " " . $languages[$lang]['family'] : $languages[$lang]['family'] . " ". ucfirst(db_trans($row, 'name')); ?>
            </p>
            <span style="color: #fff; font-size: 1.5em;"><?php echo $row->users()->member()->count(); ?> <i class="fa fa-user"></i></span>
            <div class="countriesImages">
				<?php
				$countries = Country::whereIn('id', $row->users()->pluck('country_id'))->get();
				foreach($countries as $country){
					?>
                    <img src="<?=asset($country['image'])?>" alt="<?=db_trans($country, 'name')?>" title="<?=db_trans($country, 'name')?>" loading="lazy">
					<?php
				} ?>
            </div>
        </div>
    </div>
</div>
<br><br>
<section class="main-section">
	<?php
	if (isset($_GET['family'])) {
		$familyId = $_GET['family'];
	} elseif (isset($_GET['flag'])) {
		$familyId = $_GET['flag'];
	} elseif (isset($_GET['f']) && $_GET['f']) {
		$familyId = $_GET['f'];
	} else {
		$familyId = $_SESSION['family_id'];
	}

	if (isset($_SESSION['user_id']) && $familyId == $_SESSION['family_id']) {
		$userId = $_SESSION['user_id'];
		$users = Family::find($familyId)->users()->where('user_id', '=', $userId);
	}
	else {
		$users = Family::find($familyId)->users()->where(function($query) {
			$query->where('users.role', '=', 'creator')->orWhere('users.role', '=', 'admin');
		});
	}
	$rowUser = $users->first();
	if(getimagesize($rowUser['image']) == 0){
		$rowUser['image'] = $siteUrl.$RELATIVE_PATH . "uploads/users/default.jpg";
		// $rowUser['image'] = asset('1618123730.jpg');
	}
	?>
    <div class="container-fluid p-0" >
        <div class="profile-header pt-5 pb-5"
             style="background-image: url('<?=asset("images/map-min.webp")?>'), url('<?=asset("images/foot-print.webp")?>'); background-image: -webkit-image-set(url('<?=asset("images/map-min.webp")?>')), -webkit-image-set(url('<?=asset("images/foot-print.webp")?>')); background-image: image-set(url('<?=asset("images/map-min.png")?>')), image-set(url('<?=asset("images/foot-print.png")?>')); background-position: center, center 200%; background-size: 100%, auto !important; background-repeat: no-repeat; width: 100% !important;">
             <picture class="profile-country-img" style="margin: auto; width: 20%;">
                <source srcset="images/logo-ar.webp" type="image/webp">
                <source srcset="images/logo-ar.png" type="image/png">
                <img src="images/logo-ar.png" class="pt-5 pb-5" alt="profile-cover">
            </picture>
			<?php
			$rowCountry = Country::find($rowUser['country_id']);
			?>
        </div>
    </div>
    <div class="container-fluid p-0" >
        <div class="main-bd">
            <div class="container-fluid">
                <div class="row m-0">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 side-menu mr-auto ml-auto">
                        <div class="h-100 pl-1 pr-1 pl-lg-0 pr-lg-0 ml-lg-4 mr-xl-5 mr-lg-0 pl-md-5 pr-md-5 ml-md-5 mr-md-5 pl-sm-5 pr-sm-5">
                            <div class="profile-img pl-xl-4 pr-xl-4">
                                <h4 class="mt-3"><?=db_trans($rowCountry, 'name') ?></h4>
                                <div class="position-relative profile-img-container mt-3 mb-3">
                                    <div class="position-relative profile-images-container">
                                        <img src="<?=$rowUser['image']? asset($rowUser['image']):asset('images/default-user.png')?>" alt="<?php echo $rowUser['name']; ?>" id="profile-image" height="auto">
                                        <img src="<?=asset($rowCountry['image'])?>" alt="<?=db_trans($country, 'name'); ?>" title="<?=db_trans($country, 'name')?>" id="profile-country">
                                    </div>
                                </div>
                                <div id="profile-info">
                                    <h6><?= ($rowUser['role'] == 'creator' || $rowUser['role'] == 'admin')? trans('family_creator'): trans('family_assistant') ?></h6>
                                    <h5><?php echo $rowUser['name']; ?></h5>
                                    <hr>
                                    <div style="direction:ltr !important">
                                        <p class="mobile-no m-0" ><i class="fa fa-mobile"></i> &nbsp;<?php echo $rowUser['phone']; ?></p>
                                        <p class="user-mail m-0" ><i class="fa fa-envelope" style="color: red;"></i> &nbsp;<?php echo $rowUser['email']; ?></p>
                                        <hr>
                                    </div>
                                    <div class="socialDiv d-flex align-items-center justify-content-center">
										<?php if(! empty($rowUser['facebook'])){ ?>
                                            <a href="<?php echo $rowUser['facebook']; ?>" class="fa fa-facebook social d-flex align-items-center justify-content-center" title="FaceBook" target="_blank"></a>
										<?php } if(! empty($rowUser['twitter'])){ ?>
                                            <a href="<?php echo $rowUser['twitter']; ?>" class="fa fa-twitter social d-flex align-items-center justify-content-center" title="twitter" target="_blank"></a>
										<?php } if(! empty($rowUser['instagram'])){ ?>
                                            <a href="<?php echo $rowUser['instagram']; ?>" class="fa fa-instagram social d-flex align-items-center justify-content-center" title="Instagram" target="_blank"></a>
										<?php } if(! empty($rowUser['snapchat'])){ ?>
                                            <a href="<?php echo $rowUser['snapchat']; ?>" class="fa fa-snapchat-ghost social d-flex align-items-center justify-content-center" title="Snapchat" target="_blank"></a>
										<?php } ?>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="" style="text-align: left !important;">
								<?php if(isset($_SESSION['user_id'])){ ?>
                                    <div class="user-bio w-100">
										<?php
										$logged_in = User::where(['user_id'=>$_SESSION['user_id']])->where(['family_id'=>  $familyId])->where('role', '!=', 'user')->first();
										if (isset($_SESSION['user_id']) && $logged_in) { ?>
                                            <a id="startRecord" class="d-block w-100 pt-4 pb-3 pl-4 text-left"
                                               style="color: black !important; border: 2px solid #f2c8c8 !important; border-radius: 15px;position: relative; z-index: 1; cursor: pointer;">
                                                <span class="ontop-change"><?=trans('family_pron')?></span>
                                                <i class="fa fa-microphone mr-2" aria-hidden="true" style="color: red;" id="microphone"></i>
												<?=trans('record_pron')?></a>
                                            <br>

                                            <a id="editBtn" class="d-block w-100 pt-4 pb-3 pl-4 text-left"
                                               family="<?php echo $_SESSION['family_id']; ?>" style="color: black !important; border: 2px solid #f2c8c8 !important; border-radius: 15px;position: relative; z-index: 1;cursor: pointer;">
                                                <span class="ontop-change"><?=trans('familyDetails')?></span>
                                                <i class="fa fa-edit mr-2" aria-hidden="true" style="color: red;"></i>
												<?=trans('edit_family_details')?></a>

                                            <br>
										<?php } ?>
                                    </div>
									<?php if (isset($_SESSION['user_id']) && $logged_in) { ?>

                                        <div id="familyActions" class="p-2" style="border: 2px solid #f2c8c8 !important; border-radius: 15px;">
                                            <a id="inviteUser" class="text-left">
                                                <span class="profile-icon-container m-1 text-center">
                                                    <picture>
                                                        <source srcset="<?=asset('img/icons/add-hover.webp')?>" type="image/webp">
                                                        <source srcset="<?=asset('img/icons/add-hover.png')?>" type="image/png">
                                                        <img src="<?=asset('img/icons/add-hover.png')?>" class="profile-icon-img">
                                                    </picture>
                                                    <picture>
                                                        <source srcset="<?=asset('img/icons/add.webp')?>" type="image/webp">
                                                        <source srcset="<?=asset('img/icons/add.png')?>" type="image/png">
                                                        <img src="<?=asset('img/icons/add.png')?>" class="profile-icon-img for-hover">
                                                    </picture>
                                                </span>
                                                <span ><?=trans('invite_member')?></span></a>
                                            <a id="addNode" class="text-left" family="<?php echo $_SESSION['family_id']; ?>">
                                                <span class="profile-icon-container m-1 text-center">
                                                    <picture>
                                                        <source srcset="<?=asset('img/icons/family-hover.webp')?>" type="image/webp">
                                                        <source srcset="<?=asset('img/icons/family-hover.png')?>" type="image/png">
                                                        <img src="<?=asset('img/icons/family-hover.png')?>" class="profile-icon-img">
                                                    </picture>
                                                    <picture>
                                                        <source srcset="<?=asset('img/icons/family.webp')?>" type="image/webp">
                                                        <source srcset="<?=asset('img/icons/family.png')?>" type="image/png">
                                                        <img src="<?=asset('img/icons/family.png')?>" class="profile-icon-img for-hover">
                                                    </picture>
                                                </span>
                                                <span ><?=trans('add_member')?></span></a>
                                            <a id="addFile" class="text-left">
                                                <span class="profile-icon-container m-1 text-center">
                                                    <picture>
                                                        <source srcset="<?=asset('img/icons/media-hover.webp')?>" type="image/webp">
                                                        <source srcset="<?=asset('img/icons/media-hover.png')?>" type="image/png">
                                                        <img src="<?=asset('img/icons/media-hover.png')?>" class="profile-icon-img">
                                                    </picture>
                                                    <picture>
                                                        <source srcset="<?=asset('img/icons/media.webp')?>" type="image/webp">
                                                        <source srcset="<?=asset('img/icons/media.png')?>" type="image/png">
                                                        <img src="<?=asset('img/icons/media.png')?>" class="profile-icon-img for-hover">
                                                    </picture>
                                                </span>
                                                <span><?=trans('add_media')?></span></a>
                                            <a id="familyMembers" class="text-left" family="<?php echo $_SESSION['family_id']; ?>">
                                                <span class="profile-icon-container m-1 text-center">
                                                    <picture>
                                                        <source srcset="<?=asset('img/icons/info-hover.webp')?>" type="image/webp">
                                                        <source srcset="<?=asset('img/icons/info-hover.png')?>" type="image/png">
                                                        <img src="<?=asset('img/icons/info-hover.png')?>" class="profile-icon-img">
                                                    </picture>
                                                    <picture>
                                                        <source srcset="<?=asset('img/icons/info.webp')?>" type="image/webp">
                                                        <source srcset="<?=asset('img/icons/info.png')?>" type="image/png">
                                                        <img src="<?=asset('img/icons/info.png')?>" class="profile-icon-img for-hover">
                                                    </picture>
                                                </span>
                                                <span><?=trans('members_assistants')?></span></a>
                                        </div>
									<?php } ?>
									<?php if (! $logged_in) {?>
                                        <div id="familyDesc">
                                            <p><?=trans('familyDesc')?></p>
											<?php

											echo db_trans($row, 'desc');

											?>
                                        </div>
									<?php } ?>
                                    <div id="familyMedia" class="p-2" style="border: 2px solid #f2c8c8 !important; border-radius: 15px;">
										<?php if (isset($_SESSION['user_id']) && $logged_in) {?>

                                            <a href="#" class="text-left" data-target="#plan-details" data-toggle="modal">
                                                <span class="profile-icon-container m-1 text-center">
                                                    <picture>
                                                        <source srcset="<?=asset('img/icons/plan-hover.webp')?>" type="image/webp">
                                                        <source srcset="<?=asset('img/icons/plan-hover.png')?>" type="image/png">
                                                        <img src="<?=asset('img/icons/plan-hover.png')?>" class="profile-icon-img">
                                                    </picture>
                                                    <picture>
                                                        <source srcset="<?=asset('img/icons/plan.webp')?>" type="image/webp">
                                                        <source srcset="<?=asset('img/icons/plan.png')?>" type="image/png">
                                                        <img src="<?=asset('img/icons/plan.png')?>" class="profile-icon-img for-hover">
                                                    </picture>
                                                </span>
                                                <span><?=trans('plan_details')?></span>
                                            </a>
                                            <a href="#" data-target="#top-up-media" class="text-left"
                                               data-toggle="modal">
                                                <span class="profile-icon-container m-1 text-center">
                                                    <picture>
                                                        <source srcset="<?=asset('img/icons/topup-hover.webp')?>" type="image/webp">
                                                        <source srcset="<?=asset('img/icons/topup-hover.png')?>" type="image/png">
                                                        <img src="<?=asset('img/icons/topup-hover.png')?>" class="profile-icon-img">
                                                    </picture>
                                                    <picture>
                                                        <source srcset="<?=asset('img/icons/topup.webp')?>" type="image/webp">
                                                        <source srcset="<?=asset('img/icons/topup.png')?>" type="image/png">
                                                        <img src="<?=asset('img/icons/topup.png')?>" class="profile-icon-img for-hover">
                                                    </picture>
                                                </span>
                                                <span ><?=trans('top_up_media')?></span></a>
                                            <a  href="#" data-target="#top-up-nodes" class="text-left"
                                                data-toggle="modal">
                                                <span class="profile-icon-container m-1 text-center">
                                                    <picture>
                                                        <source srcset="<?=asset('img/icons/nodes-hover.webp')?>" type="image/webp">
                                                        <source srcset="<?=asset('img/icons/nodes-hover.png')?>" type="image/png">
                                                        <img src="<?=asset('img/icons/nodes-hover.png')?>" class="profile-icon-img">
                                                    </picture>
                                                    <picture>
                                                        <source srcset="<?=asset('img/icons/nodes.webp')?>" type="image/webp">
                                                        <source srcset="<?=asset('img/icons/nodes.png')?>" type="image/png">
                                                        <img src="<?=asset('img/icons/nodes.png')?>" class="profile-icon-img for-hover">
                                                    </picture>
                                                </span>

                                                <span ><?=trans('top_up_nodes')?></span></a>

										<?php } else { ?>
                                            <div id="familyActions" style="box-shadow: 0px 0px 0px white;">
                                                <a id="join_family" class="mt-1 text-left"><i class="fa fa-sign-in"></i> <span ><?=trans('request_join')?></span></a>
                                            </div>
										<?php } ?>
                                    </div>
								<?php } else { ?>
                                    <div id="familyDesc">
                                        <p><?=trans('familyDesc')?></p>
										<?php

										echo db_trans($row, 'desc');

										?>
                                    </div>
                                    <div id="familyMedia" class="p-2" style="border: 2px solid #f2c8c8 !important; border-radius: 15px;">
                                        <div id="familyActions" style="box-shadow: 0px 0px 0px white;">
                                            <a id="join_family" class="mt-1 text-left"><i class="fa fa-sign-in"></i> <span ><?=trans('request_join')?></span></a>
                                        </div>
                                    </div>
								<?php } ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 mr-auto ml-auto main-view p-0" id="vue-app">
                        <br>
                        <div class="nav" id="nav">
                            <h1 class="mt-2 w-100 text-center text-dark"><?=trans('family_gallery')?></h1>
                        </div>
						<?php
						if (isset($_GET['family'])) {
							$familyId = $_GET['family'];
						} elseif (isset($_GET['flag'])) {
							$familyId = $_GET['flag'];
						} elseif (isset($_GET['f']) && $_GET['f']) {
							$familyId = $_GET['f'];
						} else {
							$familyId = $_SESSION['family_id'];
						}
						$media = Family::find($familyId)->media()->gallery()->image()->get();
						$count = count($media);
						$family_status = checkFamilyStatus($familyId);
						if( (! isset($_GET['type']) && ! isset($_GET['flag']) && ! get_family_invitation($_GET['type'])
										&& $family_status == 2 && ! isset($_SESSION['user_id']) && ! isset($_GET['f']) )
								|| (isset($_SESSION['user_id']) && $_SESSION['family_id'] != $familyId && $family_status == 2 )
								|| (isset($_GET['f']) && $_GET['f'] != '' && isset($_GET['id']) && $family_status == 2
										&& strtotime(date('Y-m-d')) > strtotime(checkUserAccessAbility($_GET['id']))) ){?>
                            <div class="text-center pt-5">
                                <h5>
									<?= trans('gallery_hidden') ?>
                                    <a class="text-dark text-decoration request_view_gallery" type="gallery" href="<?= $familyId ?>" status="<?= $family_status ?>" >
										<?= trans('here') ?>
                                    </a>
                                </h5>
                            </div>
							<?php
						} else {
							?>
                            <div class="profile-body">
                                <div class="profile-posts text-center">
                                    <section class="gallery" id="gallery-section">
                                        <div class="mt-2 member-gallery-main">
                                            <div :id="'family-'+main_tab" v-if="active_service == main_tab" v-for="main_tab in Object.keys(this.media)">
                                                <div class="col-md-10 m-md-auto col-lg-11 col-xl-12 p-0" >
                                                    <div class="row">
                                                        <div class="col-12 col-lg-10 offset-lg-1 col-md-10 offset-md-1 p-xs-0">
                                                            <ul class="nav nav-pills nav-fill">
                                                                <li class="nav-item profile-nav-item p-0 m-0">
                                                                    <a class="nav-link profile-nav-link active m-0 p-xs-0" href="#" :id="main_tab+'-images-switch'"
                                                                       data-toggle="tab" v-bind:data-target="'#'+main_tab+'-images'" role="tab" v-bind:aria-controls="main_tab+'-images'" aria-selected="true"
                                                                       v-on:click="changeActiveTab('#'+main_tab+'-images', main_tab, 'Image', 1)">
																		<?=trans('images')?>
                                                                        <i class="fa fa-camera" aria-hidden="true"></i>
                                                                    </a>
                                                                </li>
                                                                <li class="nav-item profile-nav-item p-0 m-0">
                                                                    <a class="nav-link profile-nav-link m-0 p-xs-0" href="#" :id="main_tab+'-videos-switch'"
                                                                       data-toggle="tab" v-bind:data-target="'#'+main_tab+'-videos'" role="tab" v-bind:aria-controls="main_tab+'-videos'" aria-selected="false"
                                                                       v-on:click="changeActiveTab('#'+main_tab+'-videos', main_tab, 'Video', 1)">
																		<?=trans('videos')?>
                                                                        <i class="fa fa-video-camera" aria-hidden="true"></i>
                                                                    </a>
                                                                </li>
                                                                <li class="nav-item profile-nav-item p-0 m-0">
                                                                    <a class="nav-link profile-nav-link m-0 p-xs-0" href="#" :id="main_tab+'-audio-switch'"
                                                                       data-toggle="tab" v-bind:data-target="'#'+main_tab+'-audio'" role="tab" v-bind:aria-controls="main_tab+'-audio'" aria-selected="false"
                                                                       v-on:click="changeActiveTab('#'+main_tab+'-audio', main_tab, 'Audio', 1)">
																		<?=trans('audio')?>
                                                                        <i class="fa fa-volume-up" aria-hidden="true"></i>
                                                                    </a>
                                                                </li>
                                                                <li class="nav-item profile-nav-item p-0 m-0">
                                                                    <a class="nav-link profile-nav-link m-0 p-xs-0" href="#" :id="main_tab+'-pdf-switch'"
                                                                       data-toggle="tab" v-bind:data-target="'#'+main_tab+'-pdf'" role="tab" v-bind:aria-controls="main_tab+'-pdf'" aria-selected="false"
                                                                       v-on:click="changeActiveTab('#'+main_tab+'-pdf', main_tab, 'PDF', 1)">
																		<?=trans('documents')?>
                                                                        <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="tab-content" id="galleryTabContent">
                                                        <div class="tab-pane fade show active p-0" :id="main_tab+'-images'" role="tabpanel" v-bind:aria-labelledby="main_tab+'-images-tab'" >
                                                            <div v-if="waiting" class="featured stacked-cards mb-5" v-cloak>
                                                                <div class="slider">
                                                                    <div class="spinner-border" style="width: 80px;height:80px;" role="status">
                                                                        <span class="sr-only"><?= trans('loading') ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div v-else-if="media[main_tab].Image.data.length > 0" class="nak-gallery nlg1 container-fluid mt-3" v-cloak >
                                                                <div id="gallery" class="pl-0 row" v-cloak>
                                                                    <div v-bind:data-responsive="image.file+' 480, '+image.file+' 800'"
                                                                         v-bind:data-src="image.file" v-bind:data-sub-html="'<h4>'+image.name+'</h4><p>'+(image.description?image.description:'')+'</p>'"
                                                                         data-pinterest-text="Pin it" data-tweet-text="share on twitter" class="opne-img col-6 col-lg-4 col-xl-3 p-0"
                                                                         v-for="(image,index) in media[main_tab].Image.data" v-cloak >

                                                                        <img class="custom-img-responsive" :src="image.file" v-cloak>
                                                                        <div class="nak-gallery-poster custom-img-container light-gallery-opener" v-bind:data-value="index"
                                                                             :style="'background-image:url(\''+image.file+'\');background-size:cover;background-repeat:no-repeat;background-position:center center;display: block;width: 100%;height: 0;'" v-cloak>
                                                                            <div class="editing-gallery-container">
                                                                                <a class="btn btn-link share-btn" v-on:click="shareFile(image)" v-bind:data-value="image.id" role="button" :tabindex="-1"
                                                                                   data-toggle="popover" title="<?=trans('share_or_send')?>"
                                                                                   v-bind:data-content="sharingContent(image)" data-placement="top"
                                                                                ><i class="fa fa-share" aria-hidden="true"></i></a>
																				<?php if (isset($_SESSION['user_id']) && $logged_in) { ?>
                                                                                    <button class="btn btn-link edit-btn text-success" v-on:click="editFile(image)"><i class="fa fa-edit" aria-hidden="true"></i></button>
                                                                                    <button class="btn btn-link delete-btn text-danger" v-on:click="deleteFile(image.id, 'Image')"><i class="fa fa-times" aria-hidden="true"></i></button>
																				<?php } ?>
                                                                            </div>
                                                                        </div>

                                                                        <div class="video-caption text-left" v-cloak>
                                                                            <h1><span class="d-inline-block mr-1">[[String(index+1).padStart(2, '0')]]</span>[[image.name]]</h1>
                                                                            <p>[[ image.description ]]</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div v-else class="text-center pt-5" v-cloak>
																<?php
																$logged_in = User::where(['user_id'=>$_SESSION['user_id']])->where(['family_id'=>  $familyId])->where('role', '!=', 'user')->first();
																if (isset($_SESSION['user_id']) && $logged_in) {?>
                                                                    <p><?= trans('no_media_for_managers') ?><br><?= trans('use_media_button') ?></p>
																<?php } else {?>
                                                                    <p><?= trans('no_media_for_visitors')  ?><br><?= trans('check_later')  ?></p>
																<?php } ?>
                                                            </div>
                                                            <ul class="pagination justify-content-center" v-if="!waiting && media[main_tab].Image.pages > 1" v-cloak>
                                                                <li :class="'page-item' + (media[main_tab].Image.page > 1 ? '' : 'disabled')">
                                                                    <a class="page-link"
                                                                       v-on:click="media[main_tab].Image.page > 1 && loadPage(main_tab, 'Image', media[main_tab].Image.page-1, '#'+main_tab+'-images')">
                                                                        <i class="fa fa-caret-left paginate-icon" aria-hidden="true"></i>
                                                                    </a>
                                                                </li>
                                                                <li v-for="page in media[main_tab].Image.pages"
                                                                    v-if="page > media[main_tab].Image.page-3 && page < media[main_tab].Image.page+3"
                                                                    :class="'page-item '+(page==media[main_tab].Image.page?'active':'')">
                                                                    <a :class="'page-link navigate '+(page==media[main_tab].Image.page?'active':'')"
                                                                       v-on:click="loadPage(main_tab, 'Image', page, '#'+main_tab+'-images')">
                                                                        [[ page ]]
                                                                    </a>
                                                                </li>
                                                                <li :class="'page-item '+(media[main_tab].Image.page < media[main_tab].Image.pages ? '' : 'disabled')">
                                                                    <a class="page-link"
                                                                       v-on:click="media[main_tab].Image.page < media[main_tab].Image.pages  && loadPage(main_tab, 'Image', media[main_tab].Image.page+1, '#'+main_tab+'-images')">
                                                                        <i class="fa fa-caret-right paginate-icon" aria-hidden="true"></i>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="tab-pane fade p-0" :id="main_tab+'-videos'" role="tabpanel" v-bind:aria-labelledby="main_tab+'-videos-tab'">
                                                            <div v-if="waiting" class="featured stacked-cards mb-5" v-cloak>
                                                                <div class="slider">
                                                                    <div class="spinner-border" style="width: 80px;height:80px;" role="status">
                                                                        <span class="sr-only">Loading...</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="nak-video-gallery nlg1 overflow-auto container-fluid mt-3" v-else-if="media[main_tab].Video.data.length > 0">
                                                                <div class="video-overlay" v-if="video_waiting"
                                                                     style="position: absolute;width:100%;height:100%;top:0;left:0;z-index:5;background:rgba(20, 20, 20 ,0.5)">
                                                                    <div class="slider" style="margin-top:20px">
                                                                        <div class="spinner-border text-danger" style="width: 80px;height:80px;" role="status">
                                                                            <span class="sr-only">Loading...</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="slider row h-auto" id="video-gallery">
                                                                    <div class="video-img col-6 col-lg-4 col-xl-3 p-0" v-for="(video,index) in media[main_tab].Video.data"
                                                                         v-bind:data-sub-html="'<h4>'+video.name+'</h4><p>'+(video.description?video.description:'')+'</p>'" v-bind:data-html="'#video-'+video.id">
                                                                        <div style="display:none;" :id="'video-'+video.id">
                                                                            <video class="lg-video-object lg-html5 video-js vjs-default-skin" controls preload="metadata" data-setup='{ "inactivityTimeout": 0 }'>
                                                                                <source :src="video.file" type="video/mp4">
                                                                                Your browser does not support HTML5 video.
                                                                            </video>
                                                                        </div>
                                                                        <img v-bind:data-value="video.id" class="custom-img-responsive video-thumbnail" src="https://via.placeholder.com/100?text=?">
                                                                        <div style="overflow:hidden">
                                                                            <div class="nak-video-gallery-poster custom-video-container">
                                                                                <div class="editing-gallery-container">
                                                                                    <a class="btn btn-link share-btn" v-on:click="shareFile(video)" v-bind:data-value="video.id" role="button" :tabindex="-1"
                                                                                       data-trigger="focus" data-toggle="popover" title="<?=trans('share_or_send')?>"
                                                                                       v-bind:data-content="sharingContent(video)" data-placement="top"
                                                                                    ><i class="fa fa-share" aria-hidden="true"></i></a>
																					<?php if (isset($_SESSION['user_id']) && $logged_in) { ?>
                                                                                        <button class="btn btn-link edit-btn text-success" v-on:click="editFile(video)"><i class="fa fa-edit" aria-hidden="true"></i></button>
                                                                                        <button class="btn btn-link delete-btn text-danger" v-on:click="deleteFile(video.id, 'Video')"><i class="fa fa-times" aria-hidden="true"></i></button>
																					<?php } ?>
                                                                                </div>
                                                                                <div class="play-overlay">
                                                                                    <i class="fa fa-3x fa-play"></i>
                                                                                </div>
                                                                                <video preload="metadata" width="100%" height="100%" :id="'video-preview-'+video.id">
                                                                                    <source :src="video.file+'#t=0.5'" type="video/mp4" >
                                                                                </video>
                                                                            </div>
                                                                            <div class="video-caption text-left">
                                                                                <h1><span class="d-inline-block mr-1">[[String(index+1).padStart(2, '0')]]</span>[[video.name]]</h1>
                                                                                <p>[[video.description]]</p>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div v-else class="text-center pt-5">
																<?php if (isset($_SESSION['user_id']) && $logged_in) {?>
                                                                    <p><?= trans('no_media_for_managers')  ?><br><?= trans('use_media_button')  ?></p>
																<?php } else {?>
                                                                    <p><?= trans('no_media_for_visitors')  ?><br><?= trans('check_later')  ?></p>
																<?php } ?>
                                                            </div>
                                                            <ul class="pagination justify-content-center" v-if="media[main_tab].Video.pages > 1">
                                                                <li :class="'page-item '+(media[main_tab].Video.page > 1 ? '' : 'disabled')">
                                                                    <a class="page-link"
                                                                       v-on:click="media[main_tab].Video.page > 1 && loadPage(main_tab, 'Video', media[main_tab].Video.page-1, '#'+main_tab+'-videos')">
                                                                        <i class="fa fa-caret-left paginate-icon" aria-hidden="true"></i>
                                                                    </a>
                                                                </li>
                                                                <li v-for="page in media[main_tab].Video.pages"
                                                                    v-if="page > media[main_tab].Video.page-3 && page < media[main_tab].Video.page+3"
                                                                    :class="'page-item ' +(page==media[main_tab].Video.page?'active':'')">
                                                                    <a class="page-link" v-on:click="loadPage(main_tab, 'Video', page, '#'+main_tab+'-videos')">
                                                                        [[ page ]]
                                                                    </a>
                                                                </li>
                                                                <li :class="'page-item '+(media[main_tab].Video.page < media[main_tab].Video.pages ? '' : 'disabled')">
                                                                    <a class="page-link"
                                                                       v-on:click="media[main_tab].Video.page < media[main_tab].Video.pages  && loadPage(main_tab, 'Video', media[main_tab].Video.page+1, '#'+main_tab+'-videos')">
                                                                        <i class="fa fa-caret-right paginate-icon" aria-hidden="true"></i>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="tab-pane fade p-0" :id="main_tab+'-audio'" role="tabpanel" v-bind:aria-labelledby="main_tab+'-audio-tab'">
                                                            <div v-if="waiting" class="featured stacked-cards mb-5" v-cloak>
                                                                <div class="slider">
                                                                    <div class="spinner-border" style="width: 80px;height:80px;" role="status">
                                                                        <span class="sr-only">Loading...</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="container-fluid mt-3" v-else-if="media[main_tab].Audio.data.length > 0">
                                                                <div class="row audio-row" >
                                                                    <div class="col-6 col-xl-3 p-1" v-for="(audio, index) in media[main_tab].Audio.data">
                                                                        <div class="editing-gallery-container">
                                                                            <a class="btn btn-link share-btn" v-on:click="shareFile(audio)" v-bind:data-value="audio.id" role="button" :tabindex="-1"
                                                                               data-trigger="focus" data-toggle="popover" title="<?=trans('share_or_send')?>"
                                                                               v-bind:data-content="sharingContent(audio)" data-placement="top"
                                                                            ><i class="fa fa-share" aria-hidden="true"></i></a>
																			<?php if (isset($_SESSION['user_id']) && $logged_in) { ?>
                                                                                <button class="btn btn-link delete-btn text-danger" v-on:click="deleteFile(audio.id, 'Audio')"><i class="fa fa-times" aria-hidden="true"></i></button>
                                                                                <button class="btn btn-link edit-btn text-success" v-on:click="editFile(audio)"><i class="fa fa-edit" aria-hidden="true"></i></button>
																			<?php } ?>
                                                                        </div>
                                                                        <audio controls class="branding audio-card">
                                                                            <source :src="audio.file" type="audio/ogg">
                                                                            <source :src="audio.file" type="audio/mpeg">
                                                                            Your browser does not support the audio tag.
                                                                        </audio>
                                                                        <div class="audio-caption text-left">
                                                                            <h1><span class="d-inline-block mr-1">[[String(index+1).padStart(2, '0')]]</span>[[audio.name]]</h1>
                                                                            <p>[[audio.description]]</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div v-else class="text-center pt-5">
																<?php if (isset($_SESSION['user_id']) && $logged_in) {?>
                                                                    <p><?= trans('no_media_for_managers')  ?><br><?= trans('use_media_button')  ?></p>
																<?php } else {?>
                                                                    <p><?= trans('no_media_for_visitors')  ?><br><?= trans('check_later')  ?></p>
																<?php } ?>
                                                            </div>

                                                            <ul class="pagination justify-content-center" v-if="media[main_tab].Audio.pages > 1">
                                                                <li :class="'page-item '+(media[main_tab].Audio.page > 1 ? '' : 'disabled')">
                                                                    <a class="page-link"
                                                                       v-on:click="media[main_tab].Audio.page > 1 && loadPage(main_tab, 'Audio', media[main_tab].Audio.page-1, '#'+main_tab+'-audio')">
                                                                        <i class="fa fa-caret-left paginate-icon" aria-hidden="true"></i>
                                                                    </a>
                                                                </li>
                                                                <li v-for="page in media[main_tab].Audio.pages"
                                                                    v-if="page > media[main_tab].Audio.page-3 && page < media[main_tab].Audio.page+3"
                                                                    :class="'page-item '+(page==media.Gallery.Audio.page?'active':'')">
                                                                    <a class="page-link" v-on:click="loadPage(main_tab, 'Audio', page, '#'+main_tab+'-audio')">
                                                                        [[ page ]]
                                                                    </a>
                                                                </li>
                                                                <li :class="'page-item '+(media[main_tab].Audio.page < media[main_tab].Audio.pages ? '' : 'disabled')">
                                                                    <a class="page-link"
                                                                       v-on:click="media[main_tab].Audio.page < media[main_tab].Audio.pages  && loadPage(main_tab, 'Audio', media[main_tab].Audio.page+1, '#'+main_tab+'-audio')">
                                                                        <i class="fa fa-caret-right paginate-icon" aria-hidden="true"></i>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="tab-pane fade p-0" :id="main_tab+'-pdf'" role="tabpanel" v-bind:aria-labelledby="main_tab+'-pdf-tab'">
                                                            <div v-if="waiting" class="featured stacked-cards mb-5" v-cloak>
                                                                <div class="slider">
                                                                    <div class="spinner-border" style="width: 80px;height:80px;" role="status">
                                                                        <span class="sr-only">Loading...</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="container-fluid mt-3" v-else-if="media[main_tab].PDF.data.length > 0">
                                                                <div class="row document-row" >
                                                                    <div class="col-6 col-xl-3 p-1" v-for="(pdf,index) in media[main_tab].PDF.data">
                                                                        <div class="document-card position-relative">
                                                                            <div class="editing-gallery-container">
                                                                                <a class="btn btn-link share-btn" v-on:click="shareFile(pdf)" v-bind:data-value="pdf.id" role="button" :tabindex="-1"
                                                                                   data-trigger="focus" data-toggle="popover" title="<?=trans('share_or_send')?>"
                                                                                   v-bind:data-content="sharingContent(pdf)" data-placement="top"
                                                                                ><i class="fa fa-share" aria-hidden="true"></i></a>
																				<?php if (isset($_SESSION['user_id']) && $logged_in) { ?>
                                                                                    <button class="btn btn-link delete-btn text-danger" v-on:click="deleteFile(pdf.id, 'PDF')"><i class="fa fa-times" aria-hidden="true"></i></button>
                                                                                    <button class="btn btn-link edit-btn text-success" v-on:click="editFile(pdf)"><i class="fa fa-edit" aria-hidden="true"></i></button>
																				<?php } ?>
                                                                            </div>
                                                                            <a :href="pdf.file" target="_blank" class="d-flex align-items-center h-100 justify-content-center">
                                                                                <img v-bind:data-pdf-thumbnail-file="pdf.file" class="pdf-thumbnail h-100" src="images/document.svg">
                                                                            </a>
                                                                        </div>
                                                                        <div class="document-caption text-left">
                                                                            <h1><span class="d-inline-block mr-1">[[String(index+1).padStart(2, '0')]]</span>[[pdf.name]]</h1>
                                                                            <p>[[pdf.description]]</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div v-else class="text-center pt-5">
																<?php if (isset($_SESSION['user_id']) && $logged_in) {?>
                                                                    <p><?= trans('no_media_for_managers')  ?><br><?= trans('use_media_button')  ?></p>
																<?php } else {?>
                                                                    <p><?= trans('no_media_for_visitors')  ?><br><?= trans('check_later')  ?></p>
																<?php } ?>
                                                            </div>
                                                            <ul class="pagination justify-content-center" v-if="media[main_tab].PDF.pages > 1">
                                                                <li :class="'page-item ' + (media[main_tab].PDF.page > 1 ? '' : 'disabled')">
                                                                    <a class="page-link"
                                                                       v-on:click="media[main_tab].PDF.page > 1 && loadPage(main_tab, 'PDF', media[main_tab].PDF.page-1, '#'+main_tab+'-pdf')">
                                                                        <i class="fa fa-caret-left paginate-icon" aria-hidden="true"></i>
                                                                    </a>
                                                                </li>
                                                                <li v-for="page in media[main_tab].PDF.pages"
                                                                    v-if="page > media[main_tab].PDF.page-3 && page < media[main_tab].PDF.page+3"
                                                                    :class="'page-item ' + (page==media[main_tab].PDF.page?'active':'')">
                                                                    <a class="page-link"
                                                                       v-on:click="loadPage(main_tab, 'PDF', page, '#'+main_tab+'-pdf')">
                                                                        [[ page ]]
                                                                    </a>
                                                                </li>
                                                                <li :class="'page-item '+ (media[main_tab].PDF.page < media[main_tab].PDF.pages ? '' : 'disabled')">
                                                                    <a class="page-link"
                                                                       v-on:click="media[main_tab].PDF.page < media[main_tab].PDF.pages  && loadPage(main_tab, 'PDF', media[main_tab].PDF.page+1, '#'+main_tab+'-pdf')">
                                                                        <i class="fa fa-caret-right paginate-icon" aria-hidden="true"></i>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    <input type="hidden" value="<?php echo $success; ?>" id="success">
                                    <input type="hidden" value="<?php if (isset($successMessage)) {echo $successMessage;} ?>" id="successMessage">
                                    <input type="hidden" value="<?php if (isset($error)) {echo $error;} ?>" id="error">
                                </div>
                            </div>
						<?php } ?>
                        <div class="modal fade" id="edit-media-modal" tabindex="-1" role="dialog">
                            <div class="modal-dialog " role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title ml-auto mt-3" ><?=trans('edit_media_item')?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="<?=trans('close')?>">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form method="post" enctype="multipart/form-data">
                                        <div class="modal-body">
                                            <input type="hidden" name="fileUser" value="<?php echo $_SESSION['user_id']; ?>">
                                            <input type="hidden" name="fileFamily" value="<?php echo $_SESSION['family_id']; ?>">
                                            <div class="form-group">
                                                <label for="editFileType"><?=trans('item_type')?> *</label>
                                                <select name="fileType" class="form-control"
                                                        v-model="editing_media.file_type" id="editFileType" required>
                                                    <option value=''><?=trans('choose')?></option>
                                                    <option value="Image"><?=trans('image')?></option>
                                                    <option value="Video"><?=trans('video')?></option>
                                                    <option value="Audio"><?=trans('audio')?></option>
                                                    <option value="PDF"><?=trans('document')?></option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="editFileTitle"><?=trans('item_name')?> *</label>
                                                <input type="text" class="form-control" required name="fileTitle"
                                                       id="editFileTitle" v-model="editing_media.name_en">
                                            </div>
                                            <div class="form-group">
                                                <label for="editFileArTitle"><?=trans('item_name_ar')?></label>
                                                <input type="text" class="form-control" name="fileArTitle"
                                                       id="editFileArTitle" v-model="editing_media.name_ar">
                                            </div>

                                            <div class="form-group">
                                                <label for="editFileDesc"><?=trans('desc')?></label>
                                                <input type="text" class="form-control" name="description"
                                                       id="editFileDesc" v-model="editing_media.description_en">
                                            </div>
                                            <div class="form-group">
                                                <label for="editFileArDesc"><?=trans('desc_ar')?></label>
                                                <input type="text" class="form-control" name="description_ar"
                                                       id="editFileArDesc" v-model="editing_media.description_ar">
                                            </div>

                                            <div class="form-group">
                                                <label for="editFamilyFile"><?=trans('file')?></label>
                                                <input type="file" class="form-control"  name="familyFile" id="editFamilyFile">
                                            </div>

                                        </div>
                                        <div class="modal-footer justify-content-center" style="margin: auto;">
                                            <input type="hidden" v-model="editing_media.id" name="file_id">
                                            <button type="submit" class="btn hbtn btn-hred" name="submitEditFile"><?=trans('submit')?></button>
                                            <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="close1"><?=trans('close')?></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
if (isset($_GET['family'])) {
	$id = $_GET['family'];
} elseif (isset($_GET['flag'])) {
	$id = $_GET['flag'];
} elseif (isset($_GET['f']) && $_GET['f']) {
	$id = $_GET['f'];
} else {
	$id = $_SESSION['family_id'];
}
$display = 1;
if(isset($_GET['type']) && isset($_GET['flag'])  && ! isset($_SESSION['user_id']) && get_family_invitation($_GET['type'])){
	$display = 0;
}

// if(! isset($_GET['type']) && ! isset($_GET['flag']) && ! get_family_invitation($_GET['type']) && checkFamilyStatus($id) == 3 && $_SESSION['family_id'] != $id){
$family_status = checkFamilyStatus($id);
if((! isset($_GET['type']) && ! isset($_GET['flag']) && ! get_family_invitation($_GET['type']) && $family_status == 3 && ! isset($_SESSION['user_id']) && ! isset($_GET['f'])) ||
		(isset($_SESSION['user_id']) && $_SESSION['family_id'] != $id && $family_status == 3) ||
		(isset($_GET['f']) && $_GET['f'] != '' && isset($_GET['id']) && $family_status == 3 && strtotime(date('Y-m-d')) > strtotime(checkUserAccessAbility($_GET['id'])))){
	?>

    <div class="family-tree-container">
        <div class="container position-relative">
            <div class="col-12 offset-lg-2 col-lg-8 text-align-center position-relative pl-0 pr-0 mb-4">
                <h5 class="text-center treeheader w-100">
					<?php echo $lang != 'ar' ? ucfirst($row['name_' . $lang]) . " " . $languages[$lang]['family'] : $languages[$lang]['family'] . " ". ucfirst($row['name_' . $lang]); ?>
                </h5>
                <div style="text-align:center;" class="text-medium low-text-contrast">
                    <picture>
                        <source srcset="images/fam-tree.webp" type="image/webp">
                        <source srcset="images/fam-tree.png" type="image/png">
                        <img src="images/fam-tree.png" style="width:50%;" class="treeimage">
                    </picture>
                </div>
            </div>
        </div>

        <div class="text-center">
            <h5>
				<?= trans('tree_hidden') ?> <a class="text-dark text-decoration request_view_tree" type="tree" href="<?= $id ?>" status="<?= $family_status ?>" >
					<?= trans('here') ?>
                </a>
            </h5>
        </div>

    </div>

	<?php
} else {

	$tree_users = Family::find($id)->users()->active()->whereIn('parent_id', ['alpha', 'alpha_2'])->get();
	$status_1 = "";
	$status_2 = "display: none;";
	if(isset($_GET['tree']) && $_GET['tree'] != ''){
		$tree_users = Family::find($id)->users()->where(['user_id'=>$_GET['tree']])->get();
		$status_1 = "display: none";
		$status_2 = "";
		$display = 0;
	}
	?>
    <div class="family-tree-container">

        <div class="family-tree-header pt-5 d-flex flex-column justify-content-between">
            <h5 class="text-center family-tree-title container-fluid mt-auto text-dark">
                <?php echo $lang != 'ar' ? ucfirst($row['name_' . $lang]) . " " . $languages[$lang]['family']. ' '.$languages[$lang]['tree_singular'] : $languages[$lang]['tree_singular']  .' '. $languages[$lang]['family'] . " ". ucfirst($row['name_' . $lang]); ?>
            </h5>
            <div class="container-fluid my-4">
                <form class="tree-search-form">
                    <div class="row justify-content-center <?=$lang == 'ar' ? 'flex-row-reverse' : '' ?>">
                        <input class="form-control col-8 col-md-6 col-lg-4" type="text" id="searchTree" placeholder="<?=trans('search_tree')?>">
                        <button class="btn btn-danger text-center" id="searchTreeSubmit" type="submit" ><i class="fa fa-search" style="left:auto"></i></button>
                    </div>
                </form>
            </div>
            <div class="w-100 text-center text-dark py-2">
                <div class="row">
                    <div class="col-12 d-flex justify-content-center">
                        <a href="#" class="action-button text-light d-inline-block family-tree-controls d-flex justify-content-center align-items-center" onclick="toggleTreeFullScreen()"><i class="align-middle fa fa-expand"></i></a>
                        <a href="#" class="action-button text-light d-inline-block family-tree-controls d-flex justify-content-center align-items-center zoom-in-btn" onclick="zoomIn(this)"><i class="align-middle  fa fa-plus"></i></a>
                        <a href="#" class="action-button text-light d-inline-block family-tree-controls d-flex justify-content-center align-items-center zoom-out-btn" onclick="zoomOut(this)"><i class="align-middle  fa fa-minus"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <hr class="mt-0">
        <div class="treeParent shadow" style="width: 100%; overflow-x: auto;overflow-y:hidden;height:20px">
            <div id="treeDiv-shadow">
            </div>
        </div>
        <div class="treeParent" style="width: 100%; overflow-x: auto;overflow-y: hidden">
            <div id="treeDiv" class="familyTree" style="width: max-content;margin: auto;display:block">
                <ul style="overflow:auto">
					<?php
					foreach($tree_users as $row){
						$userId = $row['user_id'];
						$userName = $row['name'];
						$userImage = $row['image'];
						$wifes = $row['wife'];
						if($row['parent_id'] == 'alpha' || isset($_GET['tree'])){
							?>
                            <li id="<?= $userId ?>" class="family_alpha">
                                <a href="<?php echo $userId; ?>" class="cell" style="background-color: blue !important;">
                                    <img src="<?=$userImage?thumb($userImage):asset('images/default-user.png')?>" height="90" width="90" loading="lazy">
                                    <figcaption class="text-white"><?php echo $userName; ?></figcaption>
                                </a>
								<?php
								if($row['gender'] == 'Male'){
									if ($wifes != 0 && check_first_father_children($userId) != 0) {
										echo getUserWifes($userId, $id, $wifes, $display, TRUE);
									} else if($wifes != 0 && check_first_father_children($userId) == 0){
										echo getUserWifes($userId, $id, $wifes, $display);
									} else {
										echo getChildren($userId, $id, $wifes, $display);
									}
								} else {
									$outerHusband = hasOuterHusband($id, $userId, $display);
									if($outerHusband > 0){
										echo getOuterHusband($id, $userId, $display);
									} else {
										$fatherId = getFatherFromMother($userId);
										echo getChildren($fatherId, $id, $userId, $display);
									}
								}
								?>
                            </li>
						<?php } else if($row['parent_id'] == 'alpha_2'){ ?>
                            <li id="<?= $userId ?>">
                                <a href="<?php echo $userId; ?>" class="cell">
                                    <img src="<?= $userImage?thumb($userImage):asset('images/default-user.png')?>" height="90" width="90" loading="lazy">
                                    <figcaption><?php echo $userName; ?></figcaption>
                                </a>
								<?php
								if($row['gender'] == 'Male'){
									if ($wifes != 0) {
										echo getUserWifes($userId, $id, $wifes, $display);
									} else {
										echo getChildren($userId, $id, $wifes, $display);
									}
								} else {
									$outerHusband = hasOuterHusband($id, $userId, $display);
									if($outerHusband > 0){
										echo getOuterHusband($id, $userId, $display);
									} else {
										$fatherId = getFatherFromMother($userId);
										echo getChildren($fatherId, $id, $userId, $display);
									}
								}
								?>
                            </li>
						<?php }}  ?>
                </ul>
            </div>
        </div>
    </div>
<?php } ?>
<br><br>
<div id="flyingSearch" class="w-100 d-none" style="height: 50px;position:fixed;bottom:20px;z-index:1000">
    <div class="container" style="border:1px solid #212529;background:linear-gradient(142deg, #d3c1af, #666453);border-radius:5px;height:50px;direction:ltr">
        <form class="tree-search-form w-100">
            <div class="row" style="height:50px;margin-left:0;margin-right:0">
                <input class="form-control col-5 offset-md-3" type="text" id="searchTreeShadow" placeholder="<?=trans('search_tree')?>" style="height:40px;margin-top:5px">
                <div class="col-2 pl-0">
                    <button class="btn btn-danger text-center" id="searchTreeShadowSubmit" type="submit" style="height:40px;margin-top:5px;line-height:30px"><i class="fa fa-search" style="left:auto"></i></button>
                </div>
                <div class="col-4 col-md-2 p-0" >
                    <a class="btn btn-danger btn-sm float-right ml-1 close-search" style="height:40px;margin-top:5px;line-height:30px"><i class="fa fa-close"></i></a>
                    <a class="btn btn-primary btn-sm float-right ml-1 next" style="height:40px;margin-top:5px;line-height:30px"><i class="fa fa-angle-down"></i></a>
                    <a class="btn btn-primary btn-sm float-right prev" style="height:40px;margin-top:5px;line-height:30px"><i class="fa fa-angle-up"></i></a>
                    <p class="float-left stats" style="color:white;font-weight:bold;height:4px;line-height:40px;margin-top:5px">-/-</p>
                </div>
            </div>
        </form>
    </div>
</div>
<button id="gallery-opener"></button>
<?php include_once(__DIR__."/include/profile/scripts.php"); ?>
