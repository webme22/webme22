
<?php
// include("../config.php");
// function user_exists($userEmail) {

//     global $con;

//     $query = $con->query("SELECT 1 FROM `users` WHERE `user_email`='$userEmail' LIMIT 1");

//     return (mysqli_num_rows($query) == 1) ? true : false;
// }

// function getUserId($userID) {

//     global $con;

//     $query = $con->query("SELECT * FROM `users` WHERE `user_id`='$userID' LIMIT 1");
//     $row_select = mysqli_fetch_array($query);

//     $user_email = $row_select['user_email'];
//     return $user_email;
// }


function total_users() {
    global $con;
    $query_select = $con->query("SELECT count(*) as count FROM `users`");
    $row_select = mysqli_fetch_array($query_select);

    return $row_select['count'];
}

function users_added_last_month() {
    global $con;
    $date_of_last_month = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-1 month" ) );
    $now = date('Y-m-d');
    $query_select = $con->query("SELECT count(*) as count FROM `users` where date between '$date_of_last_month' and '$now'");
    $row_select = mysqli_fetch_array($query_select);

    return $row_select['count'];
}


function userEmailExists($email, $id = 0){

    global $con;
    
    $result = mysqli_query($con, "SELECT * FROM `users` WHERE `user_email`='$email' AND `user_id`!='$id'");

    return (mysqli_num_rows($result) == 1) ? true : false;
}

function userNameExists($name, $id = 0){

    global $con;

    $result = mysqli_query($con, "SELECT * FROM `users` WHERE `user_name`='$name' AND `user_id`!='$id'");

    return (mysqli_num_rows($result) == 1) ? true : false;

}

function add_user($users, $countries, $services,  $families, $setting, $messages, $clients, $name, $userName, $userEmail, $userPassword, $userPhone, $countryId, $gender, $nationalityId){
    global $con;
    $userPassword = password_hash($userPassword, PASSWORD_DEFAULT);
    $con->query("INSERT INTO `users` (`parent_id`, `name`, `user_name`, `user_password`, `email`, `phone`, `family_id`, `country_id`, `role`, `verified`, `users`, `families`, `countries`, `services`, `setting`, `clients`, `messages`, `date`, `gender`, `member`, `display`, `nationality`) VALUES ('0', '$name','$userName','$userPassword','$userEmail','$userPhone', '0', '$countryId', 'admin', '1','$users', '$families', '$countries', '$services', '$setting','$clients', '$messages', '".date('Y-m-d')."', '$gender', '0', '1', '$nationalityId')") or die(mysqli_error($con));
    return mysqli_insert_id($con);
}

function user_count() {

    global $con;

    $query = $con->query("SELECT * FROM `users` where `user_type` (`user_type` <> 5 and `user_type` <> 6) ORDER BY `user_id` ASC");

    $user_count = mysqli_num_rows($query);

    return $user_count;
}

function parentName($parentId){
    global $con;
    
    $query = $con->query("SELECT name FROM `users` where `user_id`='$parentId' ");

    $row = mysqli_fetch_array($query);

    return $row['name'];
    
}

function count_clients(){
    global $con;
    $query = $con->query("SELECT count(*) as count FROM `users` where `role`!='admin' ORDER BY `user_id` DESC");

    return mysqli_fetch_assoc($query)['count'];
}

function view_users($flag, $lang, $start = FALSE) {

    global $con;
    
    $sql = "SELECT * FROM `users` ";
    if($flag == 1){
        $sql .= "where `role`='admin' ORDER BY `user_id` DESC";
    } elseif($flag == 0) {
        $sql .= "where `role`!='admin' ORDER BY `user_id` DESC limit $start, 20";
    }
    $query = $con->query($sql);

    $x = ($flag == 0)? $start : 1;

    while ($row = mysqli_fetch_assoc($query)) {

        $user_id = $row['user_id'];
        $name = $row['name'];
        $user_name = $row['user_name'];
        $user_email = $row['email'];
        $user_phone = $row['phone'];
        $user_image = $row['image'];
        $date = $row['date'];
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
            <?php  
            
            if($familyId != 0 && $flag == 1){
                $family = familyName($familyId); 
                echo "<td><a href='family_details.php?familyId={$familyId}&lang={$lang}'>{$family}</a></td>";
            }
    
            ?>
            <td><?= ($user_name)? $user_name : "-" ?></td>
            <td><?= ($user_email)? $user_email : "-" ?></td>
            <td><?= ($user_phone)? $user_phone : "-" ?></td>

            <td>
                <a href="<?=asset($user_image)?>" class="image-popup" title="<?php echo $name; ?>">
                    <img src="<?=asset($user_image)?>" class="thumb-img" alt="<?php echo $name; ?>" height="100" style="width:100px;">
                </a>			
            </td>

            <td>
                <?php
                echo $date;
                ?>

            </td>
            <td class="actions">
                
                <?php if($flag == 1){ ?>
                <a href="user_edit.php?userID=<?php echo $user_id; ?>&lang=<?php echo $lang; ?>" class="on-default"><i class="fa fa-pencil"></i></a>
                <?php } ?>
                <a href="user_details.php?userID=<?php echo $user_id; ?>&lang=<?php echo $lang; ?>" class=""><i class="fa fa-eye"></i></a>
                
                
                <a href="<?php echo $user_id; ?>" class="on-default remove-row"><i class="fa fa-trash-o"></i></a>
            </td>
        </tr>		
        <?php
        $x++;
    }

    return mysqli_insert_id($con);
}

if (isset($_POST['user_id'])) {
    
    include("../connection.php");
    include("languages.php");
    $lang = $_POST['lang'];

    $user = $_POST['user_id'];

    $result = $con->query("SELECT * FROM `users` WHERE `user_id`='$user' ");

    $row = mysqli_fetch_assoc($result);

    $img_path = $row['image'];

    unlink($img_path);
    

    $query = $con->query("DELETE FROM `users` WHERE `user_id`='$user' ");

    if($query){
        
        echo get_success($languages[$lang]["deleteMessage"]);

    }

}
?>
