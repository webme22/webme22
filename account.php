<?php
include_once(__DIR__."/config.php");
middleware('user');
include_once(__DIR__."/lib/Mailer.php");
if(isset($_SESSION['user_id']) && $_SESSION['family_id'] == 0){
    header("location: home.php");
    exit();
}
if(isset($_GET['user']) && (! checkLoggedUser($_SESSION['user_id'], $_SESSION['family_id']) || checkUserFamily($_SESSION['user_id'], $_GET['user']) || checkEditedUser($_SESSION['user_id'], $_GET['user']))){
    header("location: profile.php");
    exit();
}
if(! isset($_SESSION['user_id'])){
    header("location: login.php");
    exit();
}
$logged_in_user = User::where(['user_id'=>$_SESSION['user_id']])->where(['family_id'=>  $_SESSION['family_id']])->where('role', '!=', 'user')->first();
if(isset($_POST['confirm_accept_request'])){
    $approval_request = $_POST['add_request'];
    $query = FamilyJoinRequest::where(['id'=>$approval_request])->where(['family_id'=>$_SESSION['family_id']])->update(['status'=>1, 'accepted_by'=>$_SESSION['user_id']]);
    if($query){
        $request = get_join_family_requests($approval_request);
        $invitation = FamilyInvitation::create([
            'family_id' =>  $request['family_id'],
            'user_id' => $_SESSION['user_id'],
            'type' =>  'Both',
            'name' =>  $request['name'],
            'date'=>date('Y-m-d h:i:s a')
        ]);
        $invitation_id = $invitation->id;
        $invitation_id = base64_encode(($invitation_id + 4548) . "bla&@bla");

        $url = $siteUrl.$RELATIVE_PATH."/profile.php?type={$invitation_id}&flag={$request['family_id']}";
//        $emailSent = mail($request['email'], "AlHamayel Family Tree", $htmlMsg, implode("\r\n", $headers));
        $faq_url = $siteUrl.$RELATIVE_PATH."/faq.php";
        $mailer = new Mailer();
        $mailer->setVars(['user_name'=>$request['name'], 'url'=>$url, 'faq_url'=>$faq_url]);
        $emailSent = $mailer->sendMail([$request['email']], "Join request accepted", 'accept_join_request.html', 'accept_join_request.txt');
        $sent = 0;
        if ($emailSent) {
            $sent = 1;
        }
		SiteMail::create([
				'family_id' => $request['family_id'],
				'name' => $request['name'],
				'email' => $request['email'],
				'title' => 'Accept Join Request',
				'sent' => $sent,
				'date' => date('Y-m-d h:i:s a'),
		]);
        $success = trans('request_accepted');
    }
}
if(isset($_POST['acceptSubmit'])){
    $family_id = $_SESSION['family_id'];
    $user = $_POST['requestUser'];
    $date = $_POST['expiryDate'];
    $email = $_POST['requestedEmail'];
    $name = $_POST['requestedName'];
    $familyName = Family::find($family_id)->name_en;
    FamilyAccess::where(['id'=>$user])->where(['family_id'=>$family_id])->update([
             "expire_date"=> $date,      
             "accept"=> '1',      
             "acceptedBy"=> $_SESSION['user_id'],      
            ]);
    $url = $siteUrl.$RELATIVE_PATH."/profile.php?id={$user}&f={$family_id}";
    $mailer = new Mailer();
    $mailer->setVars(['user_name'=>$name, 'url'=>$url, 'familyName'=>$familyName, 'date'=>$date]);
    $mailer->sendMail([$email], "Access request accepted", 'accept_access_request.html', 'accept_access_request.txt');
    $success = trans('email_sent');
}

if(isset($_POST['sendReminder'])){
    $user = getUserData($_POST['assistantToremind']);
    if(strlen($user->user_name) < 3 && strlen($user->user_password) < 3 && $user->family_id == $_SESSION['family_id']){
        $flag = $user->user_id + 10;
		$belong = $user->family_id + 20;
		$url = $siteUrl.$RELATIVE_PATH."/login.php?status=complete&flag={$flag}&belong={$belong}";
		$familyName = getFamilyName($user->family_id);
		$mailer = new Mailer();
		$mailer->setVars(['user_name'=>$user->name, 'url'=>$url, 'family_name'=>$familyName]);
		$emailSent = $mailer->sendMail([$user->email], "You are now a family assistant on alhamayel",
				'assistant_inform.html', 'assistant_inform.txt');
        $sent = 0;
        if ($emailSent) {
            $sent = 1;
        }
        SiteMail::create([
            'family_id' => $user->family_id,
            'name' => $user->name,
            'email' => $user->email,
            'title' => 'remindAssistant',
            'sent' => $sent,
            'date' => date('Y-m-d h:i:s a'),
        ]);

        $success = trans('email_sent');
    }
}

