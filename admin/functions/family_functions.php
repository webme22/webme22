<?php

include("languages.php");

// $lang = isset($_GET['lang'])? $_GET['lang'] : 'en';
// echo $lang; die();

if(isset($_GET['get_info_for_charts'])){
    include_once(__DIR__."/../connection.php");

    $data = [];
    $data['visitors_this_year'] = $data['unique_visitors_this_year'] =
    $data["users_all_year"] = $data["family_all_year"] = [
        'January' => 0, 
        'February' => 0, 
        'March' => 0, 
        'April' => 0, 
        'May' => 0, 
        'June' => 0, 
        'July' => 0, 
        'August' => 0, 
        'September' => 0, 
        'October' => 0, 
        'November' => 0, 
        'December' => 0
    ];
    $data['sessions_countries'] = [];
    $this_year = date('Y');

    // Visitors this year
    $query_select = $con->query("SELECT MONTHNAME(created_at) MONTH, COUNT(*) COUNT FROM `visitors_info` WHERE YEAR(created_at)='$this_year' GROUP BY MONTHNAME(created_at)");
    while($row_select = mysqli_fetch_array($query_select)){
        if(array_key_exists($row_select['MONTH'], $data['visitors_this_year'])){
            $data['visitors_this_year'][$row_select['MONTH']] = $row_select['COUNT'];
        }
    }

    // unique visitors this year
    $query_select = $con->query("SELECT MONTHNAME(created_at) MONTH, COUNT(*) COUNT FROM `visitors_info` WHERE YEAR(created_at)='$this_year' GROUP BY MONTHNAME(created_at), `ip`");
    while($row_select = mysqli_fetch_array($query_select)){
        if(array_key_exists($row_select['MONTH'], $data['unique_visitors_this_year'])){
            ($data['unique_visitors_this_year'][$row_select['MONTH']] >= 1)? $data['unique_visitors_this_year'][$row_select['MONTH']] += 1 : $data['unique_visitors_this_year'][$row_select['MONTH']] = 1;
        }
    }

    // users all year
    $query_select = $con->query("SELECT MONTHNAME(created_at) MONTH, COUNT(*) COUNT FROM `users` WHERE YEAR(created_at)='$this_year' GROUP BY MONTHNAME(created_at)");
    while($row_select = mysqli_fetch_array($query_select)){
        if(array_key_exists($row_select['MONTH'], $data['users_all_year'])){
            $data['users_all_year'][$row_select['MONTH']] = $row_select['COUNT'];
        }
    }

    // family all year
    $query_select = $con->query("SELECT MONTHNAME(created_at) MONTH, COUNT(*) COUNT FROM `family` WHERE YEAR(created_at)='$this_year' GROUP BY MONTHNAME(created_at)");
    while($row_select = mysqli_fetch_array($query_select)){
        if(array_key_exists($row_select['MONTH'], $data['family_all_year'])){
            $data['family_all_year'][$row_select['MONTH']] = $row_select['COUNT'];
        }
    }

    // Sessions' countries
    $query_select = $con->query("SELECT country, count(*) as count FROM `visitors_info` group by country order by count asc
    ");
    while($row_select = mysqli_fetch_array($query_select)){
        $data['sessions_countries'][$row_select['country']] = $row_select['count'];
    }
    echo json_encode($data);
}

function get_family_plans($family_id, $lang){
    global $con;
    $data = [];
    $query_select = $con->query("SELECT `plan_id`, `start_date`, `end_date`, `created_at` FROM `family_plans` where `family_id`='$family_id' order by id desc");
    while($row_select = mysqli_fetch_array($query_select)){
        $row_select['plan'] = planName($row_select['plan_id'], $lang);
        array_push($data, $row_select);
    }
    return $data;
}

function visits_number_for_every_ip(){
    global $con;
    $data = [];
    $query_select = $con->query("SELECT ip, count(*) as count FROM `visitors_info` group by ip order by count asc
    ");
    while($row_select = mysqli_fetch_array($query_select)){
        $data[$row_select['ip']] = $row_select['count'];
    }
    return $data;
}

function visitors_info($start_from, $per_page_record) {

    global $con;
    $ips_visits = visits_number_for_every_ip();
    $visitors = [];
    $visitors['data'] = [];
    $sql = "SELECT * FROM `visitors_info` ";
    $query_select = $con->query($sql . "ORDER BY `id` DESC");
    $visitors['count'] = mysqli_num_rows($query_select);
    $query_select = $con->query($sql . "ORDER BY `id` DESC LIMIT $start_from, $per_page_record");
    while($row_select = mysqli_fetch_array($query_select)){
        $row_select['number_of_visits'] = $ips_visits[$row_select['ip']];
        $row_select['leave_at'] = ($row_select['leave_at'])? $row_select['leave_at'] : "-";
        array_push($visitors['data'], $row_select);
    }

    $query_select = $con->query($sql . "GROUP BY `ip`");
    $visitors['unique_count'] = mysqli_num_rows($query_select);

    return $visitors;
}

function total_families() {
    global $con;
    $query_select = $con->query("SELECT count(*) as count FROM `family`");
    $row_select = mysqli_fetch_array($query_select);

    return $row_select['count'];
}

function families_added_last_month() {
    global $con;
    $date_of_last_month = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-1 month" ) );
    $now = date('Y-m-d');
    $query_select = $con->query("SELECT count(*) as count FROM `family` where date between '$date_of_last_month' and '$now'");
    $row_select = mysqli_fetch_array($query_select);

    return $row_select['count'];
}

function familyName($id) {
    global $con;
    $query_select = $con->query("SELECT * FROM `family` WHERE `id`='" . $id . "' ORDER BY `id` LIMIT 1 ");
    $row_select = mysqli_fetch_array($query_select);
    // $lang = $_GET['lang'];
    if($lang == 'ar'){
        $name = $row_select['name_ar'];
    } else {
        $name = $row_select['name_en'];
    }

    return $name;
}

function family_users($familyId, $lang){

    global $con;
    
    $sql = "SELECT * FROM `users` where `family_id`='$familyId'";

    $query = $con->query($sql . " ORDER BY `user_id` ASC");

    $x = 1;

    while ($row = mysqli_fetch_assoc($query)) {

        $user_id = $row['user_id'];
        $name = $row['name'];
        $user_name = $row['user_name'];
        $user_email = $row['email'];
        $user_phone = $row['phone'];
        $user_image = $row['image'];
        $date = $row['date'];
        $parentId = $row['parent_id'];
        $familyId = $row['family_id'];
        if($lang == 'ar'){
            $message = 'لا يوجد';
        } else {
            $message = 'Not Exist ';
        }

        ?>
        <tr class="gradeX">
            <td><?php echo $x; ?></td>
            <td><?php echo $name; ?></td>
            <td><?php

                if($parentId != 0){
                    $parent = parentName($parentId);
                    echo "<a href='user_details.php?userID={$parentId}&lang={$lang}'>{$parent}</a>";
                } else {
                    echo "<span style='color: red;'>{$message}</span>";
                }

                ?></td>
            <td><?php

                if($familyId != 0){
                    $family = familyName($familyId);
                    echo "<a href='family_details.php?familyId={$familyId}&lang={$lang}'>{$family}</a>";
                } else {
                    echo "<span style='color: red;'>{$message}</span>";
                }

                ?></td>
            <td><?php echo $user_name; ?></td>
            <td><?php echo $user_email; ?></td>
            <td><?php echo $user_phone; ?></td>

            <td>
                <a href="<?php echo $user_image; ?>" class="image-popup" title="<?php echo $user_name; ?>">
                    <img src="<?=asset($user_image)?>" class="thumb-img" alt="<?php echo $user_name; ?>" height="100" style="width:100px;">
                </a>
            </td>

            <td>
                <?php
                echo $date;
                ?>

            </td>
            <td class="actions">

                <a href="user_edit.php?userID=<?php echo $user_id; ?>&lang=<?php  echo $lang; ?>" class="on-default"><i class="fa fa-pencil"></i></a>

                <a href="user_details.php?userID=<?php echo $user_id; ?>&lang=<?php  echo $lang;  ?>" class=""><i class="fa fa-eye"></i></a>


                <a href="<?php echo $user_id; ?>" class="on-default remove-row"><i class="fa fa-trash-o"></i></a>
            </td>
        </tr>
        <?php
        $x++;
    }

    return mysqli_insert_id($con);

}



if(isset($_POST['plan'])){
    include("../connection.php");

    $plan = $_POST['plan'];
    $lang = $_POST['lang'];
    $familyId = $_POST['familyId'];

    // var_dump($_POST);
    // die();

    $result = $con->query("update `family` set `plan_id`='$plan' where `id`='$familyId'") or die(mysqli_error($con));

    echo get_success($languages[$lang]["updateMessage"]);

}

function view_payments($lang){
    global $con;
//     $query = $con->query("SELECT payment.date as date, family.id as family_id,payment.value as payment_value,family.name_ar, family.name_en,
// family_plans.family_id as family_plan_id, payment.id as pay_id, payment.payment_id as payment_id, payment.confirmed,
//  payment.payment_type from family, family_plans, payment where family.id = family_plans.family_id 
//  and payment.id = family_plans.payment_id 
//  UNION  SELECT payment.date as date, family.id as family_id,payment.value as payment_value,family.name_ar, family.name_en,
// payment.family_id as family_plan_id, payment.id as pay_id, payment.payment_id as payment_id, payment.confirmed,
//  payment.payment_type from payment, family where family.id = payment.family_id and payment.purpose='mostpopular' order by confirmed, date desc");
    $query = $con->query("select payment.purpose, payment.date as date, payment.confirmed,
    payment.payment_type, payment.id as pay_id, payment.payment_id as payment_id, family.id as family_id, payment.value as payment_value, family.name_ar, family.name_en from family, payment where family.id = payment.family_id order by confirmed, date desc");
    $x = 1;
    while ($row = mysqli_fetch_assoc($query)) {
        $id = $row['pay_id'];
        $ar_name = $row['name_ar'];
        $en_name = $row['name_en'];
        $payment_type = $row['payment_type'];
        $payment_id = $row['payment_id'];
        $confirmed = $row['confirmed'];
        $payment_value = $row['payment_value'];
        ?>
        <tr class="gradeX">
            <td><?php echo $x; ?></td>
            <td><?=$lang == 'ar'?$ar_name:$en_name?></td>
            <td><?=$payment_type?></td>
            <td><?=$payment_id?></td>
            <td><?=$payment_value?></td>
           <td><?=$row['purpose']?></td>
            <td>
                <?php if ($confirmed == true) { ?>
                    <input class="change_payment_confirmed_off" data-id="<?php echo $id; ?>" type="checkbox"
                           checked
                           data-plugin="switchery" data-color="#81c868"/>
                <?php } else if ($confirmed == false) {
                    ?>
                    <input class="change_payment_confirmed_on" data-id="<?php echo $id; ?>" type="checkbox"
                           data-plugin="switchery" data-color="#81c868"/>
                <?php }
                ?>
            </td>
        </tr>
        <?php
        $x++;
    }
    return mysqli_insert_id($con);

}
function get_family_last_plan($family_id){
    global $con;
    
    $query = $con->query("SELECT * FROM `family_plans` where family_id='$family_id' ORDER BY `id` DESC limit 1");
    return mysqli_fetch_assoc($query);
}
function view_families($lang) {

    global $con;
    
    $query = $con->query("SELECT * FROM `family` ORDER BY `id` DESC");

    $x = 1;
    while ($row = mysqli_fetch_assoc($query)) {
        $id = $row['id'];

        $date = $row['date'];
        $mostpopular = $row['mostpopular'];
        $display = $row['display'];
        $status = $row['status'];
        $countryId = $row['country_id'];
        $plan = planName(get_family_last_plan($id)['plan_id'], $lang);
        $userId = $row['user_id'];

        ?>
        <tr class="gradeX">
            <td><?php echo $x; ?></td>
            <td><a href='family_users.php?familyId=<?php echo $id; ?>&lang=<?php echo $lang; ?>'><?= $row['name_'.$lang] ?></a></td>
            <td><a href='user_details.php?userID=<?php echo $userId; ?>&lang=<?php echo $lang; ?>'><?php echo parentName($userId); ?></a></td>
            <td><?php echo countryName($countryId, $lang); ?></td>

            <td>
                <?= $plan ?>
            </td>
            <td><?= get_family_last_plan($id)['end_date'] ?></td>
            <td>
                <?php if ($mostpopular == 1) { ?>
                    <input class="change_cat_mostpopular_off" data-id="<?php echo $id; ?>" type="checkbox"
                           checked
                           data-plugin="switchery" data-color="#81c868"/>
                <?php } else if ($mostpopular == 0) {
                    ?>
                    <input class="change_cat_mostpopular_on" data-id="<?php echo $id; ?>" type="checkbox"
                           data-plugin="switchery" data-color="#81c868"/>
                <?php }
                ?>
            </td>

            <td>
                <?php if ($status == 1) { ?>
                    <input class="change_cat_view_off" data-id="<?php echo $id; ?>" type="checkbox"
                           checked
                           data-plugin="switchery" data-color="#81c868"/>
                <?php } else if ($status == 0) {
                    ?>
                    <input class="change_cat_view_on" data-id="<?php echo $id; ?>" type="checkbox"
                           data-plugin="switchery" data-color="#81c868"/>
                <?php }
                ?>
            </td>

            <td>
                <?php if ($display == 1) { ?>
                    <input class="change_cat_status_off" data-id="<?php echo $id; ?>" type="checkbox"
                           checked
                           data-plugin="switchery" data-color="#81c868"/>
                <?php } else if ($display == 0) {
                    ?>
                    <input class="change_cat_status_on" data-id="<?php echo $id; ?>" type="checkbox"
                           data-plugin="switchery" data-color="#81c868"/>
                <?php }
                ?>
            </td>

            <td><?php echo $date; ?></td>

            <td class="actions">
                <a href="family_details.php?familyId=<?php echo $id ?>&lang=<?php echo $lang; ?>" class=""><i class="fa fa-eye"></i></a>

                <a href="<?php echo $id; ?>"  class="on-default remove-row" id="deleteParent"><i class="fa fa-trash-o"></i></a>
            </td>
        </tr>
        <?php
        $x++;

    }

    return mysqli_insert_id($con);
}


if (isset($_POST['family'])) {

    include("../connection.php");

    $deleteFamily = $_POST['family'];
    $lang = $_POST['lang'];

    $con->query("DELETE FROM `family` WHERE `id`='$deleteFamily' ");
    $con->query("DELETE FROM `familyAccess` WHERE `family_id`='$deleteFamily' ");
    $con->query("DELETE FROM `familyHistory` WHERE `family_id`='$deleteFamily' ");
    $con->query("DELETE FROM `familyInvitations` WHERE `family_id`='$deleteFamily' ");
    $con->query("DELETE FROM `familyMedia` WHERE `family_id`='$deleteFamily' ");
    $con->query("DELETE FROM `family_plans` WHERE `family_id`='$deleteFamily' ");
    $con->query("DELETE FROM `invitation_links` WHERE `family_id`='$deleteFamily' ");
    $con->query("DELETE FROM `siteMails` WHERE `family_id`='$deleteFamily' ");
    $con->query("DELETE FROM `users` WHERE `family_id`='$deleteFamily'");

    echo get_success($languages[$lang]["deleteMessage"]);

}
if (isset($_POST['change_cat_status_on'])) {

    include("../connection.php");

    $change_status = $_POST['change_cat_status_on'];
    $lang = $_POST['lang'];
    $query = $con->query("UPDATE `family` SET `display`=1 WHERE `id`='$change_status'");

    if ($query) {
        echo get_success($languages[$lang]["changeStatus"]);
    }
}

if (isset($_POST['change_cat_status_off'])) {

    include("../connection.php");

    $change_status = $_POST['change_cat_status_off'];
    $lang = $_POST['lang'];
    $query = $con->query("UPDATE `family` SET `display`=0 WHERE `id`='$change_status'");

    if ($query) {
        echo get_success($languages[$lang]["changeStatus"]);
    }
}



if (isset($_POST['change_cat_mostpopular_on'])) {

    include("../connection.php");

    $change_status = $_POST['change_cat_mostpopular_on'];
    $lang = $_POST['lang'];
    $query = $con->query("UPDATE `family` SET `mostpopular`=1 WHERE `id`='$change_status'");

    if ($query) {
        echo get_success($languages[$lang]["changeStatus"]);
    }
}

if (isset($_POST['change_cat_view_off'])) {

    include("../connection.php");

    $change_status = $_POST['change_cat_view_off'];
    $lang = $_POST['lang'];
    $query = $con->query("UPDATE `family` SET `status`=0 WHERE `id`='$change_status'");

    if ($query) {
        echo get_success($languages[$lang]["changeStatus"]);
    }
}

if (isset($_POST['change_cat_view_on'])) {

    include("../connection.php");

    $change_status = $_POST['change_cat_view_on'];
    $lang = $_POST['lang'];
    $query = $con->query("UPDATE `family` SET `status`=1 WHERE `id`='$change_status'");

    if ($query) {
        echo get_success($languages[$lang]["changeStatus"]);
    }
}

if (isset($_POST['change_cat_mostpopular_off'])) {

    include("../connection.php");
    $lang = $_POST['lang'];
    $change_status = $_POST['change_cat_mostpopular_off'];

    $query = $con->query("UPDATE `family` SET `mostpopular`=0 WHERE `id`='$change_status'");

    if ($query) {
        echo get_success($languages[$lang]["changeStatus"]);
    }
}



if (isset($_POST['change_payment_confirmed_on'])) {

    include("../connection.php");
    include_once(__DIR__."/../../lib/Mailer.php");

    $change_status = $_POST['change_payment_confirmed_on'];
    $lang = $_POST['lang'];
    $query = $con->query("UPDATE `payment` SET `confirmed`=1 WHERE `id`='$change_status'");
    if ($query) {
        $payment = $con->query("select user_id, family_id, purpose from payment where id=$change_status");
        $payment = mysqli_fetch_assoc($payment);
        $user_id = $payment['user_id'];
        $family_id = $payment['family_id'];
        $purpose = $payment['purpose'];
        if ($purpose == 'mostpopular'){
	    $con->query("UPDATE `family` SET `mostpopular`=1 WHERE `id`='$family_id'");
        }
        $user = $con->query("select * from users where user_id = $user_id");
        $user = mysqli_fetch_assoc($user);

        $url = "$siteUrl/login.php";


//        mail("${user['email']}" ,"AlHamayel Family Tree",$htmlMsg,implode("\r\n", $headers));
        $mailer = new Mailer();
        $mailer->setVars(['user_name'=>$user['name'], 'url'=>$url]);
        $r = $mailer->sendMail([$user['email']], "Bank Transfer Confirmed", 'transfer_confirmed.html', 'transfer_confirmed.txt');
        echo get_success($languages[$lang]["changeStatus"]);
    }
}
if (isset($_POST['change_payment_confirmed_off'])) {

    include("../connection.php");
    $lang = $_POST['lang'];
    $change_status = $_POST['change_payment_confirmed_off'];

    $query = $con->query("UPDATE `payment` SET `confirmed`=0 WHERE `id`='$change_status'");

    if ($query) {
	$payment = $con->query("select user_id, family_id, purpose from payment where id=$change_status");
	$payment = mysqli_fetch_assoc($payment);
	$family_id = $payment['family_id'];
	$purpose = $payment['purpose'];
	if ($purpose == 'mostpopular'){
	    $con->query("UPDATE `family` SET `mostpopular`=0 WHERE `id`='$family_id'");
	}
        echo get_success($languages[$lang]["changeStatus"]);
    }
}
?>
