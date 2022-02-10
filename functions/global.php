<?php
function check_wifes($wifes_ids){
	return User::whereIn('user_id', explode(',', $wifes_ids))->count();
}
function check_first_father_children($first_father_id){
	$children = User::where(['parent_id' => $first_father_id])->get(['user_id', 'mother_id']);
	$check = 0;
	foreach($children as $child){
		if($child->mother_id == 0 || ($child->mother_id != 0 && check_user_exists($child->mother_id) == 0)){
			$check++;
		}
	}

	return $check;
}
function check_user_exists($user_id){
	return User::where('user_id', $user_id)->count();
}
function get_join_family_requests($request_id){
	return FamilyJoinRequest::find($request_id);
}
function getUserName($user_id){
	return User::find($user_id)->name;
}
function creator_daily_report($family_id){
	$report = [];
	$report['access_requests'] = FamilyAccess::today()->pending()
											->where(['family_id'=>$family_id])
											->count();
	
	$report['join_requests'] = 	FamilyJoinRequest::today()->pending()
												->where(['family_id'=>$family_id])
												->count();
												
	$report['invitations'] = 	FamilyInvitation::today()
												->where(['family_id'=>$family_id])
												->count();
	
	$report['media'] = 	FamilyMedia::today()
									->where(['family_id'=>$family_id])
									->count();
												
	$report['added_members'] = User::today()->memberOnly()
								   ->where(['family_id'=>$family_id])
								   ->count();
	
	$report['added_assistants'] = User::today()->assistantOnly()
								   ->where(['family_id'=>$family_id])
								   ->count();

	$report['added_assistants_and_members'] = User::today()->memberAndAssistant()
								   ->where(['family_id'=>$family_id])
								   ->count();

	return $report;

}
function get_home_notifications($family_id){
	$family = Family::find($family_id);
	$now = date('Y-m-d');
	$six_months_ago = date('Y-m-d', strtotime('-6 months'));
	return $family->users()->whereBetween('date', [$six_months_ago, $now])->count() +
			$family->media()->whereBetween('date', [$six_months_ago, $now])->count();
}
function familyNotifications($family_id){
    $family = Family::where(['id'=>$family_id])->first();
	return $family ? FamilyAccess::where(['family_id'=>$family_id])->where(['accept'=>2])->count() +
			FamilyJoinRequest::where(['family_id'=>$family_id])->where(['status'=>2])->count() + 
			Family::find($family_id)->assistants()->whereNull('user_name')->whereNull('user_password')->count() : 0;
}
function checkEditedUser($loggedUser, $editedUser){
	$user = new User;

	$editedUserRole = $user->find($editedUser)->role;

	$loggedUserRole = $user->find($loggedUser)->role;

	if ($editedUserRole != 'user' && $loggedUserRole != 'creator') {
		return true;
	} else {
		return false;
	}
}
function get_family_invitation($invitation_id){

	$invitation_id = ((int) base64_decode($invitation_id)) - 4548;

	return FamilyInvitation::find($invitation_id);

}
function get_family_request($request_id){
	$request_id = ((int) base64_decode($request_id)) - 8575;

	return FamilyRequest::find($request_id);
}
function getMemberParents($user_id){
	return User::find($user_id, ['mother_id', 'parent_id']);
}
function getFatherFromMother($mother_id){
	return User::male()->whereRaw("find_in_set($mother_id, wife)")->select(['user_id'])->first()->user_id;

}
function checkUserFamily($admin_id, $user_id){
	$user = new User;

	$adminFamily = $user->find($admin_id)->family_id;

	$userFamily = $user->find($user_id)->family_id;

	if ($adminFamily != $userFamily) {
		return true;
	} else {
		return false;
	}
}
function getUserChildren($user_id, $gender){
	$user = new User;

	if ($gender == 'parent') {
		return $user->where('parent_id', $user_id)->select(['user_id', 'name'])->orderBy('date_of_birth')->get();
	} elseif ($gender == 'mother') {
		return $user->where('mother_id', $user_id)->select(['user_id', 'name'])->orderBy('date_of_birth')->get();
	}
}
function getSiblings($user_id, $family_id, $father_id, $mother_id){
	if($father_id == 'alpha' || $father_id == 'alpha_2'){
		return User::where('family_id', $family_id)->where('parent_id' , 'like', '%alpha%')->where('user_id', '!=', $user_id)->get();
	} else {
		return ($father_id != 0)? User::where('user_id', '!=', $user_id)->where('parent_id', $father_id)->get() : [];
		if($father_id != 0 && getUserData($father_id)['outer_husband'] == 0){
			return User::where('user_id', '!=', $user_id)->where('parent_id', $father_id)->get();
		} elseif($father_id != 0 && getUserData($father_id)['outer_husband'] == 1) {
			return User::where('user_id', '!=', $user_id)->where('mother_id', $mother_id)->get();
		}
	}
}
function checkFemaleMaritalStatus($female_id){

	return User::male()->whereRaw("find_in_set($female_id, wife)")->count();

}
function get_member_arrangement($family_id, $user_id){
	return Family::find($family_id)->users()->member()->where('user_id', '<=', $user_id)->count();
}
function getNationalityName($nationality_id){
	return Nationality::find($nationality_id)->name;
}
function getCountryName($country_id){
	return Country::find($country_id)->name_en;
}
function getCountry($country_id){
	return Country::find($country_id);
}
function getwifesNames($wifes){
	return User::whereIn('user_id', explode(",", $wifes))->select(['user_id AS id', 'name'])->get();
}
function getUseName($user_id){
	return User::find($user_id)->name;
}
function checkFamilyStatus($familyId){
	return Family::find($familyId)->status;
}
function getFamilyCountries($familyId){
//	$result = $con->query("SELECT * FROM `countries` WHERE id in (select country_id from users where family_id='$familyId' group by country_id)");
    return Country::whereIn('id', array_unique(Family::find($familyId)->users()->pluck('country_id')->toArray()))->get();
//	$data = [];
//	if(mysqli_num_rows($result) > 0){
//		while($row = mysqli_fetch_assoc($result)){
//			array_push($data, $row);
//		}
//	}
//
//	return $data;
}
function getFamilyImage($family_id){
	return User::creator()->where('family_id', $family_id)->first()->image;
}
function showWifes($wifes){
	$wifes = explode(',', $wifes);
	foreach($wifes as $wife){
//		$con->query("update users set display='1' where user_id='$wife'");
		// User::find($wife)->update(['display'=>1]);
		$user = User::where(['user_id' => $wife])->first();
		if($user){
			$user->update(['display' => 1]);
		}
	}
}
function showOuterHusbands($family_id, $user){
//	$result2 = $con->query("select * from users where wife='$user' and family_id='$family_id' and outer_husband='1' order by user_id desc");
	$result2 = Family::find($family_id)->users()->where(['wife'=>$user])->where(['outer_husband'=>1])->get();
	$x = 1;
	foreach($result2 as $row2){
		$userId = $row2['user_id'];
		if($x == 1){
//			$con->query("update users set display='1' where user_id='$userId'");
			User::find($userId)->update(['display'=>1]);
		} else {
//			$con->query("update users set display='0' where user_id='$userId'");
			User::find($userId)->update(['display'=>1]);
		}
		showChildren($user, 'Female');
		$x++;
	}

}
function showChildren($user, $gender){
//	$sql = "select * from users where";
    $result3 = new User;
	if($gender == 'Male'){
//		$sql .= " parent_id='$user'";
		$result3 = $result3->where(['parent_id'=>$user]);
	} else {
//		$sql .= " mother_id='$user'";
		$result3 = $result3->where(['mother_id'=>$user]);
	}

	$result3 = $result3->orderBy('date_of_birth', 'ASC')->get();
		foreach($result3 as $row3){
			$userId = $row3['user_id'];
//			$con->query("update users set display='1' where user_id='$userId'");
			User::find($userId)->update(['display'=>1]);
			if($row3['gender'] == 'Male'){
				if($row3['wife'] != '0' && $row3['outer_husband'] != '1'){
					showWifes($row3['wife']);
				}
				showChildren($row3['user_id'], $row3['gender']);

			} elseif($row3['gender'] == 'Female'){

				$hasOuterHusband = hasOuterHusband($row3['family_id'], $row3['user_id'], 0);
				if($hasOuterHusband > 0){
					showOuterHusbands($row3['family_id'], $row3['user_id']);
				} else {
					showChildren($row3['user_id'], $row3['gender']);
				}

			}

		}
}
function hideWifes($wifes){
	$wifes = explode(',', $wifes);
	foreach($wifes as $wife){
//		$con->query("update users set display='2' where user_id='$wife'");
		$user = User::where(['user_id' => $wife])->first();
		if($user){
			$user->update(['display' => 2]);
		}
	}
}
function hideOuterHusbands($family_id, $user){
//	$result2 = $con->query("select * from users where wife='$user' and family_id='$family_id' and outer_husband='1'");
	$result2 = Family::find($family_id)->users()->where(['wife'=>$user])->where(['outer_husband'=>1])->get();
	foreach($result2 as $row2){
		$userId = $row2['user_id'];
//		$con->query("update users set display='2' where user_id='$userId'");
        User::find($userId)->update(['display'=>2]);
		hideChildren($user, 'Female');
	}

}
function hideChildren($user, $gender){
//	$sql = "select * from users where";
    $result3 = new User;
	if($gender == 'Male'){
//		$sql .= " parent_id='$user'";
		$result3 = $result3->where(['parent_id'=>$user]);
	} else {
//		$sql .= " mother_id='$user'";
		$result3 = $result3->where(['mother_id'=>$user]);
	}

	$result3 = $result3->get();
		foreach($result3 as $row3){
			$userId = $row3['user_id'];
//			$con->query("update users set display='2' where user_id='$userId'");
			User::find($userId)->update(['display'=>2]);
			if($row3['gender'] == 'Male'){

				if($row3['wife'] != '0' && $row3['outer_husband'] != '1'){
					hideWifes($row3['wife']);
				}

				hideChildren($row3['user_id'], $row3['gender']);

			} elseif($row3['gender'] == 'Female'){

				$hasOuterHusband = hasOuterHusband($row3['family_id'], $row3['user_id'], 0);
				if($hasOuterHusband > 0){
					hideOuterHusbands($row3['family_id'], $row3['user_id']);
				} else {
					hideChildren($row3['user_id'], $row3['gender']);
				}

			}

		}


}
function get_delete_request($request_id, $family_id){
	return FamilyDelete::where(['id' => $request_id, 'family_id' => $family_id])->first();
}
function deleteWifes($wifes){
	User::destroy(explode(',', $wifes));
}
function deleteOuterHusbands($family_id, $wife_id){

	$husbands = User::where(['wife' => $wife_id, 'family_id' => $family_id, 'outer_husband' => '1'])->get();
	foreach($husbands as $husband){
		User::destroy($husband->user_id);
		deleteChildren($wife_id, 'Female');
	}

}
function deleteChildren($user, $gender){
	$sql = "select * from users where";
    $result3 = new User;
	if($gender == 'Male'){
//		$sql .= " parent_id='$user'";
		$result3 = $result3->where(['parent_id'=>$user]);
	} else {
//		$sql .= " mother_id='$user'";
		$result3 = $result3->where(['mother_id'=>$user]);
	}

	$result3 = $result3->get();
		foreach($result3 as $row3){
			$userId = $row3['user_id'];
//			$con->query("delete from users where user_id='$userId'");
			User::find($userId)->delete();
			if($row3['gender'] == 'Male'){
				if($row3['wife'] != '0' && $row3['outer_husband'] != '1'){
					deleteWifes($row3['wife']);
				}
				deleteChildren($row3['user_id'], $row3['gender']);
			} elseif($row3['gender'] == 'Female'){
				$hasOuterHusband = hasOuterHusband($row3['family_id'], $row3['user_id'], 0);
				if($hasOuterHusband > 0){
					deleteOuterHusbands($row3['family_id'], $row3['user_id']);
				} else {
					deleteChildren($row3['user_id'], $row3['gender']);
				}
			}
		}
}
function getUserWifes($userId, $id, $wifes, $display, $call_for_first_father = FALSE){
	if($wifes != 0){
		$wifes = explode(',', $wifes);
		echo "<ul>";
		if($display == 1){
			$hiddenUsers = checkHiddenParents($id, $userId, 'Male');
			if(count($hiddenUsers) > 0){
				foreach($hiddenUsers as $hiddenUser){
					echo "<li id='{$hiddenUser}'>";
					echo "<a class='cell hidden-node' href='{$hiddenUser}'></a>";
					echo getChildren($userId, $id, $hiddenUser, 1);
					echo "</li>";
				}
			}
		}
		foreach ($wifes as $wife) {
//			$sql = "select user_id, image, name from users where user_id='$wife'";
			$wife_instance = User::where(['user_id'=>$wife]);
			if($display == 1){
				$wife_instance = $wife_instance->active();
//				$sql .= " and display='1'";
			}
			$res2 = $wife_instance->get();
			if (count($res2) > 0) {
				$r2 = $res2->first();
					?>
                    <li id="<?= $r2['user_id']; ?>">
                        <a href="<?php echo $r2['user_id']; ?>" class="cell" style="background-color: pink !important;" data-title="wife">
                            <img src="<?=$r2['image']?thumb($r2['image']):asset('images/default-user.png')?>" height="90" width="90" loading="lazy">
                            <figcaption><?php echo $r2['name']; ?></figcaption>

                        </a>
						<?php
						echo getChildren($userId, $id, $wife, $display);
						?>
                    </li>
					<?php
			}
		}
		if($call_for_first_father){
			echo getChildren($userId, $id, 0, $display, TRUE);
		}
		echo "</ul>";
	}
}
function getChildren($user, $id, $wife, $display, $call_for_first_father = FALSE){
    $users = Family::find($id)->usersOrderByDOB()->where(['parent_id'=>$user])->where(['mother_id'=>$wife])->get();
	if (count($users) >= 1) {
		echo (! $call_for_first_father)? "<ul>" : "";
		foreach ($users as $r) {
			$userId = $r['user_id'];
			$wifes = $r['wife'];
			$status = $r['display'];
			?>
            <li id="<?= $userId ?>">
				<?php if(($display == 1 && $status == 1) || $display == 0){ ?>
                    <a href="<?php echo $userId; ?>" class="cell">
                        <img src="<?=$r['image']?thumb($r['image']):asset('images/default-user.png')?>" height="90" width="90" loading="lazy">
                        <figcaption><?php echo $r['name']; ?></figcaption>

                    </a>
				<?php } else if($display == 1 && $status == 0){ ?>
                    <a class='cell hidden-node' href='<?= $userId ?>'></a>
				<?php } ?>
				<?php
				if($r['gender'] == 'Female'){
					$outerHusband = hasOuterHusband($id, $userId, $display);
					if($outerHusband > 0){
						echo getOuterHusband($id, $userId, $display);
					}
				} else {
					echo getUserWifes($userId, $id, $wifes, $display);
				}

				?>
            </li>

			<?php
		}
		echo (! $call_for_first_father)? "</ul>" : "";
	}
}
function getOuterHusband($id, $wife, $display){
//	$sql = "select * from users where wife='$wife' and `family_id`='$id' and `outer_husband`='1'";
	$users = Family::find($id)->users()->where(['wife'=>$wife])->where(['outer_husband'=>1]);
	if($display == 1){
	    $users = $users->active();
//		$sql .= " and display='1'";
	}
	$result = $users->get();
	if(count($result) > 0){
		echo "<ul>";
		if($display == 1){
			$hiddenUsers = checkHiddenParents($id, $wife, 'Female');
			if(count($hiddenUsers) > 0){
				// var_dump($hiddenUsers); die();
				foreach($hiddenUsers as $hiddenUser){
					echo "<li id='{$hiddenUser}'>";
					echo "<a class='cell hidden-node' href='{$hiddenUser}'></a>";
					echo getChildren($hiddenUser, $id, $wife, 1);
					echo "</li>";
				}
			}
		}
		foreach($result as $row3){
			$userId = $row3['user_id'];
			?>
            <li id="<?= $userId ?>">
                <a href="<?php echo $userId; ?>" class="cell" style="background-color: green !important;" data-title="husband">
                    <img src="<?=$row3['image']?thumb($row3['image']):asset('images/default-user.png')?>" height="90" width="90" loading="lazy">
                    <figcaption><?php echo $row3['name']; ?></figcaption>
                </a>
				<?php
				echo getChildren($userId, $id, $wife, $display);
				?>
            </li>
			<?php
		}
		echo "</ul>";
	}
}
function hasOuterHusband($id, $wife, $display){
//	$sql = "select * from users where wife='$wife' and `family_id`='$id' and `outer_husband`='1'";
    $users = Family::find($id)->users()->where(['wife'=>$wife])->where(['outer_husband'=>1]);
	if($display == 1){
//		$sql .= " and display='1'";
		$users = $users->active();
	}
	return $users->count();

}
function checkHiddenParents($family, $user, $gender){
	$users = [];
	if($gender == 'Male'){
		$user_instance = User::find($user);
		$wifes = explode(',', $user_instance['wife']);
		foreach($wifes as $wife){
			$wife_instance = User::where(['user_id'=>$wife])->notactive()->first();
			if($wife_instance) array_push($users, $wife_instance['user_id']);
		}
	} elseif($gender == 'Female'){
//		$result3 = $con->query("select user_id from users where wife='$user' and `family_id`='$family' and `outer_husband`='1' and display='0'");
		$user_instance = Family::find($family)->users()->where(['wife'=>$user])->notactive()->where(['outer_husband'=>1])->first();
		if($user_instance){
				array_push($users, $user_instance['user_id']);
		}
	}
	return $users;
}
function checkUserAccessAbility($id){
//	$result = $con->query("select expire_date from familyAccess where id='$id'");
	$row = FamilyAccess::find($id);
	return $row['expire_date'];
}
function getFamilyName($id){
	return Family::find($id)->name_en;
}
function add_user($userName, $password, $name, $country, $phone, $email, $imageName, $role, $kunya, $occupation, $gender, $DOB, $nationality, $facebook, $twitter, $instagram, $snapchat, $club_name, $interests, $about){
//	$con->query("insert into users values (null, 'alpha', '$name', '$userName', '$password', '$email', '$phone',
//                          '$imageName', Null, '-1', '$country', '$role', '0', '0', '0', '0', '0', '0', '0', '0',
//                          '" . date('Y-m-d') . "', '$gender', '0', '1', '0', null, '$occupation', '$kunya',
//                           '$DOB', '$nationality', '$facebook', '$twitter', '$instagram', '$snapchat', '$interests',
//                            '$club_name', '0', null, '0', '1', null, '$about')") or die(mysqli_error($con));
    $user = User::create(['parent_id'=>'alpha', 'name'=>$name,'user_name'=>$userName, 'user_password'=>password_hash($password, PASSWORD_DEFAULT),
            'email'=>$email, 'phone'=>$phone,'image'=>$imageName,'family_id'=>'-1','country_id'=>$country,'role'=>$role,
            'date'=>date('Y-m-d'), 'gender'=>$gender,'member'=>1,'occupation'=>$occupation, 'kunya'=>$kunya,
            'date_of_birth'=>$DOB, 'nationality'=>$nationality,'facebook'=>$facebook, 'twitter'=>$twitter,'instagram'=>$instagram,
            'snapchat'=>$snapchat, 'interests'=>$interests,'club_name'=>$club_name,'display'=>1, 'about'=>$about]);
	return $user->user_id;
}
function add_family($fArName, $fEnName, $fArDesc, $fEnDesc, $fStatus, $country, $plan, $mostPopular, $add_user, $display=null){
	$display=$display?:'1';
//	$con->query("insert into family values (null, '$fArName', '$fEnName', '$fArDesc', '$fEnDesc', '$country', '$plan',
//                           '$fStatus','$mostPopular', '$display', '" . date('Y-m-d') . "', '$add_user', null)") or die(mysqli_error($con));
	
    $family = Family::create(['name_ar'=>$fArName, 'name_en'=>$fEnName, 'desc_ar'=>$fArDesc, 'desc_en'=>$fEnDesc,
            'country_id'=>$country,'plan_id'=>$plan, 'status'=>$fStatus, 'mostpopular'=>$mostPopular, 'display'=>$display,
            'date'=>date('Y-m-d'), 'user_id'=>$add_user]);
	return $family->id;
}
function checkUserNameExists($name, $id){
	$user = User::where(['user_name'=>$name]);
	if ($id != 0) {
		$user = $user->where('user_id', '!='. $id);
	}
	return  $user->first() ? true : false;
}
function checkFamilyExists($fArName, $id){
//	$sql = "select * from family where `name_ar`='$fArName'";
	$family = Family::where(['name_ar'=>$fArName]);
	if ($id != 0) {
//		$sql .= " and `id` != '$id'";
		$family = $family->where('id', '!=', $id);
	}
	return $family->first() ? true : false;
}
function checkAdmin($user, $family){
	$creator = Family::find($family)->user_id;
	if ($user == $creator) {
		return true;
	}
}
function get_pending_access_requests($family_id){
//	$query = $con->query("select count(*) as count from familyAccess where `family_id`='$family_id' and `accept`='2'");
//	$row = mysqli_fetch_assoc($query);

	return FamilyAccess::where(['family_id'=>$family_id])->where(['accept'=>2])->count();

}
function get_pending_join_requests($family_id){
//	$query = $con->query("select count(*) as count from join_family_requests where `family_id`='$family_id' and `status`='2'");
//	$row = mysqli_fetch_assoc($query);

	return FamilyJoinRequest::where(['family_id'=>$family_id])->where(['status'=>2])->count();

}
function checkLoggedUser($user, $family){
//	$query = $con->query("select user_id from users where `family_id`='$family' and `role`!='user'");
	$query = Family::find($family)->users()->where('role', '!=', 'user')->get();
		foreach ($query as $rowQuery) {
			if ($user == $rowQuery['user_id']) {
				return true;
			}
		}
}
function checkEmailExists($email, $id){
//	$sql = "select * from users where `email`='$email'";
	$query = User::where(['email'=>$email]);
	if ($id != 0) {
//		$sql .= " and `user_id` != '$id'";
		$query = $query->where(['user_id'=>$id]);
	}
	$query = $query->first();
    return $query ? true : false;
//	if (mysqli_num_rows($query) >= 1) {
//		return true;
//	}
}
function countFamilyUsers($family){
	$data = [];
//	$query = $con->query("select * from users where `family_id`='$family' and member!='0'");
	$query = Family::find($family)->users()->member();
	$data['count'] = $query->count();
	$data['users'] = $query->get()->toArray();
//	while ($row = mysqli_fetch_assoc($query)) {
//		$user = [];
//
//		$user['user_id'] = $row['user_id'];
//		$user['name'] = $row['name'];
//
//		array_push($data['users'], $user);
//	}
	return $data;
}
function checkFamilyPlanForMembers($family, $usersNum){
//	$result = $con->query("select members from plans inner join family on family.id='$family' and family.plan_id = plans.id");
    $plan = Family::find($family)->plan;
//	$row = mysqli_fetch_assoc($result);
	$members = $plan['members'];
	if (($members - $usersNum) < 1) {
		return true;
	}
	return false;
}
function availableMembers($family){
	$count = countFamilyUsers($family)['count'];
//	$query = $con->query("select members from plans inner join family on family.id='$family' and family.plan_id = plans.id");
	$plan = Family::find($family)->plan;

	$diff = $plan['members'] - $count;
	return $diff;
}
function checkPlan($family, $familyType){
//	$query = $con->query("select * from plans inner join family on family.id='$family' and family.plan_id = plans.id");
	$row = Family::find($family)->plan;;

	if ($familyType == "Media") {
//		$result = $con->query("select size from familyMedia where family_id='$family'");
//		while($sizeRow = mysqli_fetch_assoc($result)){
//			$sum += $sizeRow['size'];
//		}
		$sum = Family::find($family)->media()->sum('size');
//		$result2 = $con->query("select * from plans inner join family on family.id='$family' and family.plan_id = plans.id");
//		$rowPlan = mysqli_fetch_assoc($result2);
		return ["media" => $row['media'], "sum" => $sum];
	} elseif ($familyType == "Members") {
//		$result = $con->query("select * from users where family_id='$family' and users.role != 'creator'");
		$count = Family::find($family)->users()->where('role', '!=', 'creator')->count();
		if ($count == $row['members']) {
			return true;
		}
	}
	return false;
}
function getFamilyData($id){
	return Family::find($id);
}
function check_if_family_deleted($id){
	return Family::find($id)->deleted;
}
function getUserData($id){
	return User::find($id);
}
function GetDefaultImage($src = false, $DefaultImage = false, $return_data_type = true){
	$img_return = $DefaultImage;
	if (empty($src) && empty($DefaultImage)) {
		return $src;
	}
	$file_headers = @get_headers($src);
	if (!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
		return ($return_data_type == true) ? $DefaultImage : false;
	} else {
		return ($return_data_type == true) ? $src : true;
	}
}