if(isset($_POST['deleteaccessRequest'])){
    $access = FamilyAccess::find($_POST['accessRequest']);
    if($access->family_id == $_SESSION['family_id']){
        if($access->delete()){
            $success = trans('deleteMessage');
        }
    }
}
if(isset($_POST['deletedEmailSubmit'])){
    $email = SiteMail::find($_POST['deletedEmail']);
    if($email->family_id == $_SESSION['family_id']){
        if($email->delete()){
            $success = trans('deleteMessage');
        }
    }
}
if(isset($_POST['deleteAddMembersRequest'])){
    $request = FamilyJoinRequest::find($_POST['add_members']);
    if($request->family_id == $_SESSION['family_id']){
        if($request->delete()){
            $success = trans('deleteMessage');
        }
    }
}
if(isset($_POST['deleteSubmit'])){
    $user = User::find($_POST['deleteAssistant']);
    if($user->family_id == $_SESSION['family_id']){
        if($user->delete()){
            $success = trans('deleteMessage');
        }
    }
}
if(isset($_POST['deleteHisSubmit'])){
    $history = FamilyHistory::find($_POST['deleteHis']);
    if($history->family_id == $_SESSION['family_id']){
        if($history->delete()){
            $success = trans('deleteMessage');
        }
    }
    
}
if(isset($_POST['deleteInvSubmit'])){
    $invitation = FamilyInvitation::find($_POST['deleteInv']);
    if($invitation->family_id == $_SESSION['family_id']){
        if($invitation->delete()){
            $success = trans('deleteMessage');
        }
    }
}
if(isset($_POST['formSubmit'])){
    $password =   $_POST["password"];
    $name =  trim($_POST["name"]);
    $username =  trim($_POST["username"]);
    $random =  trim($_POST["random"]);
    $family_id = $_SESSION['family_id'];
    $user_id = $_POST['user_id'];

    $country =  trim($_POST["country"]);
    $key =  trim($_POST["key"]);
    $phone = ($_POST["phone"] != '') ? trim($_POST["phone"]) : null;
    $kunya = ($_POST["kunya"] != '')?  trim($_POST["kunya"]) : null;
    $occupation = ($_POST["occupation"] != '')?  trim($_POST["occupation"]) : null;
    $gender = $_POST['gender'];
    $DOB = $_POST['DOB'];
    $nationality =  trim($_POST["nationality"]);
    $facebook = ($_POST["facebook"] != '')?  trim($_POST["facebook"]) : null;
    $twitter = ($_POST["twitter"] != '')?  trim($_POST["twitter"]) : null;
    $instagram = ($_POST["instagram"] != '')?  trim($_POST["instagram"]) : null;
    $snapchat = ($_POST["snapchat"] != '')?  trim($_POST["snapchat"]) : null;
    $club_name = ($_POST["club_name"] != '')?  trim($_POST["club_name"]) : null;
    $interests = ($_POST["interests"] != '')?  trim($_POST["interests"]) : null;
    $about = ($_POST["about"] != '')?  trim($_POST["about"]) : null;

    $email = trim($_POST["email"]);
    $imageName = $_FILES['image']['name'];
    $imageTmpName = $_FILES['image']['tmp_name'];
    $logoName = $_FILES['logo']['name'];
    $logoTmpName = $_FILES['logo']['tmp_name'];

    $role = $_POST['role'];
    $current_user = User::where('user_id', $user_id)->where('family_id', $family_id)->first();
    $old_role = $current_user['role'];
    $errors = [];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($email) ) {
        array_push($errors, trans("invalidEmail"));
    }
    if(empty($errors)){
        if($random == 88 && $username != ""){
            $updates = [
                'user_name' => $username,
                'name' => $name,
                'country_id' => $country,     
                'phone' => $phone,     
                'email' => $email,     
                'date_of_birth' => $DOB,     
                'nationality' => $nationality,     
                'kunya' => $kunya,     
                'facebook' => $facebook,     
                'twitter' => $twitter,     
                'instagram' => $instagram,     
                'snapchat' => $snapchat,     
                'occupation' => $occupation,     
                'interests' => $interests,     
                'club_name' => $club_name,     
                'gender' => $gender,     
                'about' => $about,     
            ];
        } else if($random == 99 || $username == ""){
            $updates = [
                'name' => $name,
                'country_id' => $country,     
                'phone' => $phone,     
                'email' => $email,     
                'date_of_birth' => $DOB,     
                'nationality' => $nationality,     
                'kunya' => $kunya,     
                'facebook' => $facebook,     
                'twitter' => $twitter,     
                'instagram' => $instagram,     
                'snapchat' => $snapchat,     
                'occupation' => $occupation,     
                'interests' => $interests,     
                'club_name' => $club_name,     
                'gender' => $gender,     
                'about' => $about,     
             ];
        }
        
        if($password && $password != "") $updates['user_password'] = password_hash($password, PASSWORD_DEFAULT);
        if($old_role != 'creator' && isset($_POST['role']) && isset($_GET['user'])){
            $updates['role'] = $role;
        }
        if(isset($imageName) && ! empty($imageName)){
            if(! file_exists("uploads/users/" . $user_id)){
                mkdir("uploads/users/" . $user_id, 0775, true);
            }
			if (!file_exists("uploads/users/" . $user_id.'/thumbnails')) {
				mkdir("uploads/users/" . $user_id.'/thumbnails', 0775, true);
			}
			$full_image_name = round(microtime(true)) . ".jpg";
            $target_path = "uploads/users/" . $user_id . "/" . $full_image_name;
			$thumb_path = "uploads/users/" . $user_id . "/thumbnails/" . $full_image_name;
			$imagedatabase = "uploads/users/" . $user_id . "/" . $full_image_name;
            $updates['image'] = $imagedatabase;
            move_uploaded_file($imageTmpName, $target_path);

            // crop image
            if (isset($_POST['image_cropping']) && $_POST['image_cropping'] !== ''){
                $cropping_details = json_decode($_POST['image_cropping'], true);
                $imagick = new \Imagick(realpath($target_path));
                $imagick->cropImage($cropping_details['width'], $cropping_details['height'], $cropping_details['x'], $cropping_details['y']);
                $imagick->writeImage(realpath($target_path));
            }
			copy($target_path, $thumb_path);
			$imagick = new \Imagick(realpath($target_path));
			$imagick->resizeImage(90,90,\Imagick::FILTER_CATROM, 1);
			$imagick->writeImage(realpath( $thumb_path));
        }
        else {
            $target_path = $current_user['image'];
            // crop image
            if (isset($_POST['image_cropping']) && $_POST['image_cropping'] !== '' && $target_path && file_exists($target_path)){
                $cropping_details = json_decode($_POST['image_cropping'], true);
                $imagick = new \Imagick(realpath($target_path));
                $imagick->cropImage($cropping_details['width'], $cropping_details['height'], $cropping_details['x'], $cropping_details['y']);
                $imagick->writeImage(realpath($target_path));
            }
        }
        if(isset($logoName) && ! empty($logoName)){
            if(! file_exists("uploads/users/" . $user_id)){
                mkdir("uploads/users/" . $user_id, 0775, true);
            }
            $logo_path = "uploads/users/" . $user_id . "/logo_" . round(microtime(true)) . ".jpg";
            $logodatabase = "uploads/users/" . $user_id . "/logo_" . round(microtime(true)) . ".jpg";
            $updates['club_logo'] = $logodatabase;
            move_uploaded_file($logoTmpName, $logo_path);
            // crop image
            if (isset($_POST['club_cropping']) && $_POST['club_cropping'] !== ''){
                $cropping_details = json_decode($_POST['club_cropping'], true);
                $imagick = new \Imagick(realpath($logo_path));
                $imagick->cropImage($cropping_details['width'], $cropping_details['height'], $cropping_details['x'], $cropping_details['y']);
                $imagick->writeImage(realpath($logo_path));
            }
        }
        else {
            $logo_path = $current_user['club_logo'];
            // crop image
            if (isset($_POST['club_cropping']) && $_POST['club_cropping'] !== ''){
                $cropping_details = json_decode($_POST['club_cropping'], true);
                $imagick = new \Imagick(realpath($logo_path));
                $imagick->cropImage($cropping_details['width'], $cropping_details['height'], $cropping_details['x'], $cropping_details['y']);
                $imagick->writeImage(realpath($logo_path));
            }
        }
        $submitStatus = trans("success");
        $message = trans("edited");
        $current_user->update($updates);
    } else {
        $submitStatus = trans("editFailed");
    }
}
include_once(__DIR__."/header.php");
?>
<link rel='stylesheet' href='//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
<input type="hidden" value="<?php echo $success;?>" id="success">
<input type="hidden" value="<?php echo $error;?>" id="error">
<input type="hidden" value="<?php echo $_SESSION['user_id'];?>" id="logged_user">
<div class="section is-hero has-gradient position-relative overflow-hidden is-subpage">
    <div class="container position-relative">
        <div class="row">
        <div class="col block-centered text-align-center lg-7 md-12">
            <h1 style=" margin-top: 4vh !important;
                
                color: #fff;
                font-size: 56px;
                line-height: 1.15;
                font-weight: 500;
                display: block !important; "><?=trans('account_title')?> </h1>
            <!--<div class="padding-left padding-right margin-bottom is-heading-color" style="font-size: 16px !important;">Innovation and simplicity makes us happy: our goal is to remove any technical or financial barriers that can prevent business owners from making a website.</div>-->
        </div>
        </div>
    </div>
</div>
<input type="hidden" id="afterSubmit" value="<?php echo $submitStatus; ?>">
<style type="text/css">
    .customers {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }
    .customers td, .customers th {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }
    .customers tr{background-color: #f2f2f2;}
    .customers tr:hover {background-color: #ddd;}
    .customers th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #1f2b36;
        color: white;
        text-align: center;
        font-family: bukra;
    }
    .loginBtn {
        box-sizing: border-box;
        position: relative;
        /* width: 13em;  - apply for fixed size */
        margin: 0.2em;
        padding: 0 15px 0 46px;
        border: none;
        text-align: left;
        line-height: 34px;
        white-space: nowrap;
        border-radius: 0.2em;
        font-size: 16px;
        color: #FFF;
    }
    .loginBtn:before {
        content: "";
        box-sizing: border-box;
        position: absolute;
        top: 0;
        left: 0;
        width: 34px;
        height: 100%;
    }
    .loginBtn:focus {
        outline: none;
    }
    .loginBtn:active {
        box-shadow: inset 0 0 0 32px rgba(0,0,0,0.1);
    }
    /* Facebook */
    .loginBtn--facebook {
        background-color: #4C69BA;
        background-image: linear-gradient(#4C69BA, #3B55A0);
        /*font-family: "Helvetica neue", Helvetica Neue, Helvetica, Arial, sans-serif;*/
        text-shadow: 0 -1px 0 #354C8C;
    }
    .loginBtn--facebook:before {
        border-right: #364e92 1px solid;
        background: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/14082/icon_facebook.png') 6px 6px no-repeat;
    }
    .loginBtn--facebook:hover,
    .loginBtn--facebook:focus {
        background-color: #5B7BD5;
        background-image: linear-gradient(#5B7BD5, #4864B1);
    }
    /* Google */
    .loginBtn--google {
        /*font-family: "Roboto", Roboto, arial, sans-serif;*/
        background: #DD4B39;
    }
    .loginBtn--google:before {
        border-right: #BB3F30 1px solid;
        background: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/14082/icon_google.png') 6px 6px no-repeat;
    }
    .loginBtn--google:hover,
    .loginBtn--google:focus {
        background: #E74B37;
    }
    input#cpass {
        margin-left: 0px;
        margin-bottom:25px;
    }
    /*form#join-form{*/
    /*    display: inline-block !important;*/
    /*    margin-left: auto !important;*/
    /*    margin-right: auto !important;*/
    /*    text-align: <?php echo $align; ?> !important;*/
    /*}*/
    form#join-form>input, form#join-form>div>input, form#join-form>textarea, form#join-form>div>select, form#join-form>select {
        margin-bottom: 25px;
        height: 48px;
        width: 85%;
        /*width: 75%;*/
    }

    .nav_black {
        color: #fff !important;
        padding: 2vh 2vw !important;
        padding-right: 2vw !important;
        height: 8vh !important;
    }

    .nav_black:hover {
        color: #000 !important;
    }

    .flex-column {
        margin-top: 5vh;
    }

    .side_bar {
        background-color: #666453;
    }

    .black {
        background-color: #000;
    }

    .black_text {
        color: #000;
    }

    .white_background {
        background-color: #fff;
    }

    .white {
        color: #fff;
    }

    #open_sidebar {
        font-size:30px;
        cursor:pointer;
    }

    .sidenav {
        height: 18%;
        width: 100%;
        /*position: relative;*/
        /*z-index: 1;*/
        /*top: 10%;*/
        /*left: 0;*/
        background-color: #666453;
        overflow-x: hidden;
        transition: 0.5s;
        padding-top: 60px;
    }

    #mySidenav {
        background-color: #666453;
        padding: 2em;
        height: 100%;
    }

    #mySidenav hr {
        border: 1px solid #fff;
        width: 100%;
    }

    #mySidenav a {
        /*padding: 8px 14px 8px 32px;*/
        text-decoration: none;
        font-size: 1.5em;
        color: #818181;
        display: block;
        transition: 0.3s;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    #mySidenav a span.badge {
        border-radius: 50%;
    }

    #mySidenav a:hover {
        border-radius: 2vh;
        padding: 1em;
    }

</style>
<script src="https://apis.google.com/js/platform.js" async defer></script>
<div class="section has-bg-accent position-relative"  style="background-color: #f7f7f7;height: auto;background-image: url();background-repeat: no-repeat;background-size: cover;">
    <div class="container-fluid margin-bottom-quad" style="vertical-align: top;top: 0;margin-bottom: 0;direction: <?=$dir?>;">
        <div class="row">
            <div class="col-12 col-md-4 col-lg-3 col-sm-12">
                <!--<span id="open_sidebar">&#9776;</span>-->
                <div id="mySidenav" class="d-flex flex-column mt-0">
                    <!--<a href="javascript:void(0)" class="closebtn">&times;</a>-->
                    <a class="for_assistants" href="#">
                        <span class="float-<?= $align ?> white"><?php echo $languages[$lang]['subAdminLst']; ?> </span>
                        <span class="badge white_background black_text float-<?= $align_2 ?> join_badge"><?= Family::find($_SESSION['family_id'])->assistants()->whereNull('user_name')->whereNull('user_password')->count() ?></span>
                    </a>
                    <hr>
                    <a class="for_access" href="#">
                        <span class="float-<?= $align ?> w-75 white"><?=$languages[$lang]['access_requests']?></span>
                        <span class="badge white_background black_text float-<?= $align_2 ?> access_badge"><?= FamilyAccess::where(['family_id'=>$_SESSION['family_id']])->pending()->count() ?></span>
                    </a>
                    <hr>
                    <a class="for_join" href="#">
                        <span class="float-<?= $align ?> w-75 white"><?=$languages[$lang]['join_requests']?></span>
                        <span class="badge white_background black_text float-<?= $align_2 ?> join_badge"><?= FamilyJoinRequest::where(['family_id'=>$_SESSION['family_id']])->pending()->count() ?></span>
                    </a>
                    <hr>
                    <a class="for_invitations" href="#">
                        <span class="float-<?= $align ?> white"><?=$languages[$lang]['inv_list']?></span>
                    </a>
                    <hr>
                    <a class="for_history" href="#">
                        <span class="float-<?= $align ?> white"><?=$languages[$lang]['familyHistory']?></span>
                    </a>
                    <hr>
                    <a class="for_emails" href="#">
                        <span class="float-<?= $align ?> white"><?=$languages[$lang]['sent_emails']?></span>
                    </a>
                </div>
            </div>
            <div class="col-12 col-md-8 col-lg-9 col-sm-12 center-block">
                <h2><?=trans('edit_details')?></h2>
                <hr>
                <div class="w-form w-100" >

                    <?php

                    if(isset($_GET['user']) && $_SESSION['role'] != 'user'){
                        $userId = $_GET['user'];
                        $submittedUrl = "account.php?user={$userId}";
                    } elseif(! isset($_GET['user']) && $_SESSION['role'] != 'user') {
                        $userId = $_SESSION['user_id'];
						$submittedUrl = "account.php";
                    } elseif(! isset($_GET['user']) && $_SESSION['role'] == 'user') {
                        $userId = $_SESSION['user_id'];
                        $submittedUrl = "account.php";
                    }
					$user = User::find($userId);
                    
                    if($_SESSION['user_id'] == $user['user_id'] || ($_SESSION['role'] == 'creator' && $user['role'] != 'user')) {
                        $disabled = "";
                        $required = "required";
                        $can_be_edited = "88"; // yes
                    } else if ($user['role'] == 'user') {
                        $disabled = "";
                        $required = "";
                        $can_be_edited = "88"; // yes
                    } else {
                        $disabled = "disabled";
                        $required = "";
                        $can_be_edited = "99"; // no
                    }
                    ?>
                    <form id="join-form" action="<?php echo $submittedUrl;?>" method="post" name="email-form-2" enctype="multipart/form-data">
                        <!--<input type="hidden" name="role" value="<?php echo $user['role']; ?>" />-->
                        <input type="hidden" name="family_id" value="<?php echo $user['family_id']; ?>" />
                        <input type="hidden" name="user_id" value="<?php echo $userId; ?>" />
                        <input type="hidden" name="random" value="<?php echo $can_be_edited; ?>" />
                        <div class="row pb-4">
                            <div class="pb-4 form-group col-md-6 col-lg-4 float-<?= $align ?>">
                                <label><?php echo $languages[$lang]["username"]; ?></label>
                                <input type="text" class="form-control w-100   " maxlength="256" name="username"  placeholder="<?=$languages[$lang]['username']?>" id="username" <?=$dis?> value="<?=$user['user_name'];?>" <?=$required?> autocomplete="off" <?=$disabled?>/>
                            </div>
                            <div class="pb-4 form-group col-md-6 col-lg-4 float-<?= $align ?>">
                                <label for="pass"><?php echo $languages[$lang]["password"]; ?></label>
                                <div class="input-group">
                                    <input type="password" class="form-control w-100" maxlength="256" name="password" <?=$dissub?> placeholder="<?=$languages[$lang]['password']?>" id="pass" autocomplete="off" />
                                    <div class="input-group-append d-none">
                                        <a type="buttton" class="input-group-text show-password" style="background: #fff; border-left: 0; cursor: pointer;"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                    </div>
                                    <div class="invalid-feedback"><?=inputErr('password')?:''?></div>
                                </div>
                            </div>

                            <div class="pb-4 form-group col-md-6 col-lg-4 float-<?= $align ?>">

                                <label><?php echo $languages[$lang]["name"]; ?> *</label>
                                <input type="text" class="form-control w-100   " maxlength="256" name="name"  placeholder="<?=$languages[$lang]['name']?>" id="name" required="" <?=$dissub?>  autocomplete="off" value="<?=$user['name'];?>" />
                            </div>

                            <div class="pb-4 form-group col-md-6 col-lg-4 float-<?= $align ?>">
                                <label><?php echo $languages[$lang]["DOB"]; ?> *</label>
                                <input type="date" class="form-control w-100   " maxlength="256" name="DOB"  required id="DOB" max="<?php echo date('Y-m-d'); ?>" value="<?php echo $user['date_of_birth']; ?>"/>
                            </div>
                            <div class="pb-4 form-group col-md-6 col-lg-4 float-<?= $align ?>">
                                <label><?php echo $languages[$lang]["gender"]; ?> *</label>
                                <select <?=$dissub?> required name="gender" class="form-control w-100">
                                    <option disabled value=""
                                        <?php

                                        if(empty($user['gender'])){
                                            echo "selected";
                                        }

                                        ?>
                                    ><?=$languages[$lang]['choose']?></option>
                                    <option value="Female"
                                        <?php

                                        if($user['gender'] == 'Female'){
                                            echo "selected";
                                        }

                                        ?>
                                    ><?=$languages[$lang]['female']?></option>
                                    <option value="Male"
                                        <?php

                                        if($user['gender'] == 'Male'){
                                            echo "selected";
                                        }

                                        ?>
                                    ><?=$languages[$lang]['male']?></option>
                                </select>
                            </div>

                            <div class="pb-4 form-group col-md-6 col-lg-4 float-<?= $align ?>">
                                <label><?php echo $languages[$lang]["kunya"]; ?></label>
                                <input type="text" class="form-control w-100   " <?=$dissub?>  autocomplete="off" value="<?=$user['kunya']?>" name="kunya"/>
                            </div>
                            <div class="pb-4 form-group col-md-6 col-lg-4 float-<?= $align ?>">
                                <label><?php echo $languages[$lang]["chooseCountry"]; ?> *</label>
                                <select <?=$dissub?>  id="country" name="country" class="form-control w-100" required>
                                    <option value=""><?=$languages[$lang]['chooseCountry']?></option>
									<?php
									$countries = Country::active()->get();
									foreach($countries as $country){?>
										<option value="<?=$country->id?>"
												<?=$user['country_id'] && $user['country_id'] == $country->id?'selected': ''?>>
											<?=db_trans($country,'name')?>
										</option>
									<?php }?>
                                </select>
                            </div>
                            <div class="pb-4 form-group col-md-6 col-lg-4 float-<?= $align ?>">
                                <label><?php echo $languages[$lang]["chooseNationality"]; ?> *</label>
                                <select id="nationality" name="nationality" class=" form-control w-100" required>
                                    <?php
                                    $nationalities = Nationality::all();
                                    foreach($nationalities as $nationality){
                                        $selected = '';
                                        if($user['nationality'] == $nationality['id']){
                                            $selected = 'selected';
                                        }
                                        echo "<option value='{$nationality["id"]}' {$selected}>{$nationality['name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="pb-4 form-group col-md-6 col-lg-4 float-<?= $align ?> center-block">
                                <label><?php echo $languages[$lang]["email"]; ?></label>
                                <input type="email" class="form-control w-100   "  name="email"  placeholder="<?=$languages[$lang]['email']?>" id="email" <?=$dis?> autocomplete="off" value="<?=$user['email'];?>" style="margin-<?=$align2?>: 0px;" />
                            </div>
                            <?php if(isset($_GET['user']) && checkAdmin($_SESSION['user_id'], $_SESSION['family_id'])){ ?>
                                <div class="pb-4 form-group col-md-6 col-lg-4 float-<?= $align ?> center-block">

                                    <label><?php echo $languages[$lang]["chooseRole"]; ?></label>
                                    <select <?=$dissub?> name="role" class="form-control w-100" <?=$user['role']=='creator'?'disabled':''?>>
                                        <option value="user" <?php if($user['role'] == 'user') echo "selected";?>><?=$languages[$lang]['justUser']?></option>
                                        <option value="assistant" <?php if($user['role'] == 'assistant') echo "selected";?>><?=$languages[$lang]['assistant']?></option>
                                    </select>
                                </div>
                            <?php } ?>

                            <div class="pb-4 form-group col-md-6 col-lg-4 float-<?= $align ?>">
                                <label><?php echo $languages[$lang]["occupation"]; ?></label>
                                <input type="text" class="form-control w-100   " name="occupation"  placeholder="<?=$languages[$lang]['occupation']?>" <?=$dissub?>  autocomplete="off" value="<?=$user['occupation']?>" />
                            </div>
                            <input type='hidden' id='oldKey' value="<?php echo getCountry($user['country_id'])['countryKey']; ?>">
                            <div class="pb-4 form-group col-md-6 col-lg-4 float-<?= $align ?>">
                                <label><?php echo $languages[$lang]["phone"]; ?></label>
                                <div class="row h-100 ml-2 w-100">
                                    <input type="number" class="form-control col-md-4   " id="key" name="key"  placeholder="<?=$languages[$lang]['key']?>" readonly="" autocomplete="off"/>
                                    <input type="text" class="form-control col-md-8   " name="phone"  placeholder="<?=$languages[$lang]['phone']?>" id="phone" title="Only Numbers" autocomplete="off" value="<?=$user['phone'];?>">
                                </div>
                            </div>
                            <div class="pb-4 form-group col-md-6 col-lg-4 float-<?= $align ?>">
                                <label><?php echo $languages[$lang]["facebook"]; ?></label>
                                <input type="text" class="facebook form-control w-100   " maxlength="256" name="facebook"  placeholder="<?=$languages[$lang]['enter_facebook']?>" value="<?=$user['facebook']?>"  id="fb">
                                <span class="fb_error d-none">Invalid Facebook Link .</span>
                            </div>

                            <div class="pb-4 form-group col-md-6 col-lg-4 float-<?= $align ?>">
                                <label><?php echo $languages[$lang]["twitter"]; ?></label>
                                <input type="text" class="twitter form-control w-100   " name="twitter"  placeholder="<?=$languages[$lang]['enter_twitter']?>" value="<?=$user['twitter']?>" id="twitter">
                                <span class="twitter_error d-none">Invalid Twitter Link .</span>
                            </div>
                            <div class="pb-4 form-group col-md-6 col-lg-4 float-<?= $align ?>">
                                <label><?php echo $languages[$lang]["instagram"]; ?></label>
                                <input type="text" class="instagram form-control w-100   " name="instagram"  placeholder="<?=$languages[$lang]['enter_instagram']?>" value="<?=$user['instagram']?>" id="instagram">
                                <span class="instagram_error d-none">Invalid Instagram Link .</span>
                            </div>
                            <div class="pb-4 form-group col-md-6 col-lg-4 float-<?= $align ?>">
                                <label><?php echo $languages[$lang]["snapchat"]; ?></label>
                                <input type="text" class="snapchat form-control w-100   " name="snapchat"  placeholder="<?=$languages[$lang]['enter_snapchat']?>" value="<?=$user['snapchat']?>" id="snapchat">
                                <span class="snapchat_error d-none">Invalid Snapchat Link .</span>
                            </div>

                            <div class="pb-4 form-group col-md-6 float-<?= $align ?>">
                                <label><?php echo $languages[$lang]["uploadPic"]; ?></label>
                                <div class="row ml-0 mr-0">
                                    <input type="hidden" name="image_cropping">
                                <input type="file" accept="image/jpeg" class="form-control col-9 has-preview " name="image"  id="img" autocomplete="off"/>
                                    <div class="crop-preview preview col-3 p-0 ml-auto" data-target="#profile-pic-chooser"
                                         onclick="<?=$user['image']?"$('#profile-pic-chooser').modal('show')":''?>">
                                        <div class="profile-preview-div preview-div">
                                        <a href="#"  data-toggle="modal" data-target="<?=$user['image']?'#profile-pic-chooser':''?>">
                                            <picture>
                                                <source srcset="<?=$user['image']?asset($user['image']):asset('images/default-user.webp')?>" type="image/webp">
                                                <source srcset="<?=$user['image']?asset($user['image']):asset('images/default-user.png')?>" type="image/png">
                                                <img class="profile-pic-image " height="100" width="100" src="<?=$user['image']?asset($user['image']):asset('images/default-user.png')?>">
                                            </picture>
                                        </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pb-4 form-group col-md-6 float-<?= $align ?>">
                                <label><?php echo $languages[$lang]["uploadLogo"]; ?></label>.
                                <div class="row ml-0 mr-0">
                                    <input type="hidden" name="club_cropping">
                                <input type="file" accept="image/jpeg" class="form-control has-preview col-9  " name="logo" autocomplete="off" />
                                    <div class="crop-preview preview col-3 p-0 ml-auto" data-target="#club-pic-chooser"
                                         onclick="<?=$user['club_logo']?"$('#club-pic-chooser').modal('show')":''?>">
                                        <div class="club-preview-div preview-div">
                                            <a href="#"  data-toggle="modal">
                                                <picture>
                                                    <source srcset="<?=$user['club_logo']?:asset('images/default-club.webp')?>" type="image/webp">
                                                    <source srcset="<?=$user['club_logo']?:asset('images/default-club.png')?>" type="image/png">
                                                    <img class="profile-pic-image " height="100" width="100" src="<?=$user['club_logo']?:asset('images/default-club.png')?>">
                                                </picture>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pb-4 form-group col-md-6 float-<?= $align ?>">
                                <label><?php echo $languages[$lang]["clubName"]; ?></label>
                                <input type="text" class="form-control w-100   " maxlength="256" name="club_name" value="<?php echo $user['club_name']; ?>" />
                            </div>

                            <div class="form-group col-md-6 float-<?= $align ?> center-block">
                                <label><?php echo $languages[$lang]["interests"]; ?></label>
                                <textarea  class="form-control w-100 h-50"  name="interests"  placeholder="<?php echo $languages[$lang]["interests"]; ?>"><?php echo $user['interests']; ?></textarea>
                            </div>
                            <div class="form-group col-md-6 float-<?= $align ?> center-block">
                                <label><?php echo $languages[$lang]["about_member"]; ?></label>
                                <textarea  class="form-control w-100 h-50"  name="about"  placeholder="<?php echo $languages[$lang]["about_member"]; ?>"><?php echo $user['about']; ?></textarea>
                            </div>
                        </div>
                        <input type="hidden" id="accountLang" value="<?php echo $lang; ?>">

                        <button type="submit" class="button-primary animated w-inline-block center-block" style="letter-spacing: 0;" name="formSubmit"><?=$languages[$lang]['save']?></button>
                    </form>
                </div>
            </div>
        </div>

        <!--<div class="col lg-6  md-12 side_bar" style="padding-top: 50px;">-->
        <!--    <button class="btn btn-primary bth-lg w-25 toggle_nav d-flex align-content-center justify-content-between" status="0">-->
        <!--        <span class="float-<?= $align ?> h4"> <?= $languages[$lang]["family_info"] ?></span>-->
        <!--        <span class="float-<?= $align_2 ?> align-self-end">  <i class="fa fa-caret-down fa-lg" aria-hidden="true"></i>  </span>-->
        <!--    </button>-->
        <!--    <ul class="nav flex-column">-->
        <!--        <li class="nav-item">-->
        <!--          <a class="nav-link nav_black for_assistants" href="#">-->
        <!--              <span class="float-<?= $align ?>"><?php echo $languages[$lang]['subAdminLst']; ?> </span>-->
        <!--          </a>-->
        <!--        </li>-->
        <!--        <li class="nav-item">-->
        <!--          <a class="nav-link nav_black for_access" href="#">-->
        <!--              <span class="float-<?= $align ?> w-75"><?=$languages[$lang]['prvTtllst']?></span>-->
        <!--            <span class="badge black float-<?= $align_2 ?> d-none access_badge"></span>-->
        <!--          </a>-->
        <!--        </li>-->
        <!--        <li class="nav-item">-->
        <!--          <a class="nav-link nav_black for_join" href="#">-->
        <!--              <span class="float-<?= $align ?> w-75"><?=$languages[$lang]['join_requests']?></span>-->
        <!--            <span class="badge black float-<?= $align_2 ?> d-none join_badge"></span>-->
        <!--          </a>-->
        <!--        </li>-->
        <!--        <li class="nav-item">-->
        <!--          <a class="nav-link nav_black for_invitations" href="#">-->
        <!--              <span class="float-<?= $align ?>"><?=$languages[$lang]['inv_list']?></span>-->
        <!--          </a>-->
        <!--        </li>-->
        <!--        <li class="nav-item">-->
        <!--          <a class="nav-link nav_black for_history" href="#">-->
        <!--              <span class="float-<?= $align ?>"><?=$languages[$lang]['familyHistory']?></span>-->
        <!--          </a>-->
        <!--        </li>-->
        <!--        <li class="nav-item">-->
        <!--          <a class="nav-link nav_black for_emails" href="#">-->
        <!--              <span class="float-<?= $align ?>"><?=$languages[$lang]['sent_emails']?></span>-->
        <!--          </a>-->
        <!--        </li>-->
        <!--    </ul>-->
        <!--</div>-->
    </div>
</div>
<div class="modal fade" id="modal5" tabindex="-1" role="dialog" style="z-index: 1060 !important;">
    <div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title ml-auto mt-3" ><?= trans('delete_assistant') ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p style="text-align: center;"><?= trans('ask_delete_assistant') ?></p>
                    <input type="hidden" value="" name="deleteAssistant" id="deleteAssistant">
                </div>
                <div class="modal-footer" style="margin: auto;">
                    <button type="submit" class="btn btn-danger" name="deleteSubmit"><?= trans('delete') ?></button>
                    <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="close3"><?= trans('close') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="remindModal" tabindex="-1" role="dialog" style="z-index: 1060 !important;">
    <div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title ml-auto mt-3" ><?= trans('remind_assistant') ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p style="text-align: center;"><?= trans('remind_complete') ?></p>
                    <input type="hidden" value="" name="assistantToremind" id="assistantToremind">
                </div>
                <div class="modal-footer" style="margin: auto;">
                    <button type="submit" class="btn btn-danger" name="sendReminder" id="sendReminder"><?= trans('send') ?></button>
                    <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="reminderClose"><?= trans('close') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modal55" tabindex="-1" role="dialog" style="z-index: 1061 !important;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ml-auto mt-3">Delete Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p style="text-align: center;">Do You Want To Delete This Request ?</p>
                <form method="POST">
                    <input type="hidden" value="" name="accessRequest" id="accessRequest">
            </div>
            <div class="modal-footer" style="margin: auto;">
                <button type="submit" class="btn btn-danger" name="deleteaccessRequest">Delete</button>
                <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="close55">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modal60" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ml-auto mt-3" ><?= trans('delete_request') ?>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <p style="text-align: center;">D<?= trans('ask_delete_request') ?></p>
                <form method="POST">
                    <input type="hidden" value="" name="add_members" id="add_members">
            </div>
            <div class="modal-footer" style="margin: auto;">
                <button type="submit" class="btn btn-danger" name="deleteAddMembersRequest"><?= trans('delete') ?></button>
                <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="close60"><?= trans('close') ?></button>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modal555" tabindex="-1" role="dialog" style="z-index: 1061 !important;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ml-auto mt-3"><?= trans('refuse_request') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p style="text-align: center;"><?= trans('ask_refuse_request') ?></p>
                <input type="hidden" value="" id="add_members_request">
            </div>
            <div class="modal-footer" style="margin: auto;">
                <button type="button" class="btn btn-danger" id="confirm_refuse_request"><?= trans('refuse') ?></button>
                <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="close555"><?= trans('close') ?></button>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="modal50" tabindex="-1" role="dialog" style="z-index: 1061 !important;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ml-auto mt-3"><?= trans('accept_request') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <input type="hidden" value="" id="add_request" name="add_request">
            </div>
            <div class="modal-footer" style="margin: auto;">
                <button type="submit" class="btn btn-primary" name="confirm_accept_request"><?= trans('accept') ?></button>
                </form>
                <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="close50"><?= trans('close') ?></button>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="modal9" tabindex="-1" role="dialog" style="z-index: 1061 !important;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ml-auto mt-3"><?= trans('delete_invitation') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p style="text-align: center;"><?= trans('ask_delete_invitation') ?></p>
                <form method="POST">
                    <input type="hidden" value="" name="deleteInv" id="deleteInv">
            </div>
            <div class="modal-footer" style="margin: auto;">
                <button type="submit" class="btn btn-danger" name="deleteInvSubmit"><?= trans('delete') ?></button>
                <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="close9"><?= trans('close') ?></button>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modal10" tabindex="-1" role="dialog" style="z-index: 1061 !important;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ml-auto mt-3" ><?= trans('delete_history') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p style="text-align: center;"><?= trans('ask_delete_history') ?></p>
                <form method="POST">
                    <input type="hidden" value="" name="deleteHis" id="deleteHis">
            </div>
            <div class="modal-footer" style="margin: auto;">
                <button type="submit" class="btn btn-danger" name="deleteHisSubmit"><?= trans('delete') ?></button>
                <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="close10"><?= trans('close') ?></button>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="modal w-100" id="assistants_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document" style="">
        <div class="modal-content w-100">
            <div class="modal-header w-100">
                <h5 class="modal-title ml-auto mt-3"><?php echo $languages[$lang]['subAdminLst']; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="height: 60vh; overflow-y: auto;">
                <table class="customers" id="subAdminLst">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th><?=$languages[$lang]['name']?></th>
                        <th><?=$languages[$lang]['email']?></th>
                        <th><?= $languages[$lang]["type"] ?></th>
                        <th><?=$languages[$lang]['status']?></th>
						<?php if(isset($_SESSION['user_id']) && checkAdmin($_SESSION['user_id'], $_SESSION['family_id'])){ ?>
                            <th><?=$languages[$lang]['manage']?></th>
						<?php } ?>
                    </tr>
                    </thead>
                    <tbody>
					<?php
					$x = 1;
					$assistants = Family::find($_SESSION['family_id'])->assistantsOrderedByDesc;
					foreach($assistants as $assistant){  
                        $style = "color: #000";
                        $reminder = "";
                        $title = "This assistant completed his data";
                        $status = $languages[$lang]["completed"];
                        if(strlen($assistant->user_name) < 3 && strlen($assistant->user_password) < 3){
                            $style = "color: red";
                            $title = "This assistant didn't complete his data";
                            $reminder = "<a href='{$assistant->user_id}' title='Remind invited assistant to complete his data' id='remindAssistant'><i class='fa fa-envelope' style='color: #556575;'></i></a>";
                            $status = $languages[$lang]["pending"];
                        }
                        
                        ?>
                        <tr>
                            <td style="<?= $style ?>" title="<?= $title ?>"><?=$x?></td>
                            <td style="<?= $style ?>" title="<?= $title ?>"><?=$assistant->name?></td>
                            <td style="<?= $style ?>" title="<?= $title ?>"><?=$assistant->email?></td>
                            <td style="<?= $style ?>" title="<?= $title ?>"><?=($assistant->waiting_to_join == 0)? $languages[$lang]["assistant_only"]:$languages[$lang]["assistant_and_member"] ?></td>
                            <td style="<?= $style ?>" title="<?= $title ?>"><?= $status ?></td>
							<?php if(isset($_SESSION['user_id']) && checkAdmin($_SESSION['user_id'], $_SESSION['family_id'])){ ?>
                                <td>
                                <a href="<?=$assistant->user_id?>" title="delete" id="askDelete" class="m-2"><i class="fa fa-remove" style="color: #556575;" ></i></a>
                                <?= $reminder ?>
                                </td>
							<?php } ?>
                        </tr>
						<?php $x++; }
					?>
                    </tbody>

                </table>
            </div>
            <div class="modal-footer" style="margin: auto;">
                <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="assistants_close">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="access_modal" tabindex="-accessalog">
    <div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content w-100">
            <div class="modal-header w-100">
                <h5 class="modal-title ml-auto mt-3"><?php echo $languages[$lang]['prvTtllst']; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body w-100" style="height: 60vh; overflow-y: auto;">
                <table class="customers">
                    <tr>
                        <th>#</th>
                        <th><?=$languages[$lang]['name']?></th>
                        <th><?=$languages[$lang]['email']?></th>
						<?php if(isset($_SESSION['user_id']) && checkAdmin($_SESSION['user_id'], $_SESSION['family_id'])){ ?>
                            <th><?=$languages[$lang]['status']?></th>

                            <th><?=$languages[$lang]['by']?></th>
						<?php } ?>
                        <th><?=$languages[$lang]['date']?></th>
                        <th><?=$languages[$lang]['manage']?></th>
                    </tr>
					<?php
					$count=0;
					$accesses = FamilyAccess::where(['family_id'=>$_SESSION['family_id']]);
					if(isset($_SESSION['user_id']) && ! checkAdmin($_SESSION['user_id'], $_SESSION['family_id'])){
						$accesses = $accesses->where(['accept'=>2]);
					}
					$accesses = $accesses->orderBy('id', 'desc')->get();
					foreach($accesses as $access)
					{
						$count++;
						$style = ($access['accept'] == 2)? "color: red;" : "";
						?>
                        <tr>
                            <td style="<?= $style ?>"><?=$count?></td>
                            <td style="<?= $style ?>"><?=$access['name']?></td>
                            <td style="<?= $style ?>"><?=$access['email']?></td>
							<?php if(isset($_SESSION['user_id']) && checkAdmin($_SESSION['user_id'], $_SESSION['family_id'])){ ?>
                                <td><?php
									if($access['accept'] == 2){
										echo $languages[$lang]['pending'];
									} elseif($access['accept'] == 1){
										echo $languages[$lang]['accepted'];
									} else {
										echo $languages[$lang]['refused'];
									}
									?></td>

                                <td><?php

									if(! empty($access['acceptedBy'])){
										echo getUserName($access['acceptedBy']);
									} else {
										echo "-";
									}

									?></td>
							<?php } ?>
                            <td style="<?= $style ?>"><?=$access['date']?></td>
                            <td>
								<?php if($access['accept'] == 2) { ?>
                                    <a href="<?php echo $access['id']; ?>" title="declince" class="decl" id="refuseRequest"><i class="fa fa-remove" style="color: #556575;" ></i></a> &nbsp;  &nbsp;
                                    <a href="<?php echo $access['id']; ?>" class="accept" title="accept" id="acceptRequest" family="<?php echo $_SESSION['family_id']; ?>" email="<?php echo $access['email']; ?>" name="<?php echo $access['name']; ?>"><i class="fa fa-check" style="color: #556575;"></i></a>
								<?php } else if($access['accept'] != 2 && checkAdmin($_SESSION['user_id'], $_SESSION['family_id'])){ ?>
                                    <a href="<?php echo $access['id']; ?>" title="delete"  class="deleteAccess"><i class="fa fa-trash" style="color: #556575;" ></i></a>

								<?php } ?>
                            </td>
                        </tr>
					<?php }?>
                </table>
            </div>
            <div class="modal-footer" style="margin: auto;">
                <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="access_close">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="join_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content w-100">
            <div class="modal-header w-100">
                <h5 class="modal-title ml-auto mt-3"><?=trans('join_requests')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body w-100" style="height: 60vh; overflow-y: auto;">
                <table class="customers">
                    <tr>
                        <th>#</th>
                        <th><?=trans('name')?></th>
                        <th><?=trans('email')?></th>
                        <th><?=trans('phone')?></th>
                        <th><?=trans('status')?></th>
						<?php if(isset($_SESSION['user_id']) && checkAdmin($_SESSION['user_id'], $_SESSION['family_id'])){ ?>
                            <th><?=trans('by')?></th>
						<?php } ?>
                        <th><?= trans("date")?></th>
                        <th><?=trans('manage')?></th>
                    </tr>
					<?php
					$count=0;
					$join_requests = FamilyJoinRequest::where(['family_id'=>$_SESSION['family_id']])->orderBy('id', 'desc')->get();
					foreach($join_requests as $join_request){
						$count++;
						$style = ($join_request['status'] == 2)? "color: red;" : "";
						?>
                        <tr>
                            <td><?=$count?></td>
                            <td style="<?= $style ?>"><?=$join_request['name']?></td>
                            <td style="<?= $style ?>"><?=$join_request['email']?></td>
                            <td style="<?= $style ?>"><?= $join_request['phone'] ?></td>
                            <td style="<?= $style ?>"><?php
								if($join_request['status'] == 2){
									echo $languages[$lang]["pending"];
								} else if($join_request['status'] == 1){
									echo $languages[$lang]["accepted"];
								} else if($join_request['status'] == 0){
									echo $languages[$lang]["refused"];
								}

								?></td>
							<?php if(isset($_SESSION['user_id']) && checkAdmin($_SESSION['user_id'], $_SESSION['family_id'])){ ?>
                                <td><?= ($join_request['accepted_by'])? getUserData($join_request['accepted_by'])['name'] : "-" ?></td>
							<?php } ?>
                            <td style="<?= $style ?>"><?= $join_request['date'] ?></td>
                            <td>
								<?php if($join_request['status'] == 2){ ?>
                                    <a href="<?php echo $join_request['id']; ?>" title="accept" id="accept_request"><i class="fa fa-check" style="color: #556575;" ></i></a>
                                    <a href="<?php echo $join_request['id']; ?>" title="refuse" id="refuse_request"><i class="fa fa-remove" style="color: #556575;" ></i></a>
								<?php } else { ?>
                                    <a href="<?php echo $join_request['id']; ?>" title="delete" id="delete_request"><i class="fa fa-trash" style="color: #556575;" ></i></a>
								<?php } ?>
                            </td>
                        </tr>
					<?php }?>
                </table>
            </div>
            <div class="modal-footer" style="margin: auto;">
                <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="join_close">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="invitations_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content w-100">
            <div class="modal-header w-100">
                <h5 class="modal-title ml-auto mt-3"><?php echo $languages[$lang]['invList']; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body w-100" style="height: 60vh; overflow-y: auto;">
                <table class="customers">
                    <tr>
                        <th>#</th>
                        <th><?=$languages[$lang]['name']?></th>
                        <th><?=$languages[$lang]['invitation_type']?></th>
                        <th><?=$languages[$lang]['status']?></th>
                        <th><?=$languages[$lang]['date']?></th>
                        <th><?=$languages[$lang]['responded']?></th>
                        <th><?=$languages[$lang]['responded_at']?></th>
                        <th><?=$languages[$lang]['manage']?></th>
                    </tr>
					<?php
					$count=0;
					$invitations = FamilyInvitation::where(['family_id'=>$_SESSION['family_id']])->orderBy('id', 'desc')->get();
					foreach($invitations as $invitation){
						$count++;
						?>
                        <tr>
                            <td><?=$count?></td>
                            <td><?=$invitation['name']?></td>
                            <td><?=$invitation['type']?></td>
                            <td><?php

								if($invitation['seen'] == 1){
									echo $languages[$lang]['seen'];
								} else {
									echo $languages[$lang]['notSeen'];
								}


								?></td>
                            <td><?=$invitation['date']?></td>
                            <td><?= ($invitation['responded'] == 0)? 'no':'yes'; ?></td>
                            <td><?= (! empty($invitation['responded_at']))? $invitation['responded_at']:'-'; ?></td>
                            <td><a href="<?php echo $invitation['id']; ?>" title="delete" id="deleteInvitation"><i class="fa fa-remove" style="color: #556575;" ></i></a></td>
                        </tr>
					<?php }?>
                </table>
            </div>
            <div class="modal-footer" style="margin: auto;">
                <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="invitations_close">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="history_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content w-100">
            <div class="modal-header w-100">
                <h5 class="modal-title ml-auto mt-3"><?php echo $languages[$lang]['familyHistory']; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body w-100" style="height: 60vh; overflow-y: auto;">
                <table class="customers">
                    <tr>
                        <th>#</th>
                        <th><?=$languages[$lang]['user']?></th>
                        <th><?=$languages[$lang]['action']?></th>
                        <th><?=$languages[$lang]['date']?></th>
						<?php if(isset($_SESSION['user_id']) && checkAdmin($_SESSION['user_id'], $_SESSION['family_id'])){ ?>
                            <th><?=$languages[$lang]['manage']?></th>
						<?php } ?>
                    </tr>
					<?php
					$count=0;
					$history = FamilyHistory::where(['family_id'=>$_SESSION['family_id']])->orderBy('id', 'desc')->get();
					foreach($history as $record){
						$count++;
						?>
                        <tr>
                            <td><?=$count?></td>
                            <td><?php
								if($record['user_id'] == 0){
									echo trans("invited_member");
								} else {
									echo User::find($record['user_id'])->name;
								}
								?></td>
                            <td><?php

								echo $record['action'];

								?></td>
                            <td><?php

								echo $record['date'];

								?></td>
							<?php if(isset($_SESSION['user_id']) && checkAdmin($_SESSION['user_id'], $_SESSION['family_id'])){ ?>
                                <td><a href="<?php echo $record['id']; ?>" title="delete" id="deleteHistory"><i class="fa fa-remove" style="color: #556575;" ></i></a></td>
							<?php } ?>
                        </tr>
					<?php }?>
                </table>
            </div>
            <div class="modal-footer" style="margin: auto;">
                <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="history_close">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="emails_modal" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-lg" role="document"  >
        <div class="modal-content w-100">
            <div class="modal-header w-100">
                <h5 class="modal-title ml-auto mt-3"><?php echo $languages[$lang]['sentEmails']; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body w-100" style="height: 60vh; overflow-y: auto;">
                <table class="customers">
                    <tr>
                        <th>#</th>
                        <th><?=$languages[$lang]['name']?></th>
                        <th><?=$languages[$lang]['email']?></th>
                        <th><?=$languages[$lang]['title']?></th>
                        <th><?=$languages[$lang]['status']?></th>
                        <th><?=$languages[$lang]['date']?></th>
						<?php if(isset($_SESSION['user_id']) &&  $logged_in_user){ ?>
                            <th><?=trans('manage')?></th>
						<?php } ?>
                    </tr>
					<?php
					$count=0;
					$site_mails = SiteMail::where(['family_id'=>"{$_SESSION['family_id']}"])->orderBy('id','desc')->get();
					foreach($site_mails as $site_mail)
					{
						$count++;
						?>
                        <tr>
                            <td><?=$count?></td>
                            <td><?php echo $site_mail['name']; ?></td>
                            <td><?php echo $site_mail['email']; ?></td>
                            <td><?php echo $site_mail['title']; ?></td>
                            <td><?php
								if($site_mail['sent'] == 1){
									echo $languages[$lang]['sent'];
								} else {
									echo $languages[$lang]['notSent'];
								}
								?></td>
                            <td><?php echo $site_mail['date']; ?></td>

							<?php if(isset($_SESSION['user_id']) && $logged_in_user){ ?>
                                <td><a href="<?php echo $site_mail['id']; ?>" title="delete" id="deleteEmail"><i class="fa fa-remove" style="color: #556575;" ></i></a></td>
							<?php } ?>
                        </tr>
					<?php }?>
                </table>
            </div>
            <div class="modal-footer" style="margin: auto;">
                <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="emails_close">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal11" tabindex="-1" role="dialog" style="z-index: 1061 !important;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ml-auto mt-3"><?= trans('delete_email') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p style="text-align: center;"><?= trans('ask_delete_email') ?></p>
                <form method="POST">
                    <input type="hidden" value="" name="deletedEmail" id="deletedEmail">
            </div>
            <div class="modal-footer" style="margin: auto;">
                <button type="submit" class="btn btn-danger" name="deletedEmailSubmit"><?= trans('delete') ?></button>
                <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="close11"><?= trans('close') ?></button>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modal8" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ml-auto mt-3"><?= trans('delete_assistant') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p style="text-align: center;"><?= trans('ask_delete_assistant') ?></p>
                <form method="POST">
                    <input type="hidden" value="" name="deleteAssistant" id="deleteAssistant">
            </div>
            <div class="modal-footer" style="margin: auto;">
                <button type="submit" class="btn btn-danger" name="deleteSubmit"><?= trans('delete') ?></button>
                <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="close3"><?= trans('close') ?></button>
            </div>
            </form>
        </div>
    </div>
</div>
<div id="modal81" class="modal fade" style="z-index: 1061 !important;">
    <div class="modal-dialog modal-lg">
        <!-- Modal content -->
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="email-form-3" method="post">
                <input type="hidden" name="requestedFamily" id="requestedFamily" value="" />
                <input type="hidden" name="requestUser" id="requestUser" value="" />
                <input type="hidden" name="requestedEmail" id="requestedEmail" value="" />
                <input type="hidden" name="requestedName" id="requestedName" value="" />

                <p style="text-align:center;"> <?=$languages[$lang]['expiryDate']?>
                    <input type="date" min="<?=date("Y-m-d")?>" max="<?=date('Y-m-d',strtotime('+90 days'))?>" class="form-input-text no-margin-bottom-lg md-no-margin-lr w-input" maxlength="50" name="expiryDate" placeholder="<?=$languages[$lang]['expiryDate']?>" id="expiryDate" required="" autocomplete="off"/><br />
                    <button type="submit" class="button-primary animated w-inline-block" name="acceptSubmit" style="letter-spacing: 0;margin: 0 auto; text-transform: none;" id="close8"><?=$languages[$lang]['send']?></button>
                </p>
            </form>
        </div>
    </div>
</div>
<div id="myModal2" class="modal fade">
    <!-- Modal content -->
    <div class="modal-content" >
        <span class="close">&times;</span>
        <form id="email-form-3" action="joinus" method="post">
            <input type="hidden" name="newUser" value="do" />
            <p style="text-align:center;"> <?=$langues[$lang]['addusr']?>
                <input type="text" class="form-input-text no-margin-bottom-lg md-no-margin-lr w-input" maxlength="50" name="name" placeholder="<?=$langues[$lang]['name']?>" id="accept" required="" autocomplete="off"/><br />
                <input type="email" class="form-input-text no-margin-bottom-lg md-no-margin-lr w-input" maxlength="50" name="email" placeholder="<?=$langues[$lang]['email']?>" id="accept" required="" autocomplete="off"/><br />
                <button type="submit" class="button-primary animated w-inline-block" style="letter-spacing: 0;margin: 0 auto;"><?=$langues[$lang]['save']?></button>
            </p>
        </form>
    </div>
</div>
<div id="myModal4" class="modal fade">
    <!-- Modal content -->
    <div class="modal-content" >
        <div class="modal-header">
            <h5 class="modal-title ml-auto mt-3"><?php
				echo $_SESSION['submitStatus'];

				?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
			<?php if(isset($_SESSION['message'])){ ?>
                <p><?php echo $_SESSION['message']; ?></p>
			<?php } elseif(isset($_SESSION['errors'])){
				echo "<ul style='list-style: none;'>";

				foreach($_SESSION['errors'] as $error){

					echo "<li>{$error}</li>";

				}


				echo "</ul>";

			} ?>
        </div>
        <div class="modal-footer">
            <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
            <button id="closeModal" class="button-primary animated w-inline-block" style="letter-spacing: 0;margin: 0 auto;"><?=$languages[$lang]['close']?></button>
        </div>
    </div>
</div>
<div id="modal6" class="modal fade">
    <div class="modal-dialog" role="document">
        <!-- Modal content -->
        <div class="modal-content" >



            <div class="modal-header">
                <h5 class="modal-title ml-auto mt-3"><?php

					echo $submitStatus;

					?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

				<?php if(! empty($errors)){

					echo "<ul style='list-style: none;'>";

					foreach($errors as $error){

						echo "<li>{$error}</li>";

					}


					echo "</ul>";

				} else { ?>

                    <p><?php echo $message ?></p>

				<?php } ?>
            </div>
            <div class="modal-footer">

                <button id="close6" class="btn btn-danger" style="letter-spacing: 0;margin: 0 auto;">Close</button>

            </div>


        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="myModal31">
    <div class="modal-dialog modal-lg" role="document" style="">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ml-auto mt-3" ><?= trans('add_file') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">


            </div>
            <div class="modal-footer" style="margin: auto;">
                <button type="submit" class="btn btn-primary" name="submitNewFile"><?= trans('submit') ?></button>
                <button type="button" class="btn hbtn btn-hmuted" data-dismiss="modal" id="close1"><?= trans('close') ?></button>

            </div>
        </div>
    </div>
</div>
<?php $default_profile = $user['image']?asset($user['image']):asset('images/default-user.png')?>
<?php $default_club = $user['image']?asset($user['club_logo']):asset('images/default-club.png')?>
<?php include_once('include/cropModals.php');?>
<?php include"footer.php";?>
<script src="//ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js" type="text/javascript"></script>
<script src="//cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
    $(document).ready(function() {

        $('body').on('click', '#remindAssistant', function(e){
            let user_id = $(this).attr('href');
            $('#assistantToremind').val(user_id);
            $('#remindModal').modal('show');
            e.preventDefault();
        })

        $('body').on('click', '#open_sidebar', function(){
            $(this).hide();
            $('.closebtn').show();
            $('#mySidenav').css('width', '80%');
        })
        $('body').on('click', '.closebtn', function(){
            $('#open_sidebar').show();
            $(this).hide();
            $('#mySidenav').css('width', '0');
        })
        $('body').on('click', '.for_assistants', function(e){
            $('#assistants_modal').modal('show');
            e.preventDefault();
        })
        $('body').on('click', '#assistants_close', function(){
            $('#assistants_modal').modal('hide');
        })
        $('body').on('click', '.for_access', function(e){
            $('#access_modal').modal('show');
            e.preventDefault();
        })
        $('body').on('click', '#access_close', function(){
            $('#access_modal').modal('hide');
        })
        $('body').on('click', '.for_join', function(e){
            $('#join_modal').modal('show');
            e.preventDefault();
        })
        $('body').on('click', '#join_close', function(){
            $('#join_modal').modal('hide');
        })
        $('body').on('click', '.for_invitations', function(e){
            $('#invitations_modal').modal('show');
            e.preventDefault();
        })
        $('body').on('click', '#invitations_close', function(){
            $('#invitations_modal').modal('hide');
        })
        $('body').on('click', '.for_history', function(e){
            $('#history_modal').modal('show');
            e.preventDefault();
        })
        $('body').on('click', '#history_close', function(){
            $('#history_modal').modal('hide');
        })
        $('body').on('click', '.for_emails', function(e){
            $('#emails_modal').modal('show');
            e.preventDefault();
        })
        $('body').on('click', '#emails_close', function(){
            $('#emails_modal').modal('hide');
        })
        $('.flex-column').hide();
        $('.toggle_nav').click(function () {
            let status = $(this).attr('status');
            if(status == 0){
                $(this).attr('status', '1');
                $.ajax({
                    type: 'post',
                    url: 'api/global.php',
                    data: {
                        family_info: <?= $_SESSION['family_id'] ?>
                    },
                    dataType: 'Json',
                    cache: false
                }).done(function(res){
                    if(res.access > 0){
                        $('.access_badge').removeClass('d-none').text(res.access);
                    } else {
                        $('.access_badge').addClass('d-none');
                    }
                    if(res.join > 0){
                        $('.join_badge').removeClass('d-none').text(res.join);
                    } else {
                        $('.join_badge').addClass('d-none');
                    }
                })
            } else {
                $(this).attr('status', '0');
            }
            $('.flex-column').toggle('slow');
        });
        let lang = $('#accountLang').val();
        $('body').on('mouseleave', '#fb', function(){
            let fb = $(this).val();
            if(fb.length > 0 && ! fb.includes('facebook.com')){
                $(this).addClass('input-error');
                $('.fb_error').removeClass('d-none');
            } else {
                $(this).removeClass('input-error');
                $('.fb_error').addClass('d-none');
            }
        })
        $('body').on('mouseleave', '#twitter', function(){
            let twitter = $(this).val();
            if(twitter.length > 0 && ! twitter.includes('twitter.com')){
                $(this).addClass('input-error');
                $('.twitter_error').removeClass('d-none');
            } else {
                $(this).removeClass('input-error');
                $('.twitter_error').addClass('d-none');
            }
        })
        $('body').on('mouseleave', '#instagram', function(){
            let instagram = $(this).val();
            if(instagram.length > 0 && ! instagram.includes('instagram.com')){
                $(this).addClass('input-error');
                $('.instagram_error').removeClass('d-none');
            } else {
                $(this).removeClass('input-error');
                $('.instagram_error').addClass('d-none');
            }
        })
        $('body').on('mouseleave', '#snapchat', function(){
            let snapchat = $(this).val();
            if(snapchat.length > 0 && ! snapchat.includes('snapchat.com')){
                $(this).addClass('input-error');
                $('.snapchat_error').removeClass('d-none');
            } else {
                $(this).removeClass('input-error');
                $('.snapchat_error').addClass('d-none');
            }
        })
        $('body').on('click', '#delete_request', function(e){
            e.preventDefault();

            let request = $(this).attr('href');
            $('#add_members').val(request);

            $('#modal60').modal('show');
        })
        $('body').on('click', '#accept_request', function(e){
            let request = $(this).attr('href');
            $('#add_request').val(request);

            $('#modal50').modal('show');

            e.preventDefault();
        })
        $('body').on('click', '#refuse_request', function(e){
            let request = $(this).attr('href');
            $('#add_members_request').val(request);

            $('#modal555').modal('show');

            e.preventDefault();
        })
        $('body').on('click', '#confirm_refuse_request', function(){
            $('#modal555').modal('hide');

            let request = $('#add_members_request').val();
            let logged_user = $('#logged_user').val();

            $.ajax({
                type: 'post',
                url: 'api/global.php',
                data: {
                    refuse_request_to_add_members: request,
                    user_id: logged_user,
                    lang: lang
                },
                dataType: 'Text',
                cache: false
            }).done(function(res){
                Swal.fire({
                    title: 'Success',
                    icon: 'success',
                    text: `${res}`,
                    confirmButtonText: 'Ok'
                }).then(function () {
                    location.reload();
                });
            })

        })
        $('#emailConfirmation').mouseleave(function(){

            let emailConfirmation = $(this).val();
            let email = $('#email').val();
            let lang = $('#accountLang').val();

            if(emailConfirmation.length > 0 && email.length > 0){

                if(emailConfirmation !== email){
                    $(this).addClass('input-error');
                    $('.email_error').removeClass('d-none');
                } else {
                    $(this).removeClass('input-error');
                    $('.email_error').addClass('d-none');
                    // $.ajax({
                    //     type: 'post',
                    //     url: 'api/global.php',
                    //     data: {
                    //         userEmail: email,
                    //         lang: lang,
                    //         bla: 1
                    //     },
                    //     dataType: 'Text',
                    //     cache: false
                    // }).done(function(res){
                    //     if(res.length > 0){
                    //         alert(res);
                    //     }
                    // })

                }
            }

        });
        let counter = 1;
        $('#plan').change(function(){
            let plan = $(this).val();
            let lang = $('#accountLang').val();
            if(plan == ''){
                let message = "My family photos , collectibles and Holding";
                if(lang.includes('ar')){
                    message = "باقة صور ومقتنيات ومستندات العائلة";
                }
                $('#planDetails').empty().append(message);
                $('#mostpopular').attr('disabled', true);
                $("#mostpopular").val('');

                return false;
            }

            $.ajax({
                type: 'POST',
                url: 'api/global.php',
                data: {
                    plan: plan,
                    lang: lang
                },
                dataType: 'Json',
                cache: false
            }).done(function(res){
                $('#planDetails').text(`

                ${res.members_prompt} ${res.members} ,
                ${res.media_prompt} ${res.media}

            `);
                if(res.name_en.includes('Platinum')){
                    $('#mostpopular').attr('disabled', false);
                } else {
                    $('#mostpopular').attr('disabled', true);
                    $("#mostpopular").val('');

                    if(counter < 3){
                        Swal.fire({
                            title: 'Notice',
                            width: 400,
                            text: "You can't add your family to Most Popular Families unless you choose Platinum Plan .",
                            icon: 'info',
                            confirmButtonText: 'Ok'
                        })
                        counter++;
                    }
                }
            })

        })
        $('body').on('click', '.deleteAccess', function(event){
            let access = $(this).attr('href');
            $('#accessRequest').val(access);
            $('#modal55').modal('show');
            event.preventDefault();
        })
        $('body').on('click', '#close55', function(){
            $('#modal55').modal('hide');
        })
        $('body').on('click', '#close50', function(){
            $('#modal50').modal('hide');
        })
        $('body').on('click', '#close555', function(){
            $('#modal555').modal('hide');
        })
        $('body').on('click', '#close60', function(){
            $('#modal60').modal('hide');
        })
        $('body').on('click', '#refuseRequest', function(e){

            let id = $(this).attr('href');
            let lang = $('#accountLang').val();
            // alert(id)
            $.ajax({
                type: 'POST',
                url : 'api/global.php',
                data: {
                    id: id,
                    ajax: 0,
                    lang: lang
                },
                dataType: 'Text',
                cache: false
            }).done(function(res){
                Swal.fire({
                    title: 'success',
                    icon: 'success',
                    text: `${res}`,
                    confirmButtonText: 'Ok'
                }).then(function () {
                    location.reload();
                });
            })

            e.preventDefault();

        })
        $('body').on('click', '#acceptRequest', function(e){
            let id = $(this).attr('href');
            let family = $(this).attr('family');
            let name = $(this).attr('name');
            let email = $(this).attr('email');
            let lang = $('#accountLang').val();

            $('#requestedEmail').val(email);
            $('#requestedName').val(name);
            $('#requestedFamily').val(family);
            $('#requestUser').val(id);
            $('#modal81').modal('show');

            e.preventDefault();
        })
        $('body').on('click', '#close81', function(){
            $('#modal81').modal('hide');
        })
        let error = $('#error').val();
        if(error.length > 0){
            Swal.fire({
                title: 'Error !',
                icon: 'error',
                text: `${error}`,
                confirmButtonText: 'Ok'
            })
        }
        let success = $('#success').val();
        // if(success.length > 0){
        //     alert(success);
        // }
        $('body').on('click', '#deleteInvitation', function(e){
            let invitation = $(this).attr('href');
            $('#deleteInv').val(invitation);
            // alert(user);
            $('#modal9').modal('show');

            e.preventDefault();
        })
        $('body').on('click', '#deleteHistory', function(e){
            let history = $(this).attr('href');
            $('#deleteHis').val(history);
            // alert(history);
            $('#modal10').modal('show');

            e.preventDefault();
        })
        $('body').on('click', '#close10', function(){
            $('#modal10').modal('hide');
        })
        $('body').on('click', '#deleteEmail', function(e){
            let email = $(this).attr('href');
            $('#deletedEmail').val(email);
            // alert(history);
            $('#modal11').modal('show');

            e.preventDefault();
        })
        $('body').on('click', '#close11', function(){
            $('#modal11').modal('hide');
        })
        $('body').on('click', '#askDelete', function(e){
            let user = $(this).attr('href');
            $('#deleteAssistant').val(user);
            // alert(user);
            $('#modal5').modal('show');

            e.preventDefault();
        })
        $('body').on('click', '#close3', function(){
            $('#modal5').modal('hide');
        })
        $('body').on('click', '#close9', function(){
            $('#modal9').modal('hide');
        })
        let afterSubmit = $('#afterSubmit').val();
        if(afterSubmit.length > 0){
            $('#modal6').modal('show');
        }
        $('body').on('click', '#close6', function(){
            $('#modal6').modal('hide');
        })
        let key = $('#oldKey').val();
        $('#key').val(key);
        let plan = $('#oldPlan').val();
        $('#planDetails').append(`
            <option>${plan}</option>
        `);
        $('.addNew').click(function(e){
            $('#myModal31').modal('show');
        })
        $('#plan').change(function(){
            let plan = $(this).val();
            let lang = $('#accountLang').val();
            if(plan == 0){
                let message = "My family photos , collectibles and Holding";
                if(lang.includes('ar')){
                    message = "باقة صور ومقتنيات ومستندات العائلة";
                }
                // $('#planDetails').empty().append(message);
                $('#planDetails').empty();
                return false;
            }

            $.ajax({
                type: 'POST',
                url: 'api/global.php',
                data: {
                    plan: plan,
                    lang: lang
                },
                dataType: 'Json',
                cache: false
            }).done(function(res){
                $('#planDetails').text(`

                    ${res.members_prompt} ${res.members} ,
                    ${res.media_prompt} ${res.media}

                `);
            })

        })
        $('#country').change(function(){
            let country = $(this).val();
            // alert(country);
            $.ajax({
                type: 'POST',
                url: 'api/global.php',
                data: {
                    country: country,
                    ajax: 'key'
                },
                dataType: 'Text',
                cache: false
            }).done(function(res){
                // alert(res);
                // console.log(typeof(res));
                $('#key').val(res);
            })
        })
        $('body').on('click', '#closeModal', function(){
            $('#myModal3').modal('hide');
            <?php
            unset($_SESSION['submitStatus']);
            unset($_SESSION['message']);
            unset($_SESSION['errors']);

            ?>
        })

        $(".show-password").click(function(){
            const input = $(this).parent().prev()[0];
            if(input.type === "text") input.type = "password";
            else input.type = "text";
        })

        $("input[type=password]").keyup(function(){
            const value = $(this).val();
            if(value) {
                $(this).next().removeClass("d-none")
            } else {
                $(this).next().addClass("d-none")
            }
        })

            $("[name='gender']").change(function() {
                const newVal = this.value;
                const oldVal = newVal === "Female" ? "Male" : "Female"
                
                Swal.fire({
                    title: 'Warning!',
                    icon: 'warning',
                    text: `Are you sure you want to change a gender?`,
                    showCancelButton: true,
                }).then((data) => {
                    if(data.isConfirmed)
                        this.value = newVal;
                    else
                        this.value = oldVal;
                })
            })
    });
</script>
<?php include_once('include/cropScripts.php');?>
